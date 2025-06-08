<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConnectivityRateController extends Controller
{
    //
    public function index(Request $request)
    {
        if (empty($request->date)) {
            // Ambil hari ini dan besok dengan waktu spesifik
            $today = new DateTime(); // sekarang
            $tomorrow = (clone $today)->modify('+1 day');

            $start = new DateTime($today->format('Y-m-d') . ' 07:00:00');
            $end = new DateTime($tomorrow->format('Y-m-d') . ' 06:59:59');
        } else {
            // Ambil berdasarkan tanggal request
            $date = new DateTime($request->date);

            $start = new DateTime($date->format('Y-m-d') . ' 07:00:00');
            $end = (clone $date)->modify('+1 day');
            $end->setTime(6, 59, 59);
        }

        $startTimeFormatted = $start->format('Y-m-d H:i:s');
        $endTimeFormatted = $end->format('Y-m-d H:i:s');

        $data = collect(DB::connection('focus')->select("
            SELECT TOP 2000
                ID,
                VHC_ID,
                OPR_REPORTTIME as FROM_UNIT,
                SYS_CREATEDAT as FROM_SYSTEM,
                OPR_NRP,
                LOC_NAME,
                RIT_TONNAGE,
                SYS_UPDATEDBY,
                CASE
                    WHEN DATEDIFF(second, OPR_REPORTTIME, SYS_CREATEDAT) > 300
                    THEN 'NOT REALTIME'
                    ELSE 'REALTIME'
                END AS STATUS
            FROM PRD_RITATION WITH (NOLOCK)
            WHERE OPR_REPORTTIME BETWEEN ? AND ?
            ORDER BY ID DESC
        ", [
            $startTimeFormatted,
            $endTimeFormatted,
        ]));

        $filtered = $data->filter(fn($row) => $row->STATUS === 'NOT REALTIME');

        // Kumpulkan semua unique VHC_ID dan LOC_NAME
        $allVhc = $filtered->pluck('VHC_ID')->unique()->values()->all();
        $allLoc = $filtered->pluck('LOC_NAME')->unique()->values()->all();

        // Buat pivot array: rows = VHC_ID, columns = LOC_NAME
        $pivot = [];

        foreach ($filtered as $row) {
            $vhc = $row->VHC_ID;
            $loc = $row->LOC_NAME;

            if (!isset($pivot[$vhc])) {
                $pivot[$vhc] = [];
            }
            if (!isset($pivot[$vhc][$loc])) {
                $pivot[$vhc][$loc] = 0;
            }
            $pivot[$vhc][$loc]++;
        }

        // Hitung total per row (VHC_ID) dan per column (LOC_NAME)
        $rowTotals = [];
        $colTotals = [];

        // Row totals
        foreach ($pivot as $vhc => $locData) {
            $rowTotals[$vhc] = array_sum($locData);
        }

        // Column totals
        foreach ($allLoc as $loc) {
            $colTotals[$loc] = 0;
            foreach ($allVhc as $vhc) {
                $colTotals[$loc] += $pivot[$vhc][$loc] ?? 0;
            }
        }

        $grandTotal = array_sum($rowTotals);

        return view('connectivity_rate.index', compact('pivot', 'allVhc', 'allLoc', 'rowTotals', 'colTotals', 'grandTotal'));
    }

}

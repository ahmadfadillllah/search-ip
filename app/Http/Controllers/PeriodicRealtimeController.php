<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodicRealtimeController extends Controller
{
    //
    public function index(Request $request)
    {

        if (empty($request->startDate) || empty($request->endDate)){
            $now = new DateTime();
            $yesterday = $now->modify('-1 day')->format('Y-m-d');

            // $end = (clone $start)->modify('+1 hour');
            $startDate = $yesterday;
            $endDate = $yesterday;

            $start = new DateTime("$startDate");
            $end = new DateTime("$endDate");

        }else{
            $start = new DateTime("$request->startDate");
            $end = new DateTime("$request->endDate");
        }


        $startTimeFormatted = $start->format('Y-m-d');
        $endTimeFormatted = $end->format('Y-m-d');


        $startDate = $startTimeFormatted;
        $endDate = $endTimeFormatted;


        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $dates = [];
        $currentDate = $start;

        while ($currentDate <= $end) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        $query = "
            SELECT
                SUM(CASE WHEN CATEGORY = 0 THEN NDATA ELSE 0 END) AS TOTAL_NOT_REALTIME,
                SUM(CASE WHEN CATEGORY = 1 THEN NDATA ELSE 0 END) AS TOTAL_REALTIME,
                VHC_ID,
                SUM(CASE WHEN CATEGORY = 0 THEN NDATA ELSE 0 END) * 1.0 / SUM(NDATA) AS PRCT,
                OPR_SHIFTDATE
            FROM (
                SELECT
                    CATEGORY,
                    COUNT(CATEGORY) AS NDATA,
                    VHC_ID,
                    OPR_SHIFTDATE
                FROM (
                    SELECT
                        OPR_REPORTTIME,
                        SYS_CREATEDAT,
                        DATEDIFF(SECOND, OPR_REPORTTIME, SYS_CREATEDAT) / 60.0 AS LATENCY,
                        VHC_ID,
                        IIF(DATEDIFF(SECOND, OPR_REPORTTIME, SYS_CREATEDAT) / 60.0 > 5, 0, 1) AS CATEGORY,
                        OPR_SHIFTDATE
                    FROM
                        focus.dbo.PRD_RITATION WITH (NOLOCK)
                    WHERE
                        OPR_SHIFTDATE BETWEEN ? AND ?
                        AND SYS_UPDATEDBY = 'SYSTEM'
                        AND SYS_CREATEDBY = 'SYSTEM'
                ) AS DATA
                GROUP BY CATEGORY, VHC_ID, OPR_SHIFTDATE
            ) AS DATA
            GROUP BY VHC_ID, OPR_SHIFTDATE
            HAVING SUM(NDATA) > 10
            ORDER BY VHC_ID, OPR_SHIFTDATE
        ";

        $results = DB::select($query, [$startDate, $endDate]);

        $formattedData = [];

        foreach ($results as $row) {
            $vhcId = $row->VHC_ID;
            $oprShiftDate = $row->OPR_SHIFTDATE;
            $prct = round((float) $row->PRCT * 100);
            $totalNotRealtime = $row->TOTAL_NOT_REALTIME;
            $totalRealtime = $row->TOTAL_REALTIME;

            if (!isset($formattedData[$vhcId])) {
                $formattedData[$vhcId] = [
                    'equipment' => $vhcId,
                    'total' => [],
                    'total_not_realtime' => 0,
                    'total_realtime' => 0,
                ];
            }

            $formattedData[$vhcId]['total'][$oprShiftDate] = $prct;
            $formattedData[$vhcId]['total_not_realtime'] += $totalNotRealtime;
            $formattedData[$vhcId]['total_realtime'] += $totalRealtime;
        }

        $displayData = [];

        foreach ($formattedData as $vhcId => $data) {
            $row = [
                'Equipment' => $data['equipment'],
                'TOTAL_NOT_REALTIME' => $data['total_not_realtime'],
                'TOTAL_REALTIME' => $data['total_realtime'],
            ];

            $totalPercentage = 0;
            $count = 0;

            foreach ($dates as $date) {
                if (isset($data['total'][$date])) {
                    $row[$date] = $data['total'][$date] . '%';
                    $totalPercentage += $data['total'][$date];
                    $count++;
                } else {
                    $row[$date] = '0%';
                }
            }

            if ($count > 0) {
                $row['Average'] = round($totalPercentage / $count);
            } else {
                $row['Average'] = '0%';
            }

            $displayData[] = $row;
        }

        usort($displayData, function ($a, $b) {
            return (float) rtrim($b['Average'], '%') - (float) rtrim($a['Average'], '%');
        });

        // dd($displayData);
        if (empty($displayData)) {
            return redirect()->back()->with('info', 'Maaf, data periodec tidak ditemukan');
        }
        return view('periodic_realtime.index', compact('displayData'));
    }
}

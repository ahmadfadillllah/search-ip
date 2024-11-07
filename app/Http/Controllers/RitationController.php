<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use DateTime;

class RitationController extends Controller
{
    //
    public function index(Request $request)
    {
        if (empty($request->startDate) || empty($request->startTime) || empty($request->endDate) || empty($request->endTime)){
            $now = new DateTime();

            $start = $now->setTime($now->format('H'), 0, 0)->modify('-1 hour');
            $end = (clone $start)->modify('+1 hour');
            $startDate = $start->format('Y-m-d');
            $startTime = $start->format('H:i');
            $endDate = $end->format('Y-m-d');
            $endTime = $end->format('H:i');

            $start = new DateTime("$startDate $startTime");
            $end = new DateTime("$endDate $endTime");

        }else{
            $start = new DateTime("$request->startDate $request->startTime");
            $end = new DateTime("$request->endDate $request->endTime");
        }


        $startTimeFormatted = $start->format('Y-m-d H:i:s');
        $endTimeFormatted = $end->format('Y-m-d H:i:s');

        $ritasi = DB::connection('sqlsrv')
                    ->table('PRD_RITATION')
                    ->select([
                        'ID',
                        'VHC_ID',
                        'OPR_REPORTTIME',
                        'SYS_CREATEDAT',
                        'LOC_NAME',
                    ])
                    ->whereBetween('OPR_REPORTTIME', [$startTimeFormatted, $endTimeFormatted])
                    ->get();

        $ritasi = $ritasi->filter(function ($item) {
            $oprReportTime = Carbon::parse($item->OPR_REPORTTIME);
            $sysCreateDate = Carbon::parse($item->SYS_CREATEDAT);

            $diffInMinutes = $oprReportTime->diffInMinutes($sysCreateDate);

            return $diffInMinutes > 5;
        });
        // dd($ritasi);

        foreach ($ritasi as $item) {
            $oprReportTime = Carbon::parse($item->OPR_REPORTTIME);
            $sysCreateDate = Carbon::parse($item->SYS_CREATEDAT);

            $diff = $oprReportTime->diff($sysCreateDate);

            $item->TIME_DIFF = $diff->format('%H:%I:%S');
        }

        return view('ritation.index', compact('ritasi'));
    }

}

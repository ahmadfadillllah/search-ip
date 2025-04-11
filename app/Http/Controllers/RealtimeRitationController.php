<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RealtimeRitationController extends Controller
{
    //
    public function index(Request $request)
    {
        $now = new DateTime();

        if (empty($request->date)){
            $date = $now->format('Y-m-d');

        }else{
            $date = new DateTime("$request->date");
        }

        $data = DB::connection('focus')->select('SET NOCOUNT ON;EXEC FOCUS_REPORTING.dbo.APP_RATE_PER_HOUR_RESUMEDATA @DATE = ?', [$date]);
        $data = collect($data);
        // dd($data);
        return view('realtime-ritation.index', compact('data'));
    }

    public function notrealtime($date, $time)
    {
        list($startTime, $endTime) = explode('-', $time);
        $start = Carbon::createFromFormat('H:i', $startTime);
        $end = Carbon::createFromFormat('H:i', $endTime);
        $date = Carbon::createFromFormat('Y-m-d', $date);

        if ($start->hour >= 0 && $start->hour < 7) {
            $date = $date->addDay();
        }

        $dateStart = Carbon::parse($date->toDateString() . ' ' . $start->format('H:i'))->format('Y-m-d H:i');
        $dateEnd = Carbon::parse($date->toDateString() . ' ' . $end->format('H:i'))->format('Y-m-d H:i');
        try {
            $data = DB::connection('focus')->table('FOCUS.DBO.PRD_RITATION')
                ->select(
                    'VHC_ID',
                    'OPR_REPORTTIME',
                    'SYS_CREATEDAT',
                    'LOD_LOADERID as LOADER',
                    'LOD_LOC_NAME as LOC_LOADER',
                    'LOC_NAME as LOC_DUMPING',
                    DB::raw('CONVERT(VARCHAR(8), DATEADD(SECOND, DATEDIFF(SECOND, OPR_REPORTTIME, SYS_CREATEDAT), \'1900-01-01\'), 108) AS DIFF_IN_TIME'),
                    DB::raw('CASE WHEN DATEDIFF(second, OPR_REPORTTIME, SYS_CREATEDAT) > 300 THEN \'NOT REALTIME\' ELSE \'REALTIME\' END AS STATUS')
                )
                ->whereBetween('OPR_REPORTTIME', [$dateStart, $dateEnd])
                ->whereRaw('DATEDIFF(second, OPR_REPORTTIME, SYS_CREATEDAT) > 300')
                ->orderBy('OPR_REPORTTIME')
                ->get();

                return view('realtime-ritation.notrealtime', compact('data'));
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('info', 'Terjadi kesalahan');
        }
    }
}

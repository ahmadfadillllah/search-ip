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
            $now2 = new DateTime();
            $yesterday = $now->modify('-1 day')->format('Y-m-d');

            $startDate = $yesterday;              // string
            $endDate = $now2->format('Y-m-d');    // string

            $start = new DateTime($startDate);    // ok
            $end = new DateTime($endDate);

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

        $results = DB::connection('focus')->select($query, [$startDate, $endDate]);


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

        $sortedTrend = $displayData;

        usort($sortedTrend, function ($a, $b) {
            return $b['TOTAL_NOT_REALTIME'] <=> $a['TOTAL_NOT_REALTIME'];
        });

        // Harus slice dari $sortedTred, bukan $displayData
        $top10 = array_slice($sortedTrend, 0, 10);

        $categoriesTrend = array_map(fn($item) => $item['Equipment'], $top10);
        $dataTrend = array_map(fn($item) => $item['TOTAL_NOT_REALTIME'], $top10);

        $trend = [
            'categoriesTrend' => $categoriesTrend,
            'dataTrend' => $dataTrend,
        ];

        // Ambil data 12 bulan terakhir (otomatis)
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d');
        $endMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

        // Ambil VHC_ID pertama dari top 10
        $top10VhcIds = array_map(fn($item) => $item['Equipment'], $top10);
        $firstVhcId = $top10VhcIds[0] ?? null;

        // Jika tidak ada data, hentikan lebih awal
        if (!$firstVhcId) {
            abort(404, 'Data VHC_ID tidak ditemukan.');
        }

        // Buat query SQL
        $rekapQuery = "
            SELECT
                BULAN,
                VHC_ID,
                TOTAL_REALTIME,
                TOTAL_NOT_REALTIME,
                PRCT_REALTIME
            FROM (
                SELECT
                    VHC_ID,
                    CONVERT(VARCHAR(7), OPR_SHIFTDATE, 120) AS BULAN,
                    SUM(CASE WHEN CATEGORY = 1 THEN NDATA ELSE 0 END) AS TOTAL_REALTIME,
                    SUM(CASE WHEN CATEGORY = 0 THEN NDATA ELSE 0 END) AS TOTAL_NOT_REALTIME,
                    CAST(
                        SUM(CASE WHEN CATEGORY = 1 THEN NDATA ELSE 0 END) * 1.0 / NULLIF(SUM(NDATA), 0) * 100
                        AS DECIMAL(5,2)
                    ) AS PRCT_REALTIME
                FROM (
                    SELECT
                        VHC_ID,
                        IIF(DATEDIFF(SECOND, OPR_REPORTTIME, SYS_CREATEDAT)/60.0 > 5, 0, 1) AS CATEGORY,
                        COUNT(*) AS NDATA,
                        OPR_SHIFTDATE
                    FROM focus.dbo.PRD_RITATION WITH (NOLOCK)
                    WHERE
                        OPR_SHIFTDATE BETWEEN ? AND ?
                        AND SYS_UPDATEDBY = 'SYSTEM'
                        AND SYS_CREATEDBY = 'SYSTEM'
                        AND VHC_ID = ?
                    GROUP BY
                        VHC_ID,
                        IIF(DATEDIFF(SECOND, OPR_REPORTTIME, SYS_CREATEDAT)/60.0 > 5, 0, 1),
                        OPR_SHIFTDATE
                ) AS AGG
                GROUP BY VHC_ID, CONVERT(VARCHAR(7), OPR_SHIFTDATE, 120)
            ) AS FINAL
            ORDER BY BULAN ASC, VHC_ID ASC
        ";

        $monthlyRekap = DB::connection('focus')->select($rekapQuery, [
            $startMonth,
            $endMonth,
            $firstVhcId,
            $startMonth,
            $endMonth,
        ]);

        return view('periodic_realtime.index', compact('displayData', 'startDate', 'endDate', 'trend', 'monthlyRekap'));
    }

    public function notRealtime($startDate, $endDate, $vhcId)
    {

        $query = "
            SELECT
                OPR_REPORTTIME,
                SYS_CREATEDAT,
                DATEDIFF(SECOND, OPR_REPORTTIME, SYS_CREATEDAT) / 60.0 AS LATENCY,
                VHC_ID,
                OPR_SHIFTDATE,
                LOD_LOADERID as LOADER,
                LOD_LOC_NAME as LOC_LOADER,
                LOC_NAME as LOC_DUMPING,
                OPR_SHIFTNO as SHIFT
            FROM
                focus.dbo.PRD_RITATION WITH (NOLOCK)
            WHERE
                OPR_SHIFTDATE BETWEEN ? AND ?
                AND VHC_ID = '$vhcId'
                AND SYS_UPDATEDBY = 'SYSTEM'
                AND SYS_CREATEDBY = 'SYSTEM'
                AND DATEDIFF(SECOND, OPR_REPORTTIME, SYS_CREATEDAT) / 60.0 > 5  -- Filter untuk LATENCY > 5 menit

        ";

        $data = DB::connection('focus')->select($query, [$startDate, $endDate]);

        $data = collect($data);

        return view('periodic_realtime.show', compact('data'));
    }

    public function allMaps(Request $request, $startDate, $endDate)
    {

        $query = "
            SELECT
                OPR_REPORTTIME,
                SYS_CREATEDAT,
                DATEDIFF(SECOND, OPR_REPORTTIME, SYS_CREATEDAT) / 60.0 AS LATENCY,
                VHC_ID,
                GPS_LAT,
                GPS_LON,
                OPR_SHIFTDATE,
                LOD_LOADERID as LOADER,
                LOD_LOC_NAME as LOC_LOADER,
                LOC_NAME as LOC_DUMPING
            FROM
                focus.dbo.PRD_RITATION WITH (NOLOCK)
            WHERE
                OPR_SHIFTDATE BETWEEN ? AND ?
                AND SYS_UPDATEDBY = 'SYSTEM'
                AND SYS_CREATEDBY = 'SYSTEM'
                AND DATEDIFF(SECOND, OPR_REPORTTIME, SYS_CREATEDAT) / 60.0 > 5  -- Filter untuk LATENCY > 5 menit
        ";

        $data = DB::connection('focus')->select($query, [$startDate, $endDate]);
        $data = collect($data);
        $data = $data->map(function ($item) {
            return [
                'lat' => (float) $item->GPS_LAT,
                'lng' => (float) $item->GPS_LON,
                'latency' => $item->LATENCY,
            ];
        });

        $queryMaps = "
            SELECT
                A.MAPOBJECTID, A.LAYERNAME, B.LAYERTAG,
                A.MAPOBJECTTYPE, A.MAPOBJECTNAME,
                A.LONGITUDE, A.LATITUDE, A.GEOPOINTS,
                A.WIDTH, A.HEIGHT, A.DIMSIZE, A.SHAPEKIND,
                A.TITLE, A.TITLEMODE, A.HINT,
                A.MARKERNAME, D.MARKERIMAGE, D.ORIGINX, D.ORIGINY,
                A.STYLE, A.STYLEHOT, A.STYLESELECTED, A.VISIBLE
            FROM
            MAP_OBJECT A
            LEFT JOIN MAP_LAYER B ON (A.LAYERNAME=B.LAYERNAME)
            LEFT JOIN (SELECT * FROM MAP_MAPLAYER WHERE MAPNAME='DEFAULT') C ON (A.LAYERNAME=C.LAYERNAME)
            LEFT JOIN MAP_MARKER D ON (A.MARKERNAME=D.MARKERNAME)
            WHERE
            C.LOADMODE=1
            AND A.LAYERNAME = 'MAP_ROAD'
            ORDER BY
            B.LAYERNAME, A.MAPOBJECTID
        ";

        $dataMaps = DB::connection('focus')->select($queryMaps, [$startDate, $endDate]);
        $dataMaps = collect($dataMaps)->map(function($item) {
            if (isset($item->GEOPOINTS)) {
                $geoPoints = $item->GEOPOINTS;
                if (is_string($geoPoints)) {
                    $geoPoints = json_decode("[$geoPoints]");
                }
                $item->GEOPOINTS = $geoPoints;
            }
            return $item;
        });

        $queryMapsDisposal = "
            SELECT
                A.MAPOBJECTID, A.LAYERNAME, B.LAYERTAG,
                A.MAPOBJECTTYPE, A.MAPOBJECTNAME,
                A.LONGITUDE, A.LATITUDE, A.GEOPOINTS,
                A.WIDTH, A.HEIGHT, A.DIMSIZE, A.SHAPEKIND,
                A.TITLE, A.TITLEMODE, A.HINT,
                A.MARKERNAME, D.MARKERIMAGE, D.ORIGINX, D.ORIGINY,
                A.STYLE, A.STYLEHOT, A.STYLESELECTED, A.VISIBLE
            FROM
            MAP_OBJECT A
            LEFT JOIN MAP_LAYER B ON (A.LAYERNAME=B.LAYERNAME)
            LEFT JOIN (SELECT * FROM MAP_MAPLAYER WHERE MAPNAME='DEFAULT') C ON (A.LAYERNAME=C.LAYERNAME)
            LEFT JOIN MAP_MARKER D ON (A.MARKERNAME=D.MARKERNAME)
            WHERE
            C.LOADMODE=1
            AND A.LAYERNAME = 'MAP_DISPOSAL'
            ORDER BY
            B.LAYERNAME, A.MAPOBJECTID
        ";

        $dataMapsDisposal = DB::connection('focus')->select($queryMapsDisposal, [$startDate, $endDate]);
        $dataMapsDisposal = collect($dataMapsDisposal);

        $queryMapsFront = "
            SELECT
                A.MAPOBJECTID, A.LAYERNAME, B.LAYERTAG,
                A.MAPOBJECTTYPE, A.MAPOBJECTNAME,
                A.LONGITUDE, A.LATITUDE, A.GEOPOINTS,
                A.WIDTH, A.HEIGHT, A.DIMSIZE, A.SHAPEKIND,
                A.TITLE, A.TITLEMODE, A.HINT,
                A.MARKERNAME, D.MARKERIMAGE, D.ORIGINX, D.ORIGINY,
                A.STYLE, A.STYLEHOT, A.STYLESELECTED, A.VISIBLE
            FROM
            MAP_OBJECT A
            LEFT JOIN MAP_LAYER B ON (A.LAYERNAME=B.LAYERNAME)
            LEFT JOIN (SELECT * FROM MAP_MAPLAYER WHERE MAPNAME='DEFAULT') C ON (A.LAYERNAME=C.LAYERNAME)
            LEFT JOIN MAP_MARKER D ON (A.MARKERNAME=D.MARKERNAME)
            WHERE
            C.LOADMODE=1
            AND A.LAYERNAME = 'MAP_FRONT'
            ORDER BY
            B.LAYERNAME, A.MAPOBJECTID
        ";

        $dataMapsFront = DB::connection('focus')->select($queryMapsFront, [$startDate, $endDate]);
        $dataMapsFront = collect($dataMapsFront);



        return view('periodic_realtime.allMaps', compact('data', 'dataMaps', 'dataMapsDisposal', 'dataMapsFront'));
    }

    public function mapsUnit($startDate, $endDate, $vhcId)
    {

        $query = "
            SELECT
                OPR_REPORTTIME,
                SYS_CREATEDAT,
                DATEDIFF(SECOND, OPR_REPORTTIME, SYS_CREATEDAT) / 60.0 AS LATENCY,
                VHC_ID,
                GPS_LAT,
                GPS_LON,
                OPR_SHIFTDATE,
                LOD_LOADERID as LOADER,
                LOD_LOC_NAME as LOC_LOADER,
                LOC_NAME as LOC_DUMPING
            FROM
                focus.dbo.PRD_RITATION WITH (NOLOCK)
            WHERE
                OPR_SHIFTDATE BETWEEN ? AND ?
                AND VHC_ID = '$vhcId'
                AND SYS_UPDATEDBY = 'SYSTEM'
                AND SYS_CREATEDBY = 'SYSTEM'
                AND DATEDIFF(SECOND, OPR_REPORTTIME, SYS_CREATEDAT) / 60.0 > 5  -- Filter untuk LATENCY > 5 menit
        ";

        $data = DB::connection('focus')->select($query, [$startDate, $endDate]);
        $data = collect($data);
        $data = $data->map(function ($item) {
            return [
                'lat' => (float) $item->GPS_LAT,
                'lng' => (float) $item->GPS_LON,
                'latency' => $item->LATENCY,
            ];
        });

        $queryMaps = "
            SELECT
                A.MAPOBJECTID, A.LAYERNAME, B.LAYERTAG,
                A.MAPOBJECTTYPE, A.MAPOBJECTNAME,
                A.LONGITUDE, A.LATITUDE, A.GEOPOINTS,
                A.WIDTH, A.HEIGHT, A.DIMSIZE, A.SHAPEKIND,
                A.TITLE, A.TITLEMODE, A.HINT,
                A.MARKERNAME, D.MARKERIMAGE, D.ORIGINX, D.ORIGINY,
                A.STYLE, A.STYLEHOT, A.STYLESELECTED, A.VISIBLE
            FROM
            MAP_OBJECT A
            LEFT JOIN MAP_LAYER B ON (A.LAYERNAME=B.LAYERNAME)
            LEFT JOIN (SELECT * FROM MAP_MAPLAYER WHERE MAPNAME='DEFAULT') C ON (A.LAYERNAME=C.LAYERNAME)
            LEFT JOIN MAP_MARKER D ON (A.MARKERNAME=D.MARKERNAME)
            WHERE
            C.LOADMODE=1
            AND A.LAYERNAME = 'MAP_ROAD'
            ORDER BY
            B.LAYERNAME, A.MAPOBJECTID
        ";

        $dataMaps = DB::connection('focus')->select($queryMaps, [$startDate, $endDate]);
        $dataMaps = collect($dataMaps)->map(function($item) {
            if (isset($item->GEOPOINTS)) {
                $geoPoints = $item->GEOPOINTS;
                if (is_string($geoPoints)) {
                    $geoPoints = json_decode("[$geoPoints]");
                }
                $item->GEOPOINTS = $geoPoints;
            }
            return $item;
        });

        $queryMapsDisposal = "
            SELECT
                A.MAPOBJECTID, A.LAYERNAME, B.LAYERTAG,
                A.MAPOBJECTTYPE, A.MAPOBJECTNAME,
                A.LONGITUDE, A.LATITUDE, A.GEOPOINTS,
                A.WIDTH, A.HEIGHT, A.DIMSIZE, A.SHAPEKIND,
                A.TITLE, A.TITLEMODE, A.HINT,
                A.MARKERNAME, D.MARKERIMAGE, D.ORIGINX, D.ORIGINY,
                A.STYLE, A.STYLEHOT, A.STYLESELECTED, A.VISIBLE
            FROM
            MAP_OBJECT A
            LEFT JOIN MAP_LAYER B ON (A.LAYERNAME=B.LAYERNAME)
            LEFT JOIN (SELECT * FROM MAP_MAPLAYER WHERE MAPNAME='DEFAULT') C ON (A.LAYERNAME=C.LAYERNAME)
            LEFT JOIN MAP_MARKER D ON (A.MARKERNAME=D.MARKERNAME)
            WHERE
            C.LOADMODE=1
            AND A.LAYERNAME = 'MAP_DISPOSAL'
            ORDER BY
            B.LAYERNAME, A.MAPOBJECTID
        ";

        $dataMapsDisposal = DB::connection('focus')->select($queryMapsDisposal, [$startDate, $endDate]);
        $dataMapsDisposal = collect($dataMapsDisposal);

        $queryMapsFront = "
            SELECT
                A.MAPOBJECTID, A.LAYERNAME, B.LAYERTAG,
                A.MAPOBJECTTYPE, A.MAPOBJECTNAME,
                A.LONGITUDE, A.LATITUDE, A.GEOPOINTS,
                A.WIDTH, A.HEIGHT, A.DIMSIZE, A.SHAPEKIND,
                A.TITLE, A.TITLEMODE, A.HINT,
                A.MARKERNAME, D.MARKERIMAGE, D.ORIGINX, D.ORIGINY,
                A.STYLE, A.STYLEHOT, A.STYLESELECTED, A.VISIBLE
            FROM
            MAP_OBJECT A
            LEFT JOIN MAP_LAYER B ON (A.LAYERNAME=B.LAYERNAME)
            LEFT JOIN (SELECT * FROM MAP_MAPLAYER WHERE MAPNAME='DEFAULT') C ON (A.LAYERNAME=C.LAYERNAME)
            LEFT JOIN MAP_MARKER D ON (A.MARKERNAME=D.MARKERNAME)
            WHERE
            C.LOADMODE=1
            AND A.LAYERNAME = 'MAP_FRONT'
            ORDER BY
            B.LAYERNAME, A.MAPOBJECTID
        ";

        $dataMapsFront = DB::connection('focus')->select($queryMapsFront, [$startDate, $endDate]);
        $dataMapsFront = collect($dataMapsFront);

        return view('periodic_realtime.mapsUnit', compact('data', 'dataMaps', 'dataMapsDisposal', 'dataMapsFront'));

    }
}

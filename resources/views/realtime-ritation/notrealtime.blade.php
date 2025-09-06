@include('layout.head', ["title" => "Realtime Ritation"])
@include('layout.sidebar')
@include('layout.header')

<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-11">
                <h4 class="page-title mb-0">Not Realtime Ritation</h4>
            </div>
            <div class="col-lg-1">
                <a href="javascript:window.history.back()" class="ladda-button btn btn-primary" dir="ltr" data-style="expand-left" id="refresh-button">
                    <i class="bx bx-back"></i>Back
                </a>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table id="customTable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Unit</th>
                                <th>From Unit</th>
                                <th>From System</th>
                                <th>Loader</th>
                                <th>Loader Location</th>
                                <th>Dumping Location</th>
                                <th>Different</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $de)
                                <tr>
                                    <td>{{ $de->VHC_ID }}</td>
                                    <td>{{ $de->OPR_REPORTTIME }}</td>
                                    <td>{{ $de->SYS_CREATEDAT }}</td>
                                    <td>{{ $de->LOADER }}</td>
                                    <td>{{ $de->LOC_LOADER }}</td>
                                    <td>{{ $de->LOC_DUMPING }}</td>
                                    <td>{{ $de->DIFF_IN_TIME }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Frequency Not Realtime Per Hour</h4>

                    <div id="cardCollpase5" class="collapse show" dir="ltr">
                        <div id="frequencyNotRealtime" class="apex-charts pt-3" data-colors="#00acc1,#1abc9c,#CED4DC"></div>
                    </div> <!-- collapsed end -->
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div>
    </div>
    <!-- end row-->

</div>
@include('layout.footer')
@php
    $categories = $data->pluck('LOC_DUMPING')->unique()->values();

    $series = $data->groupBy('VHC_ID')->map(function($items, $vhc) use ($categories) {
        return [
            'name' => $vhc,
            'data' => $categories->map(function($cat) use ($items) {
                return $items->where('LOC_DUMPING', $cat)->count();
            })->values()
        ];
    })->values();
@endphp

<script>
    var categories = @json($categories);
    var series = @json($series);

    dataColorsFrequencyNotRealtime = $("#frequencyNotRealtime").data("colors");
    var options = {
        chart: {
            height: 380,
            type: "bar",
            toolbar: { show: !1 }
        },
        plotOptions: {
            bar: {
                horizontal: !1,
                endingShape: "rounded",
                columnWidth: "55%"
            }
        },
        dataLabels: { enabled: !1 },
        stroke: { show: !0, width: 2, colors: ["transparent"] },
        colors: dataColorsFrequencyNotRealtime ? dataColorsFrequencyNotRealtime.split(",") : ["#556ee6","#34c38f","#f46a6a"],
        series: series,
        xaxis: {
            categories: categories
        },
        legend: { offsetY: 5 },
        yaxis: {
            title: { text: "Count VHC_ID" }
        },
        fill: { opacity: 1 },
        grid: {
            row: { colors: ["transparent","transparent"], opacity: .2 },
            borderColor: "#f1f3fa",
            padding: { bottom: 10 }
        },
        tooltip: {
            y: { formatter: function (val) { return val + " kali"; } }
        }
    };

    (chart = new ApexCharts(document.querySelector("#frequencyNotRealtime"), options)).render();
</script>


<script>
    function getQueryParam(name, defaultValue) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name) || defaultValue;
    }

    const currentDate = new Date();

    // Format default (YYYY-MM-DD)
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = ('0' + (date.getMonth() + 1)).slice(-2);
        const day = ('0' + date.getDate()).slice(-2);
        return `${year}-${month}-${day}`;
    };

    const dateDefault = getQueryParam('date', formatDate(currentDate));

    document.getElementById('date').value = dateDefault;
</script>


<script>
    $(document).ready(function() {
        $('#customTable').DataTable({
            "pageLength": 32,
            "order": [[1, 'desc']],
            "columnDefs": [
                { "orderable": false, "targets": 0 }
            ]
        });
    });
</script>


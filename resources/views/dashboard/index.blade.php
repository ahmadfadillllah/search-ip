@include('layout.head', ["title" => "Dashboard"])
@include('layout.sidebar')
@include('layout.header')


<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Dashboard</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-md-6 col-xl-2">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-end">Daily</span>
                        <h5 class="card-title mb-0">Total Unit</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                               {{ $unit->count() }}
                            </h2>
                        </div>
                    </div>

                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div>
        <div class="col-md-2 col-xl-2">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-end">Daily</span>
                        <h5 class="card-title mb-0">Total Access Point</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                               {{  $aruba->count() }}
                            </h2>
                        </div>
                    </div>

                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div>
        <div class="col-md-2 col-xl-2">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-end">Daily</span>
                        <h5 class="card-title mb-0">Up</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                               {{  $aruba->where('Status', '!=', 'Down')->count() }}
                            </h2>
                        </div>
                    </div>

                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div>
        <div class="col-md-2 col-xl-2">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-end">Daily</span>
                        <h5 class="card-title mb-0">Down</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                               {{  $aruba->where('Status', 'Down')->count() }}
                            </h2>
                        </div>
                    </div>

                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div>
        <div class="col-md-2 col-xl-2">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-end">Daily</span>
                        <h5 class="card-title mb-0">Active Aruba 175P</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                               {{  $type_aruba->where('AP Type', '175P')->count() }}
                            </h2>
                        </div>
                    </div>

                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div>
        <div class="col-md-6 col-xl-2">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-end">Daily</span>
                        <h5 class="card-title mb-0">Active Aruba 374</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                               {{  $type_aruba->where('AP Type', '374')->count() }}
                            </h2>
                        </div>
                    </div>

                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div> <!-- end col-->
    </div>
    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-end">Daily</span>
                        <h5 class="card-title mb-0">Status Device</h5>
                    </div>
                    <div class="mt-4 chartjs-chart">
                        <canvas id="devices" height="350" class="mt-4" data-colors="#00acc1,#fa5c7c,#4fc6e1,#ebeff2"></canvas>
                    </div>
                    <p><span class="badge bg-primary">---</span> Connected: {{  $device->where('Type', null)->count() }}</p>
                    {{-- <p>Android: {{  $device->where('Type', 'Android')->count() }}</p> --}}
                    <p><span class="badge bg-danger">---</span> Disconnected: {{ $unit->count() - $device->where('Type', null)->count() }}</p>

                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div>


        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-end">Daily</span>
                        <h5 class="card-title mb-0">Status Unit</h5>
                    </div>

                    <!-- Tabel untuk menampilkan data -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th style="text-align: center">EX</th>
                                    <th style="text-align: center">HD</th>
                                    <th style="text-align: center">MG</th>
                                    <th style="text-align: center">BD</th>
                                    <th style="text-align: center">WT</th>
                                    <th style="text-align: center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['Operasi', 'Standby', 'Breakdown'] as $status)
                                    <tr>
                                        <td>{{ $status }}</td>

                                        <!-- Tipe kendaraan -->
                                        @foreach(['EX', 'HD', 'MG', 'BD', 'WT'] as $type)
                                            <td style="text-align: center">
                                                {{
                                                    $statusUnit->where('VSA_GROUPDESC', $status)
                                                               ->where('VHC_TYPE', $type)
                                                               ->sum('NDATA')
                                                }}
                                            </td>
                                        @endforeach

                                        <!-- Total per status -->
                                        <td style="text-align: center">
                                            {{
                                                $statusUnit->where('VSA_GROUPDESC', $status)
                                                           ->sum('NDATA')
                                            }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <!-- Total keseluruhan -->
                            <tfoot>
                                <tr>
                                    <td>Total</td>
                                    @foreach(['EX', 'HD', 'MG', 'BD', 'WT'] as $type)
                                        <td style="text-align: center">
                                            {{
                                                $statusUnit->where('VHC_TYPE', $type)
                                                           ->sum('NDATA')
                                            }}
                                        </td>
                                    @endforeach
                                    <td style="text-align: center">
                                        {{
                                            $statusUnit->sum('NDATA')
                                        }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                    </div>

                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div>

        <div class="col-md-6 col-xl-5">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-end">Daily</span>
                        <h5 class="card-title mb-0">Status Ritation</h5>
                    </div>

                    <!-- Tabel untuk menampilkan data -->
                    <div class="table-responsive">
                        <table id="selection-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Range Hour</th>
                                    <th>Total</th>
                                    <th>Realtime</th>
                                    <th>Not Realtime</th>
                                    <th>Percentage</th>

                                </tr>
                            </thead>
                            @php
                                $totalRitRealtime = 0;
                                $totalRitNotRealtime = 0;
                                $totalRateFMS = 0;
                                $totalPercentageRealtime = 0;
                            @endphp
                            <tbody>
                                @foreach($ritasi as $dt)
                                @php
                                    $totalRitRealtime += $dt->N_RIT_REALTIME;
                                    $totalRitNotRealtime += $dt->N_RIT_NOT_REALTIME;
                                    $totalRateFMS += $dt->N_RATEFMS;
                                @endphp
                                    <tr>
                                        <td>{{ $dt->RANGEJAM }}</td>
                                        <td>{{ $dt->N_RATEFMS }}</td>
                                        <td>{{ $dt->N_RIT_REALTIME }}</td>
                                        <td>{{ $dt->N_RIT_NOT_REALTIME }}</td>
                                        <td>{{ number_format($dt->PERCENTAGE_REALTIME * 100, 1) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @php
                                // Menangani Division by Zero
                                $totalPercentageRealtime = ($totalRateFMS != 0) ? ($totalRitRealtime / $totalRateFMS) : 0;
                            @endphp
                            <tfoot>
                                <tr style="background-color:#ffcbc2">
                                    <td ><strong>Total</strong></td>
                                    <td>{{ $totalRateFMS }}</td>
                                    <td>{{ $totalRitRealtime }}</td>
                                    <td>{{ $totalRitNotRealtime }}</td>
                                    <td>{{ number_format($totalPercentageRealtime * 100, 1) }}%</td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>

                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div>


    </div>
    <!-- end row-->

</div> <!-- container -->


@include('layout.footer')

<script>
    var device = @json($device);

    var androidCount = device.filter(function(item) {
        return item.Type == 'Android';
    }).length;

    var notAndroidCount = device.filter(function(item) {
        return item.Type !== 'Android';
    }).length;

    var unitCount = @json($unit->count());

    var disconnectedCount = unitCount - notAndroidCount;


    if (document.getElementById("devices")) {
        var ctx = document.getElementById("devices").getContext("2d");

        var e = {
            labels: ["Connected",'Disconnected'],
            datasets: [{
                data: [notAndroidCount, disconnectedCount],
                backgroundColor: ["#346ee0", "#fa5944"],
                borderColor: "transparent"
            }]
        };

        var chart = new Chart(ctx, {
            type: 'pie',
            data: e,
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                }
            }
        });
    }
</script>


@include('layout.head', ["title" => "Connectivity Rate"])
@include('layout.sidebar')
@include('layout.header')
<style>
    #customTable th.highlight-col {
        background-color: #FCEF91 !important;
        color: black !important;
        text-align: center
    }

    /* Untuk baris yang dihighlight */
    #customTable tr.highlight-row {
        background-color: #FCEF91 !important;
        color: black !important;
    }

    #customTable td.highlight-col {
        background-color: #FCEF91 !important;
        color: black !important;
    }

    #customTable tr.highlight-row>td {
        background-color: #FCEF91 !important;
        color: black !important;
    }

    th.vertical-text {
        height: 120px;
        white-space: nowrap;
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        text-align: center;
        vertical-align: middle;

        /* padding: 0px 0px; */
    }

</style>
<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="page-title mb-0">Connectivity Rate</h4>
            </div>
            <div class="col-lg-4">
                <form action="" method="GET">
                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label" for="date">Date: </label>
                        <div class="col-md-6">
                            <input class="form-control" type="date" name="date" id="date">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="ladda-button btn btn-primary" dir="ltr"
                                data-style="expand-left" id="refresh-button">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <!-- TOTAL per LOKASI -->
    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <h5 class="mb-3">Total per Area</h5>
                <div id="colTotalsWrap" style="height: {{ max(420, count($allLoc)*26 + 140) }}px;">
                    <canvas id="colTotalsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

        <!-- DISTRIBUSI per VHC (stacked) -->
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Distribusi per Vehicle</h5>
                    <!-- Tinggi dinamis agar semua label VHC terbaca -->
                    <div id="vhcStackWrap" style="height: {{ max(420, count($allVhc)*26 + 140) }}px;">
                        <canvas id="vhcStackedChart"></canvas>
                    </div>
                    <small class="text-muted d-block mt-2">
                        *Ambang highlight: Row ≥10 (kuning).
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="customTable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>VHC_ID</th>
                                @foreach ($allLoc as $loc)
                                <th style="text-align: center"
                                    class="vertical-text {{ $colTotals[$loc] >= 20 ? 'highlight-col' : '' }}">{{ $loc }}
                                </th>
                                @endforeach
                                <th style="text-align: center">Row Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allVhc as $vhc)
                            <tr class="{{ $rowTotals[$vhc] >= 10 ? 'highlight-row' : '' }}">
                                <td>{{ $vhc }}</td>
                                @foreach ($allLoc as $loc)
                                <td style="text-align: center"
                                    class="{{ $colTotals[$loc] >= 20 ? 'highlight-col' : '' }}">
                                    {{ $pivot[$vhc][$loc] ?? 0 }}
                                </td>
                                @endforeach
                                <td style="text-align: center">{{ $rowTotals[$vhc] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Col Total</th>
                                @foreach ($allLoc as $loc)
                                <th style="text-align: center"
                                    class="{{ $colTotals[$loc] >= 20 ? 'highlight-col' : '' }}">{{ $colTotals[$loc] }}
                                </th>
                                @endforeach
                                <th style="text-align: center">{{ $grandTotal }}</th>
                            </tr>
                        </tfoot>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->

</div>
@include('layout.footer')
<script>
    // --- simpan instance agar tidak dobel ---
    let colChart = null;
    let vhcChart = null;

    // data dari Blade (punya kamu sudah benar)
    const allLoc = @json($allLoc);
    const allVhc = @json($allVhc);
    const pivot = @json($pivot);
    const colTotals = @json($colTotals);
    const rowTotals = @json($rowTotals);

    const COL_THRESHOLD = 20;
    const ROW_THRESHOLD = 10;

    function colorFromIndex(i, alpha = 0.8) {
        const hue = (i * 47) % 360;
        return `hsla(${hue}, 60%, 55%, ${alpha})`;
    }

    // ===== CHART 1: Total per Lokasi =====
    (function renderColTotals() {
        const colLabels = allLoc.slice();
        const colData = colLabels.map(loc => colTotals[loc] || 0);
        const colBarColors = colData.map(v => v >= COL_THRESHOLD ? '#FCEF91' : '#4e79a7');
        const colBorder = colData.map(v => v >= COL_THRESHOLD ? '#c9b800' : '#2f4b6c');

        // HANCURKAN chart lama jika ada
        if (colChart) colChart.destroy();

        const ctx = document.getElementById('colTotalsChart').getContext('2d');
        colChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: colLabels,
                datasets: [{
                    label: 'Total',
                    data: colData,
                    backgroundColor: colBarColors,
                    borderColor: colBorder,
                    borderWidth: 1.2
                }]
            },
            options: {
                responsive: true,

                animation: false, // << matikan animasi (opsional)
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 60,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            afterBody(items) {
                                const v = items[0].parsed.y;
                                return v >= COL_THRESHOLD ? '≥ threshold 20 (highlight)' : '';
                            }
                        }
                    }
                }
            }
        });
    })();

    // ===== CHART 2: (kalau dipakai) juga pakai pola destroy =====
    (function renderVhcStacked() {
        const datasetsStacked = allLoc.map((loc, idx) => {
        const dataForLoc = allVhc.map(vhc => Number((pivot[vhc] || {})[loc] ?? 0));
        return {
            label: loc,
            data: dataForLoc,
            backgroundColor: colorFromIndex(idx, 0.85),
            borderColor: colorFromIndex(idx, 1),
            borderWidth: 1
        };
        });

        if (vhcChart) vhcChart.destroy();

        const ctx = document.getElementById('vhcStackedChart').getContext('2d');
        vhcChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: allVhc,
                datasets: datasetsStacked
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                animation: false, // << opsional
                scales: {
                    x: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    },
                    y: {
                        stacked: true,
                        ticks: {
                            callback(value) {
                                const vhc = this.getLabelForValue(value);
                                const total = rowTotals[vhc] || 0;
                                return total >= ROW_THRESHOLD ? `• ${vhc}` : vhc;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 14
                        }
                    },
                    tooltip: {
                        callbacks: {
                            footer: (items) => {
                                const vhc = items[0].label;
                                return `Total VHC ${vhc}: ${rowTotals[vhc] || 0}`;
                            }
                        }
                    }
                }
            }
        });
    })();

</script>


<script>
    function getQueryParam(name, defaultValue) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name) || defaultValue;
    }

    const currentDate = new Date();

    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = ('0' + (date.getMonth() + 1)).slice(-2);
        const day = ('0' + date.getDate()).slice(-2);
        return `${year}-${month}-${day}`;
    };
    const dateDefault = getQueryParam('date', formatDate(currentDate));

    // Set nilai pada elemen input
    document.getElementById('date').value = dateDefault;

</script>
<script>
    $(document).ready(function () {
        $('#customTable').DataTable({
            "pageLength": 50,
            "order": [
                [1, 'desc']
            ],
            "columnDefs": [{
                "orderable": false,
                "targets": 0
            }]
        });
    });

</script>

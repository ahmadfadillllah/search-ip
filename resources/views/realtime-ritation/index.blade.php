@include('layout.head', ["title" => "Realtime Ritation"])
@include('layout.sidebar')
@include('layout.header')

<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="page-title mb-0">Realtime Ritation</h4>
            </div>
            <div class="col-lg-4">
                <form action="" method="GET">
                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label" for="date">Date: </label>
                        <div class="col-md-6">
                            <input class="form-control" type="date" name="date" id="date">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="ladda-button btn btn-primary" dir="ltr" data-style="expand-left" id="refresh-button">
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
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table id="selection-datatable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Code</th>
                                <th rowspan="2">Date</th>
                                <th rowspan="2">Range Hour</th>
                                <th colspan="4">FMS</th>
                                <th rowspan="2">Action</th>
                            </tr>
                            <tr>
                                <th>FMS Rate Total</th>
                                <th>FMS Rate Realtime</th>
                                <th>FMS Rate Not Realtime</th>
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
                            @foreach($data as $dt)
                            @php
                                $totalRitRealtime += $dt->N_RIT_REALTIME;
                                $totalRitNotRealtime += $dt->N_RIT_NOT_REALTIME;
                                $totalRateFMS += $dt->N_RATEFMS;
                            @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dt->CODE }}</td>
                                    <td>{{ $dt->DATE }}</td>
                                    <td>{{ $dt->RANGEJAM }}</td>
                                    <td>{{ $dt->N_RATEFMS }}</td>
                                    <td>{{ $dt->N_RIT_REALTIME }}</td>
                                    <td>{{ $dt->N_RIT_NOT_REALTIME }}</td>
                                    <td>{{ number_format($dt->PERCENTAGE_REALTIME * 100, 1) }}%</td>
                                    <td>
                                        @if ($dt->N_RIT_NOT_REALTIME > 0 )
                                            <a href="{{ route('realtimeritation.notrealtime', [$dt->DATE, $dt->RANGEJAM]) }}" class="btn btn-info btn-xs waves-effect waves-light">Show Not Realtime</a>
                                        @endif
                                    </td>
                                    {{-- <td><a href="{{ route('realtimeritation.notrealtime', [$dt->DATE, $dt->RANGEJAM]) }}">Show Not Realtime</a></td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                        @php
                            // Menangani Division by Zero
                            $totalPercentageRealtime = ($totalRateFMS != 0) ? ($totalRitRealtime / $totalRateFMS) : 0;
                        @endphp
                        <tfoot>
                            <tr style="background-color:#ffcbc2">
                                <td colspan="4"><strong>Total</strong></td>
                                <td>{{ $totalRateFMS }}</td>
                                <td>{{ $totalRitRealtime }}</td>
                                <td>{{ $totalRitNotRealtime }}</td>
                                <td>{{ number_format($totalPercentageRealtime * 100, 1) }}%</td>

                                <td></td> <!-- Untuk aksi Show Not Realtime jika diperlukan -->
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
    // Fungsi untuk mendapatkan parameter query dari URL
    function getQueryParam(name, defaultValue) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name) || defaultValue; // Mengembalikan nilai default jika parameter tidak ditemukan
    }

    const currentDate = new Date();

    // Format default (YYYY-MM-DD)
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = ('0' + (date.getMonth() + 1)).slice(-2); // Menambahkan leading zero jika perlu
        const day = ('0' + date.getDate()).slice(-2); // Menambahkan leading zero jika perlu
        return `${year}-${month}-${day}`;
    };

    // Mendapatkan parameter tanggal dari URL, jika ada, atau menggunakan tanggal saat ini sebagai default
    const dateDefault = getQueryParam('date', formatDate(currentDate));

    // Set nilai pada elemen input
    document.getElementById('date').value = dateDefault;
</script>

{{--
<script>
    $(document).ready(function() {
        $('#customTable').DataTable({
            "pageLength": 32, // Default page length
            "order": [[1, 'desc']], // Urutkan berdasarkan kolom "Average" (index 1) secara menurun
            "columnDefs": [
                { "orderable": false, "targets": 0 } // Nonaktifkan pengurutan di kolom "Equipment"
        ]
        });
    });
</script> --}}

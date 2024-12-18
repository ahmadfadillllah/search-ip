@include('layout.head', ["title" => "Periodic Realtime"])
@include('layout.sidebar')
@include('layout.header')

<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Periodic Realtime</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                        <li class="breadcrumb-item active">Periodic Realtime</li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-6">
                <form action="" method="GET">
                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label" for="startDate">Start Date: </label>
                        <div class="col-md-3">
                            <input class="form-control" type="date" name="startDate" id="startDate">
                        </div>
                        <label class="col-md-2 col-form-label" for="endDate">End Date: </label>
                        <div class="col-md-3">
                            <input class="form-control" type="date" name="endDate" id="endDate">
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

                    <table id="customTable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Equipment</th>
                                <th>Total Not Realtime</th>
                                <th>Total Realtime</th>
                                <th>Average</th>
                                @foreach($displayData[0] as $date => $value)
                                    @if($date != 'Equipment' && $date != 'Average' && $date != 'TOTAL_NOT_REALTIME' && $date != 'TOTAL_REALTIME')
                                        <th>{{ $date }}</th>
                                    @endif
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($displayData as $data)
                                <tr>
                                    <td>{{ $data['Equipment'] }}</td>
                                    <td>{{ $data['TOTAL_NOT_REALTIME'] }}</td>
                                    <td>{{ $data['TOTAL_REALTIME'] }}</td>
                                    <td>{{ round($data['Average']) }}%</td>
                                    @foreach($displayData[0] as $date => $value)
                                        @if($date != 'Equipment' && $date != 'Average' && $date != 'TOTAL_NOT_REALTIME' && $date != 'TOTAL_REALTIME')
                                            <td>{{ $data[$date] }}</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>

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

    // Ambil tanggal kemarin
    const currentDate = new Date();
    currentDate.setDate(currentDate.getDate() - 1); // Kurangi 1 hari untuk mendapatkan tanggal kemarin
    const yesterday = currentDate.toISOString().split('T')[0];  // Format 'YYYY-MM-DD'

    // Ambil nilai dari query string atau gunakan nilai default (kemarin)
    const startDate = getQueryParam('startDate', yesterday);  // Default ke tanggal kemarin jika tidak ada
    const endDate = getQueryParam('endDate', yesterday);  // Default ke tanggal kemarin jika tidak ada

    // Set nilai pada elemen input
    document.getElementById('startDate').value = startDate;
    document.getElementById('endDate').value = endDate;
</script>

<script>
    $(document).ready(function() {
        $('#customTable').DataTable({
            "pageLength": 20, // Default page length
            "order": [[1, 'desc']], // Urutkan berdasarkan kolom "Average" (index 1) secara menurun
            "columnDefs": [
                { "orderable": false, "targets": 0 } // Nonaktifkan pengurutan di kolom "Equipment"
        ]
        });
    });
</script>

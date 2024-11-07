@include('layout.head', ["title" => "Ritation Not Realtime"])
@include('layout.sidebar')
@include('layout.header')

<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="header-title">Daftar ritasi yang tidak realtime</h4>
            </div>
            <div class="col-lg-4">
                <form action="{{ route('ritation.index') }}" method="GET">
                    <div class="col-lg-12">
                        <div class="mb-2 row">
                            <label class="col-md-2 col-form-label" for="example-date">Start Date</label>
                            <div class="col-md-5">
                                <input class="form-control" type="date" name="startDate" id="startDate">
                            </div>
                            <div class="col-md-5">
                                <input class="form-control" type="time" name="startTime" id="startTime">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-2 row">
                            <label class="col-md-2 col-form-label" for="example-date">End Date</label>
                            <div class="col-md-5">
                                <input class="form-control" type="date" name="endDate" id="endDate">
                            </div>
                            <div class="col-md-5">
                                <input class="form-control" type="time" name="endTime" id="endTime">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="ladda-button btn btn-primary" dir="ltr" data-style="expand-left"
                        id="refresh-button">
                        Search
                    </button>
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
                                <th rowspan="2">Unit</th>
                                <th colspan="2">Report Time</th>
                                <th rowspan="2">Dumping Site</th>
                                <th rowspan="2">Time Diff</th>
                            </tr>
                            <tr>
                                <th>From Unit</th>
                                <th>From System</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($ritasi as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->VHC_ID }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->OPR_REPORTTIME)->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->SYS_CREATEDAT)->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $item->LOC_NAME }}</td>
                                    <td>{{ $item->TIME_DIFF }}</td>


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

    // Ambil nilai dari query string atau gunakan nilai default
    const currentDate = new Date();
    const today = currentDate.toISOString().split('T')[0];  // Format 'YYYY-MM-DD'

    const startDate = getQueryParam('startDate', today);  // Default ke tanggal hari ini jika tidak ada
    const startTime = getQueryParam('startTime', formatTimeToNearestHour(currentDate.getHours() - 1, currentDate.getMinutes()));  // Default ke waktu 1 jam sebelumnya
    const endDate = getQueryParam('endDate', today);  // Default ke tanggal hari ini jika tidak ada
    const endTime = getQueryParam('endTime', formatTimeToNearestHour(currentDate.getHours(), currentDate.getMinutes()));  // Default ke waktu saat ini

    // Set nilai pada elemen input
    document.getElementById('startDate').value = startDate;
    document.getElementById('endDate').value = endDate;
    document.getElementById('startTime').value = startTime;
    document.getElementById('endTime').value = endTime;

    // Fungsi untuk memformat waktu ke jam terdekat
    function formatTimeToNearestHour(hour, minute) {
        if (minute >= 30) {
            hour += 1;  // Jika menit >= 30, tambahkan 1 jam
        }
        return hour < 10 ? `0${hour}:00` : `${hour}:00`;  // Format waktu menjadi 'HH:00'
    }
</script>

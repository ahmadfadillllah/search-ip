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
</script>

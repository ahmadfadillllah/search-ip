@include('layout.head', ["title" => "Client"])
@include('layout.sidebar')
@include('layout.header')

<style>
        .loading {
        position: fixed;
        top: 50%; /* Posisi di tengah vertikal */
        left: 50%; /* Posisi di tengah horizontal */
        transform: translate(-50%, -50%); /* Pindah ke tengah */
        z-index: 9999; /* Pastikan di atas konten */
        transition: opacity 0.5s ease-in-out;
        color: #063970; /* Warna teks */
        font-size: 60px; /* Ukuran font */
        font-weight: bold; /* Tebal */
    }

    .text {
        display: flex;
        gap: 10px; /* Jarak antar huruf */
    }

    .text span {
        display: inline-block;
        animation: blink 2s ease-in-out infinite;
        opacity: 0.1; /* Opasitas awal */
    }

    @keyframes blink {
        0% { opacity: 0.1; }
        50% { opacity: 1; }
        100% { opacity: 0.1; }
    }

    .text span:nth-child(1) { animation-delay: 0s; }
    .text span:nth-child(2) { animation-delay: 0.4s; }
    .text span:nth-child(3) { animation-delay: 0.8s; }
    .text span:nth-child(4) { animation-delay: 1.2s; }
    .text span:nth-child(5) { animation-delay: 1.6s; }
    .text span:nth-child(6) { animation-delay: 2.0s; }
    .text span:nth-child(7) { animation-delay: 2.4s; }
    .text span:nth-child(8) { animation-delay: 2.8s; }

    .content {
        padding: 20px;
        background: rgba(249, 249, 249, 0.5);
        transition: opacity 0.5s ease-in-out;
    }

    .content.visible {
        opacity: 1;
    }
</style>
<div class="loading" id="loading" style="display:none;">
    <div class="text">
        <span>I</span>
        <span>T</span>
        <span> </span>
        <span>C</span>
        <span>R</span>
        <span>E</span>
        <span>W</span>
        <span>...</span>
    </div>
</div>
<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0" id="waktuSekarang">Last Refresh: </h4>
            </div>
            <div class="col-lg-6">
                <button class="ladda-button btn btn-primary" dir="ltr" data-style="expand-left" style="float: right" id="refresh-button">
                    Refresh
                </button>
                {{-- <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                        <li class="breadcrumb-item active">Clients</li>
                    </ol>
                </div> --}}
            </div>
        </div>
    </div>
    <!-- end page title -->



    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Detail Client</h4>
                    {{-- <p class="text-muted font-size-13 mb-4">
                        DataTables has most features enabled by default, so all you need to do to use it with your own
                        tables is to call the construction
                        function:
                        <code>$().DataTable();</code>.
                    </p> --}}

                    <table id="selection-datatable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Client</th>
                                <th>Connect To</th>
                                <th>IP</th>
                                <th>MAC</th>
                                <th>Durasi Connect</th>
                            </tr>
                        </thead>


                        <tbody>
                            {{-- @foreach($datas['data'] as $cl)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $cl['Name'] }}</td>
                                    <td>{{ $cl['AP name'] }}</td>
                                    <td>{{ $cl['IP'] }}</td>
                                    <td>{{ $cl['MAC'] }}</td>
                                    <td>{{ $cl['Age(d:h:m)'] }}</td>

                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->

</div>
@include('layout.footer')
{{-- <script>
    function clickRefreshButton() {
        var refreshButton = document.getElementById('refresh-button');
        refreshButton.click();
    }
    setInterval(clickRefreshButton, 20000);
</script> --}}

<script>
    function getData() {
        $('#loading').show(); // Tampilkan watermark
    $.ajax({
        url: "{{ route('client.api') }}", // Route yang mengembalikan data JSON
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            var table = $('#selection-datatable').DataTable({
                // Opsi DataTables tambahan bisa ditambahkan di sini
                "paging": true,
                "ordering": true,
                "info": true,
                "responsive": true,
                "destroy": true, // Menghancurkan tabel sebelum inisialisasi ulang
                // dll.
            });

            // Kosongkan dan tambahkan baris baru ke tabel
            table.clear().draw();
            var rowNumber = 1;
            $.each(response, function (index, item) {
                var row = [
                        rowNumber,
                        item['Name'],
                        item['AP name'],
                        item['IP'],
                        item['MAC'],
                        item['Age(d:h:m)']
                    ];
                    table.row.add(row).draw(false);
                    rowNumber++;
            });
            // $('#selection-datatable').addClass('table table-striped dt-responsive nowrap w-100');

        },
        error: function (xhr, status, error) {
            alert('Terjadi kesalahan');
        },
        complete: function() {
            $('#loading').hide(); // Sembunyikan watermark setelah selesai
        }
    });
    const sekarang = new Date();

    // Mendapatkan berbagai bagian dari tanggal dan waktu
    const tahun = sekarang.getFullYear();
    const bulan = sekarang.getMonth() + 1; // Bulan dimulai dari 0 (Januari) hingga 11 (Desember)
    const tanggal = sekarang.getDate();
    const jam = sekarang.getHours();
    const menit = sekarang.getMinutes();
    const detik = sekarang.getSeconds();

    // Format tanggal dan waktu
    const waktuFormat = `${tahun}-${bulan.toString().padStart(2, '0')}-${tanggal.toString().padStart(2, '0')} ${jam.toString().padStart(2, '0')}:${menit.toString().padStart(2, '0')}:${detik.toString().padStart(2, '0')}`;

    // Menampilkan hasil di elemen dengan id "waktuSekarang"
    document.getElementById('waktuSekarang').textContent = `Last Refresh: ${waktuFormat}`;
}

    $(document).ready(function () {
        // Memanggil fungsi getData() pertama kali saat dokumen siap
        getData();

        // Event handler untuk tombol "Refresh"
        $('#refresh-button').click(function () {
            getData(); // Panggil lagi fungsi getData() saat tombol "Refresh" ditekan
        });
    });

</script>


@include('layout.head', ["title" => "Topology"])
@include('layout.sidebar')
@include('layout.header')

<style>
    #data-list-container li {
    margin-bottom: 5px; /* Jarak antar item */
    padding: 1px; /* Ruang dalam item */
    border-bottom: 1px solid #ddd; /* Garis pemisah */
}

#data-list-container li:last-child {
    border-bottom: none; /* Hilangkan garis di item terakhir */
}

    /* .tree-view {
    list-style-type: none;
    padding-left: 0;
} */

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
{{-- <div id="loading" style="display:none;">Loading...</div> --}}
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
                <button class="ladda-button btn btn-primary" dir="ltr" data-style="expand-left" style="float: right"
                    id="refresh-button">
                    Refresh
                </button>

                {{-- <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                        <li class="breadcrumb-item active">Topology</li>
                    </ol>
                </div> --}}
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-9">

            <div class="card">
                <div class="card-body">

                    <h4 class="header-title">Topology</h4>
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
                                <th>Nama AP</th>
                                <th>RSSI</th>
                                <th>Last Update</th>
                                <th>Parent</th>
                                <th>Mesh Role</th>
                                <th>Path Cost</th>
                                <th>Node Cost</th>
                                <th>Link Cost</th>
                                <th>Hop Count</th>
                                <th>Rate Tx/Rx</th>
                                <th>Uplink Age</th>
                                {{-- <th>Children</th> --}}
                            </tr>
                        </thead>


                        <tbody>
                            {{-- @foreach($data as $groupName => $groupItems)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                            <td>{{ $groupName }}</td>
                            @foreach($groupItems as $item)
                            <td>{{ $item['Mesh Role'] }}</td>
                            <td>{{ $item['Parent'] }}</td>
                            <td>{{ $item['Path Cost'] }}</td>
                            <td>{{ $item['Node Cost'] }}</td>
                            <td>{{ $item['Link Cost'] }}</td>
                            <td>{{ $item['Hop Count'] }}</td>
                            <td>{{ $item['Rate Tx/Rx'] }}</td>
                            @if ($item['RSSI'] == 0 )
                            <td><button type="button"
                                    class="btn btn-dark waves-effect waves-light btn-xs">{{ $item['RSSI']  }}</button>
                            </td>
                            @elseif ($item['RSSI'] <= 20) <td><button type="button"
                                    class="btn btn-danger btn-xs">{{ $item['RSSI']  }}</button></td>
                                @else
                                <td><button type="button" class="btn btn-success btn-xs">{{ $item['RSSI']  }}</button>
                                </td>
                                @endif
                                <td>{{ $item['Last Update'] }}</td>
                                <td>{{ $item['Uplink Age'] }}</td>
                                @endforeach
                                </tr>
                                @endforeach --}}
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->

        <div class="col-3">

            <div class="card">
                <div class="card-body">

                    <h4 class="header-title">Tree View</h4>
                    <div id="data-list-container"></div>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div>
    </div>
    <!-- end row-->

</div>
@include('layout.footer')
{{-- <script>
    function clickRefreshButton() {
        var refreshButton = document.getElementById('refresh-button');
        refreshButton.click();
    }
    setInterval(clickRefreshButton, 15000);
</script> --}}

<script>
    function fetchDataAndUpdate() {
        $('#loading').show(); // Tampilkan watermark
        $.ajax({
            url: "{{ route('topology.api') }}", // Route yang mengembalikan data JSON
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                // Proses untuk DataTable
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
                $.each(response.data_topology, function (groupName, groupItems) {
                    $.each(groupItems, function (index, item) {
                        var row = [
                            rowNumber,
                            item.Name,
                            (item.RSSI == 0 ?
                                '<button type="button" class="btn btn-dark waves-effect waves-light btn-xs">' +
                                item.RSSI + '</button>' :
                                (item.RSSI <= 20 ?
                                    '<button type="button" class="btn btn-danger btn-xs">' +
                                    item.RSSI + '</button>' :
                                    '<button type="button" class="btn btn-success btn-xs">' +
                                    item.RSSI + '</button>'
                                )
                            ),
                            item['Last Update'],
                            item.Parent,
                            item['Mesh Role'],
                            item['Path Cost'],
                            item['Node Cost'],
                            item['Link Cost'],
                            item['Hop Count'],
                            item['Rate Tx/Rx'],
                            item['Uplink Age']
                        ];
                        table.row.add(row).draw(false);
                        rowNumber++;
                    });
                });

                // Proses untuk Tree View
                var container = $('#data-list-container');
                container.empty(); // Kosongkan kontainer sebelum menambahkan data

                var parentMap = {}; // Map untuk menyimpan children dari setiap parent
                var allItems = {}; // Map untuk menyimpan semua item dengan nama sebagai kunci

                // Memproses data untuk membangun parentMap dan allItems
                $.each(response.data_tree, function (parent, children) {
                    $.each(children, function (index, item) {
                        // Cek apakah item memiliki nama yang valid
                        if (item.Name) {
                            allItems[item.Name] = item; // Menyimpan item dengan nama sebagai kunci

                            // Menyimpan children di parentMap
                            if (item.Parent && !parentMap[item.Parent]) {
                                parentMap[item.Parent] = [];
                            }
                            if (item.Parent) {
                                parentMap[item.Parent].push(item.Name);
                            }
                        }
                    });
                });

                var displayedItems = {}; // Map untuk melacak item yang sudah ditampilkan

                // Fungsi rekursif untuk membangun HTML dari parent-child map
                function buildTree(parent) {
                    var ul = $('<ul class="tree-view"></ul>');

                    if (parentMap[parent]) {
                        // Urutkan children berdasarkan nama
                        var sortedChildren = parentMap[parent].sort();
                        $.each(sortedChildren, function (index, child) {
                            var item = allItems[child];
                            if (item && !displayedItems[child]) {
                                displayedItems[child] =
                                true; // Tandai item sebagai sudah ditampilkan
                                var li = $('<li></li>').text(child).addClass('tree-item');
                                ul.append(li);

                                // Tambahkan children ke parent
                                li.append(buildTree(child));
                            }
                        });
                    }

                    return ul;
                }

                // Menampilkan root level (parent yang tidak memiliki parent)
                $.each(response.data_tree, function (parent, children) {
                    // Pastikan parent tidak sudah ditampilkan sebelumnya
                    if (!displayedItems[parent] && parent) {
                        var li = $('<li></li>').text(parent).addClass('tree-item');
                        container.append(li);
                        li.append(buildTree(parent));

                        // Tandai parent sebagai sudah ditampilkan
                        displayedItems[parent] = true;
                    }
                });

            },

            error: function (xhr, status, error) {
                console.error('Terjadi kesalahan:', error);
                alert('Terjadi kesalahan: ' + error);
            },
            complete: function() {
                $('#loading').hide(); // Sembunyikan watermark setelah selesai
            }
        });
    }

    function getTime() {
        const sekarang = new Date();

        // Mendapatkan berbagai bagian dari tanggal dan waktu
        const tahun = sekarang.getFullYear();
        const bulan = sekarang.getMonth() + 1; // Bulan dimulai dari 0 (Januari) hingga 11 (Desember)
        const tanggal = sekarang.getDate();
        const jam = sekarang.getHours();
        const menit = sekarang.getMinutes();
        const detik = sekarang.getSeconds();

        // Format tanggal dan waktu
        const waktuFormat =
            `${tahun}-${bulan.toString().padStart(2, '0')}-${tanggal.toString().padStart(2, '0')} ${jam.toString().padStart(2, '0')}:${menit.toString().padStart(2, '0')}:${detik.toString().padStart(2, '0')}`;

        // Menampilkan hasil di elemen dengan id "waktuSekarang"
        document.getElementById('waktuSekarang').textContent = `Last Refresh: ${waktuFormat}`;
    }

    $(document).ready(function () {
        // Memanggil fungsi getData() pertama kali saat dokumen siap
        fetchDataAndUpdate();
        getTime();
    });

    $('#refresh-button').click(function () {
        fetchDataAndUpdate(); // Panggil lagi fungsi getData() saat tombol "Refresh" ditekan
        getTime();

    });

</script>

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
        <div class="row align-items-center">
            <div class="col-lg-6 d-flex align-items-center">
                <h4 class="page-title mb-0" id="waktuSekarang">Last Refresh:</h4>
            </div>
            <div class="col-lg-6 d-flex justify-content-end gap-2">
                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#history-button">
                    History
                </button>
                <button class="ladda-button btn btn-primary" dir="ltr" data-style="expand-left" id="refresh-button">
                    Refresh
                </button>
            </div>
            @include('topology.modal.history')
        </div>
    </div>

    <!-- end page title -->

    <div class="row">
        <div class="col-9">

            <div class="card">
                <div class="card-body">

                    <h4 class="header-title">Topology</h4>
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

<script>
    function fetchDataAndUpdate() {
        $('#loading').show();
        $.ajax({
            url: "{{ route('topology.api') }}",
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                var table = $('#selection-datatable').DataTable({
                    "paging": true,
                    "ordering": true,
                    "info": true,
                    "responsive": true,
                    "destroy": true,
                });

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

                var container = $('#data-list-container');
                container.empty();

                var parentMap = {};
                var allItems = {};

                $.each(response.data_tree, function (parent, children) {
                    $.each(children, function (index, item) {
                        if (item.Name) {
                            allItems[item.Name] = item;

                            if (item.Parent && !parentMap[item.Parent]) {
                                parentMap[item.Parent] = [];
                            }
                            if (item.Parent) {
                                parentMap[item.Parent].push(item.Name);
                            }
                        }
                    });
                });

                var displayedItems = {};
                function buildTree(parent) {
                    var ul = $('<ul class="tree-view"></ul>');

                    if (parentMap[parent]) {
                        var sortedChildren = parentMap[parent].sort();
                        $.each(sortedChildren, function (index, child) {
                            var item = allItems[child];
                            if (item && !displayedItems[child]) {
                                displayedItems[child] =
                                true;
                                var li = $('<li></li>').text(child).addClass('tree-item');
                                ul.append(li);

                                li.append(buildTree(child));
                            }
                        });
                    }

                    return ul;
                }

                $.each(response.data_tree, function (parent, children) {
                    if (!displayedItems[parent] && parent) {
                        var li = $('<li></li>').text(parent).addClass('tree-item');
                        container.append(li);
                        li.append(buildTree(parent));

                        displayedItems[parent] = true;
                    }
                });

            },

            error: function (xhr, status, error) {
                console.error('Terjadi kesalahan:', error);
                alert('Terjadi kesalahan: ' + error);
            },
            complete: function() {
                $('#loading').hide();
            }
        });
    }

    function getTime() {
        const sekarang = new Date();

        const tahun = sekarang.getFullYear();
        const bulan = sekarang.getMonth() + 1;
        const tanggal = sekarang.getDate();
        const jam = sekarang.getHours();
        const menit = sekarang.getMinutes();
        const detik = sekarang.getSeconds();

        const waktuFormat =
            `${tahun}-${bulan.toString().padStart(2, '0')}-${tanggal.toString().padStart(2, '0')} ${jam.toString().padStart(2, '0')}:${menit.toString().padStart(2, '0')}:${detik.toString().padStart(2, '0')}`;

        document.getElementById('waktuSekarang').textContent = `Last Refresh: ${waktuFormat}`;
    }

    $(document).ready(function () {
        fetchDataAndUpdate();
        getTime();
    });

    $('#refresh-button').click(function () {
        fetchDataAndUpdate();
        getTime();

    });

</script>

<script>
$(document).ready(function () {
    $('#search-button').on('click', function () {
        const startDate = $('#startDate').val();
        const startHour = $('#startHour').val();

        if (!startDate ) {
            alert('Silakan pilih tanggal dulu.');
            return;
        }

        $.ajax({
            url: "{{ route('topology.history') }}",
            type: 'GET',
            data: {
                startDate: startDate,
                startHour: startHour,
            },
            success: function (response) {
                const table = $('#history-datatable');
                const tbody = table.find('tbody');
                tbody.empty();

                if (response.length === 0) {
                    tbody.append('<tr><td colspan="12" class="text-center">Data tidak ditemukan.</td></tr>');
                } else {
                    response.forEach((item, index) => {
                        const rssiClass = item.RSSI == 0 ? 'dark' : item.RSSI <= 20 ? 'danger' : 'success';
                        const row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.NAME}</td>
                                <td><button class="btn btn-${rssiClass} btn-xs">${item.RSSI}</button></td>
                                <td>${item.LAST_UPDATED}</td>
                                <td>${item.PARENT}</td>
                                <td>${item.MESH_ROLE}</td>
                                <td>${item.PATH_COST}</td>
                                <td>${item.NODE_COST}</td>
                                <td>${item.LINK_COST}</td>
                                <td>${item.HOP_COUNT}</td>
                                <td>${item.RATE_TX_RX ?? '-'}</td>
                                <td>${item.UPLINK_AGE}</td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                }

                // Tampilkan tabel
                table.show();
            },
            error: function () {
                alert("Terjadi kesalahan saat mengambil data.");
            }
        });
    });
});

</script>

@include('layout.head', ["title" => "Access Point"])
@include('layout.sidebar')
@include('layout.header')

<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Access Point</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                        <li class="breadcrumb-item active">Access Point</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Daftar Access Point</h4>
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
                                <th>IP Address</th>
                                <th>AP Type</th>
                                <th>MAC Address</th>
                                <th>Serial Number</th>
                                <th>Status</th>
                                <th>Details</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>


                        <tbody>
                            @foreach($data as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ $item['Name'] }}</td>
                                <td>{{ $item['IP Address'] }}</td>
                                <td>{{ $item['AP Type'] }}</td>
                                <td>{{ $item['Wired MAC Address'] }}</td>
                                <td>{{ $item['Serial #'] }}</td>
                                <td>
                                    @if ($item['Status'] == 'Down')
                                        <div style="color: red">{{ $item['Status']  }}</div>
                                    @else
                                        <div style="color: green">{{ $item['Status']  }}</div>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-info btn-xs" href="{{ route('access_point.details', $item['Name'] ) }}">Show</a>
                                    {{-- @if ($item['Status'] == 'Down') --}}
                                    {{-- <button type="button" class="btn btn-warning btn-xs reboot-btn" data-ip="{{ $item['Name'] }}|{{ $item['IP Address'] }}|{{ $item['Status']  }}">Reboot</button> --}}
                                    {{-- @endif --}}

                                </td>
                                {{-- <td>
                                    <a href="{{ route('ping', $item['IP Address'] ) }}">Ping</a>
                                </td> --}}
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
    $(document).ready(function() {
        $('#customTable').DataTable({
            "pageLength": 50, // Default page length
        });
    });
</script>
<script>
    // Event listener untuk tombol reboot
    $('.reboot-btn').click(function() {
        var ip = $(this).data('ip');
        var parts = ip.split('|');

        var name = parts[0];
        var ipAddress = parts[1];
        var statusAP = parts[2];

        Swal.fire({
            title: "Harap pastikan Access Point bisa diping/reply!",
            text: "AP yang akan direboot: " + name,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, reboot sekarang!"
        }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan loading / processing
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang mengirim perintah reboot ke Access Point.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Kirim request POST ke API Laravel untuk reboot
            $.ajax({
                url: '{{ route("access_point.reboot") }}',
                type: 'POST',
                data: {
                    ip: ipAddress,
                    apName: name,
                    statusAP: statusAP,
                    _token: '{{ csrf_token() }}' // CSRF token
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Information!',
                        text: response.message,
                        icon: 'info'
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat memproses permintaan.',
                        icon: 'error'
                    });
                }
            });
        }
    });

    });
</script>

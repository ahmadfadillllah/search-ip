@include('layout.head', ["title" => "Units"])
@include('layout.sidebar')
@include('layout.header')

<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Clients</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                        <li class="breadcrumb-item active">Clients</li>
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
                    <h4 class="header-title">Clients</h4>
                    {{-- <p class="text-muted font-size-13 mb-4">
                        DataTables has most features enabled by default, so all you need to do to use it with your own
                        tables is to call the construction
                        function:
                        <code>$().DataTable();</code>.
                    </p> --}}

                    <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Unit</th>
                                <th>Tipe</th>
                                <th>IP Address</th>
                                <th>Version</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($unit as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->VHC_ID }}</td>
                                <td>{{ $item->EQU_TYPEID }}</td>
                                <td>{{ $item->NET_IPADDRESS }}</td>
                                <td>{{ $item->APP_VERSION }}</td>
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
            "pageLength": 25, // Default page length
        });
    });
</script>

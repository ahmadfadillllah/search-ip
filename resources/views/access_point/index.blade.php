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

                    <table id="customTable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama AP</th>
                                <th>IP Address</th>
                                <th>AP Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach($data as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ $item['Name'] }}</td>
                                <td>{{ $item['IP Address'] }}</td>
                                <td>{{ $item['AP Type'] }}</td>
                                <td>
                                    @if ($item['Status'] == 'Down')
                                        <div class="btn btn-danger width-xs waves-effect waves-light btn-sm" role="alert">
                                            {{ $item['Status'] }}
                                        </div>
                                    @else
                                        <div class="btn btn-success width-xs waves-effect waves-light btn-sm" role="alert">
                                            {{ $item['Status'] }}
                                        </div>
                                    @endif
                                </td>
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

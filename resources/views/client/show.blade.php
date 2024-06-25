@include('layout.head', ["title" => "Show Client"])
@include('layout.sidebar')
@include('layout.header')

<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Show Clients {{ $data['ap_name'] }}</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                        <li class="breadcrumb-item active">Show Clients  {{ $data['ap_name'] }}</li>
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
                    <h4 class="header-title">Total Clients: <a href="#" type="button" class="btn btn-soft-primary waves-effect waves-light">{{ $data['total'] }}</a> </h4>
                    {{-- <p class="text-muted font-size-13 mb-4">
                        DataTables has most features enabled by default, so all you need to do to use it with your own
                        tables is to call the construction
                        function:
                        <code>$().DataTable();</code>.
                    </p> --}}

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Client</th>
                                <th>IP Address</th>
                                <th>Mac</th>
                                <th>Durasi Connect</th>
                                <th>Profile</th>
                                <th>Cara Connect</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach($data['datas'] as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['Name'] }}</td>
                                    <td>{{ $item['IP'] }}</td>
                                    <td>{{ $item['MAC'] }}</td>
                                    <td>{{ $item['Age(d:h:m)'] }}</td>
                                    <td>{{ $item['Profile'] }}</td>
                                    <td>{{ $item['Roaming'] }}</td>

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

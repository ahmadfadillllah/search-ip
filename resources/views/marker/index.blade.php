@include('layout.head', ["title" => "Marker Tower"])
@include('layout.sidebar')
@include('layout.header')

<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Marker Tower</h4>
            </div>
            <div class="col-lg-6">
               <div class="d-none d-lg-block">
                <ol class="breadcrumb m-0 float-end">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                    <li class="breadcrumb-item active">Marker Tower</li>
                </ol>
               </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name Tower</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Icon</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($marker as $item)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->lat }}</td>
                                        <td>{{ $item->long }}</td>
                                        <td>{{ $item->icon }}</td>
                                        <td>
                                            <div class="button-list">
                                                <a href="#" class="btn btn-warning btn-bordered rounded-pill waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#editmarker{{$item->id}}">Edit</a>
                                                {{-- <a href="#" class="btn btn-danger btn-bordered rounded-pill waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#deletemarker{{$item->id}}">Delete</a> --}}
                                            </div>
                                        </td>
                                    </tr>
                                    @include('marker.modal.edit')
                                    {{-- @include('marker.modal.delete') --}}
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- end table-responsive-->
                </div>
            </div> <!-- end card -->
        </div> <!-- end col -->
    </div>

</div>
@include('layout.footer')


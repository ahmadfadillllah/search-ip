@include('layout.head', ["title" => "Periodic Realtime"])
@include('layout.sidebar')
@include('layout.header')

<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Ritation Not Realtime in {{ $data[0]->VHC_ID }}</h4>
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
                                <th>No</th>
                                <th>FROM UNIT</th>
                                <th>FROM SYSTEM</th>
                                <th>LATENCY (Minutes)</th>
                                <th>Shift Date</th>
                                <th>Shift</th>
                                <th>LOCATION LOADER</th>
                                <th>LOCATION DUMPING</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $dt)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dt->OPR_REPORTTIME }}</td>
                                    <td>{{ $dt->SYS_CREATEDAT }}</td>
                                    <td>{{ $dt->LATENCY }}</td>
                                    <td>{{ $dt->OPR_SHIFTDATE }}</td>
                                    @if ($dt->SHIFT == 6)
                                        <td>Siang</td>
                                    @else
                                        <td>Malam</td>
                                    @endif
                                    <td>{{ $dt->LOC_LOADER }}</td>
                                    <td>{{ $dt->LOC_DUMPING }}</td>
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

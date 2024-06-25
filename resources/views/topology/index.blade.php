@include('layout.head', ["title" => "Topology"])
@include('layout.sidebar')
@include('layout.header')

<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Topology</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                        <li class="breadcrumb-item active">Topology</li>
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
                    <h4 class="header-title">Topology</h4>
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
                                <th>Mesh Role</th>
                                <th>Parent</th>
                                <th>RSSI</th>
                                <th>Last Update</th>
                                {{-- <th>Children</th> --}}
                            </tr>
                        </thead>


                        <tbody>
                            @foreach($data as $groupName => $groupItems)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $groupName }}</td>
                                    @foreach($groupItems as $item)
                                        <td>{{ $item['Mesh Role'] }}</td>
                                        <td>{{ $item['Parent'] }}</td>
                                        @if ($item['RSSI'] == 0 )
                                        <td style="color: black">{{ $item['RSSI']  }}</td>
                                        @elseif ($item['RSSI']  <= 20)
                                        <td style="color: red">{{ $item['RSSI']  }}</td>
                                        @else
                                        <td style="color: green">{{ $item['RSSI'] }} </td>
                                        @endif
                                        <td>{{ $item['Last Update'] }}</td>
                                        {{-- <td>{{ $item['Children'] }}</td> --}}
                                    @endforeach
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

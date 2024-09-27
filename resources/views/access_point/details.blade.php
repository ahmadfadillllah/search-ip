@include('layout.head', ["title" => "Access Point"])
@include('layout.sidebar')
@include('layout.header')
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 16px;
        text-align: left;
    }

    th,
    td {
        padding: 12px;
        border: 1px solid #ddd;
    }

    th {
        background-color: #333;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:nth-child(odd) {
        background-color: #fff;
    }

    tr:hover {
        background-color: #ddd;
    }

</style>
<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Details Access Point</h4>
            </div>
            <div class="col-lg-6">
                <a href="{{ url()->previous() }}" class="btn btn-primary" style="float: right">
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- <h4 class="header-title">Daftar Access Point</h4> --}}
                    {{-- <p class="text-muted font-size-13 mb-4">
                        DataTables has most features enabled by default, so all you need to do to use it with your own
                        tables is to call the construction
                        function:
                        <code>$().DataTable();</code>.
                    </p> --}}

                    @foreach ($data as $title => $rows)
                    <h2>{{ $title }}</h2>
                    <table class="table table-striped dt-responsive nowrap w-100">
                        <tr>
                            <th>Item</th>
                            <th>Source</th>
                            <th>Value</th>
                        </tr>
                        @foreach ($rows as $row)
                        <tr>
                            <td>{{ $row['Item'] ?? 'N/A'  }}</td>
                            <td>{{ $row['Source'] ?? 'N/A'  }}</td>
                            <td>{{ $row['Value'] ?? 'N/A'  }}</td>
                        </tr>
                        @endforeach
                    </table>
                    @endforeach

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->

</div>
@include('layout.footer')
{{-- <script>
    $(document).ready(function() {
        $('#customTable').DataTable({
            "pageLength": 50, // Default page length
        });
    });
</script> --}}

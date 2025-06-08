@include('layout.head', ["title" => "Connectivity Rate"])
@include('layout.sidebar')
@include('layout.header')
<style>
    #customTable th.highlight-col {
        background-color: #FCEF91 !important;
        color: black !important;
        text-align: center
    }

    /* Untuk baris yang dihighlight */
    #customTable tr.highlight-row {
        background-color: #FCEF91 !important;
        color: black !important;
    }
    #customTable td.highlight-col {
        background-color: #FCEF91 !important;
        color: black !important;
    }

    #customTable tr.highlight-row > td {
        background-color: #FCEF91 !important;
        color: black !important;
    }
    th.vertical-text {
    height: 120px;
    white-space: nowrap;
    writing-mode: vertical-rl;
    transform: rotate(180deg);
    text-align: center;
    vertical-align: middle;

    /* padding: 0px 0px; */
}
</style>
<div class="container-fluid">

    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="page-title mb-0">Connectivity Rate</h4>
            </div>
            <div class="col-lg-4">
                <form action="" method="GET">
                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label" for="date">Date: </label>
                        <div class="col-md-6">
                            <input class="form-control" type="date" name="date" id="date">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="ladda-button btn btn-primary" dir="ltr" data-style="expand-left" id="refresh-button">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
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
                                <th>VHC_ID</th>
                                @foreach ($allLoc as $loc)
                                    <th style="text-align: center" class="vertical-text {{ $colTotals[$loc] >= 20 ? 'highlight-col' : '' }}">{{ $loc }}</th>
                                @endforeach
                                <th style="text-align: center">Row Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allVhc as $vhc)
                                <tr class="{{ $rowTotals[$vhc] >= 10 ? 'highlight-row' : '' }}">
                                    <td>{{ $vhc }}</td>
                                    @foreach ($allLoc as $loc)
                                        <td style="text-align: center" class="{{ $colTotals[$loc] >= 20 ? 'highlight-col' : '' }}">
                                            {{ $pivot[$vhc][$loc] ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td style="text-align: center">{{ $rowTotals[$vhc] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Col Total</th>
                                @foreach ($allLoc as $loc)
                                    <th style="text-align: center" class="{{ $colTotals[$loc] >= 20 ? 'highlight-col' : '' }}">{{ $colTotals[$loc] }}</th>
                                @endforeach
                                <th style="text-align: center">{{ $grandTotal }}</th>
                            </tr>
                        </tfoot>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->

</div>
@include('layout.footer')
<script>
    function getQueryParam(name, defaultValue) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name) || defaultValue;
    }

    const currentDate = new Date();

    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = ('0' + (date.getMonth() + 1)).slice(-2);
        const day = ('0' + date.getDate()).slice(-2);
        return `${year}-${month}-${day}`;
    };
    const dateDefault = getQueryParam('date', formatDate(currentDate));

    // Set nilai pada elemen input
    document.getElementById('date').value = dateDefault;
</script>
<script>
    $(document).ready(function() {
        $('#customTable').DataTable({
            "pageLength": 50,
            "order": [[1, 'desc']],
            "columnDefs": [
                { "orderable": false, "targets": 0 }
        ]
        });
    });
</script>



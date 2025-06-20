<!-- Modal -->
<div id="history-button" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header d-flex align-items-center justify-content-between gap-2" style="flex-wrap: nowrap;">
                <h4 class="modal-title mb-0" id="fullWidthModalLabel">History Topology</h4>

                <!-- Bukan form lagi -->
                <div class="d-flex align-items-center gap-2 mb-0">
                    <label for="startDate" class="form-label mb-0">Date:</label>
                    <input class="form-control form-control-sm" type="date" id="startDate" required>

                    <label for="startHour" class="form-label mb-0">Hour:</label>
                    <select class="form-select form-select-sm" id="startHour" required>
                        @for ($i = 0; $i < 24; $i++)
                            <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d:00', $i) }}</option>
                        @endfor
                    </select>

                    <button type="button" class="btn btn-primary btn-sm ms-2" id="search-button">
                        Search
                    </button>
                </div>

                <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <table id="history-datatable" class="table table-striped dt-responsive nowrap w-100" style="display: none;">
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
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>


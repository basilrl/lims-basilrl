<link rel="stylesheet" href="<?php echo base_url('assets/dataTables/css/dataTables.bootstrap4.min.css'); ?>">

<script src="<?php echo base_url('assets/dataTables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/dataTables/js/dataTables.bootstrap4.min.js'); ?>"></script>

<div class="container-fluid" style="margin-top: 50px; min-height: 520px;">
    <div class="card shadow-sm">
        <div class="card-header bg-info">
            <h4 class="mb-0">Approve Revise Report</h4>
        </div>
        <div class="card-body">
            <form autocomplete="off">
                <table id="container_data" class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr class="bg-info text-light">
                            <th>SL</th>
                            <th>ANALYSIS NO</th>
                            <th>REQUEST FOR</th>
                            <th>REQUEST BY</th>
                            <th>REQUESTER REASON</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                </table>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="report_approve_reject_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Revise Report - <b id="report_approve_reject_report_num"></b></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-5">
                <form method="post" id="report_approve_reject_form">
                    <input type="hidden" class="form-control" id="report_approve_reject_report_id" />
                    <input type="hidden" class="form-control" id="report_approve_reject_action" />
                    <div class="form-group">
                        <label for="report_approve_reject_reason">Reason:</label>
                        <textarea class="form-control" id="report_approve_reject_reason" rows="5" placeholder="Type reason..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="report_approve_reject_submit">Send Request</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="approve_revise_report_log_modal">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content bg-light border-0">
            <div class="modal-header bg-info">
                <h5 class="modal-title">LOG DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-5 mb-5 text-center text-primary" id="approve_revise_report_log_loader">
                    <h4>Please wait...</h4>
                </div>
                <span id="approve_revise_report_log_data"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-bs-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<script>
    const _tokken = $('meta[name="_tokken"]').attr("value");

    $(document).ready(function() {

        var dataTable = $('#container_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "language": {
                searchPlaceholder: "Analysis & Request by..."
            },
            "ajax": {
                url: "<?php echo base_url('Approve_Revise_Report/fetch_records'); ?>",
                type: "post",
                dataType: "json",
                data: {
                    _tokken: _tokken
                },
                data: function(data) {
                    data._tokken = $('meta[name="_tokken"]').attr("value");
                }
            },
            "columnDefs": [{
                "targets": [4, 5],
                "orderable": false,
            }, ],
        });

        $(document).on('click', '.report_approve_reject', function() {
            let report_id = $(this).attr('report_id');
            let report_num = $(this).attr('report_num');
            let action = $(this).attr('action');
            if (report_id.length > 0 && report_num.length > 0 && action.length > 0) {
                $('#report_approve_reject_modal').modal('show');
                $('#report_approve_reject_report_id').val(report_id);
                $('#report_approve_reject_report_num').text(report_num);
                $('#report_approve_reject_action').val(action);
                return false;
            }
        });

        $(document).on('click', '#report_approve_reject_submit', function() {
            let report_id = $('#report_approve_reject_report_id').val();
            let action = $('#report_approve_reject_action').val();
            let reason = $('#report_approve_reject_reason').val();
            if (report_id.length > 0 && action.length > 0 && reason.length > 0) {
                $.ajax({
                    type: 'post',
                    url: '<?php echo base_url("Approve_Revise_Report/report_approve_reject"); ?>',
                    data: {
                        _tokken: _tokken,
                        report_id: report_id,
                        action: action,
                        reason: reason
                    },
                    beforeSend: function() {
                        $('body').append('<div id="pageloader" class="pageloader"></div>');
                        $('#report_approve_reject_submit').prop('disabled', true);
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.code == 1) {
                            $.notify(data.message, "success");
                            window.location.reload();
                        } else {
                            $('#pageloader').removeClass('pageloader'); 
                            $.notify(data.message, "error");
                        }
                    }
                });
            } else {
                $("#report_approve_reject_reason").notify(
                    "Reason is required!", {
                        position: "bottom center"
                    }
                );
            }
        });

        $(document).on("click", ".report_log", function() {
            let id = $(this).attr('sample_reg_id');
            if (id.length > 0) {
                $.ajax({
                    url: "<?php echo base_url('Approve_Revise_Report/report_log'); ?>",
                    method: "post",
                    data: {
                        record_id: id,
                        _tokken: _tokken
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#approve_revise_report_log_data').html('');
                        $('#approve_revise_report_log_loader').removeClass('d-none');
                        $('#approve_revise_report_log_modal').modal('show');
                    },
                    success: function(data) {
                        $('#approve_revise_report_log_loader').addClass('d-none');
                        let html = '<table class="table table-sm table-striped" id="log-details-table"><thead><tr class="bg-primary text-light">';
                        html += '<th>SL</th>';
                        html += '<th>ACTION</th>';
                        html += '<th>ACTION BY</th>';
                        html += '<th>ACTION ON</th></tr></thead><tbody>';
                        $.each(data, function(key, value) {
                            html += '<td>' + (parseInt(key) + 1) + '</td>';
                            html += '<td>' + value.action_message + '</td>';
                            html += '<td>' + value.full_name + '</td>';
                            html += '<td>' + value.activity_on + '</td></tr>';
                        });
                        html += '</tbody></table>';
                        $('#approve_revise_report_log_data').html(html);
                        $('#log-details-table').dataTable();
                        return false;
                    }
                });
            }
        });
    });
</script>
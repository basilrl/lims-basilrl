<link rel="stylesheet" href="<?php echo base_url('assets/dataTables/css/dataTables.bootstrap4.min.css'); ?>">

<script src="<?php echo base_url('assets/dataTables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/dataTables/js/dataTables.bootstrap4.min.js'); ?>"></script>

<div class="container-fluid" style="margin-top: 70px; min-height: 550px;">
    <div class="card shadow-sm">
        <div class="card-header bg-info">
            <h4 class="mb-0">Total Conversations
                <a href="<?php echo base_url('Bot_Configuration'); ?>" class="btn btn-sm btn-primary float-right">BACK</a>
            </h4>
        </div>
        <div class="card-body">
            <form autocomplete="off">
                <table id="container_data" class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr class="bg-info text-light">
                            <th>SL</th>
                            <th>SENDER ID</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                </table>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="conversation_details_modal">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content bg-light border-0">
            <div class="modal-header bg-info">
                <h5 class="modal-title">CONVERSATIONS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form autocomplete="off">
                    <div class="table-responsive">
                        <table id="conversation_details_data" class="table table-sm table-hover w-100">
                            <thead>
                                <tr class="bg-primary text-light">
                                    <th>SL</th>
                                    <th>NAME</th>
                                    <th>MESSAGE</th>
                                    <th>TIMING</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </form>
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
                searchPlaceholder: "Sender ID..."
            },
            "ajax": {
                url: "<?php echo base_url('Bot_Configuration/fetch_conversation'); ?>",
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
                "targets": [2],
                "orderable": false,
            }, ],
        });

        $(document).on('click', '.conversation_details', function () {
            let id = $(this).attr('data-id');
            if (id.length > 0) {
                if ($.fn.DataTable.isDataTable('#conversation_details_data')) {
                    $('#conversation_details_data').DataTable().destroy();
                }
                $('#conversation_details_data tbody').empty();
                $('#conversation_details_modal').modal('show');
                let dataTableLog = $('#conversation_details_data').DataTable({
                    "destroy": true,
                    "processing": true,
                    "serverSide": true,
                    "searching": false,
                    "order": [],
                    "language": {
                        searchPlaceholder: "Message..."
                    },
                    "ajax": {
                        url: "<?php echo base_url('Bot_Configuration/conversation_details'); ?>",
                        type: "post",
                        dataType: "json",
                        data: function (data) {
                            data.sender_id = id, 
                            data._tokken = _tokken
                        }
                    },
                    "columnDefs": [{
                        "targets": [0],
                        "orderable": false,
                    },],
                });
            }
        });

        // $(document).on("click", ".conversation_details", function() {
        //     let id = $(this).attr('data-id');
        //     if (id.length > 0) {
        //         $.ajax({
        //             url: "<?php echo base_url('Bot_Configuration/conversation_details'); ?>",
        //             method: "post",
        //             data: {
        //                 sender_id: id,
        //                 _tokken: _tokken
        //             },
        //             dataType: 'html',
        //             beforeSend: function() {
        //                 $('#conversation_details_data').html('');
        //                 $('#conversation_details_loader').removeClass('d-none');
        //                 $('#conversation_details_modal').modal('show');
        //             },
        //             success: function(data) {
        //                 $('#conversation_details_loader').addClass('d-none');
        //                 $('#conversation_details_data').html(data);
        //             }
        //         });
        //     }
        // });
    });
</script>
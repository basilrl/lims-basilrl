<link rel="stylesheet" href="<?php echo base_url('assets/dataTables/css/dataTables.bootstrap4.min.css'); ?>">
<style>
    .dataTables_filter input {
        width: 250px !important;
    }

    .custom-switch .custom-control-label::before {
        width: 1.75rem;
        pointer-events: all;
        border-radius: 0.5rem;
    }

    .custom-switch .custom-control-label::after {
        top: calc(0.25rem + 2px);
        width: calc(1rem - 4px);
        height: calc(1rem - 4px);
        background-color: #adb5bd;
        border-radius: 0.5rem;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-transform 0.15s ease-in-out;
        transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-transform 0.15s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .custom-switch .custom-control-label::after {
            transition: none;
        }
    }

    .custom-switch .custom-control-input:checked~.custom-control-label::after {
        background-color: #fff;
        -webkit-transform: translateX(0.75rem);
        transform: translateX(0.75rem);
    }

    .custom-switch .custom-control-input:disabled:checked~.custom-control-label::before {
        background-color: rgba(47, 164, 231, 0.5);
    }

    @media (min-width: 768px) {
        .modal-xl {
            width: 90%;
            max-width: 1200px;
        }
    }
</style>

<script src="<?php echo base_url('assets/dataTables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/dataTables/js/dataTables.bootstrap4.min.js'); ?>"></script>

<div class="container-fluid" style="margin-top: 70px; min-height: 550px;">
    <div class="card shadow-sm">
        <div class="card-header bg-info">
            <h4 class="mb-0">Bot Management
                <?php if (exist_val('Bot_Configuration/training', $this->session->userdata('permission'))) { ?>
                    <button type="button" id="train_bot" class="btn btn-sm btn-warning float-right ml-2">GEN-YML</button>
                <?php } ?>
                <?php if (exist_val('Bot_Configuration/conversation', $this->session->userdata('permission'))) { ?>
                    <a href="<?php echo base_url('Bot_Configuration/conversation'); ?>" class="btn btn-sm btn-primary float-right ml-2">CONVERSATIONS</a>
                <?php } ?>
                <?php if (exist_val('Bot_Configuration/bot_configuration_add_edit', $this->session->userdata('permission'))) { ?>
                    <button type="button" id="add_btn_bot_configuration" class="btn btn-sm btn-primary float-right">ADD NEW</button>
                <?php } ?>
                <?php if (exist_val('Bot_Configuration/mail_outofscope', $this->session->userdata('permission'))) { ?>
                    <button type="button" id="mail_outofscope" class="btn btn-sm btn-warning float-right">MAIL OUT-OF-SCOPE</button>
                <?php } ?>
                <!-- <a href="<?php echo base_url('Bot_Configuration/mail_outofscope'); ?>" class="btn btn-sm btn-danger float-right mr-2">MAIL</a> -->
            </h4>
        </div>
        <div class="card-body">
            <form autocomplete="off">
                <table id="container_data" class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr class="bg-info text-light">
                            <th>SL</th>
                            <th>INTENTS</th>
                            <th>CREATED BY</th>
                            <th>CREATED ON</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                </table>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="bot_configuration_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content bg-light border-0">
            <form id="bot_configuration_form" method="post" autocomplete="off">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">
                        <span class="bot_configuration_modal_title">ADD </span>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="intents_id" class="form-control">
                    <div class="form-group">
                        <label>Intent Name: </label>
                        <input type="text" class="form-control" id="intents" name="intents">
                        <p id="error-intents"></p>
                        <small class="form-text text-muted">Note*: Allowed characters are a-z (in lowercase) and underscore only!</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" id="bot_configuration_submit" class="btn btn-primary">
                        <span class="bot_configuration_modal_title">ADD </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="bot_configuration_log_modal">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content bg-light border-0">
            <div class="modal-header bg-info">
                <h5 class="modal-title">LOG DETAILS</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-5 mb-5 text-center text-primary" id="bot_configuration_log_loader">
                    <h4>Please wait...</h4>
                </div>
                <span id="bot_configuration_log_data"></span>
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
                searchPlaceholder: "Intents..."
            },
            "ajax": {
                url: "<?php echo base_url('Bot_Configuration/fetch_records'); ?>",
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

        $('#add_btn_bot_configuration').click(function() {
            $('.bot_configuration_modal_title').text('ADD');
            $('#intents_id, #intents').val('');
            $('#bot_configuration_form')[0].reset();
            $('#bot_configuration_modal').modal('show');
            return false;
        });

        $(document).on("click", ".bot_configuration_edit", function() {
            let id = $(this).attr('data-id');
            if (id.length > 0) {
                $('.bot_configuration_modal_title').text('UPDATE');
                $.ajax({
                    url: "<?php echo base_url('Bot_Configuration/fetch_edit_bot_configuration'); ?>",
                    type: 'post',
                    data: {
                        id: id,
                        _tokken: _tokken
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#bot_configuration_modal').modal('show');
                        $('#intents_id').val(data.id);
                        $('#intents').val(data.intents);
                    }
                });
            }
        });

        $('#bot_configuration_form').on('submit', function(e) {
            e.preventDefault();
            let text = $.trim($('#intents').val());
            if (text.charAt(0) == '_' || text.charAt(parseInt(text.length) - 1) == '_') {
                $('#error-intents').notify('Underscore is not allowed at begning and end.', {
                    position: 'bottom center'
                });
                return false;
            }
            $.ajax({
                url: "<?php echo base_url('bot_Configuration/bot_configuration_add_edit'); ?>",
                type: 'post',
                data: $('#bot_configuration_form').serialize(),
                dataType: 'json',
                success: function(data) {
                    if (data.message) {
                        $.notify(data.message, {
                            position: 'top center',
                            className: (data.code == 1) ? 'success' : 'primary'
                        });
                    }
                    if (data.code == 1) {
                        dataTable.ajax.reload();
                        $('#bot_configuration_form')[0].reset();
                        $('#intents_id, #intents').val('');
                        $('#bot_configuration_modal').modal('hide');
                    } else {
                        if (data.error) {
                            $.each(data.error, function(key, value) {
                                $('#error-' + key).notify(value, {
                                    position: 'bottom center'
                                });
                            });
                        }
                    }
                }
            });
        });

        $(document).on('change', '.bot_configuration_status', function() {
            let value = ($(this).prop("checked") == true) ? '1' : '0';
            let id = $(this).attr('id').split('_')['3'];
            if (id.length > 0) {
                $.ajax({
                    url: "<?php echo base_url('Bot_Configuration/bot_configuration_status'); ?>",
                    method: "post",
                    data: {
                        id: id,
                        status: value,
                        _tokken: _tokken
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.code == 1) {
                            if (value == '1') {
                                $('#bot_configuration_status_title_' + id).attr('title', 'Active');
                            } else {
                                $('#bot_configuration_status_title_' + id).attr('title', 'In-Active');
                            }
                        }
                        if (data.message) {
                            $.notify(data.message, {
                                position: 'top center',
                                className: (data.code == 1) ? 'success' : 'info'
                            });
                        }
                    }
                });
            }
        });


        $(document).on('click', '#train_bot', function() {
            let cnf = confirm("Are you want to Train-Bot?");
            if (cnf == true) {
                $.ajax({
                    url: "<?php echo base_url('Bot_Configuration/training'); ?>",
                    method: "post",
                    data: {
                        action: 'auth',
                        _tokken: _tokken
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.message) {
                            $.notify(data.message, {
                                position: 'top center',
                                className: (data.code == 1) ? 'success' : 'info'
                            });
                        }
                    }
                });
            }
        });


        $(document).on('click', '.bot_configuration_delete', function() {
            let id = $(this).attr('data-id');
            if (id.length > 0) {
                let cnf = confirm("Are you want to delete Intents?");
                if (cnf == true) {
                    $.ajax({
                        url: "<?php echo base_url('Bot_Configuration/bot_configuration_delete'); ?>",
                        method: "post",
                        data: {
                            id: id,
                            _tokken: _tokken
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.code == 1) {
                                dataTable.ajax.reload();
                            }
                            if (data.message) {
                                $.notify(data.message, {
                                    position: 'top center',
                                    className: (data.code == 1) ? 'success' : 'info'
                                });
                            }
                        }
                    });
                }
            }
        });

        $(document).on("click", ".bot_configuration_log", function() {
            let id = $(this).attr('data-id');
            if (id.length > 0) {
                $.ajax({
                    url: "<?php echo base_url('Bot_Configuration/bot_configuration_log'); ?>",
                    method: "post",
                    data: {
                        record_id: id,
                        _tokken: _tokken
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#bot_configuration_log_data').html('');
                        $('#bot_configuration_log_loader').removeClass('d-none');
                        $('#bot_configuration_log_modal').modal('show');
                    },
                    success: function(data) {
                        $('#bot_configuration_log_loader').addClass('d-none');
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
                        $('#bot_configuration_log_data').html(html);
                        $('#log-details-table').dataTable();
                        return false;
                    }
                });
            }
        });

        $(document).on('click', '#mail_outofscope', function() {
            let cnf = confirm("Are you want to mail out of scope questions?");
            if (cnf == true) {
                $.ajax({
                    url: "<?php echo base_url('Bot_Configuration/mail_outofscope'); ?>",
                    method: "post",
                    data: {
                        _tokken: _tokken
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.message) {
                            $.notify(data.message, {
                                position: 'top center',
                                className: (data.code == 1) ? 'success' : 'info'
                            });
                        }
                    }
                });
            }
        });
    });
</script>
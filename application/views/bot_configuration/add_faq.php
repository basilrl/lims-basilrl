<link rel="stylesheet" href="<?php echo base_url('assets/dataTables/css/dataTables.bootstrap4.min.css'); ?>">

<script src="<?php echo base_url('assets/dataTables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/dataTables/js/dataTables.bootstrap4.min.js'); ?>"></script>

<div class="container-fluid" style="margin-top: 70px; min-height: 550px;">
    <div class="card shadow-sm">
        <div class="card-header bg-info">
            <h4 class="mb-0">FAQ | <span class="text-light"><?php echo $intents->intents; ?></span>
                <a href="<?php echo base_url('Bot_Configuration'); ?>" class="btn btn-sm btn-primary float-right">BACK</a>
            </h4>
        </div>
        <div class="card-body bg-light">
            <form method="post" id="faq_forms" autocomplete="off">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" id="intent_id" name="intents_id" value="<?php echo $intents->id; ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-info"><b>QUESTIONS</b>
                                <button type="button" data-id="questions" class="btn btn-sm btn-warning float-right add_fields"><i class="fa fa-plus-circle"></i></button>
                            </div>
                            <div class="card-body">
                                <table class="table" id="table_questions">
                                    <tbody>
                                        <?php if (empty($questions)) { ?>
                                            <tr class="count_questions">
                                                <input type="hidden" name="questions_id[]" value="">
                                                <td width="95%" class="border-0">
                                                    <textarea type="text" class="form-control" name="questions[]" id="questions_1" rows="2"></textarea>
                                                </td>
                                                <td width="5%" class="border-0">
                                                    <button type="button" action="questions" del-id="" class="btn btn-sm btn-danger remove_fields"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        <?php } else { ?>
                                            <?php foreach ($questions as $qk => $qv) { ?>
                                                <?php $i = $qk + 1; ?>
                                                <tr class="count_questions" id="del_row_questions_<?php echo $qv->questions_id; ?>">
                                                    <input type="hidden" name="questions_id[]" value="<?php echo $qv->questions_id; ?>">
                                                    <td width="95%" class="border-0">
                                                        <textarea type="text" class="form-control" name="questions[]" id="questions_<?php echo $i; ?>" rows="2"><?php echo $qv->questions; ?></textarea>
                                                    </td>
                                                    <td width="5%" class="border-0">
                                                        <button type="button" action="questions" del-id="<?php echo $qv->questions_id; ?>" class="btn btn-sm btn-danger remove_fields"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-info"><b>ANSWERS</b>
                                <button type="button" data-id="answers" class="btn btn-sm btn-warning float-right add_fields"><i class="fa fa-plus-circle"></i></button>
                            </div>
                            <div class="card-body">
                                <table class="table" id="table_answers">
                                    <tbody>
                                        <?php if (empty($answers)) { ?>
                                            <tr class="count_answers">
                                                <input type="hidden" name="answers_id[]" value="">
                                                <td width="95%" class="border-0">
                                                    <textarea type="text" class="form-control" name="answers[]" id="answers_1" rows="2"></textarea>
                                                </td>
                                                <td width="5%" class="border-0">
                                                    <button type="button" action="answers" del-id="" class="btn btn-sm btn-danger remove_fields"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        <?php } else { ?>
                                            <?php foreach ($answers as $ak => $av) { ?>
                                                <?php $j = $ak + 1; ?>
                                                <tr class="count_answers" id="del_row_answers_<?php echo $av->answers_id; ?>">
                                                    <input type="hidden" name="answers_id[]" value="<?php echo $av->answers_id; ?>">
                                                    <td width="95%" class="border-0">
                                                        <textarea type="text" class="form-control" name="answers[]" id="answers_<?php echo $j; ?>" rows="2"><?php echo $av->answers; ?></textarea>
                                                    </td>
                                                    <td width="5%" class="border-0">
                                                        <button type="button" action="answers" del-id="<?php echo $av->answers_id; ?>" class="btn btn-sm btn-danger remove_fields"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-primary" id="add_faq_form">SUBMIT</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const _tokken = $('meta[name="_tokken"]').attr("value");

    function add_fields(name) {
        let count = parseInt($('.count_' + name).length) + 1;
        $('#table_' + name).append('<tr class="count_' + name + '"><td class="border-0"><input type="hidden" name="' + name + '_id[]" value=""><textarea type="text" class="form-control" name="' + name + '[]" id="' + name + '_' + count + '" rows="2"></textarea></td><td class="border-0"><button type="button" del-id="" action="' + name + '" class="btn btn-sm btn-danger remove_fields"><i class="fa fa-trash"></i></button></td></tr>');
        return false;
    }

    $(document).ready(function() {

        $(document).on("click", ".add_fields", function() {
            let name = $(this).attr('data-id');
            if (name.length > 0) {
                add_fields(name);
            }
        });

        $(document).on("click", ".remove_fields", function() {
            let name = $(this).attr('action');
            let del_id = $(this).attr('del-id');
            let intent_id = $('#intent_id').val();
            let count = parseInt($('.count_' + name).length);
            if (count > 1 && intent_id.length > 0) {
                let cnf = confirm("Are you want to delete record?");
                if (cnf == true) {
                    if (name.length > 0 && del_id.length > 0) {
                        $.ajax({
                            url: "<?php echo base_url('Bot_Configuration/delete_questions_answers'); ?>",
                            method: "post",
                            data: {
                                id: del_id,
                                intent_id: intent_id,
                                action: name,
                                _tokken: _tokken
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data.code == 1) {
                                    $('#del_row_' + name + '_' + del_id).remove();
                                }
                                if (data.message) {
                                    $.notify(data.message, {
                                        position: 'top center',
                                        className: (data.code == 1) ? 'success' : 'info'
                                    });
                                }
                            }
                        });
                    } else {
                        $(this).closest("tr").remove();
                        $.notify('Record has been deleted.', {
                            position: 'top center',
                            className: 'success'
                        });
                    }
                }
            } else {
                $.notify('Sorry, at least one field is required!', {
                    position: 'top center',
                    className: 'info'
                });
            }
        });

        $('#add_faq_form').on('click', function() {
            let isValid = false;
            $('form#faq_forms :input[type=text]').each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $.notify('All fields are mandatory!', {
                        position: 'top center',
                        className: 'danger'
                    });
                    return false;
                } else {
                    isValid = true;
                }
            });
            if (isValid) {
                $.ajax({
                    url: "<?php echo base_url('Bot_Configuration/save_faq'); ?>",
                    type: 'post',
                    data: $('#faq_forms').serialize(),
                    dataType: 'json',
                    success: function(data) {
                        if (data.message) {
                            $.notify(data.message, {
                                position: 'top center',
                                className: (data.code == 1) ? 'success' : 'primary'
                            });
                        }
                        if (data.code == 1) {
                            window.location = "<?php echo base_url('Bot_Configuration'); ?>";
                        }
                    }
                });
            }
        });
    });
</script>
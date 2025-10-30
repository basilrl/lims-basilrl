<?php
if (empty($service)) {
    $action = base_url().'Services_Controller/add_services';
    $status = 1;
    $lab_location_id = '';
    $lab_location = '';
    $product_destination_id = '';
    $product_destination = '';
    $test_standard_id = '';
    $test_standard_name = '';
    $certificate_id = '';
    $certificate_name = '';
} else {
    $action = base_url().'Services_Controller/edit_services/'.$service['services_id'];
    $lab_location_id = $service['lab_location_id'];
    $lab_location = $service['lab_location'];
    $product_destination_id = $service['product_destination_id'];
    $product_destination = $service['product_destination'];   
    $status = $service['status'];   
    $test_standard_id = $service['test_standard_id'];   
    $test_standard_name = $service['test_standard_name'];   
    $certificate_id = $service['certificate_id'];   
    $certificate_name = $service['certificate_name'];   
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Services </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('Services_Controller'); ?>">Services</a></li>
                        <li class="breadcrumb-item active">Add Services</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Services</h3>
                        </div>
                        <!-- /.card-header -->
                        <form action="<?php echo $action; ?>" method="post" autocomplete="off" id="services_form">
                            <div class="card-body">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Lab Location :</label>
                                            <select name="lab_location" class="form-control" style="width: 100%;" id="lab_location">
                                            <?php if(!empty($lab_location_id)){?>
                                            <option value="<?php echo $lab_location_id; ?>" selected><?php echo $lab_location; ?></option>
                                            <?php } ?>
                                            </select>
                                            <?php echo form_error('lab_location', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product Destination :</label>
                                            <select name="product_destination" class="form-control" style="width: 100%;" id="product_destination">
                                            <?php if(!empty($product_destination_id)){?>
                                            <option value="<?php echo $product_destination_id; ?>" selected><?php echo $product_destination; ?></option>
                                            <?php } ?>
                                            </select>
                                            <?php echo form_error('product_destination', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Test Standards :</label>
                                            <select name="test_standards" class="form-control" style="width: 100%;" id="test_standard">
                                            <?php if(!empty($test_standard_id)){?>
                                            <option value="<?php echo $test_standard_id; ?>" selected><?php echo $test_standard_name; ?></option>
                                            <?php } ?>
                                            </select>
                                            <?php echo form_error('test_standards[]', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Certification :</label>
                                            <select name="certification" class="form-control form-control-sm" id="certificate">
                                            <?php if(!empty($certificate_id)){?>
                                            <option value="<?php echo $certificate_id; ?>" selected><?php echo $certificate_name; ?></option>
                                            <?php } ?>
                                            </select>
                                            <?php echo form_error('certification', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status :</label>
                                            <select name="status" id="" class="form-control">
                                                <option disabled selected>Select Status</option>
                                                <option value="1" <?php if ($status == "1") {
                                                                        echo "selected";
                                                                    } ?>>Active</option>
                                                <option value="0" <?php if ($status == "0") {
                                                                        echo "selected";
                                                                    } ?>>Inactive</option>
                                            </select>
                                            <?php echo form_error('status', '<div class="text-danger">', '</div>'); ?>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div style="margin-left: 43%;">
                                    <a href="<?php echo base_url('Services_Controller/index'); ?>"><button type="button" class="btn btn-danger">Cancel</button></a>
                                    <button type="submit" name="submit" class="btn btn-primary" id="submit">Save</button>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- /.col (right) -->
</div>
<!-- /.row -->
</div><!-- /.container-fluid -->
</section>
</div>

<script>
    $(document).ready(function() {
        const url = $('body').data('url');
        // Get lab location
        $('#lab_location').select2({
            allowClear: true,
            ajax: {
                url: "<?php echo base_url('Services_Controller/get_lab_location'); ?>",
                dataType: 'json',
                data: function(params) {
                    return {
                        key: params.term, // search term
                    };
                },
                processResults: function(response) {

                    return {
                        results: response
                    };
                },
                cache: true
            },
            placeholder: 'Search lab location',
            minimumInputLength: 0,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });
        // Get lab location

        // Get product destination
        $('#product_destination').select2({
            allowClear: true,
            ajax: {
                url: "<?php echo base_url('Services_Controller/get_product_destination'); ?>",
                dataType: 'json',
                data: function(params) {
                    return {
                        key: params.term, // search term
                    };
                },
                processResults: function(response) {

                    return {
                        results: response
                    };
                },
                cache: true
            },
            placeholder: 'Search product destination',
            minimumInputLength: 0,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });
        // Get product destination

        // Get test standards
        $('#test_standard').select2({
            allowClear: true,
            ajax: {
                url: "<?php echo base_url('Services_Controller/get_test_standard'); ?>",
                dataType: 'json',
                data: function(params) {
                    return {
                        key: params.term, // search term
                    };
                },
                processResults: function(response) {

                    return {
                        results: response
                    };
                },
                cache: true
            },
            placeholder: 'Search test standards',
            minimumInputLength: 0,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });
        // Get test standards

        
        // Get certificate
        $('#certificate').select2({
            allowClear: true,
            ajax: {
                url: "<?php echo base_url('Services_Controller/get_certificate'); ?>",
                dataType: 'json',
                data: function(params) {
                    return {
                        key: params.term, // search term
                    };
                },
                processResults: function(response) {

                    return {
                        results: response
                    };
                },
                cache: true
            },
            placeholder: 'Search certificate',
            minimumInputLength: 0,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });
        // Get certificate

        function formatRepo(repo) {
            if (repo.loading) {
                return repo.text;
            }
            var $container = $(
                "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__title'></div>" +
                "</div>"
            );

            $container.find(".select2-result-repository__title").text(repo.name);
            return $container;
        }

        function formatRepoSelection(repo) {
            return repo.full_name || repo.text;
        }

        $('#services_form').submit(function(e){
            e.preventDefault();
            $('#submit').html('Wait...');
            $('#submit').attr('disabled', 'disabled');
            $('body').append('<div class="pageloader"></div>');
            $.ajax({
                type: 'post',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(data) {
                    var data = $.parseJSON(data);
                    $('.pageloader').remove();
                    $('.errors_msg').remove();
                    if (data.status > 0) {
                        $.notify(data.message, "success");
                        window.setTimeout(function() {
                            window.location.replace(url + 'Services_Controller/index');
                        }, 1000);
                    } else {
                        $.notify(data.message, "error");
                        $('#submit').html('Save');
                        $('#submit').attr('disabled', false);
                    }
                    if (data.error) {
                        $.each(data.error, function(i, v) {
                            $('#services_form *[name="' + i + '"]').after('<span class="text-danger errors_msg">' + v + '</span>');
                            $('#submit').html('Save');
                            $('#submit').attr('disabled', false);
                        });
                    }
                }
            });
        });
    });
</script>
<div class="content-wrapper">
    <section class="content-header">
        <!-- container fluid start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1>TAX RULES</h1>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-8"></div>
                                <div class="col-sm-2">
                                    <input type="text" id="search_lbl" class="form-control form-control-sm" value="<?php echo ($search_lbl != 'NULL') ? $search_lbl : ''; ?>" placeholder="SEARCH BY Label ...">
                                </div>
                                <div class="col-sm-2">
                                    <a class="btn btn-primary btn-sm" onclick="filter_by()" href="javascript:void(0);">SUBMIT</a>
                                    <a class="btn btn-danger btn-sm" href="<?php echo base_url('TaxRules/index'); ?>">CLEAR</a>
                                </div>
                            </div>
                        </div>
                        <!-- end card header -->
                        <div class="table-responsive p-2">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Label</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <?php (empty($this->uri->segment(3))) ? $i = 1 : $i = $this->uri->segment(3) + 1; ?>
                                <tbody>
                                    <?php if ($tax_rl_listing || $tax_rl_listing != NULL) {
                                    ?> <?php foreach ($tax_rl_listing as $trl) : ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $trl->cfg_Name; ?></td>
                                            <td><?php echo $trl->cfg_Value; ?></td>
                                        </tr>
                                        <?php $i++;
                                        endforeach; ?><?php } else { ?>
                                        <tr>
                                            <td class="text-center font-weight-bold"> <?php echo "NO RECORD FOUND"; } ?></td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- card end -->
                </div>
            </div>
            <!-- menu end -->

            <div class="card-header">
                <?php echo $links ?>
                <?php if ($tax_rl_listing && count($tax_rl_listing) > 0) {
                    echo "<span class='text-dark font-weight-bold'>" . $result_count . "</span>";
                } else {
                    echo "<h5 class='text-center font-weight-bold'> NO RECORD FOUND  </h5>";
                } ?>
            </div>
        </div>
        <!-- container fluid end -->
    </section>
</div>


<script>
    $('document').ready(function() {
        const _tokken = $('meta[name="_tokken"]').attr('value');
    });

    function filter_by() {
        var base_url = '<?php echo base_url('TaxRules/index'); ?>';
        var search_lbl = $('#search_lbl').val();
        base_url += '/' + ((search_lbl) ? btoa(search_lbl) : 'NULL');
        location.href = base_url;
    }
    var css = {
        position: "absolute",
        width: "95%",
        "font-size": "12px",
        "z-index": 999,
        "overflow-y": "auto",
        "overflow-x": "hidden",
        "max-height": "200px",
        cursor: "pointer",
    };
</script>
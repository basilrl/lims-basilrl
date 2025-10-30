<?php if (empty($this->uri->segment(4))) {
  $i = 1;
} else {
  $i = $this->uri->segment(4) + 1;
}
?>
<script src="<?php echo base_url('ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/jquery-validation/jquery.validate.min.js'); ?>"></script>
<script>
  CKEDITOR.replace('description');
</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>SEO Meta Content</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">SEO Meta Content</li>
          </ol>
        </div>
      </div>

      <div class="container-fluid">
        <div class="row">

          <!--                <div class="col-sm-2">
                    <select name="client_id" id="client_id" class="form-control form-control-sm exporter">
                        <option value="">Select Client</option>
                        <?php if (isset($client)) {
                          foreach ($client as $cl) { ?>
                            <option value="<?php echo $cl->customer_details_id; ?>" <?php if ($client_id == $cl->customer_details_id) { ?>selected="selected" <?php } ?>><?php echo $cl->company_name; ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
                
               
                
                <div class="col-sm-2">
                    <input name="search" value="<?= isset($search) ? $search : ''; ?>" id="search" class="form-control form-control-sm" type="text" placeholder="Search By Page Title" aria-label="Search">
                </div>
                
               
                <div class="col-sm-6 text-right">
                    <button type="button" onclick="filterReport()" class="btn btn-primary btn-sm">Search</button>
                <a href="<?= base_url('SEOMetaDesc/index'); ?>" class="btn btn-sm btn-danger">CLEAR ALL</a>
              
            </div>-->

          <br>

        </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">SEO Meta Content</h3>
              <div class="card-tools">
                <div class="input-group input-group-sm">
                <?php if (exist_val('SEOMetaDesc/add_seo_meta_desc', $this->session->userdata('permission'))) { ?> <!-- added by Millan on 22-02-2021 -->    
                  <button data-bs-toggle="modal" data-bs-target="#add_seo_meta_form" type="button" class="btn btn-primary">ADD</button>
                <?php } ?>
                </div>
              </div>
            </div>

            <input type="hidden" id="order" value="">
            <input type="hidden" id="column" value="">
            <div class="card-body small p-0">
              <table class="table table-hover table-sm" id="sample-list">
                <thead>
                  <tr>
                    <th>SL No.</th>
                    <th>Page Title</th>
                    <th>Page Url</th>
                    <th>Description</th>
                    <th>Keyword</th>
                    <th>Created By</th>
                    <th>Created On</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="sample-listing">
                  <?php if (!empty($listing)) {
                    foreach ($listing as $seo) { ?>
                      <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $seo['page_title']; ?></td>
                        <td><?= $seo['page_url']; ?></td>
                        <td><?= html_entity_decode($seo['description']); ?></td>
                        <td><?= $seo['keywords']; ?></td>
                        <td><?= $seo['admin_fname']; ?></td>
                        <td><?= $seo['created_date']; ?></td>
                        <td>
                          <?php if (exist_val('SEOMetaDesc/edit_seo', $this->session->userdata('permission'))) { ?> <!-- added by Millan on 22-02-2021 -->    
                            <a href="#" data-bs-toggle="modal" data-bs-target="#edit_seo_meta_form" onclick="editSeo('<?= $seo['page_id']; ?>')">
                              <img src="<?= base_url('/public/img/icon/edit.png'); ?>" style="width: 20px;">
                            </a>
                          <?php } ?>
                        </td>
                      </tr>
                  <?php }
                  } ?>
                </tbody>
              </table>

            </div>

            <!-- Pagination -->
            <div class="card-header">
              <span id="sample-pagination"><?php echo $links; ?></span>
              <span><?= isset($result_count) ? "Total->" . $result_count : ''; ?></span>

            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!--Add SEO Meta-->
<div class="modal fade" id="add_seo_meta_form" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h4 class="modal-title text-white">Add Seo Meta Content</h4>
        <button type="button" class="close text-danger" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <?= form_open_multipart('SEOMetaDesc/add_seo_meta_desc', ['id' => 'add_seo_meta_form']); ?>
        <div class="row">
          <div class="col-md-6">
            <label><span class="text-danger">*</span> Page Title:</label>
            <input name="page_title" class="form-control" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <label><span class="text-danger">*</span> Keywords:</label>
            <input name="keywords" class="form-control" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-8">
            <label><span class="text-danger">*</span> Page URL:</label>
            <input name="page_url" class="form-control" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-10">
            <label><span class="text-danger">*</span> Description:</label>
            <textarea class="ckeditor" name="description"></textarea>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
      <?= form_close(); ?>
    </div>
  </div>
</div>

<!--edit seo-->
<div class="modal fade" id="edit_seo_meta_form" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h4 class="modal-title text-white">Edit Seo Meta Content</h4>
        <button type="button" class="close text-danger" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="editSeoForm"></div>
      </div>
    </div>
  </div>


  <script>
    $('#add_seo_meta_form').validate({});
    function editSeo(page_id) {
      if (page_id !== '') {
        $('#editSeoForm').empty();
        $.ajax({
          url: '<?= base_url('SEOMetaDesc/edit_seo'); ?>',
          data: {
            pid: page_id
          },
          dataType: 'JSON',
          success: function(data) {
            $('#editSeoForm').append(data);
          }
        });
      }
    }
  </script>

  <script>
    function filter() {
      var url = "<?= base_url('SEOMetaDesc/index'); ?>";
      var client_id = $('#client_id').val();
      if (client_id !== '') {
        url = url + '/' + client_id;
      } else {
        url = url + '/0';
      }

      var search = $('#search').val();
      if (search !== '') {
        url = url + '/' + search;
      } else {
        url = url + '/NULL';
      }
      window.location.href = url;
    }
    (function() {
      var age = document.getElementById('search');
      age.addEventListener('keypress', function(event) {
        if (event.keyCode === 13) {
          filter();
        }
      });
    }());
  </script>
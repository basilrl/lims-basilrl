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
          <h1>CVG Storge</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">CVG Storge</li>
          </ol>
        </div>
      </div>

      <div class="container-fluid">
        <div class="row">



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
              <h3 class="card-title">CVG Storage</h3>
              <div class="card-tools">
                <div class="input-group input-group-sm">
                  <?php
                  if (exist_val('CVG_Storage/add_cvg_storage', $this->session->userdata('permission'))) { ?>
                    <!-- added by Millan on 04-03-2021 -->
                    <button data-bs-toggle="modal" data-bs-target="#add_cvg_form" type="button" class="btn btn-primary">ADD</button>
                  <?php
                  } ?>
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
                    <th>Title</th>
                    <th>Accredited By</th>
                    <th>Country</th>
                    <th>Created On</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <?php
                    if ((exist_val('CVG_Storage/edit_cvg', $this->session->userdata('permission'))) || (exist_val('CVG_Storage/delete_cvg_storage', $this->session->userdata('permission'))) || (exist_val('CVG_Storage/download_file', $this->session->userdata('permission')))) { ?>
                      <!-- added by Millan on 04-03-2021 -->
                      <th>Action</th>
                    <?php
                    } ?>
                  </tr>
                </thead>
                <tbody id="sample-listing">
                  <?php if (!empty($listing)) {
                    foreach ($listing as $cvg) { ?>
                      <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $cvg['title']; ?></td>
                        <td><?php echo $cvg['accredited_by']; ?></td>
                        <td><?php echo $cvg['country_name']; ?></td>
                        <td><?php echo change_time($cvg['create_on'],$this->session->userdata('timezone')); ?></td>
                        <td><?php echo $cvg['status'] == 1 ? 'Active' : 'Inactive'; ?></td>
                        <td><?php echo $cvg['admin_fname']; ?></td>
                        <td>
                          <?php
                          if (exist_val('CVG_Storage/edit_cvg', $this->session->userdata('permission'))) { ?>
                            <!-- added by Millan on 04-03-2021 -->
                            <a href="#" data-bs-toggle="modal" data-bs-target="#edit_seo_meta_form" onclick="editSeo('<?php echo $cvg['cvg_storage_id']; ?>')">
                              <img src="<?php echo base_url('/public/img/icon/edit.png'); ?>" style="width: 20px;">
                            </a>
                          <?php
                          } ?>

                          <?php
                          if (exist_val('CVG_Storage/delete_cvg_storage', $this->session->userdata('permission'))) { ?>
                            <!-- added by Millan on 04-03-2021 -->
                            <a href="<?php echo base_url('CVG_Storage/delete_cvg_storage?cs_id=' . base64_encode($cvg['cvg_storage_id'])); ?>" class="confirm">
                              <i class="fa fa-trash text-danger"></i>
                            </a>
                          <?php
                          } ?>

                          <?php
                          // if (exist_val('CVG_Storage/download_file', $this->session->userdata('permission'))) { ?>
                            <!-- added by Millan on 04-03-2021 -->
                            <?php if (!empty($cvg['file_path'])) { ?>
                              <a href="<?php echo base_url('CVG_Storage/download_cvg_file/') . base64_encode($cvg['file_path']);?>" target="_blank">
                                <img src="<?php echo base_url('public/img/icon/download.png'); ?>" style="width: 25px;">
                              </a>
                            <?php 
                          } ?>
                          <?php
                          // } ?>
                          <?php
                          if (exist_val('CVG_Storage/get_log_data', $this->session->userdata('permission'))) { ?>
                            <a href="javascript:void(0)" data-id="<?php echo base64_encode($cvg['cvg_storage_id']); ?>" class="log_view" data-bs-toggle='modal' data-bs-target='#lo_view_target' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>
                          <?php
                          } ?>

                        </td>
                      </tr>
                  <?php }
                  } ?>
                </tbody>
              </table>

            </div>

            <!-- Pagination -->
            <div class="card-header">
              <span id="sample-pagination"><?php echo isset($links) ? $links : ''; ?></span>

              <b><span><?php echo isset($result_count) ? "TOTAL RECORDS  : " . $result_count : 0; ?></span></b>

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
<div class="modal fade" id="add_cvg_form" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h4 class="modal-title text-white">Add CVG Storage Form</h4>
        <button type="button" class="close text-danger" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <?php echo form_open_multipart('CVG_Storage/add_cvg_storage', ['id' => 'add_seo_meta_form']); ?>
        <div class="row">
          <div class="col-md-6">
            <label><span class="text-danger">*</span> Title:</label>
            <input name="title" class="form-control" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <label><span class="text-danger">*</span> Country:</label>
            <select name="country" class="form-control" required>
              <option value=""></option>
              <?php if (isset($country)) {
                foreach ($country as $ctry) { ?>
                  <option value="<?php echo $ctry->country_id; ?>"><?php echo $ctry->country_name; ?></option>
              <?php }
              } ?>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-8">
            <label><span class="text-danger">*</span> Accredited By:</label>
            <select name="accredited_by" class="form-control" required>
              <option value=""></option>
              <?php if (isset($accredited_by) && !empty($accredited_by)) {
                foreach ($accredited_by as $acr) { ?>
                  <option value="<?php echo $acr['accredited_by_id']; ?>"><?php echo $acr['accredited_by']; ?></option>
              <?php }
              } ?>
            </select>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-10">
            <label><span class="text-danger">*</span> File Upload:</label>
            <input type="file" name="document" required>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- user log -->
<div class="modal fade" id="lo_view_target" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="max-height: 500px;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">CVG Storage LOG</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>SL.No.</th>
              <th>Operation</th>
              <th>Action</th>
              <th>Performed By</th>
              <th>Performed at</th>
            </tr>
          </thead>
          <tbody id="table_log"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- useer log -->

<!--edit seo-->
<div class="modal fade" id="edit_seo_meta_form" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h4 class="modal-title text-white">Edit CVG Storage</h4>
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

    function editSeo(cvg_id) {
      if (cvg_id !== '') {
        $('#editSeoForm').empty();
        $.ajax({
          url: '<?php echo base_url('CVG_Storage/edit_cvg'); ?>',
          data: {
            cs_id: cvg_id
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
      var url = "<?php echo base_url('SEOMetaDesc/index'); ?>";
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

  <!-- AJIT CODE 09-04-2021 -->
  <script>
    $(function() {
      $('.modal-header').removeClass("bg-warning").addClass("bg-primary");
      $('.confirm').click(() => {
        return confirm("Are You Sure!");
      });

      const url = $('body').data('url');
      const _tokken = $('meta[name="_tokken"]').attr('value');
      // Ajax call to get log
      $('.log_view').click(function() {
        $('#table_log').empty();
        var id = $(this).data('id');
        $.ajax({
          type: 'post',
          url: url + 'CVG_Storage/get_log_data',
          data: {
            _tokken: _tokken,
            id: id
          },
          success: function(data) {
            var data = $.parseJSON(data);
            var value = '';
            sno = Number();
            $.each(data, function(i, v) {
              sno += 1;
              var operation = v.action_taken;
              var action_message = v.text;
              var taken_by = v.taken_by;
              var taken_at = new Date(v.taken_at+ ' UTC');
              value += '<tr>';
              value += '<td>' + sno + '</td>';
              value += '<td>' + operation + '</td>';
              value += '<td>' + action_message + '</td>';
              value += '<td>' + taken_by + '</td>';
              value += '<td>' + taken_at.toLocaleString() + '</td>';
              value += '</tr>';

            });
            $('#table_log').append(value);
          }
        });
      });

    })
  </script>
  <!-- END -->
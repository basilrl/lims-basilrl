<style>
    #application_name-error,
    #application_desc-error {
        color: red;
    }
</style>
<script src="<?php echo base_url(); ?>public/js/role.js"></script>
<main class="main">
    <div class="container text-center"><br /><br />
        <h2><i class="fa fa-bars"></i> ROLE'S LIST2</h2>
    </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
            <?php if (exist_val('Role/add', $this->session->userdata('permission'))) { ?>
                <button type="button" class="btn btn-sm btn-primary add_application" data-bs-toggle="modal" data-bs-target="#add_application"><span> <i class="fa fa-plus"> </i></span></button>
            <?php } ?>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-6">
                        <input type="text" class="search form-control form-control-sm" placeholder="ENTER ROLE NAME" name="" id="">
                    </div>
                    <div class="col-sm-4">
                        <button class="btn btn-sm btn-primary search_listing">SEARCH</button>
                        <button class="btn btn-sm btn-danger clear_listing">CLEAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class=" mt-3 table-responsive small">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th scope="col">SL NO</th>
                    <th>ROLE NAME</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody id="application_list"></tbody>
        </table>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-4" id="application_pagination"></div>
            <div class="col-sm-4"></div>
            <div class="col-sm-4" id="result_count"></div>
        </div>
    </div>
</main>

<div class="modal fade" id="add_application" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">ADD ROLE</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center role_errors"></div>
            </div>
            <form method="post" name="submit_application" action="javscript:void(0);" class="add_submit"><input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="row p-2"><label for="">ROLE Name</label><input type="text" name="admin_role_name" id="" class="form-control form-control-sm" placeholder="Type role name"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button type="submit" id="form-submit" class="btn btn-primary">ADD</button></div>
            </form>
        </div>
    </div>
</div>




<div class="modal fade" id="edit_application" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">EDIT ROLE</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="row">
                <div class="col-sm-12 role_edit_errors"></div>
            </div>
            <form method="post" class="edit_submit_application" action="javscript:void(0);">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id_admin_role" class="edit_application_id" value="">
                <div class="modal-body">
                    <div class="row p-2"><label for="">Role Name</label><input type="text" name="admin_role_name" id="" class="form-control form-control-sm edit_application_name" placeholder="Type role name" value=""></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button type="submit" id="form-submit" class="btn btn-primary edit_application_submit">UPDATE</button></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="role_permission" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">PERMISSION</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" class="role_permission" action="javscript:void(0);">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="role_id" class="role_id" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12"><h4 class=""><input type="checkbox" class="check_all_permission"> <label for=""> Check All</label></h4></div>
                        </div>
                            <div class="row" id="permission_Set_html">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button type="submit" class="btn btn-primary">UPDATE</button></div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(document).on('click','.check_all_permission',function(){
            $('#permission_Set_html input:checkbox').not(this).prop('checked', this.checked);
        });
    });
</script>

  <!-- Modal to show log -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Role log</h5>
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
            <tbody id="role_log"></tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- added by saurabh on 23-03-2021 -->
  <script>
    $(document).ready(function() {
      const url = $('body').data('url');
      const _tokken = $('meta[name="_tokken"]').attr('value');
      // Ajax call to get log
      $(document).on('click','.log_view',function() {
        $('#role_log').empty();
        var role_id = $(this).data('id');
        $.ajax({
          type: 'post',
          url: url + 'Role/get_role_log',
          data: {
            _tokken: _tokken,
            role_id: role_id
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
            $('#role_log').append(value);
          }
        });
      });
      // ajax call to get log ends here
    });
  </script>
  <!-- added by saurabh on 23-03-2021 -->
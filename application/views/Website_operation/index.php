<script src="<?php echo base_url(); ?>public/js/website_operation.js"></script>
<main class="main">
    <div class="container text-center"><br /><br />
        <h2><i class="fa fa-bars"></i> OPERATION'S LIST</h2>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
            <?php //if (exist_val('Operation/add', $this->session->userdata('permission'))) { ?>
                <button type="button" class="btn btn-sm btn-primary add_application" data-bs-toggle="modal" data-bs-target="#add_operation"><span> <i class="fa fa-plus"> </i></span></button>
            <?php //} ?>
            </div>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-6">
                        <input type="text" class="search form-control form-control-sm" placeholder="ENTER FUNCTION NAME" name="" id="">
                    </div>
                    <div class="col-sm-4">
                        <button class="btn btn-sm btn-primary search_listing">SEARCH</button>
                        <button class="btn btn-sm btn-danger clear_listing">CLEAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 table-responsive small">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th scope="col">SL NO</th>
                    <th>CONTROLLER Name</th>
                    <th>FUNCTION NAME</th>
                    <th>ALIAS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody id="operation_list"></tbody>
        </table>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4" id="operation_pagination"></div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</main>
<div class="modal fade" id="add_operation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">ADD FUNCTION</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center role_errors"></div>
            </div>
            <form method="post" action="javscript:void(0);" class="add_operation"><input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 text-center error_add"></div>
                </div>
                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for="">Controller Name</label>
                            <input type="text" name="controller_name" id="" class="form-control form-control-sm" placeholder="Type Controller Name">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Function Name</label>
                            <input type="text" name="function_name" id="" class="form-control form-control-sm" placeholder="Type Function Name">
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-sm-12">
                            <label for="">ALIAS</label>
                            <input type="text" name="alias" id="" class="form-control form-control-sm" placeholder="Type ALIAS name">
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button type="submit" id="form-submit" class="btn btn-primary">ADD</button></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="edit_operation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">EDIT FUNCTION</h5><button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="row">
                <div class="col-sm-12 operation_edit_errors"></div>
            </div>
            <form method="post" action="javscript:void(0);" class="edit_operation">
            <input type="hidden" name="function_id" value="">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 text-center error_edit"></div>
                </div>
                    <div class="row p-2">
                        <div class="col-sm-6">
                            <label for="">Controller Name</label>
                            <input type="text" name="controller_name" id="" class="form-control form-control-sm" placeholder="Type Controller Name">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Function Name</label>
                            <input type="text" name="function_name" id="" class="form-control form-control-sm" placeholder="Type Function Name">
                        </div>
                    </div>
                    <div class="row p-2">
                        <div class="col-sm-12">
                            <label for="">ALIAS</label>
                            <input type="text" name="alias" id="" class="form-control form-control-sm" placeholder="Type ALIAS name">
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button><button type="submit" id="form-submit" class="btn btn-primary">ADD</button></div>
            </form>
        </div>
    </div>
</div>


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
            <tbody id="operation_log"></tbody>
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
        $('#operation_log').empty();
        var operation_id = $(this).data('id');
        $.ajax({
          type: 'post',
          url: url + 'Website_Operation/get_operation_log',
          data: {
            _tokken: _tokken,
            operation_id: operation_id
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
            $('#operation_log').append(value);
          }
        });
      });
      // ajax call to get log ends here
    });
  </script>
  <!-- added by saurabh on 23-03-2021 -->
<div class="content-wrapper">
    <section class="content-header">
        <section class="content">
            <div class="container-fluid">
                <h3 class="text-center font-weight-bold text-primary">Buyer Manual</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" id="buyer" class="form-control form-control-sm" value="<?php echo ($buyer != 'NULL') ? $buyer : ''; ?>" placeholder="SEARCH BY Buyer">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" id="title" class="form-control form-control-sm" value="<?php echo ($title != 'NULL') ? $title : ''; ?>" placeholder="SEARCH BY Title...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 text-center">
                                                <input id="start_date" value="<?php echo ($start_date != 'NULL') ? $start_date : ''; ?>" placeholder="START DATE" type="text" onfocus="(this.type='date')" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-sm-2 text-center">
                                                <input id="end_date" placeholder="END DATE" value="<?php echo ($end_date != 'NULL') ? $end_date : ''; ?>" type="text" onfocus="(this.type='date')" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-sm-2">
                                        <select class="form-control form-control-sm" name="" id="created_by">
                                            <option value="">Created By</option>

                                            <?php foreach ($created_by as $key => $value) { ?>
                                                <option <?php echo ($created_by > 0) ? (($created_by == $value->uidnr_admin) ? 'SELECTED' : '') : '' ?> value="<?php echo $value->created_by; ?>"><?php echo $value->created_by; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-1 text-center">
                                        <a class="btn btn-primary btn-sm" onclick="filter_by()" href="javascript:void(0);">SUBMIT</a>
                                    </div>
                                    <div class="col-sm-1 text-center">
                                        <a class="btn btn-danger btn-sm" href="<?php echo base_url('Buyer_manual/index'); ?>">CLEAR</a>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                   <div class="col-md-12">
                                   <a href="<?php echo base_url();?>Buyer_manual/open_buyer_manual" class="btn btn-sm btn-success">Add New</a>
                                   </div>
                                   
                                   
                                    
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-hovered text-center text-secondary">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Buyer</th>
                                            <th>Title</th>
                                            <th>File Name</th>
                                            <th>Created By</th>
                                            <th>Created On</th>
                                            
                                         
                                                <th>Action</th>
                                          
                                        </tr>
                                    </thead>
                                    <?php (empty($this->uri->segment(8))) ? $i = 1 : $i = $this->uri->segment(8) + 1; ?>

                                    <tbody>
                                        <?php if ($accepted_sample || $accepted_sample != NULL) {
                                        ?> <?php foreach ($accepted_sample as $AS) : ?>

                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $AS->customer_name; ?></td>
                                                    <td><?php echo $AS->title; ?></td>
                                                    <td><?php echo $AS->upload_filename; ?></td>
                                                    <td><?php echo $AS->admin_fname .' '. $AS->admin_lname; ?></td>
                                                    <td><?php echo change_time($AS->created_date,$this->session->userdata('timezone')); ?></td>
                                                  
                                                    <td>

                                                    <?php if (exist_val('Buyer_manual/edit_buyer_manual', $this->session->userdata('permission'))) { ?>

                                                    <a class="edit_buyer_manual" href="<?php echo base_url('/Buyer_manual/edit_buyer_manual/' . base64_encode($AS->buyer_manual_id)) ;?>" ><img src="<?php echo base_url(); ?>assets/images/icon/edit1.png" alt="" width="6%;" title="Edit Buyer Manual"></a>
                                                    <?php }?>
                                                    <?php if (exist_val('Buyer_manual/delete_buyer_manual', $this->session->userdata('permission'))) { ?>

                                                    <a class="delete_buyer_manual" href="<?php echo base_url('/Buyer_manual/delete_buyer_manual/' . base64_encode($AS->buyer_manual_id)) ;?>" ><img src="<?php echo base_url(); ?>assets/images/icon/reject.png" alt="" width="8%;" title="Delete Buyer Manual"></a>
                                                    <?php }?>
                                                    <?php if (exist_val('Buyer_manual/downlaod_pdf', $this->session->userdata('permission'))) { ?>

                                                    <a class="download file" href="<?php echo $AS->upload_file_path; ?>" download="" target="_blank" ><img src="<?php echo base_url(); ?>assets/images/icon/download.png" alt="" width="8%;" title="Download Buyer Manual"></a>
                                                   <?php }?>
                                                   <a href="javascript:void(0)" data-id="<?php echo $AS->buyer_manual_id;?>" class="log_view" data-bs-toggle='modal' data-bs-target='#exampleModal' class="btn btn-sm" title="Log View"><img src="<?php echo base_url('assets/images/log-view.png'); ?>" alt="Log view" width="20px"></a>

                                                    </td>
                                                </tr>

                                                <?php $i++;
                                            endforeach; ?><?php } else { ?>
                                                <tr>
                                                    <td> <?php echo "NO RECORD FIND";
                                                        } ?></td>
                                                </tr>

                                    </tbody>
                                </table>

                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-sm-12"> <?php echo $links; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
</div>

</div>



<script>

        

    function filter_by() {
        var base_url = '<?php echo base_url('Buyer_manual/index/'); ?>';
       
        var created_by = $('#created_by').val();
        var buyer = $('#buyer').val();
        var title = $('#title').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
    
        base_url += '/' + ((created_by) ? created_by : 'NULL');
        base_url += '/' + ((buyer) ? btoa(buyer) : 'NULL');
        base_url += '/' + ((title) ? btoa(title) : 'NULL');
        base_url += '/' + ((start_date) ? start_date : 'NULL');
        base_url += '/' + ((end_date) ? end_date : 'NULL');
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
    var base_url = $("body").attr("data-url");
    getAutolist(
        "customer_id",
        "customer_name",
        "customer_list",
        "customer_li",
        "",
        "customer_name",
        "customer_id as id,customer_name as cust_name",
        "cust_customers"
    );
    getAutolist(
        "buyer_manual_id",
        "title",
        "customer_list",
        "customer_li",
        "",
        "title",
        "buyer_manual_id as id,title as title",
        "cps_buyermanual"
    );
   


    function getAutolist(hide_input, input, ul, li, where, like, select, table) {

        var base_url = $("body").attr("data-url");
        var hide_inputEvent = $("input." + hide_input);
        var inputEvent = $("input." + input);
        var ulEvent = $("ul." + ul);

        inputEvent.focusout(function() {
            ulEvent.fadeOut();
        });

        inputEvent.on("click keyup", function(e) {
            var me = $(this);
            var key = $(this).val();
            var _URL = base_url + "get_auto_list";
            const _tokken = $('meta[name="_tokken"]').attr("value");
            e.preventDefault();
            if (key) {
                $.ajax({
                    url: _URL,
                    method: "POST",
                    data: {
                        key: key,
                        where: where,
                        like: like,
                        select: select,
                        table: table,
                        _tokken: _tokken,
                    },

                    success: function(data) {
                        var html = $.parseJSON(data);
                        ulEvent.fadeIn();
                        ulEvent.css(css);
                        ulEvent.html("");
                        if (html) {
                            $.each(html, function(index, value) {
                                ulEvent.append(
                                    $(
                                        '<li class="list-group-item ' +
                                        li +
                                        '"' +
                                        "data-id=" +
                                        value.id +
                                        ">" +
                                        value.name +
                                        "</li>"
                                    )
                                );
                            });
                        } else {
                            ulEvent.append(
                                $(
                                    '<li class="list-group-item ' +
                                    li +
                                    '"' +
                                    'data-id="">NO REORD FOUND</li>'
                                )
                            );
                        }

                        var liEvent = $("li." + li);
                        liEvent.click(function() {
                            var id = $(this).attr("data-id");
                            var name = $(this).text();
                            inputEvent.val(name);
                            hide_inputEvent.val(id);
                            ulEvent.fadeOut();
                        });

                        // ****
                    },

                });
            } else {
                hide_inputEvent.val('');
            }

        });
    }

</script>

  <!-- Modal to show log -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Branch log</h5>
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
          <tbody id="buyer_manual_log"></tbody>
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
  $(document).ready(function(){
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');
        // Ajax call to get log
        $('.log_view').click(function() {
        $('#buyer_manual_log').empty();
        var buyer_manual_id = $(this).data('id');
        $.ajax({
            type: 'post',
            url: url + 'Buyer_manual/get_buyer_manual_log',
            data: { _tokken: _tokken, buyer_manual_id: buyer_manual_id },
            success: function(data) {
                var data = $.parseJSON(data);
                var value = '';
                sno = Number();
                $.each(data, function(i, v) {
                    sno += 1;
                    var operation = v.action_taken;
                    var action_message = v.text;
                    var taken_by = v.taken_by;
                    var taken_at = v.taken_at;
                    value += '<tr>';
                    value += '<td>' + sno + '</td>';
                    value += '<td>' + operation + '</td>';
                    value += '<td>' + action_message + '</td>';
                    value += '<td>' + taken_by + '</td>';
                    value += '<td>' + taken_at + '</td>';
                    value += '</tr>';
                    
                  });
                  $('#buyer_manual_log').append(value);
            }
        });
    });
    // ajax call to get log ends here
  });
  </script>
    <!-- added by saurabh on 23-03-2021 -->
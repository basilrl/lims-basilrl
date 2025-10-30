<script src="<?php echo base_url(); ?>ckeditor/ckeditor.js"></script>
<style>
    .delimage {
        border: 1px solid black;
        padding: 0px 6px;
        background: white;
        font-weight: bold;
        font-size: 16px;
    }
    
</style>
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>EDIT BUYER MANUAL</h1>
                </div>
               
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section>





        <div class="container-fluid" style="width: 90%;">


            <form action="javascript:void(0);" enctype="multipart/form-data" name="edit_report_generation" id="edit_report_generation">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="buyer_manual_id" class="buyer_manual_id" value="<?php echo $buyer_manual['buyer_manual_id']; ?>">


                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <div class="row">
                        <div class="col-md-6">
                      <label for="">Select Buyer</label>
                      <select name="buyer_name" id="buyer" class="form-control" required>
                      <option value="<?php echo $buyer_manual['buyer_name']; ?>"><?php echo $buyer_manual['customer_name']; ?></option>

                      <?php foreach($buyer as $buy){?>
                      <option value="<?php echo $buy->customer_id;?>"><?php echo $buy->customer_name;?></option>
                     <?php }?>
                      
                      </select>
                    </div>
                    <div class="col-md-6">
                        <label for="">Document Version:</label>
                        <input type="number" name="doc_version" class="doc_version form-control" required value="<?php echo $buyer_manual['doc_version']; ?>">

                    </div>
                        
                       
                    </div>
                   
                   
                    <div class="col-md-12">
                        <label for="">Title</label>
                       <input type="text" name="title" id="" class="form-control" value="<?php echo $buyer_manual['title']; ?>">


                    </div>
                    <div class="col-md-12">
                        <label for="">Upload Document</label>
                        <input type="file" class="form-control form_data" id="multiple_image" name="multiple_image" value="<?php echo $buyer_manual['upload_filename']; ?>" ><?php echo $buyer_manual['upload_filename']; ?>
                    </div>
                    <div class="col-md-12 m-2" style="border: 1px solid lightgrey;padding:10px;">
                        <h3 class="text-center text-dark">Content</h3>
                        <label for="">Main Content</label>
                        <textarea cols='52' rows='3' name="mail_content" id="mail_content" class="form-control ckeditor "><?php echo base64_decode(html_entity_decode($buyer_manual['mail_content'])); ?></textarea>
                        
                    </div>
                    <div class="row">
                   
                    <div class="col-md-12 text-right p-2">
                        <a href="<?php echo base_url('Buyer_manual/index') ?>" class="btn btn-danger" type="submit">Back</a>
                        <button type="submit" class="btn btn-success submit" type="submit">Submit</button>
                    </div>
            </form>

        </div>
    </section>
</div>


<script>
    // frond end validation 
    const _tokken = $('meta[name="_tokken"]').attr('value');

    $(document).ready(function() {
       



        // form submission
        $("#edit_report_generation").on('submit', function(e) {
            e.preventDefault();
          
            var formData = new FormData(this);




            $.ajax({
                url: "<?php echo base_url('Buyer_manual/update_buyer_manual'); ?>",
                contentType: false,
                processData: false,
                type: 'post',
                data: formData,
                // console.log(data);
                success: function(result) {
                    console.log(result);
                    var data = $.parseJSON(result);
                    if (data.status > 0) {
                        $.notify(data.msg, 'success');
                        location.href = '<?php echo base_url('Buyer_manual/index'); ?>';
                    } else {
                        $.notify(data.msg, 'error');
                    }
                }

            });
        });
    })
</script>




<script>
    $(document).ready(function() {
        CKEDITOR.replaceClass = 'ckeditor';
        
    });
</script>



<script src="<?php echo base_url(); ?>ckeditor/ckeditor.js"></script>
<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ADD BUYER MANUAL</h1>
                </div>
              
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section>
       




        <div class="container-fluid" style="width: 90%;">


            <form action="javascript:void(0);" method="post" enctype="multipart/form-data" name="report_generations" id="buyer_manual">
                <div class="row">
                    <div class="col-md-6">
                      <label for="">Select Buyer</label>
                      <select name="buyer_name" id="buyer" class="form-control" required>
                      <option value="">Select Buyer</option>
                      <?php foreach($buyer as $buy){?>
                      <option value="<?php echo $buy->customer_id;?>"><?php echo $buy->customer_name;?></option>
                     <?php }?>
                      
                      </select>
                    </div>
                    <div class="col-md-6">
                        <label for="">Document Version:</label>
                        <input type="number" name="doc_version" class="doc_version form-control" required>

                    </div>
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                       
                    </div>
                   
                   
                    <div class="col-md-12">
                        <label for="">Title</label>
                       <input type="text" name="title" id="" class="form-control">


                    </div>
                    <div class="col-md-12">
                        <label for="">Upload Document</label>
                        <input type="file" class="form-control form_data" id="multiple_image" name="multiple_image"  >
                    </div>
                    <div class="col-md-12 m-2" style="border: 1px solid lightgrey;padding:10px;">
                        <h3 class="text-center text-dark">Content</h3>
                        <label for="">Main Content</label>
                        <textarea cols='52' rows='3' name="mail_content" id="mail_content" class="form-control ckeditor "></textarea>
                        
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
 function check(input) {
   
   if (input.value == 0) {
     input.setCustomValidity('The number must not be zero.');
   } else {
     // input is fine -- reset the error message
     input.setCustomValidity('');
   }
 }
</script>
<script>
    // frond end validation 
    const _tokken = $('meta[name="_tokken"]').attr('value');

    $(document).ready(function() {





        // form submission
        $(document).on('submit', "#buyer_manual", function(e) {
            e.preventDefault();
           
            var formData = new FormData(this);
            


          
            $.ajax({
                url: "<?php echo base_url('Buyer_manual/add_buyer_manual'); ?>",
                contentType: false,
                processData: false,
                type: 'post',
                data: formData,
                success: function(result) {
                    // console.log(result);
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



    $(document).ready(function(){
        var base_url = $('body').attr('data-url');
        var css = {"position":"absolute",
        "width":"100%",
        "font-size":"12px",
        "z-index":999,
        "overflow-y":"auto",
        "overflow-x":"hidden",
        "height":"200px" ,
        "cursor":"pointer"};

        $(".cust_name").focusout(function(){
            $('.drop_list').fadeOut();
          });

          $(".buyer_name").focusout(function(){
            $('.buyer_list').fadeOut();
          });

          $(".contact_name").focusout(function(){
            $('.con_list').fadeOut();
          });

          $(".country_of_origin").focusout(function(){
            $('.origin_list').fadeOut();
          });

          $(".country_of_destination").focusout(function(){
            $('.desti_list').fadeOut();
          });

          $(".crm_user_list").focusout(function(){
            $('.crm_list').fadeOut(); 
          })

          

        $('.drop_list').fadeOut();
        $('.buyer_list').fadeOut();
        $('.con_list').fadeOut();
        $('.origin_list').fadeOut();
        $('.desti_list').fadeOut();
        $('.crm_list').fadeOut();
        

        $('.cust_name').on('keyup',function(){
            var key = $(this).val();
            // alert(base_url);
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: base_url+'Temp_reg/getcustomers',
                method:'POST',
                data:{'search':key,
                _tokken:_tokken
                        },
                success:function(data){
                    console.log(data);
                    var html = $.parseJSON(data);
                    $('.drop_list').fadeIn(); 
                    $('.drop_list').html('');
                    $('.drop_list').css(css);
       
                            if (html) {
                                $.each(html, function(i, item) {
                                        $('.drop_list').append($('<li class="list-group-item cust_li" data-id='+item.customer_id+'>'+item.customer_name+'</li>'));
                                });
                            }
                             
                            $('.cust_li').click(function(){
                                var id = $(this).attr('data-id');
                                var name = $(this).text();
                                $('.cust_name').val(name);
                                $('.customer_id').val(id);
                                $('.drop_list').fadeOut();

                            })
                 }
            })
        })


       $('.buyer_name').on('keyup',function(){
           var key = $('.customer_id').val();
           var search =$(this).val();
           const _tokken = $('meta[name="_tokken"]').attr('value');
           $.ajax({
               url:base_url+'Temp_reg/getbuyer',
               method:'POST',
               data:{'key':key,
                    'search':search,
                    _tokken:_tokken
                    },
               success:function(data){
                    var html = $.parseJSON(data);
                    $('.buyer_list').fadeIn();
                    $('.buyer_list').html('');
                    $('.buyer_list').css(css);

                    if(html){
                        $.each(html, function(i,item){
                            $('.buyer_list').append($('<li class="list-group-item buy_li" data-id='+item.customer_id+'>'+item.customer_name+'</li>'));
                        })
                    }

                    $('.buy_li').click(function(){
                        var id = $(this).attr('data-id');
                        var name = $(this).text();
                        $('.buyer_name').val(name);
                        $('.buyer_id').val(id);
                        $('.buyer_list').fadeOut();

                    })
// ****
               }
           })
       })

       $('.contact_name').on('keyup',function(){
        var key = $('.customer_id').val();
        var search = $(this).val();
        const _tokken = $('meta[name="_tokken"]').attr('value');
        $.ajax({
            url:base_url+'Temp_reg/getContacts',
            method:'POST',
            data:{
                    'key':key,
                    'search':search,
                    _tokken:_tokken
                    },
            success:function(data){
                 var html = $.parseJSON(data);
                 $('.con_list').fadeIn();
                 $('.con_list').html('');
                 $('.con_list').css(css);
                
                 if(html){
                     $.each(html, function(i,item){
                         $('.con_list').append($('<li class="list-group-item cont_li" data-id="'+item.contact_id+'" data-email="'+item.email+'">'+item.contact_name+'</li>'));
                     })
                 }

                 $('.cont_li').click(function(){
                     var email = $(this).attr('data-email')
                     var id = $(this).attr('data-id');
                     var name = $(this).text();
                     $('.contact_name').val(name);
                     $('.contact_id').val(id);
                     $('.contact_email').val(email)
                     $('.con_list').fadeOut();

                 })
// ****
            }
        })
    })

  

    $('.country_of_origin').on('keyup',function(){
        var search = $(this).val();
        const _tokken = $('meta[name="_tokken"]').attr('value');
        $.ajax({
            url:base_url+'Temp_reg/getCountry',
            method:'POST',
            data:{
                    'search':search,
                    _tokken:_tokken
                    },
            success:function(data){
                 var html = $.parseJSON(data);
                 $('.origin_list').fadeIn();
                 $('.origin_list').html('');
                 $('.origin_list').css(css);
                
                 if(html){
                     $.each(html, function(i,item){
                         $('.origin_list').append($('<li class="list-group-item origin_li" data-id="'+item.country_id+'">'+item.country_name+'</li>'));
                     })
                 }

                 $('.origin_li').click(function(){
                     var id = $(this).attr('data-id');
                     var name = $(this).text();
                     $('.country_of_origin').val(name);
                     $('.country_origin').val(id);
                     $('.origin_list').fadeOut();

                 })
// ****
            }
        })
    })


    $('.country_of_destination').on('keyup',function(){
        var search = $(this).val();
        const _tokken = $('meta[name="_tokken"]').attr('value');
        $.ajax({
            url:base_url+'Temp_reg/getCountry',
            method:'POST',
            data:{
                    'search':search,
                    _tokken:_tokken
                    },
            success:function(data){
                 var html = $.parseJSON(data);
                 $('.desti_list').fadeIn();
                 $('.desti_list').html('');
                 $('.desti_list').css(css);
                
                 if(html){
                     $.each(html, function(i,item){
                         $('.desti_list').append($('<li class="list-group-item desti_li" data-id="'+item.country_id+'">'+item.country_name+'</li>'));
                     })
                 }

                 $('.desti_li').click(function(){
                     var id = $(this).attr('data-id');
                     var name = $(this).text();
                     $('.country_of_destination').val(name);
                     $('.country_dest').val(id);
                     $('.desti_list').fadeOut();

                 })
// ****
            }
        })
    })

        $('.crm_user_list').on('keyup',function(){
            var search = $(this).val();
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url:base_url+'Temp_reg/crm_list',
                method:'POST',
                data:{'search':search,
                _tokken:_tokken},
                success:function(data){
                    var html = $.parseJSON(data);
                    $('.crm_list').fadeIn();
                    $('.crm_list').html('');
                    $('.crm_list').css(css);
                    if(html){
                        $.each(html,function(i,crm){
                            $('.crm_list').append($('<li class="list-group-item crm" data-id="'+crm.uidnr_admin+'">'+crm.user_name+'</li>'));
                        })
                    }
                    
                    $('.crm').click(function(){
                        var id = $(this).attr('data-id');
                        var name = $(this).text();
                        $('.crm_user_list').val(name);
                        $('.crm_user').val(id);
                        $('.crm_list').fadeOut();
   
                    })


                }

            })

            
        })

    
        $(".hidecol").click(function() {

            var id = this.id;
            var splitid = id.split("_");
            var colno = splitid[1];
            var checked = true;

            // Checking Checkbox state
            if ($(this).is(":checked")) {
                checked = true;

            } else {
                checked = false;
            }
            setTimeout(function() {
                if (checked) {
                    $('#emp_table td:nth-child(' + colno + ')').hide();
                    $('#emp_table th:nth-child(' + colno + ')').hide();
                } else {
                    $('#emp_table td:nth-child(' + colno + ')').show();
                    $('#emp_table th:nth-child(' + colno + ')').show();
                }
            }, 100);
            $.ajax({
                type: "POST",
                url: base_url+"Temp_reg/col_hide",
                data: {
                    checked: checked,
                    colno: colno,
                    _tokken:_tokken
                },
                success: function(result) {

                }
            });
        });

        $(".checkbox-menu").on("change", "input[type='checkbox']", function() {
            $(this).closest("li").toggleClass("active", this.checked);
        });

        $(document).on('click', '.allow-focus', function(e) {
            e.stopPropagation();
        });

        
        
            
        
        //  const _tokken = $('meta[name="_tokken"]').attr('value');
        //  var id = $('.customer_id').val();
        //  $.ajax({
        //     url:base_url+'Temp_reg/get_customerbyId ',
        //     method:'POST',
        //     data:{
        //         customer_id:id,
        //     _tokken:_tokken},
        //     success:function(data){
        //         var html = $.parseJSON(data);
        //         // console.log(html[0].customer_name);
        //         $('.cust_name').val(html[0].customer_name);


        //     }

        // })

        // var id = $('.buyer_id').val();
        //  $.ajax({
        //     url:base_url+'Temp_reg/get_buyerbyId',
        //     method:'POST',
        //     data:{
        //         buyer_id:id,
        //     _tokken:_tokken},
        //     success:function(data){
        //         var html = $.parseJSON(data);
        //         // onsole.log(html[0].customer_name);
        //         $('.buyer_name').val(html[0].customer_name);

        //     }

        // })

        // const _tokken = $('meta[name="_tokken"]').attr('value');
        //  $.ajax({
        //     url:base_url+'Temp_reg/get_cust_list',
        //     method:'POST',
        //     data:{
        //     _tokken:_tokken},
        //     success:function(data){
        //         var html = $.parseJSON(data);
        //         // console.log(html);
        //         if(html){
        //             $.each(html,function(i,cust){
        //                 $('.cus_name').append($('<option class="" value="'+cust.customer_id+'">'+cust.customer_name+'</option>'));
        //             })
        //         }
                


        //     }

        // })
        // $.ajax({
        //     url:base_url+'Temp_reg/get_buyer',
        //     method:'POST',
        //     data:{
        //     _tokken:_tokken},
        //     success:function(data){
        //         var html = $.parseJSON(data);
        //         // console.log(html);
        //         if(html){
        //             $.each(html,function(i,buy){
        //                 $('.buy_name').append($('<option class="" value="'+buy.buyer_id+'">'+buy.customer_name+'</option>'));
        //             })
        //         }
                


        //     }

        // })
        // $.ajax({
        //     url:base_url+'Temp_reg/get_reference',
        //     method:'POST',
        //     data:{
        //     _tokken:_tokken},
        //     success:function(data){
        //         var html = $.parseJSON(data);
        //         // console.log(html);
        //         if(html){
        //             $.each(html,function(i,ref){
        //                 $('.ref').append($('<option class="" value="'+ref.reference_no+'">'+ref.reference_no+'</option>'));
        //             })
        //         }
                


        //     }

        // })
         
      $('.click_worksheet').on('click',function(){
          var src = $(this).attr('data-url');
          $('.worksheet').attr('src',src);
      })

})   
               
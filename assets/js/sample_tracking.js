$(document).ready(function(){

    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');
    var sample = $('.gc_number_value');

    

    //AUTO CAPITALIZATION
    $('.gc_number_text').on('keyup',function(){
        $(this).css(
            "text-transform", "uppercase"
        )
    });

    $('.refresh').on('click',function(){
        $('.gc_number_value').val("");
        $('.gc_number_text').val("");
        $('.sample_details_div').html("");
        $('.sample_tracking_div').html("");
        $('.sample_details_div').css("border","none");
        window.location.reload();

    });


    $('.search').on('click',function(){
        sample_reg_id = sample.val();
        var where = {'sr.sample_reg_id':sample_reg_id};
        var controller_fn = 'get_sample_details';
        get_ajax_data(where,append_fn,controller_fn);
    });

    function append_fn(data){
        
      
            var sample_status = data.sample_status;
            if(sample_status){
                innerDiv = "<div class='row'>";
                innerDiv += "<div class='col-3 gc_no '></div>";
               innerDiv += "<div class='col-3 sample_name '></div>";
               innerDiv += "<div class='col-3 created_by '></div>";
               innerDiv += "<div class='col-3 create_on '></div>";
               innerDiv += "</div>";

               innerDiv += "<div class='row'>";
               innerDiv += "<div class='col-3 customer_name '></div>";
               innerDiv += "<div class='col-3 buyer_name '></div>";
               innerDiv += "<div class='col-3 collection_date '></div>";
               innerDiv += "<div class='col-3 received_date '></div>";
              
               innerDiv += "</div>";

               innerDiv += "<div class='row'>";
               innerDiv += "<div class='col-3 test_completed_on '></div>";
               innerDiv += "<div class='col-3 final_status '></div>";
               innerDiv += "<div class='col-3 type '></div>";
               innerDiv += "<div class='col-3 sample_retain_period '></div>";
               innerDiv += "</div>";

               innerDiv += "<div class='row'>";
               innerDiv += "<div class='col-3 lab_name '></div>";
               innerDiv += "<div class='col-3 due_date '></div>";
               innerDiv += "<div class='col-3 qty_received '></div>";
               innerDiv += "<div class='col-3 sample_desc '></div>";
              
               innerDiv += "</div>";
                $('.sample_details_div').css("border","2px solid green");
                $('.sample_details_div').html(innerDiv);

                 $('.gc_no').html("<b>BASIL REPORT NUMBER</b> : " + sample_status.gc_no);
                $('.sample_name').html("<b>SAMPLE NAME</b> : " + sample_status.sample_name);
                $('.created_by').html("<b>CREATED BY</b> : " + sample_status.created_by);
                $('.create_on').html("<b>CREATED ON</b> : " + sample_status.create_on);

                $('.customer_name').html("<b>CUSTOMER NAME</b> : " + sample_status.customer_name);
                $('.buyer_name').html("<b>BUYER NAME</b> : " + sample_status.buyer_name);
                $('.collection_date').html("<b>COLLECTION DATE</b> : " + sample_status.collection_date);
                $('.received_date').html("<b>RECEIVED DATE</b> : " + sample_status.received_date);
                
                $('.qty_received').html("<b>QUANTITY RECEIVED</b> : " + sample_status.qty_received);
                $('.test_completed_on').html("<b>TEST COMPLETED ON</b> : " + sample_status.test_completed_on);
                $('.type').html("<b>TYPE</b> : " + sample_status.type);
                $('.sample_retain_period').html("<b>SAMPLE RETAINTION PERIOD</b> : " + sample_status.sample_retain_period);

                $('.lab_name').html("<b>LAB NAME</b> : " + sample_status.lab_name);
                $('.due_date').html("<b>DUE DATE</b> : " + sample_status.due_date);
                $('.final_status').html("<b>STATUS</b> : " + sample_status.final_status);
                $('.sample_desc').html("<b>SAMPLE DESCRIPTION</b> : " + sample_status.sample_desc);
            }
            else{
                innerDiv = "<div class='col-12'>NO RECORD FOUND!</div>";
                $('.sample_details_div').html(innerDiv);
            }
          
            var sample_track = data.sample_tracking_status;
            $('.sample_tracking_div').empty();
            $.each(sample_track,function(index,value){
                innerDiv =  '<div class="eclipse"><div class="eclipse_content">'+value.status+" By "+value.status_created_by+" On "+value.log_activity_on+'';
                innerDiv += '</div></div>';
                $('.sample_tracking_div').append(innerDiv);
                if(index == sample_track.length - 1){
                   var element = $('.eclipse_content')[index];
                    $(element).removeClass('eclipse_content');
                    $(element).addClass('eclipse_content_without_after');

                }
            })
             
        
    }
     // gc number autosuggetion
     get_auto_gc_number_p(
        'gc_number_value',
        'gc_number_text',
        'gc_tack_list',
        'gc_tack_li',
        '',
        'gc_no',
        'sample_reg_id as id,gc_no as name',
        'sample_registration'
    );

    var css = {
        "position": "absolute",
        "width": "96%",
        "height": "auto",
        "font-size": "18px",
        "z-index": 999,
        "overflow-y": "auto",
        "overflow-x": "hidden",
        "max-height": "200px",
        "cursor": "pointer",
        "padding" : "2px",
        "list-style" : "none"
    };

    function get_auto_gc_number_p(hide_input, input, ul, li, where, like, select, table) {

        var base_url = $("body").attr("data-url");
        var hide_inputEvent = $("input." + hide_input);
        var inputEvent = $("input." + input);
        var ulEvent = $("ul." + ul);

        inputEvent.focusout(function() {
            ulEvent.fadeOut();
        });
        
        inputEvent.on("keyup", function(e) {
            var me = $(this);
            var key = $(this).val();
            var _URL = url + 'Sample_tracking/get_auto_gc_list';
            const _tokken = $('meta[name="_tokken"]').attr("value");
            e.preventDefault();


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
                            ulEvent.append($('<li class="list-group-item ' + li + '"' + "data-id=" + value.id + " style='padding:2px'>" + value.name + "<li>"));
                        });
                    } else {
                        ulEvent.append($('<li class="list-group-item ' + li + '"' + 'data-id="" style="padding:2px">NO REORD FOUND</li>'));
                    }

                    var liEvent = $("li." + li);
                    liEvent.click(function() {
                        var id = $(this).attr("data-id");
                        var name = $(this).text();
                        inputEvent.val(name);
                        hide_inputEvent.val(id);
                        // inputEvent.css('border','2px solid green');
                        ulEvent.fadeOut();
                    });

                    // ****
                },

            });

            return false;

        });
    }
    
    // ajax call function 
    function get_ajax_data(param,success_fn,controller_fn){
        obj = JSON.stringify(param);
        $.ajax({
            url:url + "Sample_tracking/get_ajax_data",
            method : "POST",
            dataType : "JSON",
            data :{
                param : obj,
                controller_fn :controller_fn,
                _tokken : _tokken
            },
            success : function(data){

                success_fn(data);
            }
        });
        return false;
    }
});
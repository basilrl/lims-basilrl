$(document).ready(function(){
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');

    // _element = $('.division_dropdown');
    // $.ajax({
    //     url: url+ 'Backlogs_details/get_divisions_due',
    //     method : "GET",
    //     success : function(data){
    //         var data = $.parseJSON(data);
    //         var op = "<option value=''>SELECT DIVISION</option>";
    //         _element.html("");
    //         _element.append(op);
    //         if(data){
    //             $.each(data,(i,v)=>{
    //                 op = "<option value='"+v.division_id+"'>"+v.division_name+"</option>";
    //                 _element.append(op);
    //             });
    //         }
    //     }
    // })

    // $.ajax({
    //     url: url + 'Backlogs_details/view_due_data/' + 1 +'/'+ 20,
    //     method : "POST",
    //     data : {
    //         _tokken:_tokken
    //     },
    //     success:function(data){
    //         var data = $.parseJSON(data);
    //         // var list = "<li class='list-group-item' style='cursor:pointer;padding: 5px;'><b>Divisions</b></li>";
    //         // $('.side_ul').html("");
    //         // $('.side_ul').append(list);
            
    //         $.each(data,function(index,backlogs){
              
    //             $.each(backlogs,function(division,backlogs_data){
                   
    //                 var count = $.map(backlogs_data, function(n, i) { return i; }).length;
    //                 $.each(backlogs_data,function(ni,div_id){
    //                     division_id = div_id.division_id;
                        
    //                 })
                   
    //                 if(division_id){
    //                     if(division_id==1){
    //                         list = "<li class='list-group-item division_backlog active first' style='cursor:pointer;padding: 5px;' data-id='"+division_id+"' >"+division+"("+count+")"+"</li>";
    //                         $('.side_ul').append(list);
    //                     }
    //                     else{
    //                         list = "<li class='list-group-item division_backlog' style='cursor:pointer;padding: 5px;' data-id='"+division_id+"' >"+division+"("+count+")"+"</li>";
    //                         $('.side_ul').append(list);
    //                     }
    //                 }
                 
                   
                 
                   
    //             })
               
    //         })
            
    //     }
    // });

    get_backlogs_by_division(null,5,0,null);

    // $(document).on('click','.division_backlog',function(){
    //     reset_pag();
    //     var div_id = $(this).attr("data-id");
    //     $('.previous_data').data("id",div_id);
    //     $('.next_data').data("id",div_id);
    //     get_backlogs_by_division(div_id,5,0,null);
    // })

   


    function get_backlogs_by_division(division_id='',limit=5,offset=0,sample_reg_id=null){
       
        $.ajax({
            url: url + 'Backlogs_details/get_division_wise_backlog',
            method: "post",
            data: {
                _tokken: _tokken,
                division_id: division_id,
                offset: offset,
                sample_reg_id: sample_reg_id,
                limit: limit,
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                buyer: $('#buyer').val(),
                agent: $('#agent').val(),
                labType: $('#labType').val(),
                division_dropdown: $('.division_dropdown').val(),
                customer: $('#customer').val(),
            },
            success: function (data) {
                var backlogs_data = $.parseJSON(data);

                $('.backlog_data_table tbody').html("");
                if (backlogs_data.data) {

                    var sn = offset + 1;
                    $.each(backlogs_data.data, function (index1, back) {

                        var tr = "<tr>";
                        tr += "<td>" + sn + "</td>";
                        tr += "<td>" + back.gc_no + "</td>";
                        tr += "<td style='width:200px'>" + back.sample_desc + "</td>";
                        tr += "<td>" + back.division_name + "</td>";
                        tr += "<td>" + back.due_date + "</td>";
                        tr += "<td><button type='button' class='btn btn-sm btn-info active_gc' data-id='" + back.sample_reg_id + "' data-toggle='modal' data-target='#view_gc_backlog' title='View Details'><i class='fas fa-eye'></i></button></td>";
                        tr += "</tr>";
                        $('.backlog_data_table tbody').append(tr);
                        sn++;
                    });
                   
                        if (backlogs_data.count <= (offset + limit)) {
                            $('.next_data').attr('disabled', true);
                            $('.previous_data ').attr('disabled', false);
                        } else {
                            $('.next_data').attr('disabled', false);
                            $('.previous_data ').attr('disabled', false);
                        }

                    $('.start').html("");
                    $('.start').html(offset + 1);
                    $('.end').html("");
                    $('.end').html(offset + limit);
                    $('.total').html("")
                    $('.total').html(backlogs_data.count);



                }
                else {
                    var tr = "<tr>";
                    tr += "<td colspan='5'>NO MORE RECORDS!</td>";
                    tr += "</tr>";
                    $('.backlog_data_table tbody').append(tr);
                     $('#showing_result_div').css("display", "none");
                     $('.start').attr('disabled', true);
                    $('.end').attr('disabled', true);
                }

            }
        });
        return false;
    }

    $('.next_data').on('click', function () {
        var div_id = $(this).val(); // dashboard
        var limit = $(this).data("limit");
        var offset = $(this).data("offset");
        var new_offset = offset + limit;
        $(this).data("offset",new_offset);
        $('.previous_data').data("offset",new_offset);
        get_backlogs_by_division(div_id,limit,new_offset,null);
    });

    $('.previous_data').on('click', function () {
        var div_id = $(this).val(); // dashboard
        var limit = $(this).data("limit");
        var offset = $(this).data("offset");
        if(offset >= limit){
            var new_offset = offset - limit;
        }
        else{
            var new_offset = offset;
        }
       
        $(this).data("offset",new_offset);
        $('.next_data').data("offset",new_offset);
        get_backlogs_by_division(div_id,limit,new_offset,null);
    })


     // gc number autosuggetion
     getAutolist_backlogs(
        'gc_hide',
        'serach_by_gc',
        'gc_backlog_list',
        'gc_backlog_li',
        '',
        'gc_no',
        'sample_reg_id as id,gc_no as name',
        'sample_registration'
    );

    var css = {
        "position": "absolute",
        "width": "96%",
        "height": "auto",
        "font-size": "10px",
        "z-index": 999,
        "overflow-y": "auto",
        "overflow-x": "hidden",
        "max-height": "200px",
        "cursor": "pointer",
        "padding" : "2px",
        "list-style" : "none"
    };

    function getAutolist_backlogs(hide_input, input, ul, li, where, like, select, table) {

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
            var _URL = url + 'Backlogs_details/get_auto_list_gc';
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
                            ulEvent.append($('<li class="list-group-item ' + li + '"' + "data-id=" + value.id + ">" + value.name + "<li>"));
                        });
                    } else {
                        ulEvent.append($('<li class="list-group-item ' + li + '"' + 'data-id="">NO REORD FOUND</li>'));
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

            return false;

        });
    }

    // $(document).on('change','.division_dropdown',function(){
    //     reset_pag();
    //     var div_id = $(this).val();
    //     $('.previous_data').data("id",div_id);
    //     $('.next_data').data("id",div_id);
    //     get_backlogs_by_division(div_id,5,0,null);
    // })

    $('.search_button').on('click',function(){
        gc_no = $('.serach_by_gc').val();

        if(gc_no==""){
            $('.gc_hide').val("");
            sample_reg_id = null;
        }
        else{
            sample_reg_id = $('.gc_hide').val();
        }

        div_id = _element.val();
       
        if(div_id==""){
            _element.val("");
            div_id = null;
        }

        reset_pag();
        $('.previous_data').data("id",div_id);
        $('.next_data').data("id",div_id);
        get_backlogs_by_division(div_id,5,0,sample_reg_id);
       
       
    })

    $('.clear_button').on('click',function(){
        _element.val("");
        $('.serach_by_gc').val("");
        $('.gc_hide').val("");
        $('.division_backlog.active').removeClass("active");
        $('.first').addClass("active");
        get_backlogs_by_division(null,5,0,null);
        reset_pag();
    })

    function reset_pag(){
        $('.previous_data').data("id",1);
        $('.previous_data').data("limit",5);
        $('.previous_data').data("offset",0);

        $('.next_data').data("id",1);
        $('.next_data').data("limit",5);
        $('.next_data').data("offset",0);
    }


    $(document).on('click','.division_backlog',function(){
        $('.division_backlog.active').removeClass("active");
        $(this).addClass("active");
    })

    

})

$(document).ready(function(){
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');
    $('.next_data_invoice').attr('disabled',false);
    $('.previous_data_invoice').attr('disabled',false);


    $('.from_date').on('change',function(){
        if($('.to_date').val()==""){

            $.notify('Please select end date!', 'error');
        }
    })

    select_element = $('.division_dropdown_invoice');
    $.ajax({
        url: url+ 'Invoice_not_upload/get_divisions',
        method : "GET",
        success : function(data){
            var data = $.parseJSON(data);
            var op = "<option value=''>SELECT DIVISION</option>";
            select_element.html("");
            select_element.append(op);
            if(data){
                $.each(data,(i,v)=>{
                    op = "<option value='"+v.division_id+"'>"+v.division_name+"</option>";
                    select_element.append(op);
                });
            }
        }
    })


// function call
    get_not_upload_invoice(null,5,0,null,null,null);

// end


    function get_not_upload_invoice(div_id_invoice,limit_invoice,offset_invoice,search,cust_id,from,to){
        $.ajax({
            url: url + 'Invoice_not_upload/get_not_upload_invoice',
            method: "post",
            data: {
                _tokken: _tokken,
                division_id: div_id_invoice,
                offset: offset_invoice,
                search: search,
                limit: limit_invoice,
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                buyer: $('#buyer').val(),
                agent: $('#agent').val(),
                labType: $('#labType').val(),
                division_dropdown: $('.division_dropdown').val(),
                customer: $('#customer').val(),
                year: $('#year').val(),
                month: $('#month').val(),
                report_reviewer: $('#report_reviewer').val(),// report reviewer
            },
            success: function (data) {
                var invoice_data = $.parseJSON(data);

                $('#invoice_not_upload').html("");
                if (invoice_data.invoice_data) {

                    var sn_no = offset_invoice + 1;
                    $.each(invoice_data.invoice_data, function (ind, invoice) {

                        var tr = "<tr>";
                        tr += "<td>" + sn_no + "</td>";
                        tr += "<td>" + invoice.gc_no + "</td>";
                        tr += "<td>" + invoice.customer_name + "</td>";
                        tr += "<td>" + invoice.division_name + "</td>";
                        tr += "<td>" + invoice.status + "</td>";
                        tr += "<td><button type='button' class='btn btn-sm btn-info active_invoice' data-id='" + invoice.sample_reg_id + "' data-toggle='modal' data-target='#view_invoice' title='View Details'><i class='fas fa-eye'></i></button></td>";
                        tr += "</tr>";
                        $('#invoice_not_upload').append(tr);
                        sn_no++;
                    });


                    $('.start_invoice').html("");
                    $('.start_invoice').html(offset_invoice + 1);
                    $('.end_invoice').html("");
                    console.log(invoice_data.count);
                    console.log(offset_invoice + 1);
                    // if(invoice_data.count < (offset_invoice+1)){
                    if (invoice_data.count < limit_invoice) {
                        $('.end_invoice').html(invoice_data.count);
                        $('.next_data_invoice').attr('disabled', true);
                        $('.previous_data_invoice').attr('disabled', true);
                    }
                    else {
                        if (invoice_data.count <= (offset_invoice + limit_invoice)) {
                            $('.end_invoice').html(offset_invoice + limit_invoice);
                            $('.next_data_invoice').attr('disabled', true);
                            $('.previous_data_invoice').attr('disabled', false);
                        } else {
                            $('.end_invoice').html(offset_invoice + limit_invoice);
                            $('.next_data_invoice').attr('disabled', false);
                            $('.previous_data_invoice').attr('disabled', false);
                        }

                    }

                    $('.total_invoice').html("");
                    $('.total_invoice').html(invoice_data.count);
                    $('#showing_result').css("display", "block");


                }
                else {
                    var tr = "<tr>";
                    tr += "<td colspan='5'>NO MORE RECORDS!</td>";
                    tr += "</tr>";
                    $('#invoice_not_upload').append(tr);
                    $('#showing_result').css("display", "none");
                    $('.next_data_invoice').attr('disabled', true);
                    $('.previous_data_invoice').attr('disabled', true);
                }

            }
        });
        return false;
    }

    $('.next_data_invoice').on('click', function () {
        var div_id_invoice = $(this).val();
        var limit_invoice = $(this).data("limit");
        var offset_invoice = $(this).data("offset");
        var search = $('.search_invoice').val();
        var cust_id = $('.cus_hide').val();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        var new_offset_invoice = offset_invoice + limit_invoice;
        $(this).data("offset",new_offset_invoice);
        $('.previous_data_invoice').data("offset",new_offset_invoice);
        get_not_upload_invoice(div_id_invoice,limit_invoice,new_offset_invoice,search,cust_id,from_date,to_date);
    });

    $('.previous_data_invoice').on('click', function () {
        var div_id_invoice = $(this).val();
        var limit_invoice = $(this).data("limit");
        var offset_invoice = $(this).data("offset");
        var search = $('.search_invoice').val();
        var cust_id = $('.cus_hide').val();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        if(offset_invoice >= limit_invoice){
            var new_offset_invoice = offset_invoice - limit_invoice;
        }
        else{
            var new_offset_invoice = offset_invoice;
        }
       
        $(this).data("offset",new_offset_invoice);
        $('.next_data_invoice').data("offset",new_offset_invoice);
        get_not_upload_invoice(div_id_invoice,limit_invoice,new_offset_invoice,search,cust_id,from_date,to_date);
    })


     
     getAutolist_invoice(
        'invoice_hide',
        'search_by_invoice',
        'invoice_no_list',
        'invoice_no_li',
        '',
        'gc_no',
        'gc_no as id,gc_no as name',
        'sample_registration'
    );

    getAutolist_invoice(
        'cus_hide',
        'search_by_cus',
        'cus_list',
        'cus_li',
        '',
        'customer_name',
        'customer_id as id,customer_name as name',
        'cust_customers'
    );

    var css = {
        "position": "absolute",
        "width": "93%",
        "height": "auto",
        "font-size": "10px",
        "z-index": 999,
        "overflow-y": "auto",
        "overflow-x": "hidden",
        "max-height": "200px",
        "cursor": "pointer",
        "padding": "2px",
        "margin": "1px",
        "list-style": "none"
    };

    function getAutolist_invoice(hide_input, input, ul, li, where, like, select, table) {

        var hide_inputEvent = $("input." + hide_input);
        var inputEvent = $("input." + input);
        var ulEvent = $("ul." + ul);

        inputEvent.focusout(function() {
            ulEvent.fadeOut();
        });

        inputEvent.on("keyup", function(e) {
            var me = $(this);
            var key = $(this).val();
            var _URL =  url+ 'Invoice_not_upload/get_auto_list_invoice';
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
                            ulEvent.append($('<li class="list-group-item ' + li + '"' + "data-id=" + value.id + " style='padding:0'>" + value.name + "<li>"));
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


    $('.search_button_invoice').on('click',function(){
       
        var customer_name_inv = $('.search_by_cus').val();
        if(customer_name_inv!=""){
            var customer_id_inv = $('.cus_hide').val();
        }
        else{
           $('.cus_hide').val("");
           customer_id_inv = null;
        }
       

        var from_date_inv = $('.from_date').val();
        if(from_date_inv!=""){
            from_date_inv=from_date_inv;
        }
        else{
            from_date_inv = null
        }

        var to_date_inv = $('.to_date').val();
        if(to_date_inv!=""){
            to_date_inv=to_date_inv;
        }
        else{
            to_date_inv=null;
        }

        var division_id_inv = $('.division_dropdown_invoice').val();
        if(division_id_inv!=""){
            division_id_inv=division_id_inv;
        }
        else{
            division_id_inv=null;
        }

       
        var gc_no_inv = $('.search_by_invoice').val();
        if(gc_no_inv!=""){
            var gc_no_hide_inv = $('.invoice_hide').val();  
        }else{
            $('.invoice_hide').val("");
            gc_no_hide_inv = null;
        }
        get_not_upload_invoice(division_id_inv,5,0,gc_no_hide_inv,customer_id_inv,from_date_inv,to_date_inv);
        reset_pag_invoice();
    })

    $('.clear_button_invoice').on('click',function(){
        select_element.val("");
        $('.search_by_invoice').val("");
        $('.search_by_invoice').val("");
        $('.invoice_hide').val("");
        $('.cus_hide').val("");
        $('.search_by_cus').val("");
        $('.from_date').val("");
        $('.to_date').val("");
        get_not_upload_invoice(null,5,0,null,null,null);
        reset_pag_invoice();
    })

    function reset_pag_invoice(){
        $('.previous_data_invoice').data("id",1);
        $('.previous_data_invoice').data("limit",5);
        $('.previous_data_invoice').data("offset",0);

        $('.next_data_invoice').data("id",1);
        $('.next_data_invoice').data("limit",5);
        $('.next_data_invoice').data("offset",0);
    }


    $(document).on('click','.active_invoice',function(){
        var manual_invoice_id = $(this).data("id");
        $.post({
            url:url+"Invoice_not_upload/get_details_particular",
            method : "POST",
            data : {
                _tokken:_tokken,
                manual_invoice_id : manual_invoice_id
            },
            success:function(res){
                var data = $.parseJSON(res);
              
                $('#invoice_view_body').html("");
              
                if(data){
                    htmld = "<div class='row'>";
                        htmld += "<div class='col-sm-4'>";
                        htmld += "<b>Customer Name : </b>"+data.customer_name;
                        htmld += "</div>";

                        htmld += "<div class='col-sm-4'>";
                        htmld += "<b>Division Name : </b>"+data.division_name;
                        htmld += "</div>";

                        htmld += "<div class='col-sm-4'>";
                        htmld += "<b>Basil Report Number : </b>"+data.gc_no;
                        htmld += "</div>";
                    htmld += "</div>";

                    htmld += "<div class='row'>";
                        htmld += "<div class='col-sm-4'>";
                        htmld += "<b>Status : </b>"+data.status;
                        htmld += "</div>";

                        htmld += "<div class='col-sm-4'>";
                        htmld += "<b>Collection Date : </b>"+data.collection_date;
                        htmld += "</div>";

                        htmld += "<div class='col-sm-4'>";
                        htmld += "<b>Received Date : </b>"+data.received_date;
                        htmld += "</div>";
                    htmld += "</div>";

                    htmld += "<div class='row'>";
                        htmld += "<div class='col-sm-4'>";
                        htmld += "<b>Sample Description : </b>"+data.sample_desc;
                        htmld += "</div>";

                        htmld += "<div class='col-sm-4'>";
                        htmld += "<b>Qty Received: </b>"+data.qty_received;
                        htmld += "</div>";

                        htmld += "<div class='col-sm-4'>";
                        htmld += "<b>Created On : </b>"+data.create_on;
                        htmld += "</div>";

                    htmld += "</div>";

                htmld += "<div class='row'>";
                    
                    htmld += "<div class='col-sm-4'>";
                    htmld += "<b>Created By: </b>"+data.create_by;
                    htmld += "</div>";

                    htmld += "<div class='col-sm-4'>";
                    htmld += "<b>Due Date : </b>"+data.due_date;
                    htmld += "</div>";
                htmld += "</div>";
                

                    $('#invoice_view_body').html(htmld);
                    $('#view_invoice').modal('show');// dashboard
                }
            }
        })
    })
 

})

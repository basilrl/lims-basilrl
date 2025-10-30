
$(document).ready(function () {

    // auto load suggetion for input common function.....   
    var base_url = $('body').attr('data-url');
    var css = {
        "position": "absolute",
        "width": "95%",
        "font-size": "12px",
        "z-index": 999,
        "overflow-y": "auto",
        "overflow-x": "hidden",
        "max-height": "200px",
        "cursor": "pointer"
    };


    function getAutolist(hide_input, input, ul, li, where, like, select, table) {

        var hide_inputEvent = $("input." + hide_input);
        var inputEvent = $("input." + input);
        var ulEvent = $("ul." + ul);

        inputEvent.focusout(function () {
            ulEvent.fadeOut();
        })

        inputEvent.on('click keyup', function () {
            var key = $(this).val();
            var _URL = base_url + 'get_auto_list';
            const _tokken = $('meta[name="_tokken"]').attr('value');
            $.ajax({
                url: _URL,
                method: 'POST',
                data: {
                    key: key,
                    where: where,
                    like: like,
                    select: select,
                    table: table,
                    _tokken: _tokken
                },

                success: function (data) {
                    var html = $.parseJSON(data);
                    ulEvent.fadeIn();
                    ulEvent.css(css);
                    ulEvent.html('');
                    if (html) {
                        $.each(html, function (index, value) {
                            ulEvent.append($('<li class="list-group-item ' + li + '"' + 'data-id=' + value.id + '>' + value.name + '</li>'));
                        })
                    }
                    else {
                        ulEvent.append($('<li class="list-group-item ' + li + '"' + 'data-id="not">NO REORD FOUND</li>'));
                    }

                    var liEvent = $("li." + li);
                    liEvent.click(function () {
                        var id = $(this).attr('data-id');
                        var name = $(this).text();
                        inputEvent.val(name);
                        hide_inputEvent.val(id);
                        ulEvent.fadeOut();

                    })



                    // ****
                }
            })
        })

    }

    // function getAutolist(hide_input,input,ul,li,where,like,select,table)

    getAutolist('sample_category_id', 'sample_category_name', 'cat_list', 'cat_li', '', 'sample_category_name', 'sample_category_id as id,sample_category_name as name', 'mst_sample_category');

    getAutolist('unit_id', 'unit', 'unit_list', 'unit_li', '', 'unit', 'unit_id as id,unit as name', 'units');

    getAutolist('roport_unit', 'report_unit_name', 'report_unit_list', 'report_unit_li', '', 'unit', 'unit_id as id,unit as name', 'units');
    var where_array = {};
    where_array['status']='1';
    getAutolist('div_id', 'div_name', 'div_list', 'div_li', JSON.stringify(where_array), 'division_name', 'division_id as id,division_name as name', 'mst_divisions');

    getAutolist('lab_id', 'lab_name', 'lab_list', 'lab_li', '', 'lab_type_name', 'lab_type_id as id,lab_type_name as name', 'mst_lab_type');

    getAutolist('sample_id', 'sample_name', 'sample_list', 'sample_li', '', 'sample_type_name', 'sample_type_id as id,sample_type_name as name', 'mst_sample_types');






    // multiple checkbox auto function 

    function multipleCheck(selectClass, optionClass, where, like, select, table) {
        var selectBox = $("select." + selectClass);
        var option = $("option." + optionClass);

        selectBox.focusout(function () {
            selectBox.fadeOut();
        })

        var _URL = base_url + 'multiple_checkboxlist';
        const _tokken = $('meta[name="_tokken"]').attr('value');

        $.ajax({
            url: _URL,
            method: "POST",
            data: {
                _tokken: _tokken,
                like: like,
                select: select,
                table: table,
                where: where
            },
            success: function (data) {
                selectBox.fadeIn();
                var html = $.parseJSON(data);
                if (html) {
                    $.each(html, function (index, value) {
                        selectBox.append($('<option value="' + value.id + '" data-value="' + value.name + '" class="' + option + '">' + value.name + '</option>'));
                    })
                }
                else {
                    selectBox.append($('<option class="' + option + '"' + 'data-id="not">NO REORD FOUND</option>'));
                }

            }

        })

    }

    //  function multipleCheck(selectClass,optionClass,where,like,select,table)

    multipleCheck('type_list', 'type_op', '', 'sample_type_name', 'sample_type_id as id,sample_type_name as name', 'mst_sample_types');

    $('.type_list').select2({
        placeholder: "Select Products..."
    });

    $('.service').select2({
        placeholder: "Select Service Type..."
    })


    // 27.11.2020

    //    protocol country multiple checkboxlist
    multipleCheck('protocol_country_id', 'protocol_country_option', '', 'country_name', 'country_id as id,country_name as name', 'mst_country');

    $('.protocol_country_id').select2({
        placeholder: "Select countries..."
    })

    // end



    //    product
    getAutolist('sample_type_id', 'sample_type_name', 'sample_list', 'sample_li', '', 'sample_type_name', 'sample_type_id as id,sample_type_name as name', 'mst_sample_types');

    // end


    getAutolist('sub_con_id', 'sub_lab_name', 'sub_lab_list', 'sub_lab_li', '', 'lab_name', 'lab_details_id as id,CONCAT(lab_name,"(",lab_address,")") as name', 'sub_contract_lab_details');
    //  buyers

    function getAutobuyers(selectClass, optionClass) {

        var selectBox = $("select." + selectClass);
        var option = $("option." + optionClass);

        selectBox.focusout(function () {
            selectBox.fadeOut();
        })

        var _URL = base_url + 'fetch_Buyers';
        const _tokken = $('meta[name="_tokken"]').attr('value');

        $.ajax({
            url: _URL,
            method: "POST",
            data: {
                _tokken: _tokken,
            },
            success: function (data) {
                selectBox.fadeIn();
                var html = $.parseJSON(data);
                if (html) {
                    $.each(html, function (index, value) {
                        selectBox.append($('<option value="' + value.buyer_id + '" data-value="' + value.buyer_name + '" class="' + option + '">' + value.buyer_name + '</option>'));
                    })
                }
                else {
                    selectBox.append($('<option class="' + option + '"' + 'data-id="not">NO REORD FOUND</option>'));
                }

            }

        })


    }
    // function call of buyers autosuggetion multiselect

    getAutobuyers('protocol_buyer_id', 'protocol_buyer_option');
    $('.protocol_buyer_id').select2({
        placeholder: "Select buyers..."
    })



    //   auto suggetion multipdropdown


    // Added by CHANDAN --16-05-2022
    $(document).on('click', '#add_result_column', function () {
        let total = parseInt($('.total_result_column_counter').length) + 1;
        if (total > 5) {
            alert('Sorry, Result column can not be more than 5.');
            return false;
        } else {
            $('#dynamic_test_parameters_table th:last-child').after('<th><input type="text" class="form-control form-control-sm total_result_column_counter" required name="head_result_' + total + '" value="RESULT ' + total + '" /></th>');

            $('#dynamic_test_parameters_table td:last-child').after('<td><input type="text" class="form-control form-control-sm" name="result_' + total + '[]" /></td>');
        }
        //let heading = ''
    });

    $(document).on('click', '#del_result_column', function () {
        let total = parseInt($('.total_result_column_counter').length);
        if (total > 1) {
            let cnf = confirm("Are you want to delete Result Column?");
            if (cnf == true) {
                $('#dynamic_test_parameters_table th:last-child').remove();
                $('#dynamic_test_parameters_table td:last-child').remove();
            }
        } else {
            alert('Sorry, One Result field is required!');
            return false;
        }
    });
    // End....
});
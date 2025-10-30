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
                    } else {
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

    getAutolist('div_id', 'div_name', 'div_list', 'div_li', '', 'division_name', 'division_id as id,division_name as name', 'mst_divisions');

    getAutolist('lab_id', 'lab_name', 'lab_list', 'lab_li', '', 'lab_type_name', 'lab_type_id as id,lab_type_name as name', 'mst_lab_type');

    getAutolist('sample_id', 'sample_name', 'sample_list', 'sample_li', '', 'sample_type_name', 'sample_type_id as id,sample_type_name as name', 'mst_sample_types');





    var selectBox = $('.type_list');
   
    selectBox.focusout(function () {
        selectBox.fadeOut();
    })


    $('.type_list').select2({
        placeholder: "Select Products..."
    });

    $('.service').select2({
        placeholder: "Select Service Type..."
    })


})
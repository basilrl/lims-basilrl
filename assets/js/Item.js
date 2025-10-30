$(document).ready(function () {
  var base_url = $("body").attr("data-url");
  var _tokken = $('meta[name="_tokken"]').attr("value");

  var pageno = 0;
  loadPagination(pageno);
  $("#application_pagination").on("click", "a", function (e) {
    e.preventDefault();
    pageno = $(this).attr("data-ci-pagination-page");
    loadPagination(pageno);
  });

  function loadPagination(pagno) {
    var search = $(".search").val() ? btoa($(".search").val()) : "NULL";
    $.ajax({
      url: base_url + "Item/store_listing/" + search + "/" + pagno,
      type: "get",
      dataType: "json",
      success: function (response) {
        $("#application_pagination").html(response.pagination);
        createTable(response.result);
      },
    });
  }

  function createTable(result) {
    $("#application_list").empty();
    $("#application_list").html(result);
  }
  $(document).on("click", ".search_listing", function () {
    loadPagination(0);
  });
  $(document).on("click", ".clear_listing", function () {
    $(".search").val("");
    loadPagination(0);
  });
  var add_role = 1;
  $(document).on("click", ".add_application", function (e) {
    $(".add_submit").trigger("reset");
    $("input[name='item_id']").val("");
    category();
    unit();
  });
  $(document).on("submit", ".add_submit", function (e) {
    e.preventDefault();
    var self = $(this);
    if (add_role == 1) {
      var formdata = new FormData(this);
      $.ajax({
        async: true,
        url: base_url + "Item/add",
        type: "post",
        processData: false,
        contentType: false,
        data: formdata,
        success: function (data) {
          $(".errors").remove();
          var data = $.parseJSON(data);
          if (data.status > 0) {
            loadPagination(0);
            var tokken = $("input[name='_tokken']").val();
            $(".add_submit input[name='item_id']").val('');
            $("input[name='_tokken']").val(tokken);
            add_role = 1;
            self.trigger("reset");
            $.notify(data.msg, "success");
            $("#add_Store").modal("hide");
          } else {
            add_role = 1;
            $.notify(data.msg, "error");
          }
          if (data.errors) {
            $.each(data.errors, function (i, v) {
              $(".add_submit input[name='" + i + "']").after(
                "<span class='text-danger errors'>" + v + "</span>"
              );
              $(".add_submit select[name='" + i + "']").after(
                "<span class='text-danger errors'>" + v + "</span>"
              );
            });
          }
        },
        error: function (e) {
          console.log(e);
        },
      });
    } else {
      $.notify("WAIT FOR RESPONSE", "error");
    }
  });

  $(document).on('click','.add_Stock_form_open',function(){
      var id = $(this).data('id');
      console.log(id);
      $('.add_stock_form_submit input[name="item_id"]').attr('value',id);
  });
  $(document).on('click','.log',function(){
    $("body").append('<div class="pageloader"></div>');
      var id = $(this).data('id');
      $.ajax({
        async: true,
        url: base_url + "Item/store_userlog_dtlsview",
        type: "post",
        data: {
          id: id,
          _tokken: _tokken,
        },
        success: function (data) {
          $(".pageloader").remove();
          var data = $.parseJSON(data);
          $('#log_html').html(data);
        },
        error: function (e) {
          $(".pageloader").remove();
          console.log(e);
        },
      });
  });
  $(document).on('submit','.add_stock_form_submit',function(e){
    e.preventDefault();
    $("body").append('<div class="pageloader"></div>');
      var form_data = new FormData(this);
      $.ajax({
        async: true,
        url: base_url + "Item/addStock",
        type: "post",
        data: form_data,
        processData: false,
        contentType: false,
        success: function (data) {
          $('.remove_add_stock_error').remove();
          $(".pageloader").remove();  
          var data = $.parseJSON(data);
            if (data.status > 0) {
              
                $('#role_permission').modal('hide');
                $('.add_stock_form_submit').trigger('reset');
                $.notify(data.message, "success");
            } else {
              $.notify(data.message, "error");
            }

          if (data.errors) {
              $.each(data.errors,function(i,v){
                $('.add_stock_form_submit input[name="'+i+'"]').after('<span class="text-danger remove_add_stock_error">'+v+'</span>')
                $('.add_stock_form_submit select[name="'+i+'"]').after('<span class="text-danger remove_add_stock_error">'+v+'</span>')
              });
          }
          
        },
        error: function (e) {
          console.log(e);
        },
      });
  });


  $(document).on("click", ".edit_role", function () {
    $("body").append('<div class="pageloader"></div>');
    var id = $(this).data("id");
    $(".add_submit input[name='item_id']").val(id);
    $.ajax({
      async: true,
      url: base_url + "Item/master_items_get",
      type: "post",
      data: {
        id: id,
        _tokken: _tokken,
      },
      success: function (data) {
        var data = $.parseJSON(data);
        if (data) {
          if (data.category_id) {
            category(data.category_id);
          }
          if (data.unit) {
            unit(data.unit);
          }
          if (data.critical_item==1) {
            $(".add_submit input[name='critical_item']").prop('checked', true);
          }else{
            $(".add_submit input[name='critical_item']").prop('checked', false);
          }
          if (data.item_name) {
            $(".add_submit input[name='item_name']").val(data.item_name);
          }
          if (data.min_quantity_required) {
            $(".add_submit input[name='min_quantity_required']").val(data.min_quantity_required);
          }
        }
        $(".pageloader").remove();
      },
      error: function (e) {
        console.log(e);
      },
    });
  });
  category();
  function category(id) {
    $.ajax({
      url: base_url + "Item/category",
      type: "post",
      data: {
        _tokken: _tokken,
      },
      success: function (result) {
        $(".category").empty().append('<option>SELECT ONE</option>');
        var data = $.parseJSON(result);
        if (data) {
          $.each(data, function (i, v) {
            if (id) {
              if (v.category_id==id) {
                $(".category").append('<option selected value="' +v.category_id +'">' +v.category_name +"</option>");
              } else {
               $(".category").append('<option value="'+v.category_id+'">'+v.category_name+"</option>");
              }
            } else {
              $(".category").append('<option value="'+v.category_id+'">'+v.category_name+"</option>");
            }
          });
        }
      },
    });
  }
  unit();
  function unit(id) {
    $.ajax({
      url: base_url + "Item/unit",
      type: "post",
      data: {
        _tokken: _tokken,
      },
      success: function (result) {
        $(".unit").empty().append('<option>SELECT ONE</option>');
        var data = $.parseJSON(result);
        if (data) {
          $.each(data, function (i, v) {
            if (id) {
              if (v.unit_id==id) {
                $(".unit").append('<option selected value="' +v.unit_id +'">' +v.unit +"</option>");
              } else {
               $(".unit").append('<option value="'+v.unit_id+'">'+v.unit+"</option>");
              }
            } else {
              $(".unit").append('<option value="'+v.unit_id+'">'+v.unit+"</option>");
            }
          });
        }
      },
    });
  }
});

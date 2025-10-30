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
      url: base_url + "Category/store_listing/" + search + "/" + pagno,
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
  $(document).on("click", ".add_application", function () {
    $("#add_Store input[name='category_id']").val('');
    $("#add_Store input[name='category_name']").val('');
    $("#add_Store input[name='category_code']").val('');
  });
  $(document).on("submit", ".add_submit", function (e) {
    e.preventDefault();
    var self = $(this);
    if (add_role == 1) {
      var formdata = new FormData(this);
      $.ajax({
        async: true,
        url: base_url + "Category/add",
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
            self.trigger("reset");
            $('.add_submit input[name="category_id"]').val('');
            $("input[name='_tokken']").val(tokken);
            add_role = 1;
            $.notify(data.msg, "success");
            $('#add_Store').modal('hide');
          } else {
            add_role = 1;
            $.notify(data.msg, "error");
          }
          if (data.errors) {
            $.each(data.errors,function(i,v){
              $(".add_submit input[name='"+i+"']").after("<span class='text-danger errors'>"+v+"</span>");
              $(".add_submit select[name='"+i+"']").after("<span class='text-danger errors'>"+v+"</span>");
             
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
    
  $(document).on("click", ".edit_role", function () {
    $('body').append('<div class="pageloader"></div>');
    var id = $(this).data("id");
    $.ajax({
      async: true,
      url: base_url + "Category/Store_management_get",
      type: "post",
      data: { id: id, _tokken: _tokken },
      success: function (data) {
        var data = $.parseJSON(data);
        if (data) {
            $.each(data,function(i,v){
              $(".add_submit input[name='"+i+"']").val(v);
            });
            $('.pageloader').remove();
         
        }
      },
      error: function (e) {
        console.log(e);
      },
    });
  });
  
});

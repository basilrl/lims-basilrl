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
      url: base_url + "Webinar/listing/" + search + "/" + pagno,
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
  $(document).on("submit", ".add_submit", function (e) {
    $('body').append('<div class="pageloader"></div>');
    e.preventDefault();
    var self = $(this);
    if (add_role == 1) {
      $.ajax({
        async: true,
        url: base_url + "Webinar/add",
        type: "post",
        data: $(this).serialize(),
        success: function (data) {
          $(".pageloader").remove();
          $(".form_error").remove();
          $(".role_errors").html("");
          var data = $.parseJSON(data);
          if (data.status > 0) {
            loadPagination(0);
            add_role = 1;
             $.notify(data.msg, "success");
            $('#add_application').modal('hide');
            self.trigger("reset");
          } else {
            add_role = 1;
            $.notify(data.msg, "error");
          }
          if (data.errors) {
            $.each(data.errors,function(i,v){
              $('.add_submit input[name="'+i+'"]').after('<span class="text-danger form_error">'+v+'</span>')
              $('.add_submit textarea[name="'+i+'"]').after('<span class="text-danger form_error">'+v+'</span>')
            })
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
  
  $(document).on("click", ".edit_web", function () {
    $('body').append('<div class="pageloader"></div>');
    $(".form_error").remove();
    var id = $(this).data("id");
    $.ajax({
      async: true,
      url: base_url + "Webinar/get_edit",
      type: "post",
      data: { id: id, _tokken: _tokken },
      success: function (data) {
        $(".pageloader").remove();
        var data = $.parseJSON(data);
        if (data) {
          $.each(data,function(i,v){
            $('.edit_submit_application input[name="'+i+'"]').val(v)
            $('.edit_submit_application textarea[name="'+i+'"]').text(v)
          })
        }
      },
      error: function (e) {
        console.log(e);
      },
    });
  });
  $(document).on("submit", ".edit_submit_application", function (e) {
    $('body').append('<div class="pageloader"></div>');
    e.preventDefault();
    var self = $(this);
    $.ajax({
      async: true,
      url: base_url + "Webinar/edit",
      type: "post",
      data: $(this).serialize(),
      success: function (data) {
        $(".pageloader").remove();
        $(".form_error").remove();
        $(".role_edit_errors").html("");
        var data = $.parseJSON(data);
        if (data.status > 0) {
          loadPagination(pageno);
          $.notify(data.msg, "success");
          $('#edit_application button[data-dismiss="modal"]').click();
        } else {
         $.notify(data.msg, "error");
        }
        if (data.errors) {
          $.each(data.errors,function(i,v){
            $('.edit_submit_application input[name="'+i+'"]').after('<span class="text-danger form_error">'+v+'</span>')
            $('.edit_submit_application textarea[name="'+i+'"]').after('<span class="text-danger form_error">'+v+'</span>')
          })
        } 
      },
      error: function (e) {
        console.log(e);
      },
    });
  });
 
});

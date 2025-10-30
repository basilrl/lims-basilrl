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
      url: base_url + "Low_item_notification/store_listing/" + search + "/" + pagno,
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
 
  $(document).on("click", ".edit_role", function () {
    $("body").append('<div class="pageloader"></div>');
    var id = $(this).data("id");
    $.ajax({
      async: true,
      url: base_url + "Low_item_notification/store_userlog_dtlsview",
      type: "post",
      data: {
        id: id,
        _tokken: _tokken,
      },
      success: function (data) {
        var data = $.parseJSON(data);
        $('#table_log').html(data);
        $(".pageloader").remove();
      },
      error: function (e) {
        console.log(e);
      },
    });
  });
 
});

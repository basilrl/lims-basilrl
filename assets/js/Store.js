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
      url: base_url + "Store_management/store_listing/" + search + "/" + pagno,
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
    e.preventDefault();
    var self = $(this);
    if (add_role == 1) {
      $.ajax({
        async: true,
        url: base_url + "Store_management/add",
        type: "post",
        processData: false,
        contentType: false,
        data: new FormData(this),
        success: function (data) {
          $(".errors").remove();
          var data = $.parseJSON(data);
          if (data.status > 0) {
            loadPagination(0);
            add_role = 1;
             $.notify(data.msg, "success");
            $('#add_Store').modal('hide');
            $('.add_submit input[name="store_id"]').val('');
            self.trigger("reset");
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
  branch_Store();
  function branch_Store() {
    $.ajax({
      async:true,
      url: base_url + "Store_management/branch_Store",
      type: "post",
      data: { _tokken: _tokken },
      success: function (result) {
        var data = $.parseJSON(result);
        $("#add_Store select[name='store_branch_id']").empty().append('<option value="">SELECT BRANCH</option>');
        if (data) {
          $.each(data,function(i,v){
            $("#add_Store select[name='store_branch_id']").append('<option value="'+v.branch_id+'">'+v.branch_name+'</option>');
          });
        }      
      },
    });
  }

  $(document).on('click','.log',function(){
    $("body").append('<div class="pageloader"></div>');
      var id = $(this).data('id');
      $.ajax({
        async: true,
        url: base_url + "Store_management/store_userlog_dtlsview",
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

  


  
  $(document).on('change',"#add_Store select[name='store_branch_id']",function(){
     var id = $(this).val();
     store_keeper_store(id);
  });
  function store_keeper_store(id) {
    $.ajax({
      async:true,
      url: base_url + "Store_management/store_keeper_store",
      type: "post",
      data: { _tokken: _tokken,id:id },
      success: function (result) {
        var data = $.parseJSON(result);
        $("#add_Store select[name='store_store_keeper_id']").empty().append('<option value="">SELECT STORE KEEPER</option>');
        if (data) {
          $.each(data,function(i,v){
            $("#add_Store select[name='store_store_keeper_id']").append('<option value="'+v.store_keeper_id+'">'+v.store_keeper_name+'</option>');
          });
        }      
      },
    });
  }
  $(document).on("click", ".edit_role", function () {
    $('body').append('<div class="pageloader"></div>');
    var id = $(this).data("id");
    $.ajax({
      async: true,
      url: base_url + "Store_management/Store_management_get",
      type: "post",
      data: { id: id, _tokken: _tokken },
      success: function (data) {
        var data = $.parseJSON(data);
        if (data) {
          store_keeper_store(data.store_branch_id);
          setTimeout(() => {
            $.each(data,function(i,v){
              
              $(".add_submit select[name='"+i+"']").val(v);
              if (i=='low_stock_notif_req'|| i == 'main_store') {
                if (v == 1) {
                  $(".add_submit input[name='"+i+"']").prop('checked', true);;
                }else{
                  $(".add_submit input[name='"+i+"']").prop('checked', false);;
                }
              }else{
                $(".add_submit input[name='"+i+"']").val(v);
              }
            });
            $('.pageloader').remove();
          }, 1000);
        }
      },
      error: function (e) {
        console.log(e);
      },
    });
  });
  
});

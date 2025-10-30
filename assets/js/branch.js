$(document).ready(function() {
    const url = $('body').data('url');
    const _tokken = $('meta[name="_tokken"]').attr('value');
    // Ajax call to get state/provice
    $(document).on('change', '#country', function() {
        $('#state').empty();
        var country = $('#country').val();
        $.ajax({
            type: 'post',
            url: url + 'Branch/get_province',
            data: { _tokken: _tokken, country: country },
            success: function(data) {
                var state = $.parseJSON(data);

                $('#state').append($('<option></option>').attr('disables', 'selected').text('Select State'));
                if (state != '') {
                    $.each(state, function(key, value) {
                        $('#state').append($('<option></option>').attr('value', value.province_id).text(value.province_name));
                    });
                } else {
                    $('#state').append('<option value="" >NO RECORD FOUND</option>');
                }
            }
        });
    });
    // Ajax call ends here

    // Ajax call to get City
    $(document).on('change', '#state', function() {
        $('#location').empty();
        var state = $('#state').val();
        $.ajax({
            type: 'post',
            url: url + 'Branch/get_location',
            data: { _tokken: _tokken, state: state },
            success: function(data) {
                var location = JSON.parse(data);
                $('#location').append($('<option></option>').attr('disables', 'selected').text('Select Location'));
                $.each(location, function(key, value) {
                    $('#location').append($('<option></option>').attr('value', value.location_id).text(value.location_name));
                });
            }
        });
    });
    // Ajax call ends here


});
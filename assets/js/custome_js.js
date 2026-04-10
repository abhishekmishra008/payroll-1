$(document).ready(function () {

    // Only letter
    $.validator.addMethod("only_letter", function (value, element) {
        var alph = new RegExp(/^[a-zA-Z]*$/);
        return alph.test(value);
    });

    // Only number
    $.validator.addMethod("only_number", function (value, element) {
        var alph = new RegExp(/\d/);
        return alph.test(value);
    });
    // Mobile number
    $.validator.addMethod("mobile_number", function (value, element) {
        var alph = new RegExp(/^[0-9]{10}$/);
        return alph.test(value);
    });

    // Pincode
    $.validator.addMethod("pincode", function (value, element) {
        var alph = new RegExp(/^[0-9]{4}$/);
        return alph.test(value);
    });

    // letter with whitespace
//    $.validator.addMethod("letter_with_whitespace", function (value, element) {
//        var alph = new RegExp(/^[a-zA-Z ]*$/);
//        alert(alph.test(value));
//    });

    // letter with underscrol
    $.validator.addMethod("letter_with_underscore", function (value, element) {
        var alph = new RegExp(/^[a-zA-Z_]*$/);
        return alph.test(value);
    });

    $.validator.addMethod("mpin_password", function (value, element) {
        var alph = new RegExp(/^[0-9]{4}$/);
        return alph.test(value);
    });



});

function showNotification(from, align, icon, message, type) {

    $.notify({
        icon: icon,
        message: message

    }, {
        type: type,
        timer: 2000,
        placement: {
            from: from,
            align: align
        }
    });
}

function getFormData(form) {
   
    var json = '{}';
    var object = JSON.parse(json);
    
    for (var i = 0; i < form.length - 1; i++) {
        if (form[i].value !== "") {
            object[form[i].name] = encypt(form[i].value);
        }
    }
    return object;
}

function encypt(value) {
    return window.btoa(value);
}

function dencypt(value) {
    return window.atob(value);
}

function getAjax(u_url, u_data) {
    var request = $.ajax({
        url: u_url,
        data: u_data,
        method: "post"
    });

    return request;
}

function load_table(url,data,tbody_id,table_id) {
    var request = getAjax(url, data);
    request.done(function (success) {
        $('#'+tbody_id).empty();
        $('#'+tbody_id).append(success['data_table']);
        $('#'+table_id).DataTable();
    });

    request.fail(function (error) {
        console.log(error);
        showNotification('top', 'right', 'add_alert', 'Error While loading..', 'danger');
    });

}

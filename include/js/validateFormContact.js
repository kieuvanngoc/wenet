var fullname = $('input[name=fullname]');
var email = $('input[name=email]');
var mobile = $('input[name=mobile]');
var title = $('input[name=title]');
var message = $('textarea[name=message]');

// On blur
fullname.blur(validateFullname);
email.blur(validateEmail);
mobile.blur(validateMobile);
title.blur(validateTitle);
message.blur(validateMessage);

//On key press
fullname.keyup(validateFullname);
email.keyup(validateEmail);
mobile.keyup(validateMobile);
title.keyup(validateTitle);
message.keyup(validateMessage);

function validateFullname(){
    var parent = $('input[name=fullname]').parent();
    var fullname_val = $('input[name=fullname]').val();

    if (fullname_val.length < 6) {
        fullname.focus();
        parent.find('.help').text("Họ và tên ít nhất phải 6 ký tự.");
        parent.find('.help').css('color', 'red');
        return false;
    }else {
        parent.find('.help').text("Họ và tên hợp lệ.");
        parent.find('.help').css('color', '#44AA2B');
        return true;
    }
}

function validateEmail() {
    var parent = $('input[name=email]').parent();
    var val_email = $('input[name=email]').val();
    var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;

    // Kiểm tra hợp lệ email
    if (val_email.length == 0) {
        // Kiểm tra email
        email.focus();
        parent.find('.help').text("Địa chỉ Email này không hợp lệ.");
        parent.find('.help').css('color', 'red');
        return false;
    } else if (filter.test(val_email)) {
        // Kiểm tra email
        parent.find('.help').text("Địa chỉ Email này sẽ nhận phản hồi của chúng tôi.");
        parent.find('.help').css('color', '#44AA2B');
        return true;
    } else {
        email.focus();
        parent.find('.help').text("Địa chỉ Email này không hợp lệ.");
        parent.find('.help').css('color', 'red');
        return false;
    }
}

function validateTitle(){
    var parent = $('input[name=title]').parent();
    var title_val = $('input[name=title]').val();

    if (title_val.length < 10) {
        title.focus();
        parent.find('.help').text("Tiêu đề thư ít nhất phải 10 ký tự.");
        parent.find('.help').css('color', 'red');
        return false;
    }else {
        parent.find('.help').text("Tiêu đề thư hợp lệ.");
        parent.find('.help').css('color', '#44AA2B');
        return true;
    }
}

function validateMobile() {
    var parent = $('input[name=mobile]').parent();
    var mobile_val = $('input[name=mobile]').val();

    if (mobile_val.length < 10 || mobile_val.length > 11) {
        mobile.focus();
        parent.find('.help').text("Số điện thoại di động là 10 hoặc 11 số.");
        parent.find('.help').css('color', 'red');
        return false;
    } else if(!(/^(0120|0121|0122|0123|0124|0125|0126|0127|0128|0129|0163|0164|0165|0166|0167|0168|0169|0188|0199|090|091|092|093|094|095|096|097|098|099)(\d{7})$/i).test(mobile_val)) {
        parent.find('.help').text("Số điện thoại không hợp lệ.");
        parent.find('.help').css('color', 'red');
        return false;
    } else {
        parent.find('.help').text("Chúng tôi sẽ gọi lại số điện thoại này.");
        parent.find('.help').css('color', '#44AA2B');
        return true;
    }
}

function validateMessage(){
    var parent = $('textarea[name=message]').parent();
    var message_val = $('textarea[name=message]').val();

    if (message_val.length < 20) {
        message.focus();
        parent.find('.help').text("Nội dung thư liên hệ ít nhất phải 20 ký tự.");
        parent.find('.help').css('color', 'red');
        return false;
    }else {
        parent.find('.help').text("Nội dung thư liên hệ đã hợp lệ.");
        parent.find('.help').css('color', '#44AA2B');
        return true;
    }
}

//On Submitting
function submitFormContact(){
    if(validateFullname() && validateEmail() && validateMobile() && validateTitle() && validateMessage())
        $('#frmContact').submit();
    else
        return false;
}
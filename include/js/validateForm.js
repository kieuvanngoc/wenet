$(document).ready(function(){
	var task = $('#frmSignup').hasClass('class_merchant');
	
	var user_email = $('#user_email');
	var user_password = $('#user_password');
	var repassword = $('#repassword');
	var user_fullname = $('#user_fullname');
	var user_mobile = $('#user_mobile');
	var user_address = $('#user_address');
	var user_city = $('#user_city');
	var user_district = $('#user_district');

	// On blur
    user_email.blur(validateEmail);
	user_password.blur(validatePassword);
	repassword.blur(validateRepassword);
	user_fullname.blur(validateUserName);
	user_mobile.blur(validateMobile);
	user_address.blur(validateAddress);

	//On key press
	user_email.keyup(validateEmail);
	user_password.keyup(validatePassword);
	repassword.keyup(validateRepassword);
	user_fullname.keyup(validateUserName);
	user_mobile.keyup(validateMobile);
	user_address.keyup(validateAddress);
	
	user_district.change(function() {
		var district = $(this).val();
		var parent = $(this).parent();
		if(district.length == 0) {
			parent.find('.help').text("Hãy chọn Quận huyện.");
			parent.find('.help').css('color', 'red');
			return false;
		} else{
			parent.find('.help').text("Chọn chính xác quận/huyện/thị xã hiện tại của bạn.");
			parent.find('.help').css('color', 'black');
		}
	});

	//On Submitting
	$("#frmSignup").submit(function(){
		// Nếu là user đăng ký
		if (!task) { 
			if(validateEmailPost() && validatePassword() && validateRepassword() && validateUserName() && validateMobile() && validateAddress() && validateCity() && validateDistrict())
				return true;
			else
				return false;
		} else { // Nếu là merchant đăng ký
			if(validateEmailPost() && validatePassword() && validateRepassword() && validateSiteName() && validateSiteDomain() && validateAddress() && validateCity() && validateDistrict() && validateUserName() && validateMobile())
				return true;
			else
				return false;
		}
	});
	
	//Kiểm tra email
	function validateEmail() {
		var parent = user_email.parent();
		var val_email = $('#user_email').val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
	
		// Kiểm tra hợp lệ email
		if (filter.test(val_email)) {
			// Kiểm tra email
			$('.img_loading').show();
			var data = 'user_email=' + val_email;
			$.ajax({
				type: "POST",
				url: "signup.php?sub=validate",
				data: data,
				dataType: "json",
				success: function(xmlhttp) {
					var objData = xmlhttp;
					$('.img_loading').hide();
					if (parseInt(objData.check) == 0) {
						user_email.focus();
						parent.find('.help').css('color', 'red');
						parent.find('.help').text(objData.msg);
	
						check_post_email = true;
						return false;
					} 
				}
			});
			
			parent.find('.help').text("Địa chỉ Email này sẽ dùng để đăng nhập và nhận các thông tin tài khoản.");
			parent.find('.help').css('color', 'black');
			return true;
		} else {
			user_email.focus();
			parent.find('.help').text("Địa chỉ Email này không hợp lệ.");
			parent.find('.help').css('color', 'red');
			return false;
		}
	}
	
	function validateEmailPost() {
		var parent = $('#user_email').parent();
		var val_email = $('#user_email').val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
		
		// Kiểm tra hợp lệ email
		console.log(val_email.length);
		if (val_email.length == 0) {
			// Kiểm tra email
			user_email.focus();
			parent.find('.help').text("Địa chỉ Email này sẽ dùng để đăng nhập và nhận các thông tin tài khoản.");
			parent.find('.help').css('color', 'red');
			return false;
		} else if (filter.test(val_email)) {
			// Kiểm tra email
			parent.find('.help').text("Địa chỉ Email này sẽ dùng để đăng nhập và nhận các thông tin tài khoản.");
			parent.find('.help').css('color', 'black');
			return true;
		} else {
			user_email.focus();
			parent.find('.help').text("Địa chỉ Email này không hợp lệ.");
			parent.find('.help').css('color', 'red');
			return false;
		}
	}
	
	// Kiểm tra pasword
	function validatePassword(){
		var user_password = $("#user_password");
		var repassword = $("#repassword");
		var parent = user_password.parent();
	
		if (user_password.val().length ==0 || user_password.val().length < 6) {
			user_password.focus();
			parent.find('.help').css('color', 'red');
			return false;
		} else {			
			parent.find('.help').css('color', 'black');
			return true;
		}
	}
	
	function validateRepassword(){
		var user_password = $("#user_password");
		var repassword = $("#repassword");
		var parent = user_password.parent();
	
		if (repassword.val().length < 6) {
			repassword.focus();
			parent.find('.help').text("ít nhất 6 ký tự.");
			parent.find('.help').css('color', 'red');
			return false;
			
		} 
		
		if (repassword.val().length == 0) {
			repassword.focus();
			parent.find('.help').css('color', 'red');
			return false;
			
		} else if (user_password.val() != repassword.val()){
			repassword.focus();
			parent.find('.help').css('color', 'red');
			parent.find('.help').text("Mật khẩu không khớp.");
			return false;
		} else{
			parent.find('.help').text("ít nhất 6 ký tự.");
			parent.find('.help').css('color', 'black');
			return true;
		}
	}
	
	function validateUserName() {
		var user_fullname = $('#user_fullname');
		var parent = user_fullname.parent();
		
		if (user_fullname.val().length < 6) {
			user_fullname.focus();
			parent.find('.help').text("Họ tên phải ít nhất 6 ký tự.");
			parent.find('.help').css('color', 'red');
			return false;
		} else{
			parent.find('.help').text("Họ tên đầy đủ giống như trên CMT hoặc Hộ chiếu.");
			parent.find('.help').css('color', 'black');
			return true;
		}
	}
	
	function validateSiteName() {
		var site_name = $('#site_name');
		var parent = site_name.parent();
		
		if (site_name.val().length == 0) {
			site_name.focus();
			parent.find('.help').text(t("Tên tổ chức, doanh nghiệp không được để trống"));
			parent.find('.help').css('color', 'red');
			return false;
		} else {
			parent.find('.help').text(t("Tên đầy đủ của doanh nghiệp."));
			parent.find('.help').css('color', 'black');
			return true;
		}
	}
	
	function validateSiteDomain() {
		var site_domain = $('#site_domain');
		var parent = site_domain.parent();
		
		if (site_domain.val().length == 0) {
			site_domain.focus();
			parent.find('.help').text(t("Website của doanh nghiệp bạn đăng ký không được để trống"));
			parent.find('.help').css('color', 'red');
			return false;
		} else {
			parent.find('.help').text(t("Ghi rõ địa chỉ website của doanh nghiệp bạn đăng ký."));
			parent.find('.help').css('color', 'black');
			return true;
		}
	}
	
	function validateMobile() {
		var user_mobile = $('#user_mobile');
		var parent = user_mobile.parent();
		
		if (user_mobile.val().length < 10 || user_mobile.val().length > 11) {
			user_mobile.focus();
			parent.find('.help').text("Độ dài của trường số điện thoại di động là 10 hoặc 11 số.");
			parent.find('.help').css('color', 'red');
			return false;
		} else if(!(/^(0120|0121|0122|0123|0124|0125|0126|0127|0128|0129|0163|0164|0165|0166|0167|0168|0169|0188|0199|090|091|092|093|094|095|096|097|098|099)(\d{7})$/i).test(user_mobile.val())) {
			parent.find('.help').text("Số điện thoại không hợp lệ.");
			parent.find('.help').css('color', 'red');
			return false;
		} else {
			parent.find('.help').text("Khi thực hiện giao dịch, hệ thống sẽ gửi mã xác thực vào số điện thoại này.");
			parent.find('.help').css('color', 'black');
			return true;
		}
	}
	
	function validateAddress() {
		var user_address = $('#user_address');
		var parent = user_address.parent();
		
		if (user_address.val().length == 0) {
			user_address.focus();
			parent.find('.help').text("Hãy nhập địa chỉ hiện tại của bạn.");
			parent.find('.help').css('color', 'red');
			return false;
		} else{
			parent.find('.help').text("Nhập chính xác địa chỉ hiện tại của bạn.");
			parent.find('.help').css('color', 'black');
			return true;
		}
	}
	
	function validateCity() {
		var user_city = $('#user_city');
		var parent = user_city.parent().parent();
		
		if (user_city.val().length == 0) {
			parent.find('.help').text("Hãy chọn Tỉnh/Thành phố.");
			parent.find('.help').css('color', 'red');
			return false;
		} else {
			parent.find('.help').text("Chọn chính xác tỉnh/thành phố hiện tại của bạn");
			parent.find('.help').css('color', 'black');
			return true;
		}
	}
	
	function validateDistrict() {
		var user_district = $('#user_district');
		var parent = user_district.parent();
		
		if (user_district.val().length == 0) {
			parent.find('.help').text("Hãy chọn Quận huyện.");
			parent.find('.help').css('color', 'red');
			return false;
		} else{
			parent.find('.help').text("Chọn chính xác quận/huyện/thị xã hiện tại của bạn.");
			parent.find('.help').css('color', 'black');
			return true;
		}
	}
});
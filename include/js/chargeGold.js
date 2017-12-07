core.chargeGold = {
    'current_step'  : 1,
    'user_info'     : {},
    'gold_number'   : 0,
    'charge_type'   : 1,
    'card_code'     : '',
    'card_type'     : 1
};

core.chargeGold.mixMoney = function(myfield){
	var thousands_sep = ',';
	myfield.value = core.numberFormat(parseInt(myfield.value.replace(/,/gi, '')),0,'',thousands_sep);
}

core.chargeGold.checkRadio = function(value){
	var str="";
	var str2="";
	if (value==1){
        $('#inputEmail').css('border','1px solid #CCCCCC');
		$('#inputEmail').css('color','#8A8A8A');
		//str += '<div><a class="orangeButton" href="javascript:void(0);" onclick="core.chargeGold.step_2();"><span><span>'+ t('Tiếp tục') +' »</span></span></a></div>';
		str2 += '<a class="orangeButton" href="javascript:void(0);" onclick="core.chargeGold.step_21();"><span><span>'+ t('Tiếp tục') +'</span></span></a>';
	}else{
		$('#inputEmail').css('border','2px solid #8A2825');
		$('#inputEmail').css('color','#8A2825');
		//str += '<div><a class="orangeButton" href="javascript:void(0);" onclick="core.chargeGold.step_3();"><span><span>'+ t('Tiếp tục') +' »</span></span></a></div>';
		str2 += '<a class="orangeButton" href="javascript:void(0);" onclick="core.chargeGold.step_4();"><span><span>'+ t('Tiếp tục') +'</span></span></a>';
	}
    core.chargeGold.charge_type = (core.chargeGold.current_step==1)?value:core.chargeGold.charge_type;
    
	//$("#selectButton").html(str);
	$("#selectLoad").html(str2);
}

core.chargeGold.focusInputTextbox = function(){
	$('#inputEmail').each(function() {
	    var default_value = this.value;
	    $(this).focus(function() {
            $('#chargeOther').attr("checked", "checked"); 
            core.chargeGold.checkRadio(2);
	        if(this.value == default_value) {
	            this.value = '';
	        }
	    });
	    $(this).blur(function() {
	        if(this.value == '') {
	            this.value = default_value;
	        }
	    });
	});
}

//10.Begin nap gold vao tai khoan (1)

core.chargeGold.step_1 = function (id, isPopup){

	if(typeof(isPopup) == 'undefined') {
		if(typeof(core.chargeGold.isPopup) == 'undefined') {
			core.chargeGold.isPopup = true;
		}
	} else {
		core.chargeGold.isPopup = isPopup;
	}

	if(typeof(id) == 'undefined') {
		if(typeof(core.chargeGold.wrapId) == 'undefined') {
			core.chargeGold.wrapId = '';
		}
	} else {
		core.chargeGold.wrapId = id;
	}

    core.chargeGold.current_step = 1;
    core.chargeGold.charge_type = 1;
    core.chargeGold.user_info.user_fullname = core.user.user_info['user_fullname'];
    core.chargeGold.user_info.user_email = core.user.user_info['user_email'];
    core.chargeGold.user_info.user_mobile = core.user.user_info['user_mobile'];
    core.chargeGold.user_info.user_id = core.user.user_info['user_id'];
    
    txtTitle = core.join ('<div class="title_popup">')
    	('<div class="text">'+ t('Nạp tiền vào tài khoản') +'</div>')
    ('</div>')();

    txtContent = core.join ('<div class="content_popup clearfix">')
    	('<div class="content_gold clearfix">')
            ('<div id="cError"></div>')
    		('<div class="radioBox"><input type="radio" id="chargeMe" name="gold" onchange="core.chargeGold.checkRadio(this.value);" value="1" checked="checked" /></div>')
    		('<div class="content_gold_right">')
    			('<div class="gold_title"><label for="chargeMe">'+ t('Nạp tiền vào tài khoản cá nhân') +' (<a href="javascript:void(0);">'+core.user.user_info['user_email']+'</a>)</label></div>')
    			('<ul>')
    				('<li>- '+ t('Bạn đang có') +': <b class="FF5A00">'+core.numberFormat(core.user.user_info['user_gold'])+' đ</b></li>')
    				('<li>- '+ t('Nạp thêm tiền để có thể mua hàng dễ dàng chỉ trong vài nhấp chuột') +'</li>')
    			('</ul>')
    		('</div>')	
    	('</div>')
    	
    	('<div class="content_gold clearfix">')
    		('<div class="radioBox"><input type="radio" id="chargeOther" name="gold" onchange="core.chargeGold.checkRadio(this.value)" value="2" /></div>')
    		('<div class="content_gold_right">')
    				('<div class="gold_title">')
    					('<div><label for="chargeOther">'+ t('Nạp hộ tiền cho tài khoản khác') +'</label></div>')
    					('<div><input type="textbox" id="inputEmail" class="inputtext" value="Nhập email cần nạp..." /></div>')
    				('</div>')
    			('<ul style="display: none;">')
    				('<li>- '+ t('Để nạp <b class="red">100.000 đ</b> bạn cần thanh toán <b class="red">102.000 đ</b>, trong đó <b class="red">100.000 Gold</b> sẽ vào tài khoản của người được nạp hộ, <b class="red">2.000 đ</b> vào tài khoản của người nạp hộ') +'</li>')
    			('</ul>')
    			('</div>')	
    		('</div>')
    	
    	('<div class="buttonDIV clearfix" id="selectButton">')
    			('<div>')
    				('<a class="orangeButton" href="javascript:void(0);" onclick="core.chargeGold.step_4();"><span><span>'+ t('Tiếp tục') +'</span></span></a>')
    			('</div>')
    	('</div>')();

	//Tạm thời bỏ chức năng nạp tiền cho tài khoản khác nên chuyển luôn sang bước 4
	core.chargeGold.step_4();
	/*
	 if(core.chargeGold.isPopup) {
		core.show_overlay_popup(10, txtTitle, txtContent);
	 } else {
		 $('#'+core.chargeGold.wrapId).html(txtContent);
	 }
     core.chargeGold.focusInputTextbox();
	 */
}

core.mobileValidation = function(isPopup, mobileNumber, step) {
	if(typeof(step) == "undefined") {
		step = 1;
	}

	switch (step) {
		case 1:
			txtTitle = core.join ('<div class="title_popup">')
				('<div class="text">Kích hoạt số điện thoại</div>')
			('</div>')();

			txtContent = core.join ('<div class="content_popup clearfix mobile-validation-step1">')
				('<div class="content_gold clearfix">')
					('<div id="cError"></div>')
					('<div class="content_gold_right">')
						('<div class="gold_title"><label for="chargeMe">'+ t('Quý khách đã đăng ký số điện thoại') +':<i style="color:red"> '+ mobileNumber +'</i></label></div>')
						('<div class="clearfix"/><div class="description">'+ t('Số điện thoại này sẽ được dùng để Sohapay gửi mật khẩu thanh toán(OTP) và nhận các thông báo từ Sohapay') +'</div>')
					('</div>')	
				('</div>')

				('<div class="buttonDIV clearfix" id="selectButton">')
						('<div class="bottom-bar">')
							('<div class="right" id="continue"><a class="orangeButton" href="javascript:void(0);" onclick="core.mobileValidation(true,\''+ mobileNumber +'\',2);"><span><span>'+ t('Tiếp tục') +'</span></span></a></div>')
						('</div>')
				('</div>')();
			 if(isPopup) {
				core.show_overlay_popup(10, txtTitle, txtContent);
			 }
		break;

		case 2:
			var con = $("#continue");
			con.html('<img src="'+ core.baseURI +'images/icons/loading2.gif" />');
			//Ajax request
			$.post("user_info.php?task=getOtp", {mobileNumber: mobileNumber, prefix: 'updateMobile'}, function(data){
				var data = jQuery.parseJSON(data);
				if(data.success == false) {
					alert(data.warning);
					core.redirect(core.baseURI+'user_info.php');
				} else {
					txtTitle = core.join ('<div class="title_popup">')
						('<div class="text">'+ t('Kích hoạt số điện thoại - Nhập mã OTP') +'</div>')
					('</div>')();

					txtContent = core.join ('<div class="content_popup clearfix mobile-validation-step1">')
						('<div class="content_gold clearfix">')
							('<div id="cError"></div>')
							('<div class="content_gold_right">')
								('<div class="gold_title"><label for="chargeMe">'+ t('Quý khách đã đăng ký số điện thoại') +':<i style="color:red"> '+ mobileNumber +'</i></label></div>')
								('<div class="clearfix"/><div class="description">Nếu quý khách chưa nhận được mã OTP thì hãy click <a href="javascript:void(0);" onclick="core.mobileValidation(true,\''+ mobileNumber +'\',2);">vào đây</a> để nhận lại(Lưu ý: Quý khách được nhận lại tối đa là 3 lần cho mỗi phiên làm việc)</div>')

								('<div><label for="chargeOther"><strong>'+ t('Nhập Mã OTP') +'</strong>	</label></div>')
								('<div><input type="textbox" id="otp_code" class="input-text wid1" value="" /></div>')

							('</div>')
						('</div>')

						('<div class="buttonDIV clearfix" id="selectButton">')
								('<div class="bottom-bar">')
									('<div class="right" id="continue"><a class="orangeButton" href="javascript:void(0);" onclick="var otp_code = document.getElementById(\'otp_code\').value;core.mobileValidation(true,otp_code,3);"><span><span>'+ t('Tiếp tục') +'</span></span></a></div>')
								('</div>')
						('</div>')();

					 if(isPopup) {
						core.show_overlay_popup(10, txtTitle, txtContent);
					 }
				}
			});
		break;

		case 3:
  			$.post("user_info.php?task=confirmOtp", {otp: mobileNumber}, function(data){
				var data = jQuery.parseJSON(data);
				if(data.success == false) {
					alert(data.warning);
					core.redirect(core.baseURI+'user_info.php');
				} else {
					alert(t("Số điện thoại đã được xác thực thành công"));
					core.redirect(core.baseURI+'user_info.php');
				}
			});
			break;
	}
}

core.updateMobile = function(isPopup, mobileNumber, step) {
	if(typeof(step) == "undefined") {
		step = 1;
	}

	switch (step) {
		case 1:
			//Ajax request
			$.post("user_info.php?task=getOtp", {mobileNumber: mobileNumber, prefix: 'updateMobile'}, function(data){
				var data = jQuery.parseJSON(data);
				if(data.success == false) {
					alert(data.warning);
					core.redirect(core.baseURI+'user_info.php');
				} else {
					txtTitle = core.join ('<div class="title_popup">')
						('<div class="text">'+ t('Thay đổi số điện thoại - Nhập mã OTP') +'</div>')
					('</div>')();

					txtContent = core.join ('<div class="content_popup clearfix mobile-validation-step1">')
						('<div class="content_gold clearfix">')
							('<div id="cError"></div>')
							('<div class="content_gold_right">')
								('<div class="gold_title"><label for="chargeMe">Quý khách hiện đang dùng số điện thoại:<i style="color:red"> '+ mobileNumber +'</i></label></div>')
								('<div class="clearfix"/><div class="description">Hệ thống vừa gửi cho bạn một mã OTP tới số điện thoại <strong>'+ mobileNumber +'</strong> bạn hãy dùng nó để xác nhận bạn muốn thay đổi số điện thoại, nếu bạn không nhận được thì hãy click <a href="javascript:void(0);" onclick="core.updateMobile(true,\''+ mobileNumber +'\',1);">vào đây</a> để nhận lại.</div>')
								('<div class="clearfix"/><div class="note">'+ t('Lưu ý: Quý khách được nhận lại tối đa là 3 lần cho mỗi phiên làm việc') +'.</div>')
								('<div><label for="chargeOther"><strong>'+ t('Nhập Mã OTP mà quý khách nhận được vào đây') +':</strong>	</label></div>')
								('<div><input type="textbox" id="otp_code" class="input-text wid1" value="" /></div>')

							('</div>')
						('</div>')

						('<div class="buttonDIV clearfix" id="selectButton">')
								('<div class="bottom-bar">')
									('<div class="right"><a class="orangeButton" href="javascript:void(0);" onclick="var otp_code = document.getElementById(\'otp_code\').value;core.updateMobile(true,otp_code,2);"><span><span>'+ t('Tiếp tục') +'</span></span></a></div>')
								('</div>')
						('</div>')();
					 if(isPopup) {
						core.show_overlay_popup(10, txtTitle, txtContent);
					 }
				}
			});
		break;

		case 2:
			var con = $("#continue");
			con.html('<img src="'+ core.baseURI +'images/icons/loading2.gif" />');

  			$.post("user_info.php?task=confirmOtp", {otp: mobileNumber}, function(data){
				var data = jQuery.parseJSON(data);
				if(data.success == false) {
					alert(data.warning);
				} else {
					txtTitle = core.join ('<div class="title_popup">')
						('<div class="text">'+ t('Kích hoạt số điện thoại - Nhập số điện thoại mới') +'</div>')
					('</div>')();

					txtContent = core.join ('<div class="content_popup clearfix mobile-validation-step1">')
						('<div class="content_gold clearfix">')
							('<div id="cError"></div>')
							('<div class="content_gold_right">')
								('<div><label for="chargeOther"><strong>'+ t('Nhập số điện thoại mới') +':</strong></label></div>')
								('<div><input type="textbox" id="new-mobile-num" class="input-text wid1" value="" /></div>')
							('</div>')
						('</div>')

						('<div class="buttonDIV clearfix" id="selectButton">')
								('<div class="bottom-bar">')
									('<div class="right"><a class="orangeButton" href="javascript:void(0);" onclick="var newMobileNum = document.getElementById(\'new-mobile-num\').value;core.updateMobile(true,newMobileNum,3);"><span><span>'+ t('Tiếp tục') +'</span></span></a></div>')
								('</div>')
						('</div>')();

					 if(isPopup) {
						core.show_overlay_popup(10, txtTitle, txtContent);
					 }
				}
				//core.redirect(core.baseURI+'user_info.php');
			});
		break;

		case 3:
  			$.post("user_info.php?task=updateMobile", {mobileNumber: mobileNumber}, function(data){
				var data = jQuery.parseJSON(data);
				alert(data.warning);
				core.redirect(core.baseURI+'user_info.php');

			});
			break;
	}
}

// Check thông tin user chọn ở STEP 1
core.chargeGold.do_step_1 = function(){
    // Check STEP 1 Value
    if (core.chargeGold.charge_type==2){

        email = $('.content_gold #inputEmail').val();
		if(email == ''){
			core.error.set('#inputEmail', t('Chưa nhập Email cần nạp tiền'), 500, '.content_gold'); return;
		}else if(!core.is_email(email)){
			core.error.set('#inputEmail', t('Địa chỉ Email không hợp lệ'), 500, '.content_gold'); return;
		}else if(email == core.user.user_info['user_email']){
			core.error.set('#inputEmail', t('Vui lòng tích vào lựa chọn ở trên nếu Quý khách tự nạp cho mình'), 500, '.content_gold'); return;
		}
        core.chargeGold.user_info.user_email = email;
        
        // Lấy thông tin user từ email
        core.ajax_popup('task=getGoldInfoByEmail',"POST",{user_email: core.chargeGold.user_info.user_email},
    	function(j){
    		if(j.err == 0)	{
                core.chargeGold.user_info = j.user_info;
    			core.chargeGold.step_3();
    		}else{
    			core.error.set('inputEmail', j.msg, 340, '.content_gold');
    		}
    	},
    	{
    		loading:function(){
    			core.show_loading(t('Đang tải thông tin khách hàng'));
    		}
    	});
    }else{
        core.chargeGold.step_2();   
    }
}

//11.Begin chon hinh thuc nap (2)
core.chargeGold.step_2 = function(userinfo){
    
    core.chargeGold.current_step = 2;
    
    if (userinfo==null) userinfo="";
    else userinfo = core.join('<div class="content_gold clearfix">')
        	('<div class="emailNH">')
        		('<p><b>'+ t('Họ tên') +' :</b> '+core.chargeGold.user_info.user_fullname+'</p>')
        		('<p><b>'+ t('Điện thoại') +' :</b> '+core.chargeGold.user_info.user_mobile+'</p>')
        		('<p><b>Email :</b> '+core.chargeGold.user_info.user_email+'</p>')
        	('</div>')
        ('</div>')();
    
   var hdTitle = core.join('<div class="title_popup">')
        ('<div class="text">'+ t('Chọn hình thức nạp') +'</div>')
        ('</div>')();
        
    var hdContent = core.join('<div class="content_popup clearfix">')
        (userinfo)
        ('<div class="content_gold clearfix">')
        ('<div class="radioBox"><input type="radio" id="input-mobicharge" name="hd" onchange="core.chargeGold.checkRadio(this.value);" value="1" /></div>')
        ('<div class="content_gold_right">')
        ('<div class="gold_title"><label for="input-mobicharge">' + t('Nạp từ thẻ cào điện thoại') + '</label></div>')
        ('<div class="small">'+ t('Hỗ trợ thẻ của mạng Vinaphone, Mobifone, Viettel và VCoin. Áp dụng cho mọi loại mệnh giá thẻ') +'.</div>')
        ('<div class="small"><a href="javascript:void(0);"><span id="mobiphone"></span></a><a href="javascript:void(0);"><span id="vinaphone"></span></a><a href="javascript:void(0);"><span id="viettel"></span></a><a href="javascript:void(0);"><span id="vcoin"></span></a></div>')
        ('</div>')	
        ('</div>')
        
        ('<div class="content_gold clearfix">')
        ('<div class="radioBox"><input type="radio" name="hd" id="input-creditcharge" onchange="core.chargeGold.checkRadio(this.value);" value="2" checked="checked" /></div>')
        ('<div class="content_gold_right">')
        ('<div class="gold_title"><label for="input-creditcharge">'+ t('Nạp từ thẻ Visa, Master Card, thẻ ATM, có Internet Banking') +'</label></div>')
        //('<div class="small">Số tiền nạp được chuyển tương ứng thành Gold theo tỉ lệ <b class="red">1 Gold ~ 1 VNĐ</b></div>')
        ('<div class="small"><a href="javascript:void(0);"><span id="visa"></span></a><a href="javascript:void(0);"><span id="master"></span></a>')
        ('<a href="javascript:void(0);" class="f1"><span id="vcb"></span></a>')
        ('<a href="javascript:void(0);" class="f1"><span id="donga"></span></a>')
        ('<a href="javascript:void(0);" class="f1"><span id="techcombank"></span></a>')
        ('<a href="javascript:void(0);" class="f1"><span id="vietinbank"></span></a>')
        ('</div>')
        ('<div class="small"><a href="javascript:void(0);"><span id="hdbank"></span></a></a>')
        ('<a href="javascript:void(0);" class="f1"><span id="vib"></span></a>')
        ('<a href="javascript:void(0);" class="f1"><span id="tienphongbank"></span></a>')
        ('</div>')
        ('</div>')	
        ('</div>')
        
        ('<div class="buttonDIV clearfix">')
        ('<div id="selectLoad">')
        ('<a class="orangeButton" href="javascript:void(0);" onclick="core.chargeGold.step_21();"><span><span>'+ t('Tiếp tục') +'</span></span></a>')
        ('</div>')
        ('<div>')
        ('<a class="orangeButton btnLeft" href="javascript:void(0);" onclick="core.chargeGold.step_1();"><span><span>'+ t('Quay lại') +'</span></span></a>')
        ('</div>')
        ('</div>')
        ('</div>')();
    
    core.show_overlay_popup(11, hdTitle, hdContent);
}

//12.BEGIN NAP GOLD TU THE CAO DIEN THOAI(2.1)
core.chargeGold.step_21 = function (){
    core.chargeGold.current_step = 21;
    
    var cardphone_Title = core.join ('<div class="title_popup">')
        ('<div class="text">'+ t('Nạp tiền từ thẻ cào điện thoại') +'</div>')
        ('</div>')();
    
    var cardphone_Content = core.join ('<div class="content_popup clearfix">')
        ('<div class="content_gold clearfix">')
            ('<div id="cError"></div>')
        	/*('<p>(<b>Ví dụ:</b> Thẻ mệnh giá 100.000đ ~ 95.000 đ)</p>')*/
        	('<div class="content_gold_right">')
        		('<div class="gold_title"><div><b>'+ t('Chọn thẻ cào của mạng') +' :</b></div>')
        			('<div class="f1"><label for="card_gold_type_vinaphone"><img src="'+core.baseURI+'templates/css/images/chargeGold/vinaphone.png" height="19" border="0" /></label><br /><input type="radio" checked="" name="card_gold_type" id="card_gold_type_vinaphone" class="radio_info" value="vinaphone" checked="checked" /></div>')
        			('<div class="f1" style="margin-top:-4px;"><label for="card_gold_type_mobifone"><img src="'+core.baseURI+'templates/css/images/chargeGold/mobiphone.png" height="12" border="0" /></label><br /><input type="radio" id="card_gold_type_mobifone" name="card_gold_type" class="radio_info" value="mobifone" /></div>')
                    ('<div class="f1" style="margin-top:-4px;"><label for="card_gold_type_viettel"><img src="'+core.baseURI+'templates/css/images/chargeGold/viettel.png" height="12" border="0" /></label><br /><input type="radio" id="card_gold_type_viettel" name="card_gold_type" class="radio_info" value="viettel" /></div>')
                    ('<div class="f1" style="margin-top:-4px;"><label for="card_gold_type_vcoin"><img src="'+core.baseURI+'templates/css/images/chargeGold/vcoin.png" height="12" border="0" /></label><br /><input type="radio" id="card_gold_type_vcoin" name="card_gold_type" class="radio_info" value="vcoin" /></div>')
        		('</div>')
        		('<div class="gold_title" style="margin-top:10px;"><div style="width: 110px;"><b>'+ t('Mã số thẻ cào') +':</b></div>')
        			('<input type="textbox" id="card_code" class="inputcard" value="" /></div>')
                ('<div class="gold_title" style="margin-top:10px; display: none;" id="card_serial_wrapper"><div style="width: 110px;"><b>'+ t('Số serial') +':</b></div>')
        			('<input type="textbox" id="card_serial" class="inputcard" value="" /></div>')
                ('<div class="small" style="margin-top: 10px;">'+ t('Vui lòng nhập chính xác mã số in trên thẻ. Hệ thống chỉ cho phép nhập sai không quá 5 lần') +'.</div>')
        	('</div>')	
        ('</div>')
        
        ('<div class="buttonDIV clearfix">')
        	('<div>')
        		('<a class="orangeButton" href="javascript:void(0);" onclick="core.chargeGold.do_step_21();"><span><span>'+ t('Tiếp tục') +'</span></span></a>')
        	('</div>')
        	('<div>')
        		('<a class="orangeButton btnLeft" href="javascript:void(0);" onclick="core.chargeGold.step_2();"><span><span>'+ t('Quay lại') +'</span></span></a>')
        	('</div>')
        ('</div>')
        ('</div>')();
    
    core.show_overlay_popup(12, cardphone_Title, cardphone_Content);
    $('#card_code').focus();
    
    $('[name=card_gold_type]').change(function (){
        card_gold_type = $(this).val();
        if (card_gold_type=='viettel' || card_gold_type=='vcoin'){
            $('#card_serial_wrapper #card_serial').val("");
            $('#card_serial_wrapper').css('display', 'block');
        }else{
            $('#card_serial_wrapper').css('display', 'none');
        }
        $('#card_code').focus();
    });
}

core.chargeGold.do_step_21 = function(){
    core.chargeGold.card_type = $('.content_gold input[name=card_gold_type]:checked').val();
    core.chargeGold.card_code = $('.content_gold #card_code').val();
    core.chargeGold.card_serial = $('.content_gold #card_serial').val();
    if (core.chargeGold.card_code.length<=11){
        $('.content_gold #card_code').focus();
        return core.error.set('#card_code', t('Mã số thẻ không hợp lệ. Bạn vui lòng chỉ nhập vào các chữ số in trên thẻ.'), 340, '.content_gold');
    }
    
    if (core.chargeGold.card_type=='viettel' || core.chargeGold.card_type=='vcoin'){
        if (core.chargeGold.card_serial.length==0){
            $('.content_gold #card_serial').focus();
            return core.error.set('#card_serial', t('Bạn vui lòng nhập vào số serial in trên thẻ.'), 340, '.content_gold');
        }
    }else{
        core.chargeGold.card_serial = '';
    }
    
    core.ajax_popup('task=chargeMobiCard',"POST",{code_card:core.chargeGold.card_code, card_type: core.chargeGold.card_type, email: core.chargeGold.user_info.user_email},
	function(j){
		core._store.variable['recharge_gold_start'] = false;
		if(j.err == 0)	{
            $('.user_gold_value').html(core.numberFormat(j.user_gold)+'₫');
			core.chargeGold.step_5();
		}else{
			var id = 0;
			switch(j.msg){
				case 'not_connect': j.msg = t('Không kết nối được với nhà cung cấp'); break;
				case 'cus_not_found': j.msg = t('Hiện tại bạn đang không đăng nhập.<br />Vui lòng tắt cửa sổ, mua lại'); break;
				case 'code_invalid': case 'invalid_card': case 'error':
					j.msg = t('Mã số thẻ không hợp lệ');
					id = '#card_code';
				break;
			}
			core.error.set(id, j.msg, 340, '.content_gold');
		}
	},
	{
		loading:function(){
			core.error.set('', t('Hệ thống đang kiểm tra mã thẻ.<br />Quý khách vui lòng <b>không tắt trình duyệt</b>.'), 340, '.goldRecharge3_card');
			core.show_loading(t('Đang kiểm tra Mã thẻ'));
		}
	});
}


//13.CHON HINH THUC NAP HO GOLD(3)

core.chargeGold.step_3 = function(){
    return core.chargeGold.step_2(true);
    core.chargeGold.current_step = 3;
    var hdTitleNH = core.join ('<div class="title_popup">')
        ('<div class="text">'+ t('Chọn hình thức nạp') +'</div>')
        ('</div>')();
    
    var hdContentNH = core.join ('<div class="content_popup clearfix">')
        ('<div class="content_gold clearfix">')
        	('<div class="emailNH">')
        		('<p><b>'+ t('Họ tên') +' :</b> '+core.chargeGold.user_info.user_fullname+'</p>')
        		('<p><b>'+ t('Điện thoại') +' :</b> '+core.chargeGold.user_info.user_mobile+'</p>')
        		('<p><b>Email :</b> '+core.chargeGold.user_info.user_email+'</p>')
        	('</div>')
        ('</div>')
        
        ('<div class="content_gold clearfix">')
        ('<div class="radioBox"><input type="radio" id="input-mobicharge" name="hd" onchange="core.chargeGold.checkRadio(this.value);" value="1" checked="checked" /></div>')
        ('<div class="content_gold_right">')
        ('<div class="gold_title"><label for="input-mobicharge">'+ t('Nạp từ thẻ cào điện thoại') +'</label></div>')
        ('<div class="small">'+ t('Hỗ trợ thẻ của mạng Vinaphone, Mobifone, Viettel và VCoin. Áp dụng cho mọi loại mệnh giá thẻ') +'.</div>')
        ('<div class="small"><a href="javascript:void(0);"><span id="mobiphone"></span></a><a href="javascript:void(0);"><span id="vinaphone"></span></a><a href="javascript:void(0);"><span id="viettel"></span></a><a href="javascript:void(0);"><span id="vcoin"></span></a></div>')
        ('</div>')	
        ('</div>')
        
        ('<div class="content_gold clearfix">')
        ('<div class="radioBox"><input type="radio" name="hd" id="input-creditcharge" onchange="core.chargeGold.checkRadio(this.value);" value="2" /></div>')
        ('<div class="content_gold_right">')
        ('<div class="gold_title"><label for="input-creditcharge">'+ t('Nạp từ thẻ Visa, Master Card, thẻ ATM, có Internet Banking') +'</label></div>')
        //('<div class="small">Số tiền nạp được chuyển tương ứng thành Gold theo tỉ lệ <b class="red">1 Gold ~ 1 VNĐ</b></div>')
        ('<div class="small"><a href="javascript:void(0);"><span id="visa"></span></a><a href="javascript:void(0);"><span id="master"></span></a>')
        ('<a href="javascript:void(0);" class="f1"><span id="vcb"></span></a>')
        ('<a href="javascript:void(0);" class="f1"><span id="donga"></span></a>')
        ('<a href="javascript:void(0);" class="f1"><span id="techcombank"></span></a>')
        ('<a href="javascript:void(0);" class="f1"><span id="vietinbank"></span></a>')
        ('</div>')
        ('<div class="small"><a href="javascript:void(0);"><span id="hdbank"></span></a></a>')
        ('<a href="javascript:void(0);" class="f1"><span id="vib"></span></a>')
        ('<a href="javascript:void(0);" class="f1"><span id="tienphongbank"></span></a>')
        ('</div>')
        ('</div>')	
        ('</div>')
        
        ('<div class="buttonDIV clearfix">')
        	('<div>')
        		('<a class="orangeButton" href="javascript:void(0);" onclick="core.chargeGold.step_4();"><span><span>'+ t('Tiếp tục') +'</span></span></a>')
        	('</div>')
        	('<div>')
        		('<a class="orangeButton btnLeft" href="javascript:void(0);" onclick="core.chargeGold.step_1();"><span><span>'+ t('Quay lại') +'</span></span></a>')
        	('</div>')
        ('</div>')
        ('</div>')();
        
    core.show_overlay_popup(13, hdTitleNH, hdContentNH);
}

//14.Nạp Gold từ thẻ Visa, Master Card, thẻ ATM, có Internet Banking (NH)
core.chargeGold.step_4 = function(){
    core.chargeGold.current_step = 4;
    var returnFunction = (core.chargeGold.user_info.user_email==core.user.user_info['user_email'])?"core.chargeGold.step_1()":"core.chargeGold.step_3()";
    var hdTitleGoldNH = core.join ('<div class="title_popup">')
        ('<div class="text">'+ t('Nạp tiền vào Ví SohaPay') +'</div>')
        ('</div>')();

    var hdContentGoldNH = core.join ('<div class="content_popup clearfix">')
    	('<div class="content_gold clearfix">')
            ('<div id="cError"></div>')
    		('<div class="content_gold_right">')
    			('<div class="gold_title" style="text-align:center;">'+ t('Số tiền cần nạp') +' : ')
    				('<input id="input-goldnumber" type="textbox" value="50,000" name="gold_number" onkeyup="core.chargeGold.mixMoney(this)" onfocus="this.select()" onkeypress="return core.numberOnly(this, event)" class="inputtext" style="font-weight: bold; color: #000;">')
    			('</div>')
    			('<div class="small" style="text-align:center;">'+ t('Mỗi lần nạp phải từ <b class="red">50.000 đ</b> trở lên') +'</div>')
    		('</div>')
    	('</div>')
    	/*
    	('<div style="border-bottom:1px solid #CCCCCC; width:100%; height:1px; float:left; margin-bottom:20px;"></div>')
    	
    	('<div class="content_gold clearfix">')
    		//('<img src="images/logoSoha.png" style="float:left; width:100px;" border="0" />')
    		('<div class="content_gold_right" style="width:550px;">')
    			('<div class="small" style="width:500px; margin-left:20px;"><a href="javascript:void(0);"><span id="visa"></span></a><a href="javascript:void(0);"><span id="master"></span></a>')
    				('<a href="javascript:void(0);" class="f1"><span id="vcb"></span></a>')
    				('<a href="javascript:void(0);" class="f1"><span id="donga"></span></a>')
    				('<a href="javascript:void(0);" class="f1"><span id="techcombank"></span></a>')
    				('<a href="javascript:void(0);" class="f1"><span id="vietinbank"></span></a>')
    			('</div>')
    			('<div class="small" style="width:500px; margin-left:20px;"><a href="javascript:void(0);"><span id="hdbank"></span></a></a>')
    				('<a href="javascript:void(0);" class="f1"><span id="vib"></span></a>')
    				('<a href="javascript:void(0);" class="f1"><span id="tienphongbank"></span></a>')
    			('</div>')
    		('</div>')	
    	('</div>')
    	*/
    	('<div class="buttonDIV clearfix">')
    		('<div>')
    			('<a class="orangeButton" href="javascript:void(0);" onclick="core.chargeGold.do_step_4();"><span><span>'+ t('Tiếp tục') +'</span></span></a>')
    		('</div>')
    		('<div>')
    			('<a class="orangeButton btnLeft" href="javascript:void(0);" onclick="'+returnFunction+';"><span><span>'+ t('Quay lại') +'</span></span></a>')
    		('</div>')
    	('</div>')
    ('</div>')();

	if(core.chargeGold.isPopup) {
		core.show_overlay_popup(14, hdTitleGoldNH, hdContentGoldNH);
	} else {
		$('#'+core.chargeGold.wrapId).html(hdContentGoldNH);
	}

}


core.orderSupport = function(url, order_id, orderSupportStatus){
core.adminRootUrl = url;
core.supportOrderId = order_id;
if (orderSupportStatus != null) {
	if (orderSupportStatus == 0) {
		firstOptionSelected = "selected";
		secondOptionSelected = "";
	} else {
		firstOptionSelected = "";
		secondOptionSelected = "selected";
	}
}

var html = "";
if (orderSupportStatus != null) {
	html += '<tr>';
	html +=	'<td align="left"  style="padding-bottom: 10px; padding-right: 10px;">';
	html +=		'<strong>'+ t('Tình trạng giao dịch') +'</strong>';
	html +=	'</td>';

	html +=	'<td  align="left" style="padding-bottom: 10px;">';
	html +=		'<select id="order_support_status">';
	html +=			'<option '+ firstOptionSelected +' value="0">'+ t('Đã yêu cầu xác minh') +'</option>';
	html +=			'<option '+ secondOptionSelected +' value="1">'+ t('Xác minh') +'</option>';
	html +=		'<select>';
	html +=	'</td>';
	html +='</tr>';
}else {
	html += '<input type="hidden" value="-1" id="order_support_status" />';
}

    var hdTitleGoldNH = core.join ('<div class="title_popup">')
        ('<div class="text">'+ t('Hỗ trợ khách hàng') +'</div>')
        ('</div>')();

    var hdContentGoldNH = core.join ('<div class="content_popup clearfix">')
    	('<div class="content_gold clearfix">')
            ('<div id="cError"></div>')
    		('<div class="content_gold_right">')
    			('<div class="gold_title" style="text-align:center;">')
					('<table>')
						(html)
						('<tr>')
							('<td style="padding-right: 10px;">')
							('<strong>'+ t('Ghi chú') +'</strong> ')
							('</td>')

							('<td align="left">')
								('<textarea style="width: 400px; height: 100px;" id="support_content">&nbsp;</textarea>')
							('</td>')
						('</tr>')
					('</table>')
    			('</div>')
    			('<div class="small" style="text-align:center;">&nbsp;</div>')
    		('</div>')
    	('</div>')

    	('<div class="buttonDIV clearfix">')
    		('<div>')
    			('<a class="orangeButton" href="javascript:void(0);" onclick="core.updateSupportStatus();"><span><span>'+ t('Cập nhật') +'</span></span></a>')
    		('</div>')
    	('</div>')
    ('</div>')();
	core.show_overlay_popup(14, hdTitleGoldNH, hdContentGoldNH);
}

core.updateSupportStatus = function() {

	var orderSupportStatus = $("#order_support_status").val();
	var support_content = $('#support_content').val();
	var order_id = core.supportOrderId;

	if (orderSupportStatus == 0 || orderSupportStatus == -1) {
		$.post(core.adminRootUrl + "qtcore/admin_risk_manager.php?task=support", {orderSupportStatus: orderSupportStatus, support_content: support_content, order_id: order_id}, function(data){
			var data = jQuery.parseJSON(data);
			if(data.success == false) {
				alert(data.warning);
			} else {
				alert('Cập nhật thành công!');
			}
		});

	} else {
		//do nothing
	}

	if(orderSupportStatus == -1) {
		core.redirect(core.adminRootUrl + '/qtcore/admin_support_orders.php');
	} else {
		core.redirect(core.adminRootUrl + '/qtcore/admin_risk_manager.php');
	}
}



core.chargeGold.do_step_4 = function() {
    core.chargeGold.gold_number = $('.content_gold input[name=gold_number]').val();
    core.chargeGold.gold_number = core.chargeGold.gold_number.replace(/,/gi, '');
    if (core.chargeGold.gold_number<50000){
        core.error.set('#input-goldnumber', t('Bạn phải nạp ít nhất là 50,000đ'), 500, '.content_gold'); return;
    }else if (core.chargeGold.gold_number>=1000000000){
        core.error.set('#input-goldnumber', t('Số tiền bạn nạp quá lớn'), 500, '.content_gold'); return;
    }
    core.redirect(core.baseURI+'charge_gold.php?task=docheckout&user_email='+core.chargeGold.user_info.user_email+'&gold_number='+core.chargeGold.gold_number);
}


//15.Thong bao thanh cong
core.chargeGold.step_5 = function(){
    core.chargeGold.current_step = 5;
    var titleTC = core.join('<div class="title_popup">')
        ('<div class="text">'+ t('Bạn đã nạp tiền thành công') +'</div>')
        ('</div>')();
    
    var contentTC = core.join('<div class="content_popup clearfix">')
        ('<div class="content_gold clearfix">')
        	('<div class="content_gold_right">')
        		('<div class="gold_title" style="text-align:center;">'+ t('Bạn đã thực hiện giao dịch nạp tiền thành công, xin cảm ơn !') +'</div>')
        	('</div>')
        ('</div>')
        
        ('</div>')();

     core.show_overlay_popup(15, titleTC, contentTC);
}
<?php
defined('PG_PAGE') or die();
require_once 'phpmailer/class.phpmailer.php';

define('EMAIL_TYPE_ADMIN', 1);
define('EMAIL_TYPE_USER', 2);
define('EMAIL_TYPE_ORDER', 3);
define('EMAIL_TYPE_CUSTOMER', 4);

function create_order_template($input_user_order = array(), $aryInfoCustomer, $totalPrice, $list_product_name, $list_product_id, $list_price, $list_qty, $list_price_total, $list_color = false, $list_size = false ){
	global $setting, $database;

	if ( !$input_user_order
        || !is_array($input_user_order)
        || empty($input_user_order)
        || !$aryInfoCustomer
        || !is_array($aryInfoCustomer)
        || empty($aryInfoCustomer)
        || !$totalPrice
        || !$list_product_name
        || !is_array($list_product_name)
        || !$list_product_id
        || !is_array($list_product_id)
        || !$list_price
        || !is_array($list_price)
        || !$list_qty
        || !is_array($list_qty)
        || !$list_price_total
        || !is_array($list_price_total) ){
		    return false;
	}

	if (count($list_product_name)){
		$build_text_description = '';
		for ($i = 0; $i<count($list_product_name); $i++){
			$input_product['order_id']		= $input_user_order['order_id'];
			$input_product['product_id'] 	= $list_product_id[$i];
			$input_product['price'] 		= $list_price[$i];
			$input_product['number'] 		= $list_qty[$i];
			$input_product['totals']		= $list_price[$i] * $list_qty[$i];
			$database->insert(TBL_ORDER_PRODUCT, $input_product);

			$build_text_description .= '<tr>';
				$build_text_description .= '<td align="left" style="padding-left: 5px; border-bottom: 1px solid #dedede; border-right: 1px solid #dedede;">'.$list_product_name[$i].'</td>';
				$build_text_description .= ( (!empty($list_color[$i]) && $list_color[$i]) ? '<td align="left" style="padding-left: 5px; border-bottom: 1px solid #dedede; border-right: 1px solid #dedede;"><div style="width: 15px; height:15px; background: #'.$list_color[$i].'"></div></td>' : '');
				$build_text_description .= ( (!empty($list_size[$i]) && $list_size[$i]) ? '<td align="left" style="padding-left: 5px; border-bottom: 1px solid #dedede; border-right: 1px solid #dedede;">'.$list_size[$i].'</td>' : '');
				$build_text_description .= '<td align="left" style="padding-left: 5px; border-bottom: 1px solid #dedede; border-right: 1px solid #dedede;">'.$list_qty[$i].'</td>';
				$build_text_description .= '<td align="left" style="padding-left: 5px; border-bottom: 1px solid #dedede; border-right: 1px solid #dedede;"><span style="color:#ed470d">'.number_format($list_price[$i], 0, ",", ".").' VNĐ</span></td>';
				$build_text_description .= '<td align="left" style="padding-left: 5px; border-bottom: 1px solid #dedede;"><span style="color:#ed470d; font-weight: bold;">'.number_format($list_price_total[$i], 0, ",", ".").' VNĐ</span></td>';
			$build_text_description .= '</tr>';
		}
	}

	$html = '<table width="950" align="center" style="background-color: #FFFFFF; border: 1px solid #128DC6; position: relative; padding: 0; font-family: Arial, Tahoma, Verdana; font-size: 12px; color: #333; line-height: 22px; display: inline-block; border-radius:5px; -webkit-border-radius:5px; -moz-border-radius:5px;">
		<thead>
		<tr>
			<th width="250" align="center" style="font-size:16px; font-weight: bold; background: rgba(20,158,224,0.7); color: #FFF; line-height: 30px; text-transform:uppercase;">'.$setting['setting_domain'].'</th>
			<th width="700" align="center" style="font-size:16px; font-weight: bold; background: rgba(20,158,224,0.7); color: #FFF; line-height: 30px;">THÔNG TIN ĐƠN ĐẶT HÀNG</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td align="left" style="font-size:13px;font-weight: bold; padding-left: 5px; text-transform:uppercase; background: rgba(20,158,224,0.7); color: #FFF; line-height: 25px;">Mã đơn hàng</td>
			<td align="left" style="padding-left: 5px;"><b>'.$input_user_order['order_code'].'</b></td>
		</tr>
		<tr>
			<td align="left" valign="top" style="font-size:13px;font-weight: bold; padding-left: 5px; text-transform:uppercase; background: rgba(20,158,224,0.7); color: #FFF; line-height: 25px;">Thông tin sản phẩm</td>
			<td>
				<table width="100%" style="border:1px solid #DEDEDE; margin:0; padding:0;">
					<tr>
						<td align="left" style="font-weight: bold; padding-left: 5px; border-bottom: 1px solid #dedede; border-right: 1px solid #dedede;">Tên sản phẩm</td>
						'.( (!empty($list_color) && is_array($list_color)) ? '<td align="left" style="font-weight: bold; padding-left: 5px; border-bottom: 1px solid #dedede; border-right: 1px solid #dedede;">Màu sắc</td>' : '').'
						'.( (!empty($list_size) && is_array($list_size)) ? '<td align="left" style="font-weight: bold; padding-left: 5px; border-bottom: 1px solid #dedede; border-right: 1px solid #dedede;">Kích thước</td>' : '').'
						<td align="left" style="font-weight: bold; padding-left: 5px; border-bottom: 1px solid #dedede; border-right: 1px solid #dedede;">Số lượng mua</td>
						<td align="left" style="font-weight: bold; padding-left: 5px; border-bottom: 1px solid #dedede; border-right: 1px solid #dedede;">Đơn giá</td>
						<td align="left" style="font-weight: bold; padding-left: 5px; border-bottom: 1px solid #dedede;">Thành tiền</td>
					</tr>
					'.$build_text_description.'
					<tr>
						<td colspan="6" align="right" style="text-transform: uppercase; font-weight: bold; font-size: 13px; line-height: 30px; padding-right: 10px;">Tổng giá trị đơn hàng : <span style="color:#ed2709">'.number_format($totalPrice, 0, ",", ".").' VNĐ</span></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="left" valign="top" style="font-size:13px;font-weight: bold; padding-left: 5px; text-transform:uppercase; background: rgba(20,158,224,0.7); color: #FFF; line-height: 25px;">Thông tin người mua</td>
			<td>
				<table>
					<tr>
						<td align="left" style="font-weight: bold; padding-left: 5px;">Tên khách hàng</td>
						<td align="left" style="padding-left: 5px;">'.$aryInfoCustomer['user_fullname'].'</td>
					</tr>
					<tr>
						<td align="left" style="font-weight: bold; padding-left: 5px;">Điện thoại liên hệ</td>
						<td align="left" style="padding-left: 5px;">'.$aryInfoCustomer['user_username'].'</td>
					</tr>
					<tr>
						<td align="left" style="font-weight: bold; padding-left: 5px;">Email đặt hàng</td>
						<td align="left" style="padding-left: 5px;"><a style="color:#128DC6; text-decoration: underline;" href="mailto:'.$aryInfoCustomer['user_email'].'">'.$aryInfoCustomer['user_email'].'</a></td>
					</tr>
					<tr>
						<td align="left" style="font-weight: bold; padding-left: 5px;">Hình thức thanh toán</td>
						<td align="left" style="padding-left: 5px;">'.$aryInfoCustomer['service'].'</td>
					</tr>
					<tr>
						<td align="left" style="font-weight: bold; padding-left: 5px;">Yêu cầu của khách hàng</td>
						<td align="left" style="padding-left: 5px;">'.$aryInfoCustomer['message'].'</td>
					</tr>
					<tr>
						<td align="left" style="font-weight: bold; padding-left: 5px;">Địa chỉ nhận hàng</td>
						<td align="left" style="padding-left: 5px;">'.$aryInfoCustomer['address'].'</td>
					</tr>
				</table>
			</td>
		</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" align="left" style="padding:5px 10px; background: #f1f1f1;">
					<div style="float: left">
						<span><b>'.$setting['setting_company'].'</b></span><br/>
						<span>Địa chỉ: '.$setting['setting_company_address'].'</span><br/>
					</div>
					<div style="float: right">
						<span>Hotline: '.$setting['setting_hotline'].'</span><br/>
						<span>Email: <a style="color:#128DC6; text-decoration: underline;" href="mailto:'.$setting['setting_email'].'">'.$setting['setting_email'].'</a></span><br/>
						<span>Website: <a style="color:#128DC6; text-decoration: underline;" target="_blank" href="http://'.$setting['setting_domain'].'">'.$setting['setting_domain'].'</a></span>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>';
	return $html;
}
function send_email($recipient, $sender='', $subject, $message, $order_id, $masterMail = FALSE)
{
	global $setting, $database;
	if (PGRequest::getBool('test_mode', false, 'COOKIE')) return true;
	
	// DECODE SUBJECT AND EMAIL FOR SENDING
	$subject = htmlspecialchars_decode($subject, ENT_QUOTES);
	$message = htmlspecialchars_decode($message, ENT_QUOTES);

	// ENCODE SUBJECT FOR UTF8
	$subject="=?UTF-8?B?".base64_encode($subject)."?=";

	// REPLACE CARRIAGE RETURNS WITH BREAKS
	$message = str_replace("\n", "", $message);
	
	//@date_default_timezone_set('Bangkok/Hanoi/Jakarta');
	
	$sendmail = false;
	//echo $setting['mail_type'];
	//print_r($setting);
	$mail             = new PHPMailer();
	$mail->CharSet	  = "utf-8";
	//$mail->SetLanguage("vn",PG_ROOT.'/include/phpmailer/language/');
	
	if ($setting['mail_type']=='smtp'){
		$mail->IsSMTP();
		$mail->SMTPAuth   = $setting['mail_smtpauth'];                  // enable SMTP authentication
		$mail->SMTPSecure = $setting['mail_smtpsecure'];                 // sets the prefix to the servier
		$mail->Host       = $setting['mail_smtphost'];      // sets GMAIL as the SMTP server
		$mail->Port       = $setting['mail_smtpport'];                   // set the SMTP port for the GMAIL server
		
		$mail->Username   = $setting['mail_smtpuser'];  // GMAIL username
		$mail->Password   = $setting['mail_smtppass'];            // GMAIL password
	}
	else {
		$mail->IsSendmail();
		$mail->Sendmail	= $setting['mail_sendmailpath'];
	}
	
	// Do Sent mail
	if (!$sender) $sender=$setting['mail_smtpuser'];
  	$mail->AddReplyTo($sender);
	
	$mail->From       = $sender;
	$mail->FromName   = $setting["setting_title_web_product"];
	$mail->IsHTML(true); // send as HTML
	
	$mail->Subject    = $subject;
	$mail->MsgHTML($message);
	$mail->AddAddress($recipient,"");
	if ( $masterMail ){
		$mail->AddCC($masterMail);
	}
	if ($mail->Send()==true){
		return true;
	}
	else return false;
} // END FUNCTION

function send_email_system($recipient, $sender='', $subject, $message, $formHTML = null, $aryEmailCC = false)
{
	global $setting, $database;
	
	// DECODE SUBJECT AND EMAIL FOR SENDING
	$subject = htmlspecialchars_decode($subject, ENT_QUOTES);
	$message = htmlspecialchars_decode($message, ENT_QUOTES);

	// ENCODE SUBJECT FOR UTF8
	$subject="=?UTF-8?B?".base64_encode($subject)."?=";

	// REPLACE CARRIAGE RETURNS WITH BREAKS
	$message = str_replace("\n", "", $message);
	if ($formHTML = 1){
		$message = $message;
	}else{
	$message = '
	<div style="width: 723px; background-color: #FFFFFF; border: 1px solid #fea700; position: relative; padding: 10px; font-family: Arial, Tahoma, Verdana; font-size: 12px; color: #999; display: inline-block; border-radius:5px; -webkit-border-radius:5px; -moz-border-radius:5px;">
	    <div style="width: 100%; height: 84px; border-bottom: 1px dotted #999999;"> <a href="'.PG_URL_HOMEPAGE.'" style="width: 200px; height: 53px; display: block; float: left;"><img src="'.$setting['logo'].'" alt="'.$setting['setting_company'].'" width="200" height="53" border="0" /></a>
	        <div style="float: right; font-size: 12px; color: #999; text-align:right">
	            <h2 style="color: #FE890A; font-size: 22px; margin: 0;">EMAIL THÔNG BÁO</h2>
	        </div>
	        <div style="clear:both"></div>
	        <div style="width:100%; height: 23px; float: left;">
	            <div style="float: left; line-height: 35px;">'.date('d/m/Y').'</div>
	            <ul style="float: right; margin: 0; padding: 0;">
	                <li style="list-style: none; float: left; margin-right: 20px; padding: 10px 0;"><a href="'.PG_URL_HOMEPAGE.'" target="_blank" style="color: #FE890A; text-decoration: none; line-height: 20px; padding-left: 5px;"><img src="'.PG_URL_HOMEPAGE.'/images/email/li_homepage.jpg" style="float:left; border:none;" />'.$setting['logo'].'</a></li>
	                <li style="list-style: none; float: left; margin-right: 20px; padding: 10px 0;"><a href="'.PG_URL_HOMEPAGE.'/lien-he.html" target="_blank" style="color: #FE890A; text-decoration: none; line-height: 20px; padding-left: 5px;"><img src="'.PG_URL_HOMEPAGE.'/images/email/li_contact.jpg" style="float:left; border:none;" />Liên hệ</a></li>
	                <li style="list-style: none; float: left; margin-right: 20px; padding: 10px 0;"><a href="'.$setting['setting_facebook'].'" target="_blank" style="color: #FE890A; text-decoration: none; line-height: 20px; padding-left: 5px;"><img src="'.PG_URL_HOMEPAGE.'/images/email/li_face.jpg" style="float:left; border:none;" />Facebook</a></li>
	            </ul>
	        </div>
	        <div style="clear:both"></div>
	    </div>
	    <div style="clear:both"></div>
	    <div style="color: #000000;padding: 10px 0 0 0;">'.$message.'</div>
	    <div style="text-align: center; color: #FE890A; font-style: italic; font-size: 15px; width: 100%; float: left; padding-top: 10px; font-weight: bold; line-height: 25px;">'.$setting['setting_title_web'].'</div>
	</div>
	<div style="width: 723px; padding: 10px 0; text-align: center; color: #333; font-weight: normal; font-size: 12px; font-family: Arial, Tahoma, Verdana;">
	    <ul style="margin: 0; padding: 0; margin-left: 40px;">
	        <li style="list-style: none; float: left; margin-right: 30px; padding-left: 5px;"><img src="'.PG_URL_HOMEPAGE.'/images/email/li_contact.jpg" style="float:left; border:none; padding-right:3px; margin-top:-3px;" />Điện thoại: '.$setting['setting_hotline'].'</li>
	        <li style="list-style: none; float: left; margin-right: 30px; padding-left: 5px;"><img src="'.PG_URL_HOMEPAGE.'/images/email/li_mail.jpg" style="float:left; border:none; padding-right:3px; margin-top:-3px;" />Email: <a style="color: #FE890A; text-decoration: none;" href="mailto:'.$setting['setting_email'].'">'.$setting['setting_email'].'</a></li>
	        <li style="list-style: none; float: left; padding-left: 5px;"><img src="'.PG_URL_HOMEPAGE.'/images/email/li_yahoo.jpg" style="float:left; border:none; padding-right:3px; margin-top:-3px;" /><a style="color: #FE890A; text-decoration: none;" href="ymsgr:sendim?'.$setting['setting_support_yahoo'].'">YM: '.$setting['setting_support_yahoo'].'</a></li>
	    </ul>
	</div>
	';
	}

	$sendmail = false;
	//echo $setting['mail_type'];
	//print_r($setting);
	$mail             = new PHPMailer();
	$mail->CharSet	  = "utf-8";
	
	if ($setting['mail_type']=='smtp'){
		$mail->IsSMTP();
		$mail->SMTPAuth   = $setting['mail_smtpauth'];                  // enable SMTP authentication
		$mail->SMTPSecure = $setting['mail_smtpsecure'];                 // sets the prefix to the servier
		$mail->Host       = $setting['mail_smtphost'];      // sets GMAIL as the SMTP server
		$mail->Port       = $setting['mail_smtpport'];                   // set the SMTP port for the GMAIL server
		
		$mail->Username   = $setting['mail_smtpuser'];  // GMAIL username
		$mail->Password   = $setting['mail_smtppass'];            // GMAIL password
	}
	else {
		$mail->IsSendmail();
		$mail->Sendmail	= $setting['mail_sendmailpath'];
	}
	
	// Debug mail
	$mail->SMTPDebug  = 1;
	
	// Do Sent mail
	if (!$sender) $sender=$setting['mail_smtpuser'];
  	$mail->AddReplyTo($sender);
	
	$mail->From       = $sender;
	$mail->FromName   = $setting["setting_title_web"];
	$mail->IsHTML(true); // send as HTML
	
	$mail->Subject    = $subject;
	$mail->MsgHTML($message);
	if ( is_array($recipient) ){
		foreach ($recipient as $fmail) {
			$mail->AddAddress($fmail,"");
		}
	}else{
		$mail->AddAddress($recipient,"");
	}
	if ( $aryEmailCC && is_array($aryEmailCC) && count($aryEmailCC) && !empty($aryEmailCC) ){
		foreach ($aryEmailCC as $cmail) {
			$mail->AddCC($cmail);
		}
	}else if ( is_string($aryEmailCC) ){
		$mail->AddCC($aryEmailCC);
	}else if ( $aryEmailCC === TRUE || $aryEmailCC == TRUE || $aryEmailCC == 1 ){
		$mail->AddCC($setting['setting_email']);
	}
	return $mail->Send();
} // END FUNCTION

function getSystemEmail($name, $aryReplace=null) {
	global $setting, $database;
	$sql = "SELECT * FROM ".TBL_SYSTEM_EMAIL." WHERE system_email_name='".mysql_escape_string($name)."' LIMIT 1";
	$aryEmail = $database->db_fetch_assoc($database->db_query($sql));

	if ($aryEmail['system_email_body'] != '') {
		$aryEmail['system_email_body'] = str_replace(array_keys($aryReplace), $aryReplace, $aryEmail['system_email_body']);
	}
	return $aryEmail;
}

// ADD FUNCTION CHECK MAIL TRUE
function checkMassMail($massmail_email){
	global $database, $validate, $site_id;

	$is_error = NULL;
	//CHECK EMAIL
	if ($massmail_email == '') {
		$is_error= 'Địa chỉ email rỗng';
	}
	if ($massmail_email !='') {
		if (!$validate->isEmail($massmail_email)) {
			$is_error = 'Email không đúng định dạng';
		}
	}
	$condition = '';
	if ( $site_id ){
		$condition = ' AND site_id='.$site_id;
	}
	//CHECK USER EXISTED
	$email = strtolower($massmail_email);
	if ($database->db_num_rows($database->db_query("SELECT massmail_email FROM ".TBL_MASSMAIL." WHERE LOWER(massmail_email)='{$email}'" . $condition)) ) {
		$is_error = 'Email này đã có trong hệ thống';
	}
	return $is_error;
}

// ADD FUNCTION ADD MAIL TO MASSMAIL
function addMassMail($aryInput){
	global $database, $site_id;

	if (is_array($aryInput)){
		if ( $site_id ){
			$aryInput['massmail_site_id'] = $site_id;
		}
		$error = checkMassMail($aryInput['massmail_email']);
		if (is_null($error)){
			if (!$database->insert(TBL_MASSMAIL, $aryInput))
				$is_message = "Lỗi hệ thống";
			else
				$is_message = "Thêm mới email thành công !";
		}
	}else{
		$is_message = "Không tồn tại mảng dữ liệu !";
	}
	if (isset($is_message))return $is_message;
	else return;
}
?>
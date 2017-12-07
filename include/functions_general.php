<?php
defined('PG_PAGE') or die();
define('SET_THANG_DAU_KY', 1);
define('SET_THANG_CUOI_KY', 6);

// THIS FUNCTION CHANGES LOCATION HEADER TO REDIRECT FOR IIS PRIOR TO SETTING COOKIES
// INPUT: $url REPRESENTING THE URL TO REDIRECT TO
// OUTPUT: 

function cheader($url)
{
  	if( strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'microsoft') !== false )
  	{
    	header("Refresh: 0; URL=".$url);
  	}
  	else
  	{
    	header("Location: $url");
  	}
  	exit();
}

// END cheader() FUNCTION

// THIS FUNCTION RETURNS APPROPRIATE PAGE VARIABLES
// INPUT: $total_items REPRESENTING THE TOTAL NUMBER OF ITEMS
//	  $items_per_page REPRESENTING THE NUMBER OF ITEMS PER PAGE
//	  $p REPRESENTING THE CURRENT PAGE
// OUTPUT: AN ARRAY CONTAINING THE STARTING ITEM, THE PAGE, AND THE MAX PAGE

function make_page($total_items, $items_per_page, $p)
{
	if( !$items_per_page ) $items_per_page = 1;
  $maxpage = ceil($total_items / $items_per_page);
	if( $maxpage <= 0 ) $maxpage = 1;
  $p = ( ($p > $maxpage) ? $maxpage : ( ($p < 1) ? 1 : $p ) );
	$start = ($p - 1) * $items_per_page;
	return array($start, $p, $maxpage);
}

// END make_page() FUNCTION

// THIS FUNCTION RETURNS A RANDOM CODE OF DEFAULT LENGTH 8
// INPUT: $len (OPTIONAL) REPRESENTING THE LENGTH OF THE RANDOM STRING
// OUTPUT: A RANDOM ALPHANUMERIC STRING

function randomcode($len=8)
{
	$code = $lchar = NULL;
	for( $i=0; $i<$len; $i++ )
  	{
	  	$char = chr(rand(48,122));
	  	while( !preg_match("/[a-zA-Z0-9]/", $char) )
    	{
		    if( $char == $lchar ) continue;
		    $char = chr(rand(48,90));
	  	}
	  	$code .= $char;
	  	$lchar = $char;
	}
	return $code;
}

function GeraHash($len){
//Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
  $Caracteres = '0123456789';
  $QuantidadeCaracteres = strlen($Caracteres);
  $QuantidadeCaracteres--;

  $Hash=NULL;
  for($x=1;$x<=$len;$x++){
    $Posicao = rand(0,$QuantidadeCaracteres);
    $Hash .= substr($Caracteres,$Posicao,1);
  }

  return $Hash;
}

// END randomcode() FUNCTION

// THIS FUNCTION CHECKS IF PROVIDED STRING IS AN EMAIL ADDRESS
// INPUT: $email REPRESENTING THE EMAIL ADDRESS TO CHECK
// OUTPUT: TRUE/FALSE DEPENDING ON WHETHER THE EMAIL ADDRESS IS VALIDLY CONSTRUCTED

function is_email_address($email)
{
	$regexp = "/^[a-z0-9]+([a-z0-9_\+\\.-]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
	return (bool) preg_match($regexp, $email);
}

// END is_email_address() FUNCTION

// THIS FUNCTION SETS STR_IREPLACE IF FUNCTION DOESN'T EXIST
// INPUT: $search REPRESENTING THE STRING TO SEARCH FOR
//	  $replace REPRESENTING THE STRING TO REPLACE IT WITH
//	  $subject REPRESENTING THE STRING WITHIN WHICH TO SEARCH
// OUTPUT: RETURNS A STRING IN WHICH ONE STRING HAS BEEN CASE-INSENSITIVELY REPLACED BY ANOTHER

if( !function_exists('str_ireplace') )
{
  function str_ireplace($search, $replace, $subject)
  {
    $search = preg_quote($search, "/");
    return preg_replace("/".$search."/i", $replace, $subject); 
  } 
}

// END str_ireplace() FUNCTION


// THIS FUNCTION SETS HTMLSPECIALCHARS_DECODE IF FUNCTION DOESN'T EXIST
// INPUT: $text REPRESENTING THE TEXT TO DECODE
//	  $ent_quotes (OPTIONAL) REPRESENTING WHETHER TO REPLACE DOUBLE QUOTES, ETC
// OUTPUT: A STRING WITH HTML CHARACTERS DECODED

if( !function_exists('htmlspecialchars_decode') )
{
  function htmlspecialchars_decode($text, $ent_quotes = ENT_COMPAT)
  {
    if( $ent_quotes === ENT_QUOTES   ) $text = str_replace("&quot;", "\"", $text);
    if( $ent_quotes !== ENT_NOQUOTES ) $text = str_replace("&#039;", "'", $text);
    $text = str_replace("&lt;", "<", $text);
    $text = str_replace("&gt;", ">", $text);
    $text = str_replace("&amp;", "&", $text);
    return $text;
  }
}

// END htmlspecialchars() FUNCTION

// THIS FUNCTION SETS STR_SPLIT IF FUNCTION DOESN'T EXIST
// INPUT: $string REPRESENTING THE STRING TO SPLIT
//	  $split_length (OPTIONAL) REPRESENTING WHERE TO CUT THE STRING
// OUTPUT: AN ARRAY OF STRINGS 
if( !function_exists('str_split') )
{
  function str_split($string, $split_length = 1)
  {
    $count = strlen($string);
    if($split_length < 1)
    {
      return false;
    }
    elseif($split_length > $count)
    {
      return array($string);
    }
    else
    {
      $num = (int)ceil($count/$split_length);
      $ret = array();
      for($i=0;$i<$num;$i++)
      {
        $ret[] = substr($string,$i*$split_length,$split_length);
      }
      return $ret;
    }
  }
}

// END str_split() FUNCTION


// THIS FUNCTION STRIPSLASHES AND ENCODES HTML ENTITIES FOR SECURITY PURPOSES
// INPUT: $value REPRESENTING A STRING OR ARRAY TO CLEAN
// OUTPUT: THE ARRAY OR STRING WITH HTML CHARACTERS ENCODED

function security($value)
{
	if( is_array($value) )
  	{
	  $value = array_map('security', $value);
	}
  	else
  	{
	  	if( !get_magic_quotes_gpc() )
    	{
	    	$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	  	}
    	else
    	{
	    	$value = htmlspecialchars(stripslashes($value), ENT_QUOTES, 'UTF-8');
	  	}
	  	$value = str_replace("\\", "\\\\", $value);
	}
	return $value;
}

// END security() FUNCTION

// THIS FUNCTION CENSORS WORDS FROM A STRING
// INPUT: $field_value REPRESENTING THE VALUE TO CENSOR
// OUTPUT: THE VALUE WITH BANNED WORDS CENSORED

function censor($field_value)
{
	global $setting;

	$censored_array = explode(",", trim($setting['setting_banned_words']));
	foreach($censored_array as $key => $value)
	{
		$trimvalue = trim($value);
		if (preg_match("/\b".$trimvalue."\b/i", $field_value)){
			$replace_value = str_pad("", strlen(trim($value)), "*");
			$field_value   = preg_replace("/\b".$trimvalue."\b/i", $replace_value, $field_value);
		}
	}

	return $field_value;
} 

// END censor() FUNCTION



// THIS FUNCTION RETURNS TIME IN SECONDS WITH MICROSECONDS
// INPUT:
// OUTPUT: RETURNS THE TIME IN SECONDS WITH MICROSECONDS

if( !function_exists('getmicrotime') )
{
  	function getmicrotime()
  	{
        list($usec, $sec) = explode(" ",microtime());
        return ((float)$usec + (float)$sec);
  	}
}

// END getmicrotime() FUNCTION


// THIS FUNCTION CLEANS HTML TAGS FROM TEXT
// INPUT: $text REPRESENTING THE STRING TO CLEAN
//	  $allowable_tags REPRESENTING THE ALLOWABLE HTML TAGS (AS A COMMA-DELIMITED STRING)
//	  $forbidden_attr (OPTIONAL) REPRESENTING AND ARRAY OF ANY ADDITIONAL FORBIDDEN ATTRIBUTES (SUCH AS A STYLE TAG)
// OUTPUT: THE CLEANED TEXT

function cleanHTML($text, $allowable_tags = null, $forbidden_attr = null)
{
  	// INCLUDE FILTER CLASS
  	if( !class_exists("InputFilter") )
  	{
    	require(PG_ROOT."/include/class_inputfilter.php");
  	}

  	// New method
  	if( !method_exists('InputFilter', 'safeSQL') )
  	{
    	return InputFilter::process($text, array(
      		'allowedTags' => $allowable_tags,
      		'forbiddenAttributes' => $forbidden_attr,
    	));
  	}
  
  	// Old method
  	else
  	{
	    // INSTANTIATE INPUT FILTER CLASS WITH APPROPRIATE TAGS
	    $xssFilter = new InputFilter(explode(",", str_replace(" ", "", $allowable_tags)), "", 0, 1, 1);
	
	    // ADD NECESSARY BLACKLIST ITEMS
	    for($i=0;$i<count($forbidden_attr);$i++)
	    {
	      	$xssFilter->attrBlacklist[] = $forbidden_attr[$i];
	    }
	
	    // RETURN PROCESSED TEXT
	    return $xssFilter->process($text);
  	}
}

// END cleanHTML() FUNCTION


// THIS FUNCTION TRIMS A GIVEN STRING PRESERVING HTML
// INPUT: $string REPRESENTING THE STRING TO SHORTEN
//	  $start REPRESENTING THE CHARACTER TO START WITH
//	  $length REPRESENTING THE LENGTH OF THE STRING TO RETURN
// OUTPUT: THE CLEANED TEXT

function chopHTML($string, $start, $length=false)
{
  $pattern = '/(\[\w+[^\]]*?\]|\[\/\w+\]|<\w+[^>]*?>|<\/\w+>)/i';
  $clean = preg_replace($pattern, chr(1), $string);

  if(!$length)
      $str = substr($clean, $start);
  else {
      $str = substr($clean, $start, $length);
      $str = substr($clean, $start, $length + substr_count($str, chr(1)));
  }
  $pattern = str_replace(chr(1),'(.*?)',preg_quote($str));
  if(preg_match('/'.$pattern.'/is', $string, $matched))
      return $matched[0];
  return $string;
}

// END chopHTML() FUNCTION

// THIS FUNCTION CHOPS A GIVEN STRING AND INSERTS A STRING AT THE END OF EACH CHOP
// INPUT: $string REPRESENTING THE STRING TO CHOP
//        $length REPRESENTING THE LENGTH OF EACH SEGMENT
//        $insert_char REPRESENTING THE STRING TO INSERT AT THE END OF EACH SEGMENT

function choptext($string, $length=32, $insert_char=' ')
{
  return preg_replace("!(?:^|\s)([\w\!\?\.]{" . $length . ",})(?:\s|$)!e",'chunk_split("\\1",' . $length . ',"' . $insert_char. '")',$string);
}

// END choptext() FUNCTION


// THIS FUNCTION CHOPS A GIVEN STRING AND INSERTS A STRING AT THE END OF EACH CHOP (PRESERVING HTML ENTITIES)
// INPUT: $html REPRESENTING THE STRING TO CHOP
//        $size REPRESENTING THE LENGTH OF EACH SEGMENT
//        $delim REPRESENTING THE STRING TO INSERT AT THE END OF EACH SEGMENT

function chunkHTML_split($html, $size, $delim)
{
   $out = '';
  $pos=$unsafe=0;
  for($i=0;$i<strlen($html);$i++)
  {
    if($pos >= $size && !$unsafe)
    {
      $out .= $delim;
      $unsafe = 0;
      $pos = 0;
    }
    $c = substr($html,$i,1);
    if($c == "&")
      $unsafe = 1;
    elseif($c == ";")
      $unsafe = 0;
    $out .= $c;
    $pos++;
  }
  return $out;
}

// END chunkHTML_split


// THIS FUNCTION RETURNS THE LENGTH OF A STRING, ACCOUNTING FOR UTF8 CHARS
// INPUT: $str REPRESENTING THE STRING
// OUTPUT: THE LENGTH OF THE STRING

function strlen_utf8($str)
{
  $i = 0;
  $count = 0;
  $len = strlen($str);
  while($i < $len)
  {
    $chr = ord ($str[$i]);
    $count++;
    $i++;
    if($i >= $len)
      break;
    
    if($chr & 0x80)
    {
      $chr <<= 1;
      while ($chr & 0x80)
      {
        $i++;
        $chr <<= 1;
      }
    }
  }
  return $count;
}

// END strlen_utf8() FUNCTION


// THIS FUNCTION MAKES UTF8 CHARS WORK IN SERIALIZE BY BASICALLY IGNORING THE STRING LENGTH PARAM
// INPUT: $str REPRESENTING THE SERIALIZED STRING
// OUTPUT: THE UNSERIALIZED DATA

function mb_unserialize($serial_str)
{
  $out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
  return unserialize($out);
} 

// END mb_unserialize() FUNCTION


function get_simple_cookie_domain($host = null)
{
  // Quick config
  if( defined('PG_COOKIE_DOMAIN') )
  {
    return PG_COOKIE_DOMAIN;
  }
  
  if( !$host )
  {
    $host = $_SERVER["HTTP_HOST"];
  }
  
  $host = parse_url($host);
  $host = $host['path'];
  $parts = explode('.', $host);
  
  switch( TRUE )
  {
    // Do not use custom for these:
    // IP Address
    case ( preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $host) ):
    // Intranet host
    case ( count($parts) === 1 ):
      return null;
      break;
    
    // Second level ccld
    case ( strlen($parts[count($parts)-1]) == 2 && strlen($parts[count($parts)-2]) <= 3 ):
      array_splice($parts, 0, count($parts) - 3);
      return join('.', $parts);
      break;
    
    // tld or first-level ccld
    default:
      array_splice($parts, 0, count($parts) - 2);
      return join('.', $parts);
  }
  
  return null;
}
function check_ip_in_banned_list($ip, $list)
{
  // We were passed a string address
  if( !is_numeric($ip) )
  {
    $ip = ip2long($ip);
  }

  // Wth it's not an ip!
  if( !$ip )
  {
    trigger_error('That isn\'t an IP address!', E_USER_NOTICE);
    return false;
  }

  // Passed a , separated string
  if( !is_array($list) )
  {
    $list = explode(',', $list);
  }

  // No banned ips
  if( empty($list) )
  {
    return false;
  }

  // Sort into banned specific IPs and ranges
  $banned_ip_addr = array();
  $banned_ip_ranges = array();

  foreach( $list as $banned_ip )
  {
    if( strpos($banned_ip, '*') !== false )
    {
      // Decode
      $tmp_low = ip2long(str_replace('*', '0', $banned_ip));
      $tmp_high = ip2long(str_replace('*', '255', $banned_ip)); // this might not work for some bytes

      // If failed to decode, or low is larger than high, skip
      if( !$tmp_low || !$tmp_high || $tmp_low > $tmp_high )
      {
        continue;
      }
      
      // Add
      $banned_ip_ranges[] = array(
        $tmp_low,
        $tmp_high
      );
    }
    else if( strpos($banned_ip, '-') !== false )
    {
      // Decode
      list($tmp_low, $tmp_high) = explode('-', $banned_ip, 2);
      $tmp_low = ip2long($tmp_low);
      $tmp_high = ip2long($tmp_high);

      // If failed to decode, or low is larger than high, skip
      if( !$tmp_low || !$tmp_high || $tmp_low > $tmp_high )
      {
        continue;
      }

      // Add
      $banned_ip_ranges[] = array(
        $tmp_low,
        $tmp_high
      );
    }
    else
    {
      $tmp = ip2long($banned_ip);
      if( $tmp )
      {
        $banned_ip_addr[] = $tmp;
      }
    }
  }

  // Now check against ip lists
  if( in_array($ip, $banned_ip_addr) )
  {
    return true;
  }

  // Check against IP ranges
  foreach( $banned_ip_ranges as $range )
  {
    if( $ip >= $range[0] && $ip <= $range[1] )
    {
      return true;
    }
  }

  return false;
}
function post_db_parse_html($t=""){
	if ( $t == "" ){
		return $t;
	}

	//-----------------------------------------
	// Remove <br>s 'cos we know they can't
	// be user inputted, 'cos they are still
	// &lt;br&gt; at this point :)
	//-----------------------------------------

	/*		if ( $this->pp_nl2br != 1 )
	{
	$t = str_replace( "<br>"    , "\n" , $t );
	$t = str_replace( "<br />"  , "\n" , $t );
	}*/

	$t = str_replace( "&#39;"   , "'", $t );
	$t = str_replace( "&#33;"   , "!", $t );
	$t = str_replace( "&#036;"  , "$", $t );
	$t = str_replace( "&#124;"  , "|", $t );
	$t = str_replace( "&amp;"   , "&", $t );
	$t = str_replace( "&gt;"    , ">", $t );
	$t = str_replace( "&lt;"    , "<", $t );
	$t = str_replace( "&quot;"  , '"', $t );

	//-----------------------------------------
	// Take a crack at parsing some of the nasties
	// NOTE: THIS IS NOT DESIGNED AS A FOOLPROOF METHOD
	// AND SHOULD NOT BE RELIED UPON!
	//-----------------------------------------

	$t = preg_replace( "/javascript/i" , "j&#097;v&#097;script", $t );
	$t = preg_replace( "/alert/i"      , "&#097;lert"          , $t );
	$t = preg_replace( "/about:/i"     , "&#097;bout:"         , $t );
	$t = preg_replace( "/onmouseover/i", "&#111;nmouseover"    , $t );
	$t = preg_replace( "/onmouseout/i", "&#111;nmouseout"    , $t );
	$t = preg_replace( "/onclick/i"    , "&#111;nclick"        , $t );
	$t = preg_replace( "/onload/i"     , "&#111;nload"         , $t );
	$t = preg_replace( "/onsubmit/i"   , "&#111;nsubmit"       , $t );
	$t = preg_replace( "/object/i"   , "&#111;bject"       , $t );
	$t = preg_replace( "/frame/i"   , "fr&#097;me"       , $t );
	$t = preg_replace( "/applet/i"   , "&#097;pplet"       , $t );
	$t = preg_replace( "/meta/i"   , "met&#097;"       , $t );
	$t = preg_replace( "/embed/i"   , "met&#097;"       , $t );

	return $t;
}

function get_debug_info($id)
{
  	$id = preg_replace('/[^a-zA-Z0-9\._]/', '', $id);
  
  	// Delete logs older than an hour
  	$dh = @opendir(PG_ROOT.DIRECTORY_SEPARATOR.'log');
  	if( $dh )
  	{
	    while( ($file = @readdir($dh)) !== false )
	    {
	      	if( $file == "." || $file == ".." ) continue;
	      	if( filemtime(PG_ROOT.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.$file)>time()-3600 ) continue;
	      	@unlink(PG_ROOT.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.$file);
	    }
  	}
  
  	return file_get_contents(PG_ROOT.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.$id.'.html');
}

function isIE () {
	$msie='/msie/i';
	return isset($_SERVER['HTTP_USER_AGENT']) &&
		preg_match($msie,$_SERVER['HTTP_USER_AGENT']) &&
		!preg_match('/opera/i',$_SERVER['HTTP_USER_AGENT']);
}
	
function createToolbarAce(){
	global $page, $page_title;
	
	$numargs = func_num_args();
	if (!$numargs) return ;
	$arrButtons = array('export' => 'Export',
						'export-all' => 'Export All',
						'import' => 'Import',
						'html' => 'Xuất HTML', 
						'purge' => 'Lọc dữ liệu',
						'publish' => 'Hiển thị', 
						'unpublish' => 'Ẩn', 
						'remove'=>'Xóa', 
						'edit'=>'Sửa',
						'update'=>'Cập nhật', 
						'add'=>'Tạo mới',
						'save'=>'Lưu lại',
						'save2add' => 'Lưu&Thêm',
						'upload' => 'Upload', 
						'apply' => 'Lưu & tiếp',
						'filter' => 'Lọc dữ liệu',
						'cancel'=>'Hủy bỏ',
						'help' => 'Trợ giúp',
						'trash' => 'Thùng rác', 
						'search'=>'Tìm kiếm', 
						'sms'=>'Gửi SMS', 
						'email'=>'Gửi email', 
						'send'=>'Gửi đi',
						'message' => 'Gửi tiếp', 
						'refund'=>'Hoàn tiền',
                        'sync' => 'Đồng bộ'
    );
	$out = '<div style="margin: 6px 0; text-align: right;"><div id="toolbar-box">
				<div class="t"><div class="t"><div class="t"></div></div></div>
				<div class="m">
					<div class="toolbar col-lg-12" id="toolbar">';
	$arg_list = func_get_args();
	for ($i = 0; $i < $numargs; $i++) {
		$button = $arg_list[$i];
		if ($arrButtons[$button]){
			if ($button == 'unpublish'){
				$class = 'btn-inverse';
				$iclass= 'icon-lock';
			}
			else if ($button == 'publish'){
				$class = 'btn-success';
				$iclass= 'icon-unlock';
			}
			else if ($button == 'delete'){
				$class = 'btn-danger';
				$iclass= 'icon-remove';
			}
			else if ($button == 'add'){
				$class = 'btn-info';
				$iclass= 'icon-pencil';
			}
			else if ($button == 'edit'){
				$class = 'btn-primary';
				$iclass= 'icon-edit';
			}
			else if ($button == 'save'){
				$class = 'btn-success btn-round';
				$iclass= 'icon-save';
			}
			else if ($button == 'save2add'){
				$class = 'btn-primary';
				$iclass= 'icon-paste';
			}
			else if ($button == 'cancel'){
				$class = 'btn-purple';
				$iclass= 'icon-undo';
			}
			else if ($button == 'import'){
				$class = 'btn-success';
				$iclass= 'icon-repeat';
			}
			else if ($button == 'export'){
				$class = 'btn-pink';
				$iclass= 'icon-repeat';
			}
			else if ($button == 'export-all'){
				$class = 'btn-warning';
				$iclass= 'icon-refresh';
			}
			else if ($button == 'html'){
				$class = 'btn-purple';
				$iclass= 'icon-download';
			}
			else if ($button == 'purge'){
				$class = 'btn-info';
				$iclass= 'icon-share';
			}
			else if ($button == 'trash'){
				$class = 'btn-purple';
				$iclass= 'icon-trash';
			}
            else if ($button == 'sync'){
              $class = 'btn-warning';
              $iclass= 'icon-refresh';
            }
            else if ($button == 'send'){
              $class = 'btn-success';
              $iclass= 'icon-refresh';
            }
			else{
				$class = 'btn-danger';
				$iclass= 'icon-remove';
			}
			if ($button=='unpublish' || $button=='publish' || $button=='delete' || $button=='edit')
			$out .= '<button onclick="javascript:if(document.adminForm.boxchecked.value==0){alert(\'Xin hãy chọn ít nhất một mục để '.$arrButtons[$button].'\');}else{  submitbutton(\''.$button.'\')}" class="btn btn-app radius-4 '.$class.' btn-xs" style="margin-right: 5px;"><i class="'.$iclass.' bigger-200"></i> '.$arrButtons[$button].'</button>';
			else 
			$out .= '<button onclick="javascript:submitbutton(\''.$button.'\')" class="btn btn-app radius-4 '.$class.' btn-xs" style="margin-right: 5px;"><i class="'.$iclass.' bigger-200"></i> '.$arrButtons[$button].'</button>';
		}
	}
	$out .=			'</div>
					<div class="clr"></div>
				</div>
				<div class="b"><div class="b"><div class="b"></div></div></div>
			</div></div>';
	return $out;
}

function convertMobileNumber($mobinumber){
	$mobinumber = preg_replace('/[^0-9]/', '', $mobinumber);
	return $mobinumber;
	
	if (substr($mobinumber,0,1)==0) return "84".substr($mobinumber,1);
	elseif (substr($mobinumber,0,2)==84) return $mobinumber;
	else return '';
}

function getCity($city_id = null, $ma_tinh = false){
  global $database;

  $where_city = array();
  if ($city_id){
    if ( !$ma_tinh )
      $where_city[] = "id=".$city_id;
    else
      $where_city[] = "ma_tinh=".$city_id;
  }
  $where_city = (count($where_city) ? ' WHERE '.implode(' AND ', $where_city) : '');

  //Cache getCity
  $cacheTime = 2592000; // 30d
  $cacheKey = 'getCityData'. ($city_id ? 'ID:'.$city_id : '');
  $output = CacheLib::get($cacheKey, $cacheTime);
  if ($output) return $output;

  $query = "SELECT * FROM ".TBL_TINH_THANH_PHO.$where_city." ORDER BY ten_tinh ASC";
  $results = $database->db_query($query);
  while ($row = $database->db_fetch_assoc($results)){
    $output[($ma_tinh ? $row['id'] : $row['id'])] = $row;
  }

  CacheLib::set($cacheKey, $output, $cacheTime);
  return $output;
}

function getDistrict($ma_tinh = null, $district_id = null){
  global $database;

  $where_district = array();
  if ($ma_tinh){
    if ( is_array($ma_tinh) )
      $where_district[] = "ma_tinh IN(".implode(",", $ma_tinh).")";
    else if (is_numeric($ma_tinh))
      $where_district[] = "ma_tinh=".$ma_tinh;
  }
  if ($district_id){
    $where_district[] = "id=".$district_id;
  }
  $where_district = (count($where_district) ? ' WHERE '.implode(' AND ', $where_district) : '');

  //Cache getDistrict
  $cacheTime = 2592000; // 30d
  $cacheKey = 'getDistrictData'. ($ma_tinh ? 'CityCode:'.$ma_tinh : '') . ($district_id ? 'DistrictID:'.$district_id : '');
  $output = CacheLib::get($cacheKey, $cacheTime);
  if ($output) return $output;

  $query = $database->db_query("SELECT * FROM ".TBL_QUAN_HUYEN.$where_district." ORDER BY ten_huyen ASC");
  while ($row = $database->db_fetch_assoc($query)){
    $output[ $row['id'] ] = $row;
  }

  if ( REDIS_ON ){
    // Redis ko lưu được data lớn ( chỉ phù hợp lưu info) nên trường hợp nhiều dữ liệu convert sang dạng json cho nhẹ
    $output = json_encode($output);
  }

  CacheLib::set($cacheKey, $output, $cacheTime);

  return $output;
}

function getRegion( $ma_huyen = null, $region_id = null){
  global $database;

  if ($ma_huyen){
    $where_region[] = "ma_huyen=".$ma_huyen;
  }
  if ($region_id){
    $where_region[] = "id=".$region_id;
  }
  $where_region = (count($where_region) ? ' WHERE '.implode(' AND ', $where_region) : '');

  //Cache getDistrict
  $cacheTime = 2592000; // 30d
  $cacheKey = 'getRegion'. ($ma_huyen ? 'DistrictCode:'.$ma_huyen : '') . ($region_id ? 'RegionID:'.$region_id : '');
  $output = CacheLib::get($cacheKey, $cacheTime);
  if ($output) return $output;

  $query = $database->db_query("SELECT * FROM ".TBL_XA_PHUONG.$where_region." ORDER BY ten_xa ASC");
  while ($row = $database->db_fetch_assoc($query)){
    $output[ $row['id'] ] = $row;
  }

  CacheLib::set($cacheKey, $output, $cacheTime);

  return $output;
}

function JsonErr($msg = '', $mixed = array()){
	$arr = array('err' => -1, 'msg' => $msg);
	if(!empty($mixed)){
		$arr = $arr + $mixed;
	}
	return json_encode($arr);
}
function JsonSuccess($msg, $mixed = array()){
	$arr = array('err' => 0, 'msg' => $msg);
	if(!empty($mixed)){
		$arr = $arr + $mixed;
	}
	return json_encode($arr);
}
function convertKhongdau($string)
{
	$trans = array(
	"đ"=>"d","ă"=>"a","â"=>"a","á"=>"a","à"=>"a","ả"=>"a","ã"=>"a","ạ"=>"a",
	"ấ"=>"a","ầ"=>"a","ẩ"=>"a","ẫ"=>"a","ậ"=>"a",
	"ắ"=>"a","ằ"=>"a","ẳ"=>"a","ẵ"=>"a","ặ"=>"a",
	"é"=>"e","è"=>"e","ẻ"=>"e","ẽ"=>"e","ẹ"=>"e",
	"ế"=>"e","ề"=>"e","ể"=>"e","ễ"=>"e","ệ"=>"e","ê"=>"e",
	"í"=>"i","ì"=>"i","ỉ"=>"i","ĩ"=>"i","ị"=>"i",
	"ú"=>"u","ù"=>"u","ủ"=>"u","ũ"=>"u","ụ"=>"u",
	"ứ"=>"u","ừ"=>"u","ử"=>"u","ữ"=>"u","ự"=>"u","ư"=>"u",
	"ó"=>"o","ò"=>"o","ỏ"=>"o","õ"=>"o","ọ"=>"o",
	"ớ"=>"o","ờ"=>"o","ở"=>"o","ỡ"=>"o","ợ"=>"o","ơ"=>"o",
	"ố"=>"o","ồ"=>"o","ổ"=>"o","ỗ"=>"o","ộ"=>"o","ô"=>"o",
	"ú"=>"u","ù"=>"u","ủ"=>"u","ũ"=>"u","ụ"=>"u",
	"ứ"=>"u","ừ"=>"u","ử"=>"u","ữ"=>"u","ự"=>"u","ư"=>"u",
	"ý"=>"y","ỳ"=>"y","ỷ"=>"y","ỹ"=>"y","ỵ"=>"y",
	"Đ"=>"D","Ă"=>"A","Â"=>"A","Á"=>"A","À"=>"A","Ả"=>"A","Ã"=>"A","Ạ"=>"A",
	"Ấ"=>"A","Ầ"=>"A","Ẩ"=>"A","Ẫ"=>"A","Ậ"=>"A",
	"Ắ"=>"A","Ằ"=>"A","Ẳ"=>"A","Ẵ"=>"A","Ặ"=>"A",
	"É"=>"E","È"=>"E","Ẻ"=>"E","Ẽ"=>"E","Ẹ"=>"E",
	"Ế"=>"E","Ề"=>"E","Ể"=>"E","Ễ"=>"E","Ệ"=>"E","Ê"=>"E",
	"Í"=>"I","Ì"=>"I","Ỉ"=>"I","Ĩ"=>"I","Ị"=>"I",
	"Ú"=>"U","Ù"=>"U","Ủ"=>"U","Ũ"=>"U","Ụ"=>"U",
	"Ứ"=>"U","Ừ"=>"U","Ử"=>"U","Ữ"=>"U","Ự"=>"U","Ư"=>"U",
	"Ó"=>"O","Ò"=>"O","Ỏ"=>"O","Õ"=>"O","Ọ"=>"O",
	"Ớ"=>"O","Ờ"=>"O","Ở"=>"O","Ỡ"=>"O","Ợ"=>"O","Ơ"=>"O",
	"Ố"=>"O","Ồ"=>"O","Ổ"=>"O","Ỗ"=>"O","Ộ"=>"O","Ô"=>"O",
	"Ú"=>"U","Ù"=>"U","Ủ"=>"U","Ũ"=>"U","Ụ"=>"U",
	"Ứ"=>"U","Ừ"=>"U","Ử"=>"U","Ữ"=>"U","Ự"=>"U","Ư"=>"U",
	"Ý"=>"Y","Ỳ"=>"Y","Ỷ"=>"Y","Ỹ"=>"Y","Ỵ"=>"Y");
	
	$string = strtr($string, $trans);
	return $string;
}

function generateSlug($phrase, $maxLength)
{
	$result = strtolower($phrase);
	
	$result = preg_replace("/[^a-z0-9\s-]/", "", $result);
	$result = trim(preg_replace("/[\s-]+/", " ", $result));
	$result = trim(substr($result, 0, $maxLength));
	$result = preg_replace("/\s/", "-", $result);
	$result = str_replace("/", "-", $result);
	
	return $result;
}

function generateSlugNotSpace($phrase, $maxLength)
{
	$result = strtolower($phrase);
	
	$result = preg_replace("/[^a-z0-9\s-]/", "", $result);
	$result = trim(preg_replace("/[\s-]+/", " ", $result));
	$result = trim(substr($result, 0, $maxLength));
	$result = preg_replace("/\s/", "", $result);
	$result = str_replace("/", "-", $result);
	
	return $result;
}

function t($string, $args = array()) {
  global $language;
  
  if (is_null($language)) return $string;

  $trans = $language->translate($string);
  
  if (count($args)>0){
    // Transform arguments before inserting them.
    foreach ($args as $key => $value) {
      switch ($key[0]) {
        case '@':
          // Escaped only.
          $args[$key] = check_plain($value);
          break;

        case '!':
          // Pass-through.
      }
    }
    $trans = strtr($trans, $args);
  }
  
  return $trans;
}

//Get full url
function full_url()
{
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
    $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

//Convert time to seconds
function time_to_sec($time) {
    $hours = substr($time, 0, -6);
    $minutes = substr($time, -5, 2);
    $seconds = substr($time, -2);

    return $hours * 3600 + $minutes * 60 + $seconds;
}

//array push with key value
function array_push_key (&$array, $key, $value) {
   $array[$key] = $value;
} 

/*
 * Function search value exist in array
 * $array - array; $searchval - search value;
 */
function search_in_array($array,$searchval){
	if(is_array($array)) {
		foreach($array as $val){ 
		if(is_array($val))
			if(in_array($searchval,$val)) return true;  
		}
	}
	else return false;
}

/*
 * END FUNCTION GET PARAMS (key), GET PARAMS (not key only value) FOR PRODUCTS, TOURS
 */

// Cắt ký tự đầu và cuối của một chuỗi
function Cut_Start_End_String($string, $characters){
	$start_string = substr($string, 0, 1);
	$end_string = substr($string, (strlen($string)-1), 1);
	if ($start_string == $characters){
		$string = substr($string, 1, (strlen($string)-1));
	}
	if ($end_string == $characters){
		$string = substr($string, 0, -1);
	}
	
	return $string;
}

// Cắt chuỗi tiếng việt , cắt hết chữ
function truncat_text($text, $len)
{
	mb_internal_encoding('UTF-8');
	if( (mb_strlen($text, 'UTF-8') > $len) ) {
      $text = mb_substr($text, 0, $len, 'UTF-8');
      $text = mb_substr($text, 0, mb_strrpos($text," ", 'UTF-8'), 'UTF-8');
	}
	return $text;
}

// Hàm lọc domain
function filterDomain($url){
	if (strpos($url, "http://") === false){
		$url = str_replace("http://", "", $url);
	}
	if (strpos($url, "https://") === false){
		$url = str_replace("http://", "", $url);
	}
	if (strpos($url, "www.") === false){
		$url = str_replace("www.", "", $url);
	}
    return $url;
}

/**
 * Delete folder
 */
function Delete($path)
{
    if (is_dir($path) === true)
    {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file)
        {
            Delete(realpath($path) . '/' . $file);
        }

        return rmdir($path);
    }

    else if (is_file($path) === true)
    {
        return unlink($path);
    }

    return false;
}

/**
 * Hàm tính khoảng cách từ 2 vị trí, mỗi vị trí chứa (kinh độ và vĩ độ) 
 */
function haversineGreatCircleDistance( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000 )
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return $angle * $earthRadius;
}

/**
 * Hàm tính khoảng cách từ 2 vị trí, mỗi vị trí chứa (kinh độ và vĩ độ) cho website
 */
function haversineGreatCircleDistanceForWebs($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344) . ' km';
  } else if ($unit == "N") {
      return ($miles * 0.8684) . ' hải lý';
  } else {
        return $miles . ' m';
  }
}

/**
 * Get Client ip 
 */
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
      $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
    else
      $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


/**
 * Functions CURL
 */
function convertParams($aParam){
  $post_items = array();
  foreach ($aParam as $key => $value) {
    $post_items[] = $key . '=' . urlencode($value);
  }
  $post_string = implode ('&', $post_items);
  return $post_string;
}
function doCurlRequest($url, $params=false, $refer=false){
  $ch = curl_init();

  if ($params){
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, convertParams($params));
  }
  if (strpos($url, 'https')===0){
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  }
  if ($refer){
    curl_setopt($ch, CURLOPT_REFERER, $refer);
  }

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
  curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__)."/tmp/cookie.txt");
  curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__)."/tmp/cookie.txt");

  $html = curl_exec ($ch);
  if ($html === false){
    echo 'Curl error: ' . curl_error($ch);
  }

  curl_close($ch);

  return $html;
}

/**
 * POST LINK IMAGE TO SERVER MEDIA
 * REMOVE LINK IMAGE FROM SERVER WEB
 */
function post_file_to_media( $files ){
    $session_object =& PGSession::getInstance();
    $ten_dang_nhap 	= $session_object->get('ten_dang_nhap');
    $key_index 		= $session_object->get('key_index');
    $password  		= $session_object->get('password');

    $post_url = "http://media.enetviet.com/upload";

    // B1: upload len server media (dung curl)
    $real_file = $_SERVER['DOCUMENT_ROOT'] . $files;
    $post_data = array(
        'file_contents' => '@' . $real_file,
        'a' => $key_index,
        'u' => $ten_dang_nhap,
        'p' => $password,
        'submit' => 'true',
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $post_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);

//    if(!curl_errno($ch))
//    {
//      $info = curl_getinfo($ch);
//      if ($info['http_code'] == 200)
//        $errmsg = "File uploaded successfully";
//      $errmsg = '';
//    }
//    else
//    {
//      $errmsg = curl_error($ch);
//    }
    curl_close($ch);

    // B2: delete file story temp di
    if (file_exists($real_file)) {
      unlink($real_file);
    }

    return 1;
}

/*
 * Random color
 */
function random_color_part() {
  return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
  return random_color_part() . random_color_part() . random_color_part();
}

/*
 * Check if a string is a URL
 */
function check_is_url( $string ){
  $regex = "((https?|ftp)\:\/\/)?"; // SCHEME
  $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
  $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
  $regex .= "(\:[0-9]{2,5})?"; // Port
  $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
  $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
  $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor

  if(preg_match("/^$regex$/", $string))
  {
    return true;
  }
  return false;
}

/**
 *
 * Detect url in string
 */
function makeLink($string){

  /*** make sure there is an http:// on all URLs ***/
  $string = preg_replace("/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i", "$1http://$2",$string);
  /*** make all URLs links ***/
  $string = preg_replace("/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i","<a target=\"_blank\" href=\"$1\">$1</a>",$string);
  /*** make all emails hot links ***/
  $string = preg_replace("/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i","<a href=\"mailto:$1\">$1</a>",$string);

  return $string;
}

/**
 * Lấy số ngày trong tuần
 */
function getNumberDayInWeek(){
  $current_date = date('Y-m-d H:i:s', time());
  $number = date('w', strtotime($current_date));
  return $number;
}

/**
 * Lấy tên ngày từ số ngày trong tuần
 */
function getDayNameFromDayNumber($day_number) {
  return date('l', strtotime("Sunday +{$day_number} days"));
}
/**
 * Lấy số tuần hiện tại của tháng
 */
function get_current_week ($date, $rollover){
  $cut = substr($date, 0, 8);
  $daylen = 86400;

  $timestamp = strtotime($date);
  $first = strtotime($cut . "00");
  $elapsed = ($timestamp - $first) / $daylen;

  $weeks = 1;

  for ($i = 1; $i <= $elapsed; $i++)
  {
    $dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
    $daytimestamp = strtotime($dayfind);

    $day = strtolower(date("l", $daytimestamp));

    if($day == strtolower($rollover))  $weeks ++;
  }

  return $weeks;
}

/**
 * Lấy tháng hiện tại
 */
function get_current_month (){
    return intval(date('m', time()));
}

/**
 * Lấy năm hiện tại
 */
function get_current_year (){
    return intval(date('Y', time()));
}

/**
 * Lấy mã năm hiện tại
 */
function get_code_current_year (){
  $current_month = get_current_month();
  $ThangDauKy2 = SET_THANG_DAU_KY;
  $ThangCuoiKy2 = SET_THANG_CUOI_KY;

  $ma_nam_hien_tai = intval(date('Y', time()));

  if ( $current_month <= $ThangCuoiKy2){
    $ma_nam_hien_tai--;
  }
  return $ma_nam_hien_tai;
}

/**
 * Lấy học kỳ hiện tại theo tháng
 */
function get_current_semester(){
    $hoc_ky = 1;
    $ThangDauKy2 = SET_THANG_DAU_KY;
    $ThangCuoiKy2 = SET_THANG_CUOI_KY;

    $current_year = get_current_year();
    $current_month = get_current_month();

    if ( $current_month <= $ThangCuoiKy2)
        $current_year--;

    if ( $current_month >= $ThangDauKy2 && $current_month <= $ThangCuoiKy2)
      $hoc_ky = 2;

    return $hoc_ky;
}

/**
 * SO SÁNH 2 MẢNG CÓ GIỐNG NHAU KHÔNG
 */
function keys_are_equal($array1, $array2) {
  return !array_diff_key($array1, $array2) && !array_diff_key($array2, $array1);
}

function mosArrayToInts( &$array, $default=null ) {
  if (is_array( $array )) {
    foreach( $array as $key => $value ) {
      $array[$key] = (int) $value;
    }
  } else {
    if (is_null( $default )) {
      $array = array();
      return array(); // Kept for backwards compatibility
    } else {
      $array = array( (int) $default );
      return array( $default ); // Kept for backwards compatibility
    }
  }
}

/**
 * Convert an object to an array
 */
function objectToArray( $object )
{
  if( !is_object( $object ) && !is_array( $object ) )
  {
    return $object;
  }
  if( is_object( $object ) )
  {
    $object = get_object_vars( $object );
  }
  return array_map( 'objectToArray', $object );
}

/**
 * Check is_html
 */
function is_html($string)
{
  return preg_match("/<[^<]+>/",$string,$m) != 0;
}

/**
 * Check isJSON
 */
function isJSON($string){
  return is_string($string) && is_array(json_decode($string, true)) ? true : false;
}
/**
 * Get random number thats not in array [duplicate]
 */
function getRandomNumberOnly($min, $max, $inArray){
    do {
        $rand = rand($min, $max);
    } while(in_array($rand, $inArray));
    return $rand;
}

/**
 * Count char repeat in string
 */
function countChar($string,$c){
  $numChars = preg_match_all("/$c/", $string, $num);
  return count($num[0]);
}

/**
 * function update school status = 2
 * Đồng bộ dữ liệu xảy ra thiếu sót
 */
function update_school_missing ( $code, $check_ma_truong = false, $cap_hoc = false ){
  global $database;

  if ( !$code ) return false;

  $where = '';
  if ( !$check_ma_truong ){
    if ( is_numeric($code) )
      $where = ' WHERE id='.$code;
    else
      $where = ' WHERE ma_truong="'.$code.'" AND cap_hoc = '.$cap_hoc;
  }else
    $where = ' WHERE ma_truong="'.$code.'"';

  $query = "UPDATE ".TBL_NHA_TRUONG." SET status=2" . $where;
  if ( $database->db_query($query) )
    return true;
  else
    return false;
}

/**
 * * CURL POST DATA
 */
function curl_post_to_url($url, $data) {
  $fields = '';
  foreach ($data as $key => $value) {
    $fields .= $key . '=' . $value . '&';
  }
  rtrim($fields, '&');

  $post = curl_init();

  curl_setopt($post, CURLOPT_URL, $url);
  curl_setopt($post, CURLOPT_POST, count($data));
  curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
  curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

  $result = curl_exec($post);

  curl_close($post);
  return $result;
}

function v4()
{
  return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),

      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,

      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
  );
}

/**
 * CURL POST AND GET
 */
/**
 * GET
 * example: echo httpGet("http://hayageek.com");
 */
function httpGet($url)
{
  $ch = curl_init();

  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//  curl_setopt($ch,CURLOPT_HEADER, false);

  $output=curl_exec($ch);

  curl_close($ch);
  return $output;
}

/**
 * POST
 * example:
 * $params = array(
    "name" => "Ravishanker Kusuma",
    "age" => "32",
    "location" => "India"
  );
  echo httpPost("http://hayageek.com/examples/php/curl-examples/post.php",$params);
 */
function httpPost($url,$params)
{
  $postData = '';
  //create name value pairs seperated by &
  foreach($params as $k => $v)
  {
    $postData .= $k . '='.$v.'&';
  }
  $postData = rtrim($postData, '&');

  $ch = curl_init();

  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
  curl_setopt($ch,CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_POST, count($postData));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

  $output=curl_exec($ch);

  curl_close($ch);
  return $output;

}

/**
 * Check on localhost
 */
function check_on_localhost(){
  $whitelist = array(
      '127.0.0.1',
      '::1'
  );

  if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    // not valid
    return false;
  }
  return true;
}

/**
 * Random code password
 */
function getNumbers($min=1,$max=10,$count=1,$margin=0) {
  $range = range(0,$max-$min);
  $return = array();
  for( $i=0; $i<$count; $i++) {
    if( !$range) {
      trigger_error("Not enough numbers to pick from!",E_USER_WARNING);
      return $return;
    }
    $next = rand(0,count($range)-1);
    $return[] = $range[$next]+$min;
    array_splice($range,max(0,$next-$margin),$margin*2+1);
  }
  return $return;
}

/*
 * Create a random string
 * @author	XEWeb <>
 * @param $length the length of the string to create
 * @return $str the string
 */
function randomString($length = 6) {
  $str = "";
  $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
  $max = count($characters) - 1;
  for ($i = 0; $i < $length; $i++) {
    $rand = mt_rand(0, $max);
    $str .= $characters[$rand];
  }
  return $str;
}

/**
 * Display price
 */
function display_price($price_old, $price_new, $discount, $date_end = false){
  if ( $date_end ){
    if ( strtotime($date_end) < time() ){
      $aryDisplayPrice['price_display'] = number_format($price_old, 0, '.', ',' );
      $aryDisplayPrice['price_brick'] = number_format($price_old, 0, '.', ',' );
      return $aryDisplayPrice;
    }
  }

  $price_display = 0;
  $price_brick = 0;
  if ( $price_old && $price_new ){
    if ( $price_old > $price_new ){
      $price_display = $price_new;
      $price_brick = $price_old;
      $aryDisplayPrice['discount'] = $discount;
    }else{
      $price_display = $price_old;
    }
  }else{
    if ( !$price_new ){
      $price_display = $price_old;
    }
    if ( !$price_old ){
      $price_display = $price_new;
    }
  }

  if ( $price_display ){
    $aryDisplayPrice['price_display'] = number_format($price_display, 0, '.', ',' );
  }
  if ( $price_brick ){
    $aryDisplayPrice['price_brick'] = number_format($price_brick, 0, '.', ',' );
  }
  if ( isset($aryDisplayPrice) && is_array($aryDisplayPrice))
    return $aryDisplayPrice;
  else
    return false;
}

/**
 * convert price
 */
function convert_price($price_old, $price_new, $discount, $prefix, $date_end = false){
  $aryDisplayPrice = array(
      'price_display'	=> '',
      'price_brick' 	=> '',
      'discount'		=> 0,
      'prefix'		=> $prefix
  );

  if ( $date_end ){
    if ( strtotime($date_end) < time() ){
      $aryDisplayPrice['price_display'] = number_format($price_old, 0, '.', ',' );
      $aryDisplayPrice['price_brick'] = number_format($price_old, 0, '.', ',' );
      $aryDisplayPrice['discount'] = 0;
      $aryDisplayPrice['prefix'] = $prefix;
      return $aryDisplayPrice;
    }
  }

  $price_display = 0;
  $price_brick = 0;
  if ( $price_old && $price_new ){
    if ( $price_old > $price_new ){
      $price_display = $price_new;
      $price_brick = $price_old;
      $aryDisplayPrice['discount'] = $discount;
    }else{
      $price_display = $price_old;
    }
  }else{
    if ( !$price_new ){
      $price_display = $price_old;
    }
    if ( !$price_old ){
      $price_display = $price_new;
    }
  }
  $price_display = $price_display/1000;
  $price_brick = $price_brick/1000;
  if ( $price_display ){
    $aryDisplayPrice['price_display'] = number_format($price_display, 0, '.', '' ) . $prefix;
  }
  if ( $price_brick ){
    $aryDisplayPrice['price_brick'] = number_format($price_brick, 0, '.', '' ) . $prefix;
  }

  return $aryDisplayPrice;
}

/**
 * Auto generate code
 */
function get_random_code($string =  false){
    $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    if ( $string ){
      $chars = $string;
    }
    $res = "";
    for ($i = 0; $i < 10; $i++) {
        $res .= $chars[mt_rand(0, strlen($chars)-1)];
    }
    return $res;
}
function get_random_code_number(){
  $chars = "0123456789";
  $res = "";
  for ($i = 0; $i < 10; $i++) {
    $res .= $chars[mt_rand(0, strlen($chars)-1)];
  }
  return $res;
}
function generate_code ($table_key = false, $string = false, $number_only = false)
{
	global $database;

    $s =  ( $number_only ? get_random_code_number() : get_random_code($string) );

    if ( !$table_key || !is_array($table_key) ) return $s;

    $total = 0;
    $_query = "SELECT COUNT(*) AS total FROM ".$table_key['name']." WHERE ".$table_key['key']." = '{$s}'";
    $_result = $database->db_query($_query);
    if ( $_row = $database->db_fetch_assoc($_result) ){
        $total = $_row['total'];
	}

    // check if code is already in db
    if ( $total ) {
        $s = generate_code($table_key, $string, $number_only);
    }

    return $s;
}
?>

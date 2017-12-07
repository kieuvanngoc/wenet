<?php
defined('PG_PAGE') or die();

//
//  THIS CLASS CONTAINS DATABASE-RELATED METHODS
//  IT IS USED TO CONNECT TO THE DATABASE, RUN QUERIES, AND REPORT ERRORS
//  CURRENTLY MySQL IS THE DATABASE TYPE, EVENTUALLY ADD SUPPORT FOR OTHER DATABASE TYPES
//
//  METHODS IN THIS CLASS:
//
//    se_database()
//    database_connect()
//    database_select()
//    database_query()
//    database_fetch_array()
//    database_fetch_assoc()
//    database_num_rows()
//    database_affected_rows()
//    database_set_charset()
//    database_real_escape_string()
//    database_insert_id()
//    database_error()
//    database_close()
//


define('PG_DATABASE_LOG_SUCCESS',   1);
define('PG_DATABASE_LOG_FAIL',      2);
define('PG_DATABASE_LOG_SLOW',      4);
define('PG_DATABASE_LOG_FAST',      8);
define('PG_DATABASE_LOG_ALL',       15);

define('PG_DATABASE_LOGOPTS_QUERY',     1);
define('PG_DATABASE_LOGOPTS_TIME',      2);
define('PG_DATABASE_LOGOPTS_BACKTRACE', 4);
define('PG_DATABASE_LOGOPTS_RESULT',    8);
define('PG_DATABASE_LOGOPTS_COUNT',     16);
define('PG_DATABASE_LOGOPTS_ERROR',     32);
define('PG_DATABASE_LOGOPTS_ALL',       63);

define('SEND_SMS_MYSQL_ERROR', 0);

class PGDatabase
{
	// INITIALIZE VARIABLES
	var $database_connection; // VARIABLE REPRESENTING DATABASE LINK IDENTIFIER
  
  	var $_last_query;
  
  	var $_last_resource;
  	static $db_num_queries 	= 0;
  
  	// DEBUG VARIABLES
	var $log_stats = NULL; // VARIABLE DETERMINING WHETHER QUERY INFO SHOULD BE LOGGED
  	var $log_trigger = PG_DATABASE_LOG_ALL;
  	var $log_options = PG_DATABASE_LOGOPTS_ALL;
  
	var $log_data = array(); // ARRAY CONTAINING RELEVANT INFORMATION ABOUT QUERIES RUN
	var $log_data_totals = array(               // ARRAY CONTAINING RELEVANT INFORMATION ABOUT QUERIES RUN
	    'total' => 0,
	    'time' => 0,
	    'success' => 0,
	    'failed' => 0,
	    'count' => 0
  	);
  	var $log_slow_threshold = '0.05';
  
  	// 1 - Index by query hash, 2 - index by backtrace
  	var $log_stats_index_mode = 2;
  
  	var $root_folder;
  
  	var $query_thresholds = array(
	    '0.0001'  => '#00ff00',
	    '0.001'   => '#55ff00',
	    '0.005'   => '#aaff00',
	    '0.05'    => '#ffff00',
	    '0.5'     => '#ffaa00',
	    '1'       => '#ff5500',
	    '99999'   => '#ff0000'
  	);

  	//
	// THIS METHOD CONNECTS TO THE SERVER AND SELECTS THE DATABASE
  	//
	// INPUT:
  	//    $database_host REPRESENTING THE DATABASE HOST
	//	  $database_username REPRESENTING THE DATABASE USERNAME
	//	  $database_password REPRESENTING THE DATABASE PASSWORD
	//	  $database_name REPRESENTING THE DATABASE NAME
  
	function PGDatabase($database_host, $database_username, $database_password, $database_name)
  	{
	    global $user, $global_plugins;

	    // GET THE PAYMENTGATEWAY ROOT
	    $this->root_folder = dirname(dirname(realpath(__FILE__)));
	    
	    // FORCE TIME AND QUERY LOGGING (IF ENABLED)
	    if( is_null($this->log_stats) ) $this->log_stats = ( defined('PG_DEBUG') ? PG_DEBUG : FALSE );   
	    $this->log_options = ( $this->log_options | PG_DATABASE_LOGOPTS_TIME | PG_DATABASE_LOGOPTS_QUERY );

		$this->db_connection = $this->db_connect($database_host, $database_username, $database_password);
		if ( !$this->db_connection ){
			if ( (strpos(PG_URL_ROOT, 'dev.enetviet.com')!==false) || (strpos(PG_URL_ROOT, 'enetviet.com')!==false) ){
				$text = 'Loi ket noi toi server mysql: '. $database_host . ' vao luc :' . date("Y-m-d H:i:s", time());

				if ( SEND_SMS_MYSQL_ERROR ){
					// Include SMS
					include (dirname(__FILE__) . '/class_sendsms.php');
					$SMS = new PGSendSMS();
//						$phone = array('0978686055');
//						$SMS->send($phone, $text);
					$phone = '0978686055';
					$SMS->send_sms_error($phone, $text, true);
				}
			}
			$this->db_error();
			die();
		}
		$this->db_select($database_name) or die($this->db_error());
	    
	    // This will prevent some problems on MySQL5+ servers
	    mysql_query("SET sql_mode='MYSQL40'", $this->db_connection);
	}
  	// END PGDatabase() METHOD

  	//
	// THIS METHOD CONNECTS TO THE SERVER AND SELECTS THE DATABASE
  	//

	function &getInstance()
  	{
	    global $database, $database_host, $database_username, $database_password, $database_name;
	    static $db;
	    
	    if( !is_a($db, 'PGDatabase') )
	    {
	      	// Backwards compatibility
	      	if( is_a($database, 'PGDatabase') )
	      	{
	        	$db =& $database;
	      	}
	      
	      	// Instantiate
	      	else
	      	{
		        $db = new PGDatabase($database_host, $database_username, $database_password, $database_name);
		        $database =& $db;
	      	}
	    }
    
    	return $db;
  	}
  	// END getInstance() METHOD


  	//
	// THIS METHOD CONNECTS TO A DATABASE SERVER
  	//
	// INPUT:
  	//    $database_host REPRESENTING THE DATABASE HOST
	//	  $database_username REPRESENTING THE DATABASE USERNAME
	//	  $database_password REPRESENTING THE DATABASE PASSWORD
  	//
	// OUTPUT:
  	//    RETURNS A DATABASE LINK IDENTIFIER
  	//
  
	function db_connect($database_host, $database_username, $database_password)
  	{
	  	return @mysql_connect($database_host, $database_username, $database_password, TRUE);
	}
  
  	// END database_connect() METHOD

  	//
	// THIS METHOD SELECTS A DATABASE
  	//
	// INPUT:
  	//    $database_name REPRESENTING THE DATABASE NAME
  	//
	// OUTPUT:
  	//    RETURNS OUTPUT FOR DATABASE SELECTION
  	//
  
	function db_select($database_name)
  	{
	  	return mysql_select_db($database_name, $this->db_connection);
	} 
  
  	// END database_select() METHOD
    
    /**
    * Helper function for db_query().
    */
    function _db_query_callback($match, $init = FALSE) {
        static $args = NULL;
        
        if ($init) {
            $args = $match;
            return;
        }
    
        switch ($match[1]) {
            case '%d': // We must use type casting to int to convert FALSE/NULL/(TRUE?)
                $value = array_shift($args);
                // Do we need special bigint handling?
                if ($value > PHP_INT_MAX) {
                    $precision = ini_get('precision');
                    @ini_set('precision', 16);
                    $value = sprintf('%.0f', $value);
                    @ini_set('precision', $precision);
                }else {
                    $value = (int) $value;
                }
                // We don't need db_escape_string as numbers are db-safe.
                return $value;
            case '%s':
                return $this->db_real_escape_string(array_shift($args));
            case '%n':
              // Numeric values have arbitrary precision, so can't be treated as float.
              // is_numeric() allows hex values (0xFF), but they are not valid.
              $value = trim(array_shift($args));
              return is_numeric($value) && !preg_match('/x/i', $value) ? $value : '0';
            case '%%':
                return '%';
            case '%f':
              return (float) array_shift($args);
        }
    }


	// THIS METHOD QUERIES A DATABASE
	// INPUT: $database_query REPRESENTING THE DATABASE QUERY TO RUN
	// OUTPUT: RETURNS A DATABASE QUERY RESULT RESOURCE
	function db_query($database_query)
  	{
  		$rtime = microtime();
		$rtime = explode(" ",$rtime);
		$rtime = $rtime[1] + $rtime[0];
		$start_time = $rtime;
        
        // DzungDH: build query
        $args = func_get_args();
        array_shift($args);
        if (isset($args[0]) and is_array($args[0])) { // 'All arguments in one array' syntax
            $args = $args[0];
        }
        if (count($args)>0){
            $this->_db_query_callback($args, TRUE);
            $database_query = preg_replace_callback('/(%d|%s|%%|%f|%n)/', array(&$this, '_db_query_callback'), $database_query);
        }
		
	    // EXECUTE QUERY
	    $query_timer_start = getmicrotime();
	    $query_result = mysql_query($database_query, $this->db_connection);
	    $query_timer_end = getmicrotime();
	    $query_timer_total = round($query_timer_end-$query_timer_start, 7);
	    
	    // LAST QUERY INFO
	    $this->_last_query = $database_query;
	    $this->_last_resource = $query_result;
		  
	    // RETURN IF NOT LOGGING STATS
	    switch( TRUE )
	    {
	      	case (!$this->log_stats):
	      	case ( $query_result && (PG_DATABASE_LOG_SUCCESS & ~ $this->log_trigger)):
	      	case (!$query_result && (PG_DATABASE_LOG_FAIL    & ~ $this->log_trigger)):
	      	case ($query_timer_total< $this->log_slow_threshold && (PG_DATABASE_LOG_FAST    & ~ $this->log_trigger)):
	      	case ($query_timer_total>=$this->log_slow_threshold && (PG_DATABASE_LOG_SLOW    & ~ $this->log_trigger)):
	        	return $query_result;
	      	break;
	    }
	    
	    // STATS
	    $log_data = array('index' => count($this->log_data));
	    $this->log_data_totals['total']++;
	    
	    // QUERY
	    if( $this->log_options & PG_DATABASE_LOGOPTS_QUERY )
	    {
	      	// When making hash, remove timestamps
	      	$log_data['query_hash']  = md5(preg_replace('/\d{10}/', '', $database_query));
	      	$log_data['query']  = $database_query;
	    }
	    
	    // TIME
	    if( $this->log_options & PG_DATABASE_LOGOPTS_TIME )
	    {
	      	$log_data['time']   = $query_timer_total;
	      	$this->log_data_totals['time'] += $query_timer_total;
	    }
	    
	    // BACKTRACE
	    if( $this->log_options & PG_DATABASE_LOGOPTS_BACKTRACE )
	    {
	      	$backtrace = debug_backtrace();
	      	foreach( $backtrace as $backtrace_index=>$single_backtrace )
	      	{
	        	if( !empty($backtrace[$backtrace_index]['file']) )
	        	{
	          		$backtrace[$backtrace_index]['file_short'] = str_replace($this->root_folder, '', $backtrace[$backtrace_index]['file']);
	        	}
	      	}
	      	$log_data['backtrace']   = &$backtrace;
	    }
	    
	    // RESULT
	    if( $this->log_options & PG_DATABASE_LOGOPTS_RESULT )
	    {
	      	$log_data['result']   = ( $query_result ? TRUE : FALSE );
	      
	      	if( $query_result ) $this->log_data_totals['success']++;
	      	else $this->log_data_totals['failed']++;
	    }
	    
	    // COUNT
	    if( $this->log_options & PG_DATABASE_LOGOPTS_COUNT )
	    {
	      	$result_count = 0;
	      
	      	if( $query_result && !$result_count ) $result_count = $this->db_affected_rows();
	      
	      	if( $query_result && !$result_count ) $result_count = $this->db_num_rows($query_result);
	      
	      	$log_data['count']   = $result_count;
	    }
	    
	    // GET ERROR
	    if( $this->log_options & PG_DATABASE_LOGOPTS_ERROR )
	    {
	      	$log_data['error'] = ( $query_result ? FALSE : $this->db_error() );
	    }
	    
  		// SENT MAIL IF HAS ERROR
      	if ($query_result==false){
        	$backtrace = debug_backtrace();
        	$log = array();
	      	foreach( $backtrace as $backtrace_index=>$single_backtrace ){
	          	if( !empty($single_backtrace['file']) ){
	            	$log[$backtrace_index] = sprintf("Backtrace %d: file %s - function %s at line %s", $backtrace_index, str_replace($this->root_folder, '', $single_backtrace['file']), $single_backtrace['function'], $single_backtrace['line']);
	        	}
	      	};

			$content = PG_URL_ROOT."<br/>".implode("<br/>", $log)."<br>".$database_query."<br />".$this->db_error();

			if ( (strpos(PG_URL_ROOT, 'dev.enetviet.com')!==false) || (strpos(PG_URL_ROOT, 'enetviet.com')!==false)){
				if ( SEND_SMS_MYSQL_ERROR ){
					// Include SMS
					include(dirname(__FILE__) . '/class_sendsms.php');
					$SMS = new PGSendSMS();
					$phone = '0978686055';
					//$SMS->send_sms_error($phone, $content, true);

					// SEND EMAIL
					$subject = 'Lỗi query';
					$email = 'kieungoc@quangich.com';
					$SMS->send_email_error($email, $subject, $content, true);

					//send_email(array("kieungoc@quangich.com"), '', "Lỗi query", $content);
				}
			}
      	}
	    
	    // GET THRESHOLD COLOR
	    foreach( $this->query_thresholds as $threshold_time=>$threshold_color )
	    {
	      	if( (float)$query_timer_total>(float)$threshold_time ) continue;
	      
	      	$log_data['color'] = $threshold_color;
	      	break;
	    }
	    
	    // ADD TO LOG
	    $this->log_data[] = $log_data;
	    
	    self::$db_num_queries++;
		if (isset($_REQUEST["debug"]) && intval($_REQUEST["debug"]) > 0) {
			/*if(class_exists('Module') && Module::$name!=''){
				$module_name = Module::$name;
			}
			else{*/								
				$module_name = "-- system";
			//}
			$effect_rows = mysql_affected_rows($this->db_connection);
			$rtime = microtime();
			$rtime = explode(" ",$rtime);
			$rtime = $rtime[1] + $rtime[0];
			$end_time = $rtime;
			$doing_time = round(($end_time - $start_time),5)."s";
	    	
        	if ( preg_match( "/^select/i", $database_query ) ){
				$eid = mysql_query("EXPLAIN $database_query", $this->db_connection);
			
				CGlobal::$query_debug .= "<table width='95%' border='1' cellpadding='6' cellspacing='0' bgcolor='#FFE8F3' align='center'>
									<tr>
									      <td colspan='8' style='font-size:14px' bgcolor='#FFC5Cb'><b>Select Query</b> -- Module : <span style='color:#FF8B00;font-weight:bold'>$module_name</span>".($call_pos?"<br /><b>Run at:</b> $call_pos<br />":"")."</td>
									</tr>
									<tr>
									 <td colspan='8' style='font-family:courier, monaco, arial;font-size:14px;color:black'>$database_query</td>
									</tr>
									<tr bgcolor='#FFC5Cb'>
									      <td><b>table</b></td><td><b>type</b></td><td><b>possible_keys</b></td>
									      <td><b>key</b></td><td><b>key_len</b></td><td><b>ref</b></td>
									      <td><b>rows</b></td><td><b>Extra</b></td>
									</tr>\n";
				while( $array = mysql_fetch_array($eid) ){
				     $type_col = '#FFFFFF';
				     
				     if ($array['type'] == 'ref' or $array['type'] == 'eq_ref' or $array['type'] == 'const'){
					     $type_col = '#D8FFD4';
				     }
				     else if ($array['type'] == 'ALL'){
					     $type_col = '#FFEEBA';
				     }
				     
				     CGlobal::$query_debug .= "<tr bgcolor='#FFFFFF'>
										      <td>$array[table]&nbsp;</td>
										      <td bgcolor='$type_col'>$array[type]&nbsp;</td>
										      <td>$array[possible_keys]&nbsp;</td>
										      <td>$array[key]&nbsp;</td>
										      <td>$array[key_len]&nbsp;</td>
										      <td>$array[ref]&nbsp;</td>
										      <td>$array[rows]&nbsp;</td>
										      <td>$array[Extra]&nbsp;</td>
										</tr>\n";
				}
				
				CGlobal::$query_time += $doing_time;
				
				if ($doing_time > 0.1){
					$doing_time = "<span style='color:red'><b>$doing_time</b></span>";
				}
				
				CGlobal::$query_debug .= "<tr>
										  <td colspan='8' bgcolor='#FFD6DC' style='font-size:14px'><b>MySQL time</b>: $doing_time</b></td>
										  </tr>
										  </table>\n<br />\n";
			}
			else{
				CGlobal::$query_debug .= "<table width='95%' border='1' cellpadding='6' cellspacing='0' bgcolor='#FEFEFE'  align='center'>
										 <tr>
										  <td style='font-size:14px' bgcolor='#EFEFEF'><b>Non Select Query</b> -- Module : <span style='color:#FF8B00;font-weight:bold'>$module_name</span>".($call_pos?"<br /><b>Run at:</b> $call_pos":"")."</td>
										 </tr>
										 <tr>
										  <td style='font-family:courier, monaco, arial;font-size:14px'>$database_query</td>
										 </tr>
										 <tr>
										  <td style='font-size:14px' bgcolor='#EFEFEF'><b>MySQL time</b>: $doing_time</span></td>
										 </tr>
										</table><br />\n\n";
			}
		}
	    
	    // RETURN
		return $query_result;
	}
  
  	// END database_query() METHOD
  
  	//
	// THIS METHOD FETCHES A ROW AS A NUMERIC ARRAY
  	//
	// INPUT:
  	//    $database_result REPRESENTING A DATABASE QUERY RESULT RESOURCE
  	//
	// OUTPUT:
  	//    RETURNS A NUMERIC ARRAY FOR A DATABASE ROW
  	//
  
	function db_fetch_array($database_result)
  	{
    	if( !is_resource($database_result) ) return FALSE;
	  	return mysql_fetch_array($database_result, MYSQL_NUM);
	}
  
  	// END database_fetch_array() METHOD


  	//
	// THIS METHOD FETCHES A ROW AS AN ASSOCIATIVE ARRAY
  	//
	// INPUT:
  	//    $database_result REPRESENTING A DATABASE QUERY RESULT RESOURCE
  	//
	// OUTPUT:
  	//    RETURNS AN ASSOCIATIVE ARRAY FOR A DATABASE ROW
  	//
  
	function db_fetch_assoc($database_result)
  	{
    	if( !is_resource($database_result) ) return FALSE;
	  	return mysql_fetch_assoc($database_result);
	}
  
  	// END database_fetch_assoc() METHOD
    
    //
	// THIS METHOD FETCHES A ROW AS AN OBJECT
  	//
	// INPUT:
  	//    $database_result REPRESENTING A DATABASE QUERY RESULT RESOURCE
  	//
	// OUTPUT:
  	//    RETURNS AN OBJECT FOR A DATABASE ROW
  	//
  
	function db_fetch_object($database_result)
  	{
    	if( !is_resource($database_result) ) return FALSE;
	  	return mysql_fetch_object($database_result);
	}
  
  	// END database_fetch_object() METHOD

  	//
	// THIS METHOD RETURNS THE NUMBER OF ROWS IN A RESULT
  	//
	// INPUT:
  	//    $database_result REPRESENTING A DATABASE QUERY RESULT RESOURCE
  	//
	// OUTPUT:
  	//    RETURNS THE NUMBER OF ROWS IN A RESULT
  	//
  
	function db_num_rows($database_result)
  	{
    	if( !is_resource($database_result) ) return FALSE;
	  	return mysql_num_rows($database_result);
	}
  
  	// END database_num_rows() METHOD

  	//
	// THIS METHOD RETURNS THE NUMBER OF ROWS IN A RESULT
  	//
	// INPUT:
  	//    $database_result REPRESENTING A DATABASE QUERY RESULT RESOURCE
  	//
	// OUTPUT:
  	//    RETURNS THE NUMBER OF ROWS IN A RESULT
  	//
  
	function db_affected_rows()
  	{
	  	return mysql_affected_rows($this->db_connection);
	}
  
  	// END database_affected_rows() METHOD 
  	
  	//
	// THIS METHOD FREES THE RESULT
  	//
	// INPUT:
  	//    $database_result REPRESENTING A DATABASE QUERY RESULT RESOURCE
  	//
	// OUTPUT:
  	//    TRUE on success, else FALSE
  	//
  
	function db_free_result($database_result)
  	{
	  	return mysql_free_result($database_result);
	}
  
  	// END database_free_result() METHOD 


  	//
	// THIS METHOD SETS THE CLIENT CHARACTER SET FOR THE CURRENT CONNECTION
  	//
	// INPUT:
  	//    $charset REPRESENTING A VALID CHARACTER SET NAME
  	//
	// OUTPUT:
  	//    RESOURCE OR FALSE
  	//
  
	function db_set_charset($charset)
  	{
	  	if( function_exists('mysql_set_charset') === TRUE )
    	{
	    	return mysql_set_charset($charset, $this->db_connection);
	  	}
    	else
    	{
	    	return $this->db_query('SET NAMES "'.$charset.'"');
	  	}
	}
  
  	// END database_set_charset() METHOD 

  	//
	// THIS METHOD ESCAPES SPECIAL CHARACTERS IN A STRING FOR USE IN AN SQL STATEMENT
  	//
	// INPUT:
  	//    $unescaped_string REPRESENTING THE STRING TO ESCAPE
  	//
	// OUTPUT: 
  	//    Escaped string
  	//
  
	function db_real_escape_string($unescaped_string)
  	{
	  	return mysql_real_escape_string($unescaped_string, $this->db_connection);
	}
  
  	// END database_real_escape_string() METHOD 


  	//
	// THIS METHOD RETURNS THE ID GENERATED FROM THE PREVIOUS INSERT OPERATION
  	//
	// INPUT: 
  	//    void
  	//
	// OUTPUT:
  	//    RETURNS THE ID GENERATED FROM THE PREVIOUS INSERT OPERATION
  	//
  
	function db_insert_id()
  	{
	  	return mysql_insert_id($this->db_connection);
	}
  
  	// END database_insert_id() METHOD

  	//
	// THIS METHOD RETURNS THE DATABASE ERROR
  	//
	// INPUT: 
  	//    void
  	//
	// OUTPUT: 
  	//    The error message for the last failed query
  	//
  
	function db_error()
  	{
	  	return mysql_error($this->db_connection);
	}
  
  	// END database_error() METHOD

  	//
	// THIS METHOD RETURNS ALL RETURNED DATA FOR THE LAST QUERY
  	//
	// INPUT: 
  	//    void
  	//
	// OUTPUT: 
  	//    An array of all returned data for the last query
  	//
  
	function db_load_all()
  	{
	    if( !is_resource($this->_last_resource) )
	    {
	      	return FALSE;
	    }
	    
	    $resource = $this->_last_resource;
	    $return_data = array();
	    while( $data = $this->db_fetch_assoc($resource) )
	    {
	      	$return_data[] = $data;
	    }
	    
	    return $return_data;
	}
  
  	// END database_load_all() METHOD


  	//
	// THIS METHOD RETURNS ALL RETURNED DATA FOR THE LAST QUERY IN AN ASSOC
  	// ARRAY USING THE COLUMN SPECIFIED AS THE KEY
  	//
	// INPUT: 
  	//    $key_column - to use as assoc index
  	//
	// OUTPUT: 
  	//    The error message for the last failed query
  	//
  
	function db_load_all_assoc($key_column)
  	{
	    if( !is_resource($this->_last_resource) )
	    {
	      	return FALSE;
	    }
	    
	    $resource = $this->_last_resource;
	    $return_data = array();
	    while( $data = $this->db_fetch_assoc($resource) )
	    {
	      	$return_data[$data[$key_column]] = $data;
	    }
	    
	    return $return_data;
	}
  
  	// END database_load_all_assoc() METHOD


  	//
	// THIS METHOD CLOSES A CONNECTION TO THE DATABASE SERVER
  	//
	// INPUT: 
  	//  void
  	//
	// OUTPUT:
  	//    Connection closure result
  	//
  
	function db_close()
  	{
    	// DO DATABASE QUERY LOGGING
    	if( $this->log_stats ) $this->db_log_stats();
    	if( $this->log_stats ) $this->db_log_stats_cleanup();
    
	  	return mysql_close($this->db_connection);
	}
  
  	// END database_close() METHOD

  	//
	// THIS METHOD SORT THE BENCHMARKS BY TIME
  	//
	// INPUT: 
  	//    void
  	//
	// OUTPUT: 
  	//    void
  	//
  
	function db_benchmark_sort()
  	{
	    if( !function_exists('dbtimecmp') )
	    {
	      	function dbtimecmp($a, $b)
	      	{
		        //return ( $a['time']==$b['time'] ? 0 : ($a['time']<$b['time'] ? -1 : 1) );
		        return ( (float)$a['time']==(float)$b['time'] ? 0 : ((float)$a['time']>(float)$b['time'] ? -1 : 1) );
	      	}
	    }
	    
	    usort($this->log_data, 'dbtimecmp');
	    
	    return '';
  	}


  	//
	// THIS METHOD GETS MYSQL CLIENT INFO
  	//
	// INPUT:
  	//    void
  	//
	// OUTPUT:
  	//    http://us2.php.net/manual/en/function.mysql-get-client-info.php
  	//
  
	function db_get_client_info()
  	{
	  	return ( function_exists('mysql_get_client_info') ? mysql_get_client_info() : FALSE );
	}
  
  	// END database_get_client_info() METHOD 


  	//
	// THIS METHOD GETS MYSQL HOST INFO
  	//
	// INPUT:
  	//    void
  	//
	// OUTPUT:
  	//    http://us2.php.net/manual/en/function.mysql-get-host-info.php
  	//
  
	function db_get_host_info()
  	{
	  	return ( function_exists('mysql_get_host_info') ? mysql_get_host_info($this->db_connection) : FALSE );
	}
  
  	// END database_get_host_info() METHOD 


  	//
	// THIS METHOD GETS MYSQL PROTOCOL INFO
  	//
	// INPUT:
  	//    void
  	//
	// OUTPUT:
  	//    http://us2.php.net/manual/en/function.mysql-get-proto-info.php
  	//
  
	function db_get_proto_info()
  	{
	  	return ( function_exists('mysql_get_proto_info') ? mysql_get_proto_info($this->db_connection) : FALSE );
	}
  
  	// END database_get_proto_info() METHOD 

  	//
	// THIS METHOD GETS MYSQL SERVER INFO
  	//
	// INPUT:
  	//    void
  	//
	// OUTPUT:
  	//    http://us2.php.net/manual/en/function.mysql-get-server-info.php
  	//
  
	function db_get_server_info()
  	{
	  	return ( function_exists('mysql_get_server_info') ? mysql_get_server_info($this->db_connection) : FALSE );
	}
  
  	// END database_get_server_info() METHOD 


  	//
	// THIS METHOD LOGS STUFF
  	//
	// INPUT:
  	//    void
  	//
	// OUTPUT:
  	//    void
  	//
  
	function db_log_stats()
  	{
	    $query_timer_start = getmicrotime();
	    
	    // DO STUFF
	    $time = time();
	    $do_insert = FALSE;
	    $insert_query = "
	      INSERT INTO ".TBL_DEBUG_LOGS."
	      (
	        debug_querylog_query,
	        debug_querylog_queryhash,
	        debug_querylog_querylocation,
	        debug_querylog_benchmark,
	        debug_querylog_backtrace,
	        debug_querylog_result,
	        debug_querylog_count,
	        debug_querylog_error,
	        debug_querylog_time
	      )
	      VALUES
	    ";
	    
	    foreach( $this->log_data as $log_index=>$log_data )
	    {
	      // LOG SINGLE QUERY
	      if( $do_insert ) $insert_query .= ", ";
	      
	      $query_location = substr($log_data['backtrace'][0]['file_short']." [".$log_data['backtrace'][0]['line']."]", 0, 254);
	      
	      $insert_query .= "(
	        '".$this->db_real_escape_string($log_data['query'])."',
	        '{$log_data['query_hash']}',
	        '{$query_location}',
	        '{$log_data['time']}',
	        '', /* TODO */
	        '".( $log_data['result'] ? '1' : '0' )."',
	        '{$log_data['count']}',
	        '".$this->db_real_escape_string($log_data['error'])."',
	        '{$time}'
	      )";
	      
	      $do_insert = TRUE;
	      
	      // LOG STATS
	      $sql = "
	        INSERT INTO ".TBL_DEBUG_STATS."
	        (
	          debug_querystats_query_hash,
	          debug_querystats_query_location,
	          debug_querystats_query,
	          debug_querystats_count,
	          debug_querystats_count_failed,
	          debug_querystats_count_slow,
	          debug_querystats_time_total,
	          debug_querystats_time_avg
	        )
	        VALUES
	        (
	          '{$log_data['query_hash']}',
	          '{$query_location}',
	          '".$this->db_real_escape_string($log_data['query'])."',
	          '1',
	          '".( !$log_data['result'] ? '1' : '0' )."',
	          '".( $log_data['time']>$this->log_slow_threshold ? '1' : '0' )."',
	          '{$log_data['time']}',
	          '{$log_data['time']}'
	        )
	        ON DUPLICATE KEY UPDATE
	          debug_querystats_count=debug_querystats_count+1,
	          debug_querystats_count_failed=debug_querystats_count_failed+".( !$log_data['result'] ? '1' : '0' ).",
	          debug_querystats_count_slow=debug_querystats_count_slow+".( $log_data['time']>$this->log_slow_threshold ? '1' : '0' ).",
	          debug_querystats_time_total=debug_querystats_time_total+".( $log_data['time'] ? $log_data['time'] : '0' ).",
	          debug_querystats_time_avg=(debug_querystats_count*debug_querystats_time_avg+".( is_numeric($log_data['time']) ? $log_data['time'] : '0' ).")/(debug_querystats_count+1)
	      ";
	      
	      mysql_query($sql, $this->db_connection);/* or die(mysql_error($this->db_connection)." ".$sql);*/
	    }
	    
	    if( $do_insert ) mysql_query($insert_query, $this->db_connection);/* or die(mysql_error($this->db_connection)." ".$insert_query);*/
	    
	    $query_timer_end = getmicrotime();
	    $query_timer_total = round($query_timer_end-$query_timer_start, 7);
	}
  
  	// END database_log_stats() METHOD

  	//
	// THIS METHOD CLEANS UP LOG STUFF
  	//
	// INPUT:
  	//    void
  	//
	// OUTPUT:
  	//    void
  	//
  
	function db_log_stats_cleanup($not_slow=TRUE)
  	{
	    $age_limit = time() - 7200;
	    //$age_limit = time() - 86400;
	    
	    $sql = "DELETE FROM ".TBL_DEBUG_LOGS." WHERE debug_querylog_time<$age_limit";
	    if( $not_slow ) $sql .= " && debug_querylog_benchmark<{$this->log_slow_threshold}";
	    
	    $resource = mysql_query($sql, $this->db_connection);/* or die(mysql_error($this->db_connection)." ".$sql);*/
  	}
  	
  	function num_queries(){
		return self::$db_num_queries;
	}
  
  	// END database_log_stats_cleanup() METHOD
  	
	/**
	* Get a quoted database escaped string
	*
	* @param	string	A string
	* @param	boolean	Default true to escape string, false to leave the string unchanged
	* @return	string
	* @access public
	*/
	function Quote( $text, $escaped = true )
	{
		return '\''.($escaped ? $this->getEscaped( $text ) : $text).'\'';
	}
	
	/**
	 * Get a database escaped string
	 *
	 * @param	string	The string to be escaped
	 * @param	boolean	Optional parameter to provide extra escaping
	 * @return	string
	 * @access	public
	 * @abstract
	 */
	function getEscaped( $text, $extra = false )
	{
		$result = mysql_real_escape_string( $text, $this->db_connection );
		if ($extra) {
			$result = addcslashes( $result, '%_' );
		}
		return $result;
	}
	
	
	
	
	/**
     * Get one row of the fiels names in a table
     * input: $database_result - representing a database query result resource
     * return an array of field names
     */
    function getOne($database_result) {
    	if( !is_resource($database_result) ) return FALSE;
    	
        if ($row = mysql_fetch_array($database_result)) {
        	$result = $row[0];
        }
        return $result;
    }
    
    /**
     * Get one row of the fiels names in a table
     * input: $database_result - representing a database query result resource
     * return an array of field names
     */
    function getRow($database_result) {
    	if( !is_resource($database_result) ) return FALSE;
    	
    	if ($row = mysql_fetch_assoc($database_result)) {
    		$result = $row;
    	}
    	if (isset($result)) return $result;
    	else return FALSE;
    }
    
    /**
     * Get one col of the fields names in a table
     * input: $database_result - representing a database query result resource
     * return an array of col names
     */
    function getCol($database_result) {
    	if( !is_resource($database_result) ) return FALSE;
    	
        $result = array();
        while ($row = mysql_fetch_array($database_result)) {
        	$result[] = $row[0];
        }
        return $result;
    }
    
    /**
     * Get array all field names in a table
     * input: $database_result - representing a database query result resource
     * return an array of col names
     */
    function getArray($database_result) {
    	if( !is_resource($database_result) ) return FALSE;
    	
        while ($row = mysql_fetch_array($database_result)) {
        	$result[] = $row;
        }
        return $result;
    }
    
    /**
     * 
     * insert data
     * @param $table: table name 
     * @param $values: array input of fields 
     * @param $replace: insert or replace action 
     * @param unknown_type $callPos
     */
	public function insert($table, $values, $replace=false){
		if ($replace) $query = 'REPLACE';
		else $query = 'INSERT INTO';
		
		$query.=' `'.$table.'`(';
		$i = 0;
		if (is_array($values)) {
			foreach ($values as $key=>$value){
				if (($key === 0) || is_numeric($key)){
					$key=$value;
				}
				if ($key){
					if ($i <> 0){
						$query .= ',';
					}
					$query .= '`'.$key . '`';
					$i++;
				}
			}
			$query .= ') VALUES(';
			$i = 0;
			
			foreach ($values as $key=>$value){
				if (is_numeric($key) || $key===0){
					$value = $_REQUEST[$value];
				}
				
				if ($i <> 0) {
					$query .= ',';
				}

				if($value === 'NULL'){
					$query .= 'NULL';
				}
				else{
					$query .= '\'' . addslashes($value) . '\'';
				}
				$i++;
			}
			$query .= ')';
			if ($this->db_query($query)){
				$id = $this->db_insert_id();		
				return $id;
			}
		}
	}
	
	/**
	 * 
	 * action update data table
	 * @param $table: name of table
	 * @param $values: array data of field
	 * @param $condition: condition for action update
	 */
	public function update($table, $values, $condition='') {
		$query = 'UPDATE `' . $table . '` SET ';
		$i = 0;
		
		if ($values) {
			foreach ($values as $key=>$value) {
				if ($key === 0 || is_numeric($key)) {
					$key = $value;
					$value = $_REQUEST[$value];
				}
				
				if ($i <> 0) {
					$query .= ',';
				}
				
				if ($key){
					if ($value === 'NULL') {
						$query .= '`' . $key  .'`=NULL';
					}
					else{
						$query .= '`' . $key . '`=\''.addslashes($value).'\'';
					}
					$i++;
				}
			}
			if ($condition) $query .= ' WHERE '.$condition;
			if ($this->db_query($query)){
				return true;
			}
		}
	}
	
	/**
	 * 
	 * action delete data of table
	 * @param $table: name of table delete
	 * @param $condition: condition for action delete
	 */
	public function delete($table, $condition=''){
		$query = 'DELETE FROM `'.$table.'`';
		if ($condition) $query .= ' WHERE '.$condition;
		if ($this->db_query($query)){
			return true;
		}
	}
	
	public function db_sql_format($sql){
		return $sql;
		if (!function_exists('PMA_SQP_formatHtml')){
			define("PARSER_LIB_ROOT", PG_ROOT."/include/sqlparserlib/");
			require_once 'include/sqlparserlib/sqlparser.lib.php';
		}
		return PMA_SQP_formatHtml(PMA_SQP_parse($sql));
	}
}
?>
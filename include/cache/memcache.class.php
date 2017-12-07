<?php
/*
+--------------------------------------------------------------------------
|   rongbay.com
|   =============================================
|   by Nova
|   =============================================
|   Web: http://www.rongbay.com
|   Started date : 9/23/2006
+---------------------------------------------------------------------------
*/
class memcacheLib{
	static $identifier, $crashed = 0, $encoding_mode, $debug = 1;

	function memcacheLib(){}

	static function connect(){
		if(!CGlobal::$memcache_connect_id && !memcacheLib::$crashed){
			if( !function_exists('memcache_connect') ){
				memcacheLib::$crashed = 1;
				return FALSE;
			}

			memcacheLib::$identifier = MEMCACHE_ID;

			if(!CGlobal::$memcache_server || !count(CGlobal::$memcache_server) ){
				memcacheLib::$crashed = 1;
				return FALSE;
			}

			if (PG_DEBUG) {
				$rtime = microtime();
				$rtime = explode(" ",$rtime);
				$rtime = $rtime[1] + $rtime[0];
				$start_rb = $rtime;
			}

		    for ($i = 0, $n = count(CGlobal::$memcache_server); $i < $n; $i++){

		        $server = CGlobal::$memcache_server[$i];

		        if( $i < 1 ) {
		       		 CGlobal::$memcache_connect_id = @memcache_connect($server['host'], $server['port']);
		        }
		        else {
					memcache_add_server( CGlobal::$memcache_connect_id, $server['host'], $server['port'] );
		        }

				if (PG_DEBUG) {
					$mtime = microtime();
					$mtime = explode(" ",$mtime);
					$mtime = $mtime[1] + $mtime[0];
					$end_rb = $mtime;
					$load_time = round(($end_rb - $start_rb),5)."s";
			    	CGlobal::$conn_debug.= " <b>Connect to Memcache server : {$server['host']} : {$server['port']} </b> [in $load_time]<br>\n";
				}

		    }

			if( !CGlobal::$memcache_connect_id ){
				memcacheLib::$crashed = 1;
				return FALSE;
			}

			if( function_exists('memcache_set_compress_threshold') ){
				memcache_set_compress_threshold( CGlobal::$memcache_connect_id, 20000, 0.2 );
			}

			memcache_debug( memcacheLib::$debug );
		}
		return CGlobal::$memcache_connect_id;
	}


	static function disconnect(){
		if( CGlobal::$memcache_connect_id ){
			memcache_close( CGlobal::$memcache_connect_id );
		}

		return TRUE;
	}

	static function stats(){
		if(self::connect()){
			if( CGlobal::$memcache_connect_id ){
				return	memcache_get_stats( CGlobal::$memcache_connect_id );
			}
		}

		return TRUE;
	}

	static function do_put( $key, $value, $ttl=0 ){
		if(self::connect()){
			memcache_set( CGlobal::$memcache_connect_id, md5( memcacheLib::$identifier . $key ),
								$value,
								MEMCACHE_COMPRESSED,
								intval($ttl) );
			
		}
	}

	static function do_get( $key ){
		if(self::connect()){
			$hash_key 	= md5( memcacheLib::$identifier . $key );
			$return_val = memcache_get( CGlobal::$memcache_connect_id, $hash_key);
	  		if($return_val){
				if(PG_DEBUG){
					CGlobal::$query_debug .= "<table width='95%' border='1' cellpadding='6' cellspacing='0' bgcolor='#FEFEFE'  align='center'>
						<tr>
						 <td style='font-size:14px' bgcolor='#EFEFEF'><span style='color:blue'><b>Memcache</b></span></td>
						</tr>
						<tr>
						 <td style='font-family:courier, monaco, arial;font-size:14px;color:blue'><b>Get:</b> $key <br /> <b>hash key:</b> $hash_key (<a href='javascript:void(0);' onclick=\"deleteCache('$key')\">Delete this cache</a>)</td>
						</tr>
						<tr>
						 <td style='font-size:14px' bgcolor='#EFEFEF'></td>
						</tr>
					       </table><br />\n\n";
				}
				return $return_val;
	  		}
  		}
  		return false;
	}

	static function do_remove( $key ){
		if(self::connect()){
			memcache_delete( CGlobal::$memcache_connect_id, md5( memcacheLib::$identifier . $key ) );
		}
	}

	static function clear(){
		if(self::connect()){
			memcache_flush (CGlobal::$memcache_connect_id );
		}
		return true;
    }
/*
    static function encode($data){
		return $data;
        if (memcacheLib::$encoding_mode == 'base64') {
            return base64_encode(serialize($data));
        } else {
            return serialize($data);
        }
    } 

    static function decode($data){
		return $data;
        if (memcacheLib::$encoding_mode == 'base64') {
            return unserialize(base64_decode($data));
        } else {
            return unserialize($data);
        }
    }
*/
}
?>
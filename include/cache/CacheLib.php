<?php
//@06 - 30 - 10 
//@Start caching
//@author tannv
//Xoá cache
//CacheLib::auto_run();
class CacheLib{
	static $expire = 3600,$subDir = '',$fileListCache = 'listCache',$cacheKey = '';
	static function auto_run(){
		if(isset($_REQUEST['trigger']) && isset($_REQUEST['cache_key']) && $_REQUEST['trigger'] && $_REQUEST['cache_key']  ){
			CacheLib::removePer();
		}
		return true;
	}
	static function cronjobDel(){
		$hour = date('H',TIME_NOW);
		$done = DIR_CACHE.self::$subDir.'done';
		if($hour>4 && $hour<5){
			if(!file_exists($done)){
				$fileListCache = DIR_CACHE.self::$subDir.self::$fileListCache;
				if(file_exists($fileListCache)){
					$listCache = file_get_contents($fileListCache);
					if($listCache){
						$listCache = substr($listCache,0,-1);
						$fileArr = explode('|',$listCache);
						$listValue = '';
						foreach($fileArr as $v){
							$fileArr = unserialize($v);
							$fileName = array_keys($fileArr);
							if($fileArr[$fileName[0]] < TIME_NOW){// check time expire
								CacheLib::removePer($fileName[0]);
							}
							else{
								$listValue .= $v.'|';
							}
						}
						if($listValue){
							@file_put_contents($fileListCache,$listValue);
						}
						@file_put_contents(DIR_CACHE.self::$subDir.'done',1);
					}
				}
			}
		}
		else{
			if(file_exists($done)){
				@unlink($done);
			}
		}
	}
	static function set($cacheKey = '', $value = '', $expire = 0, $subDir = '',$create = false){
		if(CACHE_ON){
			if($cacheKey!=''){
				self::$cacheKey=$cacheKey;
				self::$expire=$expire;
				self::$subDir=$subDir;
				self::setCache($value,$create);
			}
		}
		return true;
	}
	static function setCacheToken($cacheKey, $key_index, $type, $ten_dang_nhap, $expire){
		global $Redis;

		if (REDIS_ON){
			RedisLib::hsetDataToken($cacheKey, $key_index, $type, $ten_dang_nhap, $expire);
		}else{
			return false;
		}
	}
	static function setCache ($value,$create = false){
		global $user;
		if(REDIS_ON) global $Redis;

		$cacheKey=self::$cacheKey;
		if(MEMCACHE_ON){
			memcacheLib::do_put($cacheKey,$value,self::$expire);
		}
		else if (REDIS_ON){
			RedisLib::setData($cacheKey, $value, self::$expire);
		}
		else{
			$expire = TIME_NOW+self::$expire;
			$cacheDir = DIR_ROOT.DIR_CACHE.self::$subDir;
			$cacheFile = $cacheDir.$cacheKey;
			//var_dump($cacheDir); die;
			if($create){
				$value = stripslashes($value);
				if(self::CheckDir($cacheDir)){
					file_put_contents($cacheFile,$value); // store cache
					$arrFile = array($cacheKey=>$expire);
					$listValue = serialize($arrFile).'|';
					if(file_exists($cacheDir.self::$fileListCache)){
						file_put_contents($cacheDir.self::$fileListCache, $listValue, FILE_APPEND);
					}
					else{
						file_put_contents($cacheDir.self::$fileListCache,$listValue); // store cache
					}
				}
			}
			else{
				$cacheKey = md5($cacheKey);
				$createCache = false;
				if(file_exists($cacheFile)){
					$create_time = filemtime($cacheFile);
					if( self::$expire > 0 && TIME_NOW > $create_time + self::$expire ){
						$createCache = true;
					}
				}
				else{
					$createCache = true;
				}
				if($createCache){
					$value = @serialize($value);
					//print_r(CGlobal::$my_server); die;
					if(is_array(CGlobal::$my_server)){
						foreach (CGlobal::$my_server as $server){
							$link = "http://{$server}createCache.php";
							//echo $link; die;
							$val = array('cacheKey' => $cacheKey,
										 'value'	=> $value,
										 'expire'	=> $expire,
										 'subDir'	=> base64_encode(self::$subDir)
										 );

							require_once(dirname(__FILE__) . "/core/Curl.php");
							$curl = new Curl();
							$return = $curl->post($link,$val);
							//print_r($return); die;
						}
					}
				}
			}
		}
	}
	static function get($cacheKey = '',$expire = 0,$subDir = ''){
		global $user;
		if(REDIS_ON) global $Redis;

		$value = false;
		if(CACHE_ON && $cacheKey!=''){
			$hour = date('H',TIME_NOW);
			self::$subDir = $subDir;
			CacheLib::cronjobDel($subDir);
			if(MEMCACHE_ON){
				$value = memcacheLib::do_get($cacheKey);
			}
			else if (REDIS_ON){
				$value = RedisLib::getData($cacheKey);
				$mixedData = base64_decode($value);
				if ( $mixedData == false || $mixedData == 'false' ){
					return $mixedData;
				}
				if ( $mixedData === 1 || $mixedData === '1' || $mixedData === "1" || $mixedData === 0 || $mixedData === '0' ){
					return intval($mixedData);
				}
				$mixedData = json_decode($mixedData);
				if ( !$mixedData ){
					return false;
				}

				// Static
				if ( isset($mixedData->data) && is_object($mixedData->data) ){ // is_object ->data
					$value = objectToArray($mixedData->data);
					$data->is_message = false;
					$data->data = $value;
					return $data;
				}else if ( is_html($mixedData) ){ // is_html
					return $mixedData;
				}else if ( is_array($mixedData) || is_object($mixedData) || $mixedData == 'Array' || $mixedData == 'Object' ){ // list data array
					$value = objectToArray($mixedData);
					return $value;
				}else if ( isJSON($mixedData) ){
					$value = json_decode($mixedData);
					return objectToArray($value);
				}else if ( is_string($mixedData) ){
					return $mixedData;
				}
			}
			else{
				$cacheKey=md5($cacheKey);
				$cacheFile = DIR_CACHE.self::$subDir.$cacheKey;
				self::$expire = $expire;
				if(file_exists($cacheFile)){
					$create_time = filemtime($cacheFile);
					if( self::$expire ==  0 || ( self::$expire > 0 && TIME_NOW < $create_time + self::$expire) ){
						$value = file_get_contents($cacheFile);
						
						if($value){
							$value = unserialize($value);
						}
						//var_dump($value);
					}
				}
			}
			return $value;
		}
	}
	static function getKeys($cacheKey){
		if(REDIS_ON && $cacheKey!='') {
			$mixData = RedisLib::getKeys($cacheKey);
			return $mixData;
		}
		return false;
	}
	static function getAllKeys(){
		if(REDIS_ON){
			$mixData = RedisLib::getAllKeys();
			return $mixData;
		}
		return false;
	}
	static function removePer($cacheKey = '',$subDir = '', $encodeKey = false){
		global $user;
		if(REDIS_ON) global $Redis;

		if($cacheKey!=''){
			self::$subDir = $subDir;
  			if(MEMCACHE_ON){
				memcacheLib::do_remove($cacheKey);
  			}
			else if (REDIS_ON){
				RedisLib::delData($cacheKey);
			}
  			else{
				$cacheKey = md5($cacheKey);
				if(is_array(CGlobal::$my_server)){
					foreach (CGlobal::$my_server as $server){
						$link = "http://{$server}?trigger=1&cache_key={$cacheKey}";
						if(self::$subDir){
							$link .= '&subDir='.base64_encode(self::$subDir);
						}

						require_once(dirname(__FILE__) . "/core/Curl.php");
						$curl = new Curl();
						$return = $curl->get($link);
//						if(PG_DEBUG){
//							echo "Deleted cache file : {$cacheKey} => link: {$link}<br>";
//						}
					}				
				}
				
				//@unlink(DIR_CACHE.self::$subDir. $cacheKey);
				
  			}
  			return true;
  		}
		elseif(isset($_REQUEST['trigger']) && isset($_REQUEST['cache_key']) && $_REQUEST['trigger'] && $_REQUEST['cache_key']  ){
			$cacheKey = $_REQUEST['cache_key'];
			self::$subDir = (isset($_REQUEST['subDir'])) ? base64_decode($_REQUEST['subDir']) : '';
			@unlink(DIR_CACHE.self::$subDir. $cacheKey);
			
		}
		return false;
	}
	static function delete($cacheKey='', $subDir = '', $encodeKey = false){
  		if($cacheKey!=''){
			return CacheLib::removePer($cacheKey, $subDir, $encodeKey);
  		}
		return false;
  	}
	function CheckDir($pDir){
		if (is_dir($pDir))
			return true;

		if (!@mkdir($pDir,0777,true)){
			return false;
		}
		self::chmod_dir($pDir,0777);
		return true;
	}
	static function chmod_dir($dir,$mod=0777){
		$parent_dir=dirname(str_replace(ROOT_PATH,'',$dir));
		if($parent_dir!='' && $parent_dir!='.'){
			//echo $parent_dir.'/<br />';
			@chmod($dir,$mod);
			self::chmod_dir($parent_dir,$mod);
		}
		return true;
	}
	/**
	 * DELETE CACHE FOR USERS
	 */
	static function deleteCacheUsers($params = ''){
		if ( CACHE_ON && REDIS_ON ){
			if (is_array($params)){
				foreach ($params as $key) CacheLib::deleteCacheUsers($key);
				return;
			}
			return CacheLib::delCacheUser($params);
		}else
			return false;
	}

	static function delCacheUser( $key ){
		if ( CACHE_ON && REDIS_ON ) {
			$dataCache = CacheLib::getKeys($key);

			if (isset($dataCache) && is_array($dataCache) && count($dataCache)) {
				foreach ($dataCache as $item) {
					CacheLib::delete($item);
				}
				return true;
			} else
				return false;
		}else
			return false;
	}
}
?>

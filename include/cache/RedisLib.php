<?php

/**
 * Created by PhpStorm.
 * User: Ngọc Kiều
 * Date: 5/30/2016
 * Time: 1:09 PM
 */
class RedisLib{
    var $iTTL = 3600; // 1 hours

    function RedisLib(){}

    static function openRedisConnection( $hostName, $port){
        global $Redis;
        // Opening a redis connection
        $Redis->connect( $hostName, $port );
        return $Redis;
    }

    /**
     * Save data in cache server
     *
     * @param string $sKey
     *        	- file name
     * @param mixed $mixedData
     *        	- the data to be cached in the file
     * @param int $iTTL
     *        	- time to live
     * @return boolean result of operation.
     */
    static function setData($sKey, $mixedData, $iTTL = false) {
        global $Redis;

        if (!isset($Redis)) return false;

        // encode data
        $mixedData = json_encode($mixedData);
        $mixedData = base64_encode($mixedData);

        return $Redis->set ( $sKey, $mixedData, $iTTL );
    }

    static function hsetDataToken($sKey, $key_index, $type, $ten_dang_nhap, $iTTL = false){
        global $Redis;

        if (!isset($Redis)) return false;

        $Redis->hSet($sKey, 'key_index', $key_index);
        $Redis->hSet($sKey, 'type', $type);
        $Redis->hSet($sKey, 'ten_dang_nhap', $ten_dang_nhap);

        return $Redis->expire( $sKey, $iTTL );
    }


    /**
     * Get data from cache server
     *
     * @param string $sKey
     *        	- file name
     * @param int $iTTL
     *        	- time to live
     * @return the data is got from cache.
     */

    static function getData( $key ){
        try{
            global $Redis;
            // getting the value from redis
            return $Redis->get( $key);
        }catch( Exception $e ){
            echo $e->getMessage();
        }
    }

    function hGetAll($sKey) {
        global $Redis;

        if (!isset($Redis) || !isset($sKey)) return null;
        $mixedData = $Redis->hGetAll($sKey);

        return false === $mixedData ? false : $mixedData;
    }

    /**
     * Delete cache from cache server
     *
     * @param string $sKey
     *        	- file name
     * @return result of the operation
     */
    static function delData($sKey) {
        global $Redis;

        $Redis->delete ( $sKey );
        return true;
    }


    static function getKeys($sKey) {
        global $Redis;

        $data = $Redis->keys ( "*{$sKey}*" );
        return $data;
    }

    static function  getAllKeys(){
        global $Redis;

        $data = $Redis->keys ( '*' );
        return $data;
    }

    static function deleteValueFromKey( $key ){
        try{
            global $Redis;
            // deleting the value from redis
            $Redis->del( $key);
        }catch( Exception $e ){
            echo $e->getMessage();
        }
    }

    static function exists($sKey){
        global $Redis;

        if (!isset($Redis) || !isset($sKey)) return false;
        return $Redis->exists($sKey);
    }

    /* Functions for converting sql result  object to array goes below  */

    function convertToArray( $result ){
        $resultArray = array();

        for( $count=0; $row = $result->fetch_assoc(); $count++ ) {
            $resultArray[$count] = $row;
        }

        return $resultArray;
    }

    /* Functions for executing the mySql query goes below   */
    function executeQuery( $query ){
        $mysqli = new mysqli( 'localhost',  'username',  'password',  'someDatabase' );

        if( $mysqli->connect_errno ){
            echo "Failed to connect to MySql:"."(".mysqli_connect_error().")".mysqli_connect_errno();
        }

        $result =  $mysqli->query( $query );
        // Calling function to convert result  to array
        $arrResult = convertToArray( $result );

        return $arrResult;
    }

    function test_connect_db(){
        $query = 'select * from sometable limit 1';
        // Calling function to execute sql query
        $arrValues = $this->executeQuery( $query );

        // Making json string
        $jsonValue = json_encode($arrValues);

        // Opening a redis connection
        $this->openRedisConnection( 'localhost', 6379 );

        // Inserting the value with ttl =  1 hours
        $this->setValueWithTtl( 'somekey1', $jsonValue, 3600);

        // Fetching value from redis using the key.
        $val = $this->getValueFromKey( 'somekey1' );

        //  Output:  the json encoded array from redis
        echo $val;

        // Unsetting value from redis
        $this->deleteValueFromKey( $key );
    }
}
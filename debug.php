<?php
define('PG_PAGE_AJAX', TRUE);
include "header.php";
$page = "debug";

// THIS FILE CONTAINS RANDOM JAVASCRIPT-Y FEATURES SUCH AS POSTING COMMENTS AND DELETING ACTIONS
$task = PGRequest::getVar('task', NULL, 'REQUEST');

// GET DEBUG INFO
if($task == "get_debug_info")
{
    if(isset($_POST['id'])) { $id = $_POST['id']; } elseif(isset($_GET['id'])) { $id = $_GET['id']; } else { exit(); }
    $id = preg_replace('/[^a-zA-Z0-9\._]/', '', $id);

    //echo PG_ROOT.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR; die;
    echo file_get_contents(PG_ROOT.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.$id.'.html');

    // Delete logs older than an hour
    $dh = @opendir(PG_ROOT.DIRECTORY_SEPARATOR.'log');
    if($dh)
    {
        while( ($file = @readdir($dh)) !== false )
        {
            if( $file == "." || $file == ".." ) continue;
            if( filemtime(PG_ROOT.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.$file)>time()-3600 ) continue;
            @unlink(PG_ROOT.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.$file);
        }
    }

    exit();
}
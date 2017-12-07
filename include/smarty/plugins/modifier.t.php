<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty indent t plugin
 *
 * Type:     modifier<br>
 * Name:     t<br>
 * Purpose:  translate text
 * @author   Dzung DH
 * @param string
 * @return string
 */
function smarty_modifier_t($string){
    return t($string);
}

?>

<?php

/**
 * @author langkhach
 * @copyright 2011
 */
 
define('PG_ERROR_TYPE_ERROR', 0);
define('PG_ERROR_TYPE_MESSAGE', 1);

class PGError{
    static $aMessage = array();
    
    static function set($message, $message_type=PG_ERROR_TYPE_ERROR, $formElement=null){
        if (!empty($message)){
            PGError::$aMessage[] = array(
                't'     => $message_type,
                'e'     => $formElement,
                'm'     => str_replace(' ', '&nbsp;', $message)
            );    
        }
        
        PGError::write();
    }
    
    static function set_message($message, $formElement=null){
        PGError::set($message, PG_ERROR_TYPE_MESSAGE, $formElement);
    }
    
    static function set_error($message, $formElement=null){
        PGError::set($message, PG_ERROR_TYPE_ERROR, $formElement);
    }
    
    static function write(){
        global $session;
        $session->set('shp_pgerror', PGError::$aMessage);
    }
    
    static function html(){
        global $session;
        $aMessage = $session->get('shp_pgerror', array());
        $aElement = array();
        $html = "";
        foreach ($aMessage as $msg){
            $msg['m'] = str_replace('&nbsp;', ' ', $msg['m']);
            $cl = ($msg['t']==0)?'ui-state-error':'ui-state-highlight';
            $html .= '<div class="ui-widget"><div class="'.$cl.' ui-corner-all"><p><span class="ui-icon"></span>'.$msg['m'].'</p></div></div>';
            if (!is_null($msg['e'])) $aElement[] = $msg['e'];
        }
        
        $html = empty($html)?$html:'<div class="error-box">'.$html.'</div>';
        if (count($aElement)>0){
            $html .= '<script language="javascript" type="text/javascript" src="./include/js/error.js"></script>';
            $html .= '<script language="javascript" type="text/javascript">';
            $html .= 'shp.error.aError=eval(\'('.json_encode($aElement).')\')';
            $html .= '</script>';
        }
        $session->clear('shp_pgerror');
        return $html;
    }
}

?>
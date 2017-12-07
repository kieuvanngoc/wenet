<?php

/**
 * @author langkhach
 * @copyright 2011
 */
 
class PGTheme{
  static $aCss = array();
  static $aJs = array();
  static $pageTitleRight = array();
  static $bodyClass = array();
  static $jsSettings = array();
    
  static function add_css($path){
    PGTheme::$aCss[] = $path;
  }
  
  static function add_js($path){
    PGTheme::$aJs[] = $path;
  }
  
  static function get_css(){
    $html = "";
    foreach (PGTheme::$aCss as $css_path){
      $html .= sprintf('<link rel="stylesheet" href="%s" title="stylesheet" type="text/css" />', $css_path);
    }
    return $html;
  }
  
  static function get_js(){
    $html = "";
    foreach (PGTheme::$aJs as $js_path){
      $html .= sprintf('<script type="text/javascript" src="%s"></script>', $js_path);
    }
    return $html;
  }
  
  static function set_page_title_right($html){
    PGTheme::$pageTitleRight[] = $html;
  }
  
  static function get_page_title_right(){
    return PGTheme::theme_list(PGTheme::$pageTitleRight, 'ul', array('id'=>'pg-title-right', 'class'=>'clearfix'));
  }
  
  static function theme_list($aList=array(), $type='ul', $attribs=array()){
    if (isset($attribs['class'])) $attribs['class'] .= ' pg-list';
    else $attribs['class'] = 'pg-list';
    foreach ($attribs as $att=>$val){
      $sAttr .= ' '.$att.'="'.$val.'"';
    }
    $html = ($type=='ol')?'<ol'.$sAttr.'>':'<ul'.$sAttr.'>';
    $count = 1;
    foreach ($aList as $list){
      $sClass = 'list-item';
      if (isset($attribs['id'])) $sClass .= ' '.$attribs['id'].'-li';
      $sClass .= ($count==1)?' list-item-first':'';
      $sClass .= ($count==count($aList))?' list-item-last':'';
      $html .= '<li class="'.$sClass.'">'.$list.'</li>';
      $count++;
    }
    $html .= ($type=='order')?'</ol>':'</ul>';
    return $html;
  }
  
  static function set_body_class($className=""){
    if (!empty($className)) PGTheme::$bodyClass[] = $className;
  }
  
  static function get_body_class(){
    return implode(' ', PGTheme::$bodyClass);
  }
  
  static function set_page_title($title){
    global $page_title;
    $page_title = $title;
  }
  
  static function get_page_title(){
    global $page_title;
    return $page_title;
  }
  
  static function add_js_setting($groupName, $aVal){
    if (!isset(PGTheme::$jsSettings[$groupName])) PGTheme::$jsSettings[$groupName] = array();
    if (is_array($aVal)) PGTheme::$jsSettings[$groupName] = array_merge(PGTheme::$jsSettings[$groupName], $aVal);
  }
  
  static function get_js_settings(){
    return json_encode(PGTheme::$jsSettings);
  }
}

?>
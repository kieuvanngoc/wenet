<?php
defined('PG_PAGE') or die();

require(PG_ROOT.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'Smarty.class.php');

class PGSmarty extends Smarty
{
  	var $_tpl_hooks;
  
  	var $_tpl_hooks_no_multi = TRUE;
  
  	function PGSmarty()
  	{
	    $this->template_dir = PG_ROOT.DIRECTORY_SEPARATOR.'templates';
	    $this->compile_dir = PG_ROOT.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'templates_c';
	    $this->cache_dir = PG_ROOT.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'cache';
	    $this->config_dir = PG_ROOT.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'configs';
	    
	    $this->_tpl_hooks = array();
  	}
  
  	function &getInstance()
  	{
	    static $instance;
	    
	    if( is_null($instance) )
	    {
	      	$instance = new PGSmarty();
	    }
	    
	    return $instance;
  	}
  
  
  
  	function assign_hook($hook, $include)
  	{
	    if( !isset($this->_tpl_hooks[$hook]) ) $this->_tpl_hooks[$hook] = array();
	    
	    if( $this->_tpl_hooks_no_multi && in_array($include, $this->_tpl_hooks[$hook]) ) return;
	    
	    $this->_tpl_hooks[$hook][] = $include;
  	}
}

?>
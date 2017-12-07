<?php

class PGSession extends CakeSession
{
  function &getInstance($base = null, $start = true)
  {
    static $instance;
    if( null === $instance )
    {
      $instance = new PGSession($base, $start);
    }
    return $instance;
  }

  function get($key, $default = null, $group = null)
  {
    if( !$group ) $group = 'default';
    if( $group ) $group = '_'.$group;

    if( isset($_SESSION[$group][$key]) )
    {
      return $_SESSION[$group][$key];
    }
    else
    {
      return $default;
    }
  }

  function set($key, $value = null, $group = 'default')
  {
    if( !$group ) $group = 'default';
    if( $group ) $group = '_'.$group;

    if( null === $value )
    {
      unset($_SESSION[$group][$key]);
    }
    else
    {
      $_SESSION[$group][$key] = $value;
    }
  }

  function has($key, $group = null)
  {
    if( !$group ) $group = 'default';
    if( $group ) $group = '_'.$group;

    return isset($_SESSION[$group][$key]);
  }

  function copy()
  {
    return $this->renew();
  }

  function clear($key, $group = null)
  {
    return $this->set($key, null, $group);
  }
}
<?php

namespace Orus\Flash;

class Alert implements \ArrayAccess, \JsonSerializable
{  
  /**
   * The alert title.
   * 
   * @var string
   */
  public $title;

  /**
   * The body of the alert.
   * 
   * @var string
   */
  public $message;

  /**
   * The level of the alert.
   * 
   * @var string
   */
  public $level = 'info';

  /**
   * Wheter the message is important or not.
   * 
   * @var boolean
   */
  public $important = false;

  /**
   * The alert additional options.
   * 
   * @var array
   */
  public $options = [];

  /**
   * Create a new Alert instance.
   * 
   * @param array  $attributes
   */
  public function __construct($attrs)
  {
    $this->refresh($attrs);
  }

  /**
   * Refresh the attributes.
   * 
   * @param  array  $attrs 
   * @return void        
   */
  public function refresh(array $attrs = [])
  {
    $attrs = array_filter($attrs);

    foreach($attrs as $key => $value) {
      $this->offsetSet($key, $value);
    }

    return $this;
  }

  /**
   * Determine an item exists at the given offset.
   * 
   * @param  mixed $offset 
   * @return bool         
   */
  public function offsetExists($offset)
  {
    return isset($this->$offset);
  }

  /**
   * Retrieve an item at a given offset.
   * 
   * @param  mixed $offset 
   * @return mixed         
   */
  public function offsetGet($offset)
  {
    return $this->$offset;
  }

  /**
   * Set the item at a given offset.
   * 
   * @param  mixed $offset
   * @param  mixed $value 
   * @return void        
   */
  public function offsetSet($offset, $value)
  {
    $this->$offset = $value;
  }

  /**
   * Unset the offset.
   * 
   * @param  mixed $offset 
   * @return void         
   */
  public function offsetUnset($offset)
  {
    unset($this->$offset);
  }

  /**
   * Specify which data should be serialized to JSON
   * @return array
   */
  public function jsonSerialize()
  {
    return get_object_vars($this);
  }

}

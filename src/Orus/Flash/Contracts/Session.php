<?php

namespace Orus\Flash\Contracts;

interface Session
{
  /**
   * Flash a key / value pair to the session.
   * 
   * @param  string $key   
   * @param  array $value 
   */
  public function flash($key, $value);

  /**
   * Retrievre an item from the session.
   * 
   * @param  string $key     
   * @param  mixed $default 
   * @return mixed          
   */
  public function get($key, $default = null);

}

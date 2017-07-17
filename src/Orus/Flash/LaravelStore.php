<?php

namespace Orus\Flash;

use Orus\Flash\Contracts\Session;
use Illuminate\Session\Store;

class LaravelStore implements Session
{
  /**
   * The session instance.
   * 
   * @var Illuminate\Session\Store
   */
  protected $store;

  /**
   * Create a new Session instance.
   * 
   * @param Illuminate\Session\Store $store
   */
  public function __construct(Store $store)
  {
    $this->store = $store;
  }
  
  /**
   * Flash a key / value pair to the session.
   * 
   * @param  string $key   
   * @param  array $value 
   * @return void        
   */
  public function flash($key, $value)
  {
    $this->store->flash($key, $value);
  }

  /**
   * Retrievre an item from the session.
   * 
   * @param  string $key     
   * @param  mixed $default 
   * @return mixed          
   */
  public function get($key, $default = null)
  {
    return $this->store->get($key, $default);
  }
  
}

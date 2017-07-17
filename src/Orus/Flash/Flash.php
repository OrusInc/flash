<?php

namespace Orus\Flash;

use Orus\Flash\Contracts\Session;

class Flash
{
  /**
   * The session key.
   */
  const SESSION_KEY = "_flash_notifications";

  /**
   * The session instance
   *
   * @var Orus\Flash\Session
   */
  private $session;
  
  /**
   * The alerts container.
   * 
   * @var Illuminate\Support\Collection
   */
  protected $alerts;

  /**
   * Create a new Flash instance.
   * @param Orus\Flash\Contracts\Session $session
   */
  public function __construct(Session $session)
  {
    $this->session = $session;
    $this->alerts = collect();
  }

  /**
   * Fetch all registered alerts.
   * 
   * @return Illuminate\Support\Collection
   */
  public function alerts()
  {
    return $this->alerts;
  }

  /**
   * Flash a default alert.
   *
   * @param $message
   * @return $this
   */
  public function default($message = null, $title = null, $important = false)
  {
    return $this->alert($message, $title, 'default', $important);
  }

  /**
   * Flash an info alert.
   *
   * @param $message
   * @return $this
   */
  public function info($message = null, $title = null, $important = false)
  {
    return $this->alert($message, $title, 'info', $important);
  }

  /**
   * Flash a warning alert.
   *
   * @param $message
   * @return $this
   */
  public function warning($message = null, $title = null, $important = false)
  {
    return $this->alert($message, $title, 'warning', $important);
  }

  /**
   * Flash a success alert.
   *
   * @param $message
   * @return $this
   */
  public function success($message = null, $title = null, $important = false)
  {
    return $this->alert($message, $title, 'success', $important);
  }
  
  /**
   * Flash a danger alert.
   *
   * @param $message
   * @return $this
   */
  public function danger($message = null, $title = null, $important = false)
  {
    return $this->alert($message, $title, 'danger', $important);
  }

  /**
   * Set the title of the most recently added alert.
   * 
   * @param  string $title
   * @return $this        
   */
  public function title($title)
  {
    return $this->updateRecentAlert(compact('title'));
  }

  /**
   * Mark the most recently added alert as IMPORTANT.
   * 
   * @param  boolean $important 
   * @return $this             
   */
  public function important($important = true)
  {
    return $this->updateRecentAlert(compact('important'));
  }
  
  /**
   * Flash a new alert message.
   * 
   * @param  string $message 
   * @param  string $title   
   * @param  string $level   
   * @param  bool $important   
   * @return $this          
   */
  public function alert($message = null, $title = null, $level = "default", $important = false)
  {
    if ($message == null) {
      return $this->updateRecentAlert(compact('title', 'level', 'important'));
    }

    if (! $message instanceof Alert) {
      $message = new Alert(compact('message', 'title', 'level', 'important'));
    }

    $this->alerts->push($message);

    return $this->flash();
  }

  public function options($options = [])
  {
    return $this->updateRecentAlert([
      'options' => $options
      ]);
  }

  /**
   * Update the most recently added alert.
   * 
   * @param  array  $overrides 
   * @return $this            
   */
  protected function updateRecentAlert($overrides = [])
  {
    if ($this->alerts->last()) {
      $this->alerts->last()->refresh($overrides);
    }

    return $this->flash();
  }

  /**
   * Fetch all registered alerts.
   * 
   * @return Illuminate\Support\Collection
   */
  public function all()
  {
    return $this->session->get(static::SESSION_KEY, collect());
  }

  /**
   * Clear all registered alerts.
   * 
   * @return $this 
   */
  public function clear()
  {
    $this->alerts = collect();

    return $this;
  }

  /**
   * Flash all alerts to the session.
   * 
   * @return $this
   */
  public function flash()
  {
    $this->session
      ->flash(
        static::SESSION_KEY,
        $this->alerts
      );

    return $this;
  }
  
}

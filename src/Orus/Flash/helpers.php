<?php

/**
 * Helper for a Flash alert.
 *
 * @param string $message
 * @param string $title
 * @param string $level
 * @param bool   $important
 * @return Orus\Flash\Flash
 */
function flash($message = null, $title = null, $level = 'info', $important = false)
{
  return is_null($message) ? 
    app('flash') : 
    app('flash')->alert($message, $title, $level, $important);
}

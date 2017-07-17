<?php

namespace Orus\Flash\Providers;

use Orus\Flash\Flash;
use Illuminate\Support\ServiceProvider;

class FlashServiceProvider extends ServiceProvider
{  
  /**
   * Indicates if loading of the provider is deferred.
   * 
   * @var bool
   */
  protected $defer = false;

  /**
   * Register the service provider.
   * 
   * @return void 
   */
  public function register()
  {
    $this->app->bind(
      'Orus\Flash\Contracts\Session',
      'Orus\Flash\LaravelStore'
    );

    $this->app->singleton('flash', function() {
      return $this->app->make(Flash::class);
    });
  }

  /**
   * Bootstrap the application events.
   * 
   * @return void
   */
  public function boot()
  {
  }

}

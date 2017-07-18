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
    $this->loadViewsFrom(__DIR__ . '../../../views', "flash");

    $this->publishes([
      __DIR__ . '../../../assets/js/components' => resource_path("assets/js/components")
    ]);

    $this->publishes([
      __DIR__ . '../../../config/flash.php' => config_path("flash.php")
    ], 'config');

    $this->publishes([
      __DIR__ . '../../../views' => resource_path("views/vendor/flash")
    ]);
  }

}

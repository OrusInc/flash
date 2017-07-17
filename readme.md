# A simple php and laravel flash alert package

this package gives you a simple way to manage alerts into your laravel project. It also works with any php project.

## Installation

Include the package in your project using composer.

```bash
composer require orus/flash
```
If you're using a version of laravel prior to 5.5 you need you to include te service provider and the alias in your `config/app.php`.
```bash
"providers"  =>  [
  ...
  Orus\Flash\Providers\FlashServiceProvider::class,
],

"aliases"  =>  [
  ...
  "Flash"  =>  Orus\Flash\Facades\Flash::class
]
```

## Usage

Before performing your redirect, you can call the `flash()` helper function.

```php
Route::post("/login", function() {
  flash("welcome in the matrix");
  
  return redirect("/profile");
}
```
You can specify the alert type by using the fluent api it offers.

```php
flash(); // Flash object.
flash()->default("message") // A default flash alert
flash()->danger("message") // A danger flash alert
flash()->warning("message") // A warning flash alert
flash()->info("message") // An info flash alert
flash()->success("message") // A success flash alert
flash()->default("message")->title("Default") // Set the alert title
flash()->danger("message")->important() // Set the alert as important
flash()->info("message")->options(["key"] => "value") // Add options to the alert
flash("message")->success(); // Or define your message and set the type.
flash()->info("message")->success("message"); // You can chain multiple alerts.


```

It also gives you the ability to set multiple flash alerts.

```php
Route::post("/login", function() {
  flash("welcome in the matrix")->default();
  flash("May the code be with you!")->info();
  
  return redirect("/profile");
}
```

Then you can get a collection of the alerts in your views.

```php
{{ flash()->all() }}
```

<?php

use Orus\Flash\Flash;
use Orus\Flash\LaravelStore;
use PHPUnit\Framework\TestCase;
use Orus\Flash\Contracts\Mineur;

class FlashTest extends TestCase
{

  protected $store;

  protected $flash;

  public function setUp()
  {
    $this->store = Mockery::spy("Orus\Flash\LaravelStore");

    $this->flash = new Flash($this->store);
  }

  /** @test */
  public function it_returns_a_collection_of_alerts()
  {
    $this->assertInstanceOf(
      Illuminate\Support\Collection::class, 
      $this->flash->alerts()
    );
  }

  /** @test */
  public function it_displays_a_default_alert()
  {
    $this->flash->default("New version comming...");
    
    $alert = $this->flash->alerts()->first();

    $this->assertEquals(null, $alert->title);
    $this->assertEquals("New version comming...", $alert->message);
    $this->assertEquals("default", $alert->level);
    $this->assertEquals([], $alert->options);
    $this->assertEquals(false, $alert->important);
    $this->assertCount(1, $this->flash->alerts());

    $this->assertAlertIsRegistered();
  }

  /** @test */
  public function it_displays_an_info_alert()
  {
    $this->flash->info("Flash package ready");
    
    $alert = $this->flash->alerts()->first();

    $this->assertEquals(null, $alert->title);
    $this->assertEquals("Flash package ready", $alert->message);
    $this->assertEquals("info", $alert->level);
    $this->assertEquals([], $alert->options);
    $this->assertEquals(false, $alert->important);
    $this->assertCount(1, $this->flash->alerts());

    $this->assertAlertIsRegistered();
  }

  /** @test */
  public function it_displays_a_warning_alert()
  {
    $this->flash->warning("Wow, pay attention");
    
    $alert = $this->flash->alerts()->first();

    $this->assertEquals(null, $alert->title);
    $this->assertEquals("Wow, pay attention", $alert->message);
    $this->assertEquals("warning", $alert->level);
    $this->assertEquals([], $alert->options);
    $this->assertEquals(false, $alert->important);
    $this->assertCount(1, $this->flash->alerts());

    $this->assertAlertIsRegistered();
  }

  /** @test */
  public function it_displays_a_danger_alert()
  {
    $this->flash->danger("Oups!!!");
    
    $alert = $this->flash->alerts()->first();

    $this->assertEquals(null, $alert->title);
    $this->assertEquals("Oups!!!", $alert->message);
    $this->assertEquals("danger", $alert->level);
    $this->assertEquals([], $alert->options);
    $this->assertEquals(false, $alert->important);
    $this->assertCount(1, $this->flash->alerts());

    $this->assertAlertIsRegistered();
  }

  /** @test */
  public function it_displays_a_success_alert()
  {
    $this->flash->success("Great!!!");
    
    $alert = $this->flash->alerts()->first();

    $this->assertEquals(null, $alert->title);
    $this->assertEquals("Great!!!", $alert->message);
    $this->assertEquals("success", $alert->level);
    $this->assertEquals([], $alert->options);
    $this->assertEquals(false, $alert->important);
    $this->assertCount(1, $this->flash->alerts());

    $this->assertAlertIsRegistered();
  }

  /** @test */
  public function it_display_an_alert_with_title()
  {
    $this->flash->alert("One more step")->title("Onboarding");
    
    $alert = $this->flash->alerts()->first();

    $this->assertEquals("Onboarding", $alert->title);
    $this->assertEquals("One more step", $alert->message);
    $this->assertEquals("default", $alert->level);
    $this->assertEquals([], $alert->options);
    $this->assertEquals(false, $alert->important);
    $this->assertCount(1, $this->flash->alerts());

    $this->assertAlertIsRegistered();
  }

  /** @test */
  public function it_display_an_important_alert()
  {
    $this->flash->alert("The token is missing")->important();
    
    $alert = $this->flash->alerts()->first();

    $this->assertEquals(null, $alert->title);
    $this->assertEquals("The token is missing", $alert->message);
    $this->assertEquals("default", $alert->level);
    $this->assertEquals([], $alert->options);
    $this->assertEquals(true, $alert->important);
    $this->assertCount(1, $this->flash->alerts());

    $this->assertAlertIsRegistered();
  }

  /** @test */
  public function it_can_add_additional_options_to_an_alert()
  {
    $this->flash->alert("@john posted a new comment")->options([
      'avatar' => 'custom_url',
      'icon'  => 'fa_user'
      ]);
    
    $alert = $this->flash->alerts()->first();

    $this->assertEquals(null, $alert->title);
    $this->assertEquals("@john posted a new comment", $alert->message);
    $this->assertEquals("default", $alert->level);
    $this->assertEquals(['avatar' => 'custom_url', 'icon' => 'fa_user'], $alert->options);
    $this->assertEquals(false, $alert->important);
    $this->assertCount(1, $this->flash->alerts());

    $this->assertAlertIsRegistered();
  }

  /** @test */
  public function it_can_chain_alerts()
  {
    $this->flash
      // An info alert.
      ->info("Final step")
      // A success alert
      ->success('Great job!');

    $this->assertCount(2, $this->flash->alerts());

  }

  /** @test */
  public function it_clears_all_alerts()
  {
    $this->flash->alert("One more step");

    $this->assertCount(1, $this->flash->alerts());

    $this->flash->clear();
    $this->assertCount(0, $this->flash->alerts());
  }


  public function assertAlertIsRegistered()
  {
    $this->store
      ->shouldReceive('flash')
      ->with(Flash::SESSION_KEY, $this->flash->alerts())
      ->once();
  }
  
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Models\OneHourElectricity;
use App\Models\Panel;

class OneHourElectricityTest extends TestCase
{

    use RefreshDatabase;
    protected $panel;

    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->panel = factory(Panel::class)->create();
    }

    /** @test */
    public function given_existent_solar_panel_save_fails_with_invalid_other_params()
    {
        $panel = factory(Panel::class)->create();
        $serial = $panel->serial;
        $response = $this->json('post', '/api/one_hour_electricities?panel_serial=' . $serial);
        $response->assertStatus(422, $response);
    }

    /** @test */
    public function given_inexistent_solar_panel_throw_404()
    {
        $response = $this->json('post', '/api/one_hour_electricities?panel_serial=test');
        $response->assertStatus(404, $response);
    }

    /** @test */
    public function given_existent_solar_panel_saves_when_all_params_valid()
    {
        $panel = factory(Panel::class)->create();
        $serial = $panel->serial;
        $params = ['kilowatts' => 9, 'panel_serial' => $serial, 'hour' => "2018-10-10 00:00:00"];
        $response = $this->json('post', '/api/one_hour_electricities', $params);
        $response->assertStatus(201, $response);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexForPanelWithElectricity()
    {
        factory(OneHourElectricity::class)->create(['panel_id' => $this->panel->id]);

        $response = $this->json('GET', '/api/one_hour_electricities?panel_serial=' . $this->panel->serial);

        $response->assertStatus(200);

        $this->assertCount(1, json_decode($response->getContent()));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexForPanelWithoutElectricity()
    {

        $response = $this->json('GET', '/api/one_hour_electricities?panel_serial=' . $this->panel->serial);

        $response->assertStatus(200);

        $this->assertCount(0, json_decode($response->getContent()));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexWithoutExistingPanel()
    {
        $response = $this->json('GET', '/api/one_hour_electricities?panel_serial=testserial');

        $response->assertStatus(404);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexWithoutPanelSerial()
    {
        $response = $this->json('GET', '/api/one_hour_electricities');

        $response->assertStatus(404);
    }
}

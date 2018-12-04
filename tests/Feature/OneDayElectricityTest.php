<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Panel;
use App\Models\OneHourElectricity;
use Illuminate\Foundation\Testing\RefreshDatabase;


class OneDayElectricityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function given_no_panel_serial_should_return_404()
    {
        $response = $this->json('get', '/api/one_day_electricities');
        $response->assertStatus(404, $response);

    }

    /** @test */
    public function given_inexistent_panel_serial_should_return_404()
    {
        $response = $this->json('get', '/api/one_day_electricities?panel_serial=testserial');
        $response->assertStatus(404, $response);
    }

    /** @test */
    public function given_panel_without_data_returns_empty_array()
    {
        $panel = factory(Panel::class)->create();

        $response = $this->json('GET', '/api/one_day_electricities?panel_serial=' . $panel->serial);

        $response->assertStatus(200);

        $this->assertCount(0, json_decode($response->getContent()));
    }

    /** @test */
    public function given_panel_with_data_returns_day_sum_min_max_avg()
    {
        $panel = factory(Panel::class)->create();
        $hour1 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-04 9:00:00']);
        $hour2 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-04 10:00:00']);
        $hour3 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-04 11:00:00']);
        $hour4 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-04 12:00:00']);
        $hour5 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-04 13:00:00']);
        $hour6 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-04 14:00:00']);
        $hour7 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-04 15:00:00']);

        $arr = [$hour1->kilowatts, $hour2->kilowatts, $hour3->kilowatts, $hour4->kilowatts, $hour5->kilowatts, $hour6->kilowatts, $hour7->kilowatts];
        $sum = array_sum($arr);
        $min = min($arr);
        $max = max($arr);
        $average = $sum / count($arr);
        $day = '2018-12-04';


        $response = $this->json('GET', '/api/one_day_electricities?panel_serial=' . $panel->serial);
        $responseArr = json_decode($response->getContent(), true)[0];
        $response->assertStatus(200);

        $this->assertEquals($sum, $responseArr['sum']);
        $this->assertEquals($min, $responseArr['min']);
        $this->assertEquals($max, $responseArr['max']);
        $this->assertEquals($average, $responseArr['average']);
        $this->assertEquals($day, $responseArr['day']);


        $this->assertCount(1, json_decode($response->getContent()));
    }

    /** @test */
    public function given_panel_with_data_of_different_dates_returns_day_sum_min_max_avg()
    {
        $panel = factory(Panel::class)->create();
        $hour1 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-04 9:00:00']);
        $hour2 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-04 10:00:00']);
        $hour3 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-04 11:00:00']);
        $hour4 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-04 12:00:00']);
        $hour5 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-05 13:00:00']);
        $hour6 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-05 14:00:00']);
        $hour7 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-05 15:00:00']);
        $hour8 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-06 14:00:00']);
        $hour9 = factory(OneHourElectricity::class)->create(['panel_id' => $panel->id, 'hour' => '2018-12-06 15:00:00']);


        $arr1 = [$hour1->kilowatts, $hour2->kilowatts, $hour3->kilowatts, $hour4->kilowatts];
        $arr2 = [$hour5->kilowatts, $hour6->kilowatts, $hour7->kilowatts];
        $arr3 = [$hour8->kilowatts, $hour9->kilowatts];
        $sum1 = array_sum($arr1);
        $min1 = min($arr1);
        $max1 = max($arr1);
        $average1 = $sum1 / count($arr1);
        $day1 = '2018-12-04';

        $sum2 = array_sum($arr2);
        $min2 = min($arr2);
        $max2 = max($arr2);
        $average2 = $sum2 / count($arr2);
        $day2 = '2018-12-05';

        $sum3 = array_sum($arr3);
        $min3 = min($arr3);
        $max3 = max($arr3);
        $average3 = $sum3 / count($arr3);
        $day3 = '2018-12-06';


        $response = $this->json('GET', '/api/one_day_electricities?panel_serial=' . $panel->serial);
        $responseArr = json_decode($response->getContent(),true);
        $response->assertStatus(200);

        foreach ($responseArr as $response) {
            if ($response['day'] == $day1) {
                $this->assertEquals($sum1, $response['sum']);
                $this->assertEquals($min1, $response['min']);
                $this->assertEquals($max1, $response['max']);
                $this->assertEquals($average1, $response['average']);
                $this->assertEquals($day1, $response['day']);
            } else if ($response['day'] == $day2) {
                $this->assertEquals($sum2, $response['sum']);
                $this->assertEquals($min2, $response['min']);
                $this->assertEquals($max2, $response['max']);
                $this->assertEquals($average2, $response['average']);
                $this->assertEquals($day2, $response['day']);
            } else if ($response['day'] == $day3) {
                $this->assertEquals($sum3, $response['sum']);
                $this->assertEquals($min3, $response['min']);
                $this->assertEquals($max3, $response['max']);
                $this->assertEquals($average3, $response['average']);
                $this->assertEquals($day3, $response['day']);
            }
        }

        $this->assertCount(3, $responseArr);
    }
}

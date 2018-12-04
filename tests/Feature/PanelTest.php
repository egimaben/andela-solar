<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PanelTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function given_valid_values_returns_201()
    {
        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef012345678',
            'longitude' => 0,
            'latitude'  => 0
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function given_invalid_serial_length_returns_status_422()
    {
        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef0123456789',
            'longitude' => 0,
            'latitude' =>0
        ]);

        $response->assertStatus(422);
    }
    /** @test */
    public function given_invalid_longitude_value_returns_status_422(){
        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef012345678',
            'longitude' => 181,
            'latitude' =>0
        ]);

        $response->assertStatus(422);
    }
    /** @test */
    public function given_invalid_latitude_value_returns_status_422(){
        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef012345678',
            'longitude' => 0,
            'latitude' =>91
        ]);

        $response->assertStatus(422);
    }
}

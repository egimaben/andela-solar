<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PanelTest extends TestCase
{
    /**
     *
    'serial'    => 'required|unique:panels|size:15',
    'latitude'  => 'required|numeric|between:-90,90',
    'longitude'  => 'required|numeric|between:-180,180'
    ];
     *   Classes: 75.00% (3/4)
    Methods: 80.00% (4/5)
    Lines:   73.33% (22/30)
     */

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
    /** @test */
    public function given_non_numeric_latitude_returns_422(){
        $response = $this->json('POST', '/api/panels', [
            'serial'    => 'abcdef012345678',
            'longitude' => 0,
            'latitude' =>'test'
        ]);

        $response->assertStatus(422);
    }
}

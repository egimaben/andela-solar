<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Panel;

class OneDayElectricityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $panel = Panel::where('serial', $request->panel_serial)->firstOrFail();
        $hourlies = $panel->oneHourElectricities()->get();
        $dateToHourlyData = $hourlies->pluck('kilowatts', 'hour');
        $dailyData = [];
        foreach ($dateToHourlyData as $date => $value) {
            $day = explode(" ", $date)[0];
            $dailyData[$day][] = $value;
        }

        $response = [];
        foreach ($dailyData as $day => $kilowatts) {
            $sum = array_sum($kilowatts);
            $min = min($kilowatts);
            $max = max($kilowatts);
            $average = $sum / count($kilowatts);

            $response[] = ['day' => $day, 'sum' => $sum, 'min' => $min, 'max' => $max, 'average' => $average];
        }
        return $response;
    }
}

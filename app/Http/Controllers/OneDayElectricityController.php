<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Panel;
use App\Models\OneHourElectricity;
use Illuminate\Support\Facades\DB;

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
//        $response = OneHourElectricity::select(
//            array(
//                DB::Raw('date(hour) day'),
//                DB::Raw('MIN(kilowatts) min'),
//                DB::Raw('MAX(kilowatts) max'),
//                DB::Raw('AVG(kilowatts) average'),
//                DB::Raw('SUM(kilowatts) sum')))
//            ->where('panel_id', $panel->id)
//            ->groupBy('day')
//            ->get();


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

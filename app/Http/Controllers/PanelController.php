<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Validator;

use App\Models\Panel;

class PanelController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Panel::$fieldValidations);
        if ($validator->fails()) {
            return Response::json($validator->errors()->all(), 422);
        }
        return Panel::create($request->all());
    }
}

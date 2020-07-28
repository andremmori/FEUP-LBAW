<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function cities(Request $request){
        $cities = City::all()->where('idcountry', $request->input('idcountry'));
        $html = '';

        foreach ($cities as $city) {
            $html .= '<option value="' . $city->id . '">' . $city->name . '</option>';
        }

        return response()->json(['html' => $html]);
    }
}

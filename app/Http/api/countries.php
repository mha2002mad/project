<?php

namespace App\Http\api;

use App\Http\Controllers\Controller;
use App\Services\CountriesServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class countries extends Controller
{
    protected CountriesServices $countriesServices;

    public function __construct(CountriesServices $countriesServices)
    {
        $this->countriesServices = $countriesServices;
    }


/**
 * Get a list of countries.
 * if you do not specify chunk, all countries will be received
 *
 * @group Countries
 * @method GET
 * @header Authorization Bearer required 
 * @queryParam chunk integer Optional. Number of countries to return. Example: 15
 */
    public function getCountries(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'chunk' => 'regex:/^[0-9]+$/'
        ]);

        $validated->validate();

        try {
            $countries = $this->countriesServices->getAllCountries($request->query('chunk'));
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
        
        return response()->json($countries, 200);
    }

    /**
     * create a country.
     *
     * @group Countries
     * @header Authorization Bearer required 
     * @method POST
     * 
     * @bodyParam name string required the country name. ex:spain
     * @bodyParam code string required the country code can nott have more than 2 letters. ex:sp
     */
    public function createCountry(Request $request)
    {
        $request->validate([
            "name" => 'string|required',
            "code" => 'string|required|size:2|regex:/^[a-zA-Z]+$/',
        ]);

        try {
            $this->countriesServices->addCountry(
            [
                'name' => $request->input('name'),
                'code' => $request->input('code'),
            ]
        );
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        }

        return response()->json(['message' => 'country created sucessfully'], 201);
    }
}

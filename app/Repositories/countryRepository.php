<?php

namespace App\Repositories;
use App\Models\countries;
use App\Repositories\Interfaces\countriesInterface;
use Exception;

class CountryRepository implements countriesInterface
{
    public function getAllCountries($chunk)
    {
        if ($chunk == null) {
            return countries::get();
        }
        return countries::limit($chunk)->get();
    }

    public function getCountryById($id)
    {
        try {
            return countries::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('country does not exist');
        }
    }

    public function createCountry(array $data)
    {
        if(countries::where('code', $data['code'])->exists()){
            throw new \Exception("Country with code {$data["code"]} already exists.");
        }
        
        if(countries::where('name', $data['name'])->exists()){
            throw new \Exception("Country with code {$data['name']} already exists.");
        }

        countries::create($data)->save();
    }

    public function deleteCountry($id)
    {
        try {
            countries::destroy($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateCountryName($id, $name)
    {
        try {
            $country = countries::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('country does not exist');
        }
        $country->name = $name;
    }
}
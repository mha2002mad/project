<?php
namespace App\Services\interfaces;

interface CountryServicesInterface
{
    public function addCountry($data);
    public function getAllCountries($chunk);
    public function getCountryById($id);
    public function deleteCountry($id);
    public function updateCountryName($id, $name);
}
<?php
namespace App\Repositories\Interfaces;
interface countriesInterface
{
    public function getAllCountries($chunk);
    public function getCountryById($id);
    public function createCountry(array $data);
    public function getStockLevelByCountry($id);
    public function updateCountryName($id, $Name);
    public function deleteCountry($id);
}
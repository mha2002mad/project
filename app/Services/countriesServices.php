<?php
namespace App\Services;

use App\Repositories\CountryRepository;
use App\Services\interfaces\CountryServicesInterface;

class CountriesServices implements CountryServicesInterface
{
    protected CountryRepository $countriesRepository;

    public function __construct(CountryRepository $countriesRepository)
    {
        $this->countriesRepository = $countriesRepository;
    }
    public function addCountry($data)
    {
        return $this->countriesRepository->createCountry($data);
    }

    public function getAllCountries($chunk)
    {
        return $this->countriesRepository->getAllCountries($chunk);
    }

    public function getCountryById($id)
    {
        return $this->countriesRepository->getCountryById($id);
    }

    public function deleteCountry($id)
    {
        return $this->countriesRepository->deleteCountry($id);
    }

    public function updateCountryName($id, $name)
    {
        return $this->countriesRepository->updateCountryName($id, $name);
    }
}
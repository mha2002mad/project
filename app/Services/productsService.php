<?php
namespace App\Services;

use App\Repositories\productsRepository;
use App\Services\interfaces\productsServiceInterface;
use Exception;

class ProductsService  implements productsServiceInterface {
    protected $productsRepository;

    public function __construct(productsRepository $productsRepository) {
        $this->productsRepository = $productsRepository;
    }

    public function getAllProducts($chunk) {
        return $this->productsRepository->getAllProducts($chunk);
    }

    public function getProductById($id) {
        return $this->productsRepository->getProductById($id);
    }

    public function createProduct(array $data) {
        $this->productsRepository->createProduct($data);
    }

    public function updateProductDescription($id, string $description) {
        $this->productsRepository->updateProductDescription($id, $description);
    }

    public function updateProductName($id, string $name) {
        $this->productsRepository->updateProductName($id, $name);
    }

    public function updateProductStatus($id, string $status) {
        $this->productsRepository->updateProductStatus($id, $status);
    }

    public function updateProductPrice($id, float $price) {
        $this->productsRepository->updateProductPrice($id, $price);
    }

    public function deleteProduct($id) {
        $this->productsRepository->deleteProduct($id);
    }
}
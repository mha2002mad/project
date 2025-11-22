<?php
namespace App\Repositories\Interfaces;

interface productsInterface
{
    public function getAllProducts($chunk);
    public function getProductById($id);
    public function createProduct(array $data);
    public function getProductLevelOnWarehouses($id);
    public function updateProductDescription($id, string $description);
    public function updateProductName($id, string $name);
    public function updateProductStatus($id, string $status);
    public function updateProductPrice($id, float $price);
    public function deleteProduct($id);
}
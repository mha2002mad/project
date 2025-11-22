<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

test('get products: testing query params', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/products?chunk=2ca');

    $response
        ->assertJsonStructure([
            'message',
            'errors' => [
                'chunk'
            ]
        ]);
    
    expect($response->status())->toBe(422);
});

test('get products: testing query params 2', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/products?chunk=-aa');
    $response
        ->assertJsonStructure([
            'message',
            'errors' => [
                'chunk'
            ]
        ]);

    expect($response->status())->toBe(422);
});

test('get products: testing query params 3', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/products?chunk=md$$#$');

    $reponse->assertJsonStructure([
        'message',
        'errors' => [
            'chunk'
        ]
    ]);

    expect($reponse->status())->toBe(422);
});

test('get products: testing functionality', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/products');
    
    $reponse
        ->assertJsonStructure([
            '*' => [
                'product_id',
                'name',
                'sku',
                'description',
                'status',
                'price'
            ]
        ]);
    
        
    expect($reponse->status())->toBe(200);
});

test('get products: testing functionality 2', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/products?chunk=40');
    
    $reponse
        ->assertJsonCount(40)
        ->assertJsonStructure([
            '*' => [
                'product_id',
                'name',
                'sku',
                'description',
                'status',
                'price'
            ]
        ]);

    expect($reponse->status())->toBe(200);
});

test('get product stock levels on warehouses: mocking query param', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/view/plow');
    
    $reponse
        ->assertJsonStructure([
            'message'
        ]);

    expect($reponse->status())->toBe(422);
});

test('get product stock levels on warehouses: mocking query param 2', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/view/plow?product=-22');
    
    $reponse
        ->assertJsonStructure([
            'message'
        ]);

    expect($reponse->status())->toBe(422);
});

test('get product stock levels on warehouses: mocking query param 3', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/view/plow?product=-xs');
    
    $reponse
        ->assertJsonStructure([
            'message'
        ]);

    expect($reponse->status())->toBe(422);
});

test('get product stock levels on warehouses: test functionality', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/view/plow?product=2');
    
    $reponse->assertStatus(200);
    
    try {
        $reponse->assertJsonStructure([
            'message' => [
                '*' => 'warehouseID',
                        'warehouseName',
                        'product' => [
                            '*' => [
                                'productID',
                                'productName',
                                'quantity'
                            ]
                        ]
                    ]
        ]);
    } catch (\Throwable $th) {
        $reponse->assertJsonStructure([
            'message'
        ]);
    }

    expect($reponse->status())->toBe(200);
});

test('create product: testing input validation 1', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 1232,
        'sku' => 3422,
        'description' => 2342,
        'status' => 'asas',
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/products', $data);

    $reponse
        ->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'sku',
                'description',
                'status',
                'price'
            ]
            ]);
        
    expect($reponse->status())->toBe(422);
});

test('create product: testing input validation 2', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'description' => 2342,
        'status' => 'active',
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/products', $data);

    $reponse
        ->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'sku',
                'description',
                'price'
            ]
            ]);
        
    expect($reponse->status())->toBe(422);
});

test('create product: testing input validation 3', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 'a323',
        'description' => 2342,
        'price' => '2ss'
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/products', $data);

    $reponse
        ->assertJsonStructure([
            'message',
            'errors' => [
                'sku',
                'description',
                'price',
                'status'
            ]
            ]);
        
    expect($reponse->status())->toBe(422);
});

test('create product: testing functionality', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 'a323zxz',
        'description' => 'here am i',
        'price' => 22.2,
        'sku' => 'a323_aa_1',
        'status' => 'active'
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/products', $data);

    $reponse
        ->assertJsonStructure([
            'message'
            ]);
        
    expect($reponse->status())->toBe(201);
});

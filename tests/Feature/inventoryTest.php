<?php

namespace Tests\Feature;
use App\Models\User;
use Tests\TestCase;

test('new transaction: input mocking 1', function () {
    $data = [
        "product" => 'abc',
        "warehouse" => "abc",
        "supplier" => "abc",
        "quantity" => '-1',
        "transaction_type" => "in",
        "created_by" => 1
    ];
    
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', "Bearer " . $token)->postJson('api/inventory/newtransaction', $data);

    expect($reponse->status())->toBe(422);    
});

test('new transaction: input mocking 2', function () {
    $data = [
        "product" => '',
        "warehouse" => "",
        "supplier" => "",
        "quantity" => '',
        "transaction_type" => "",
        "created_by" => "",
    ];
    
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', "Bearer " . $token)->postJson('api/inventory/newtransaction', $data);

    expect($reponse->status())->toBe(422);    
});

test('new transaction: input mocking 3', function () {
    $data = [
        "product" => '*&NB^',
        "warehouse" => "M*N&B%",
        "supplier" => "(*N&",
        "quantity" => '*&N',
        "transaction_type" => "M*&N",
        "created_by" => "M*&N",
    ];
    
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', "Bearer " . $token)->postJson('api/inventory/newtransaction', $data);

    expect($reponse->status())->toBe(422);    
});

test('new transaction: input mocking 4', function () {
    $data = [
        
    ];
    
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', "Bearer " . $token)->postJson('api/inventory/newtransaction', $data);

    expect($reponse->status())->toBe(422);    
});

test('new transaction: functionality test', function () {
    $data = [
        "product" => 1,
        "warehouse" => 2,
        "supplier" => 1,
        "quantity" => 2,
        "transaction_type" => 'in',
        "created_by" => 1,
    ];
    
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', "Bearer " . $token)->postJson('api/inventory/newtransaction', $data);

    expect($reponse->status())->toBe(400);
});

test('new transaction: functionality test 2', function () {
    $data = [
        "product" => 1,
        "warehouse" => 2,
        "supplier" => 1,
        "quantity" => 2,
        "transaction_type" => 'out',
        "created_by" => 1,
    ];
    
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', "Bearer " . $token)->postJson('api/inventory/newtransaction', $data);

    expect($reponse->status())->toBe(400);
});

test('transfer inventory between warehouses: mocking inputs ', function () {
    $data = [
        "product" => '',
        "from_warehouse" => '',
        "to_warehouse" => '',
        "created_by" => '',
        "quantity" => ''
    ];
    
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', "Bearer " . $token)->postJson('api/inventory-transfer', $data);

    expect($reponse->status())->toBe(422);
});

test('transfer inventory between warehouses: mocking inputs 2', function () {
    $data = [
        "product" => 'a1a',
        "from_warehouse" => 'a1a',
        "to_warehouse" => 'aa1',
        "created_by" => 'a1a',
        "quantity" => 'aa1'
    ];
    
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->postJson('api/inventory-transfer', $data);

    expect($reponse->status())->toBe(422);
});

test('transfer inventory between warehouses: mocking inputs 3', function () {
    $data = [
        "product" => '-1',
        "from_warehouse" => '-1',
        "to_warehouse" => '-0',
        "created_by" => '-9',
        "quantity" => '-765'
    ];
    
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', "Bearer " . $token)->postJson('api/inventory-transfer', $data);

    expect($reponse->status())->toBe(422);
});

test('transfer inventory between warehouses functionality test', function () {
    $data = [
        "product" => 1,
        "from_warehouse" => 1,
        "to_warehouse" => 1,
        "created_by" => 1,
        "quantity" => 2
    ];
    
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);
    
    $reponse = $this->withHeader('Authorization', "Bearer " . $token)->postJson('api/inventory-transfer', $data);
    
    expect($reponse->status())->toBe(400);
});

test('global inventory view: mock inputs', function (){
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);
    
    $reponse = $this->withHeader('Authorization', "Bearer " . $token)->getJson('api/inventory/globalview');

    $reponse->assertJsonStructure([
        'message'
    ]);
    
    expect($reponse->status())->toBe(422);
});

test('global inventory view: mock inputs 2', function (){
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);
    
    $reponse = $this->withHeader('Authorization', "Bearer " . $token)->getJson('api/inventory/globalview?country=3');

    $reponse->assertJsonStructure([
        'message'
    ]);
    
    expect($reponse->status())->toBe(422);
});

test('global inventory view: mock inputs 3', function (){
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);
    
    $reponse = $this->withHeader('Authorization', "Bearer " . $token)
    ->getJson('api/inventory/globalview?product=aa');

    $reponse->assertJsonStructure([
        'message',
        'errors' => [
            "product"
            ]
        ]);

    expect($reponse->status())->toBe(422);
});

test('global inventory view: mock inputs 4', function (){
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);
    
    $reponse = $this->withHeader('Authorization', "Bearer " . $token)
    ->getJson('api/inventory/globalview?chunk=-11');

    $reponse->assertJsonStructure([
        'message'
    ]);
    
    expect($reponse->status())->toBe(422);
});

test('global inventory view: functionality by product', function (){
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);
    
    $reponse = $this->withHeader('Authorization', "Bearer " . $token)
    ->getJson('api/inventory/globalview?product=2');
    
    expect($reponse->status())->toBe(200);
});

test('global inventory view: functionality by country', function (){
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);
    
    $response = $this->withHeader('Authorization', "Bearer " . $token)
    ->getJson('api/inventory/globalview?product=2&country=4');


    $response->assertJsonStructure([
        'message',
    ]);
    
    expect($response->status())->toBe(200);
});

test('global inventory view: functionality by warehouse', function (){
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);
    
    $response = $this->withHeader('Authorization', "Bearer " . $token)
    ->getJson('api/inventory/globalview?product=2&country=4&warehouse=2');
    
    expect($response->status())->toBe(200);
});
<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

test('get warehouses: testing query params', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/warehouses?chunk=-1');

    $response
        ->assertJsonStructure([
            'message'
        ]);
    
    expect($response->status())->toBe(422);
});

test('get warehouses: testing query params 2', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/warehouses?chunk=-aa');
    $response
        ->assertJsonStructure([
            'message'
        ]);

    expect($response->status())->toBe(422);
});

test('get warehouses: testing query params 3', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/warehouses?chunk=md$$#$');

    $reponse->assertJsonStructure([
        'message'
    ]);

    expect($reponse->status())->toBe(422);
});

test('get warehouses: testing functionality', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/warehouses');
    
    $reponse
        ->assertJsonStructure([
            '*' => [
                'warehouse_id',
                'location',
                'name',
                'country'
            ]
        ]);
    
        
    expect($reponse->status())->toBe(200);
});

test('get warehouse: testing functionality 2', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/warehouses?chunk=20');
    
    $reponse
        ->assertJsonCount(20)
        ->assertJsonStructure([
            '*' => [
                'warehouse_id',
                'location',
                'name',
                'country'
            ]
        ]);

    expect($reponse->status())->toBe(200);
});

test('create warehouse: testing input validation 1', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 'nornorway'
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/warehouses', $data);

    $reponse
        ->assertJsonStructure([
            'message',
            'errors' => [
                'location',
                'country'
            ]
            ]);
        
    expect($reponse->status())->toBe(422);
});

test('create warehouse: testing input validation 2', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => '',
        'country' => 2
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/warehouses', $data);

    $reponse
        ->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'location'
            ]
            ]);
        
    expect($reponse->status())->toBe(422);
});

test('create warehouse: testing input validation 3', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 232,
        'location' => 232,
        'country' => 'aa'
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/warehouses', $data);

    $reponse
        ->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'location',
                'country'
            ]
            ]);
        
    expect($reponse->status())->toBe(422);
});

test('create warehouse: testing input validation 4', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 232,
        'location' => 'a',
        'country' => 'aa'
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/warehouses', $data);

    $reponse
        ->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'location',
                'country'
            ]
            ]);
        
    expect($reponse->status())->toBe(422);
});

test('create warehouse: testing functionality', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 'nonorway',
        'location' => 'erbil,soran',
        'country' => 3
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/warehouses', $data);

    $reponse
        ->assertJsonStructure([
            'message'
            ]);
        
    expect($reponse->status())->toBe(201);
});

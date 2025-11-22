<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

test('get suppliers: testing query params', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/suppliers?chunk=2ca');

    $response
        ->assertJsonStructure([
            'message',
            'errors' => [
                'chunk'
            ]
        ]);
    
    expect($response->status())->toBe(422);
});

test('get suppliers: testing query params 2', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/suppliers?chunk=-aa');
    $response
        ->assertJsonStructure([
            'message',
            'errors' => [
                'chunk'
            ]
        ]);

    expect($response->status())->toBe(422);
});

test('get suppliers: testing query params 3', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/suppliers?chunk=md$$#$');

    $reponse->assertJsonStructure([
        'message',
        'errors' => [
            'chunk'
        ]
    ]);

    expect($reponse->status())->toBe(422);
});

test('get suppliers: testing functionality', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/suppliers');
    
    $reponse
        ->assertJsonStructure([
            '*' => [
                'supplier_id',
                'name',
                'contact_info',
                'address',
            ]
        ]);
    
        
    expect($reponse->status())->toBe(200);
});

test('get suppliers: testing functionality 2', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/suppliers?chunk=40');
    
    $reponse
        ->assertJsonCount(40)
        ->assertJsonStructure([
            '*' => [
                'supplier_id',
                'name',
                'contact_info',
                'address',
            ]
        ]);

    expect($reponse->status())->toBe(200);
});

test('create supplier: testing input validation 1', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 1232,
        'contact_info' => 3422,
        'address'=> 'ada'
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/suppliers', $data);

    $reponse
        ->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'contact_info',
                'address'
            ]
            ]);
        
    expect($reponse->status())->toBe(422);
});

test('create supplier: testing input validation 2', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => '',
        'contact_info' => '',
        'address' => '-1'
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/suppliers', $data);

    $reponse
        ->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'contact_info',
                'address'
            ]
            ]);
        
    expect($reponse->status())->toBe(422);
});

test('create product: testing input validation 3', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 'a323',
        'contact_info' => '2342',
        'address' => '2ss'
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/suppliers', $data);

    $reponse
        ->assertJsonStructure([
            'message',
            'errors' => [
                'name',
                'contact_info',
                'address'
            ]
            ]);
        
    expect($reponse->status())->toBe(422);
});

test('create supplier: testing functionality', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 'a323zxz',
        'contact_info' => '964 0000 000 000 00 00',
        'address' => 'erbil/soran/abcd'
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/suppliers', $data);

    $reponse
        ->assertJsonStructure([
            'message'
            ]);
        
    expect($reponse->status())->toBe(201);
});

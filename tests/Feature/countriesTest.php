<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

test('get countries: testing query params', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/countries?chunk=-1');

    $response
        ->assertJsonStructure([
            'message'
        ]);
    
    expect($response->status())->toBe(422);
});

test('get countries: testing query params 2', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/countries?chunk=-aa');
    $response
        ->assertJsonStructure([
            'message'
        ]);

    expect($response->status())->toBe(422);
});

test('get countries: testing query params 3', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/countries?chunk=mdow22$$$');

    $reponse->assertJsonStructure([
        'message'
    ]);

    expect($reponse->status())->toBe(422);
});

test('get countries: testing functionality', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/countries');
    
    $reponse
        ->assertJsonStructure([
            '*' => [
                'country_id',
                'name',
                'code'
            ]
        ]);
    
        
    expect($reponse->status())->toBe(200);
});

test('get countries: testing functionality 2', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson('/api/countries?chunk=10');
    
    $reponse
        ->assertJsonCount(10)
        ->assertJsonStructure([
            '*' => [
            'country_id',
            'name',
            'code'
            ]
        ]);

    expect($reponse->status())->toBe(200);
});

test('create country: testing input validation 1', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 'nornorway',
        'code' => 'wwww'
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/countries', $data);

    $reponse
        ->assertJsonStructure([
            'message',
            'errors' => [
                'code'
            ]
            ]);
        
    expect($reponse->status())->toBe(422);
});

test('create country: testing input validation 2', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 'nornorway',
        'code' => ''
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/countries', $data);

    $reponse
        ->assertJsonStructure([
            'message',
            'errors' => [
                'code'
            ]
            ]);
        
    expect($reponse->status())->toBe(422);
});

test('create country: testing input validation 3', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 'nornorway',
        'code' => 232
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/countries', $data);

    $reponse
        ->assertJsonStructure([
            'message',
            'errors' => [
                'code'
            ]
            ]);
        
    expect($reponse->status())->toBe(422);
});

test('create country: testing functionality', function () {
    $user = User::factory()->create();
    $token = auth()->guard('api')->login($user);

    $data = [
        'name' => 'nonorway',
        'code' => 'no'
    ];

    $reponse = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/countries', $data);

    $reponse
        ->assertJsonStructure([
            'message'
            ]);
        
    expect($reponse->status())->toBe(201);
});

<?php
namespace Tests\Feature;
use Tests\TestCase;

test('register: testing with only name', function () {
    $data = [
        'name' => 'aaa' 
    ];

    $response = $this->postJson('api/register', $data);
    
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'email',
            'password'
        ]
    ]);

    $response->assertStatus(422);

    expect($response->json())->toBeArray();
});

test('register: testing with only email', function () {
    $data = [
        'email' => 2345678
    ];

    $response = $this->postJson('api/register', $data);
    
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'name',
            'email',
            'password'
        ]
    ]);

    $response->assertStatus(422);

    expect($response->json())->toBeArray($response->__tostring());
});

test('register: testing with random data 1', function () {
    $data = [
        'email' => 2345678,
        'password' => 2345678,
        'name' => '1wxxw2'
    ];

    $response = $this->postJson('api/register', $data);
    
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'email',
            'password'
        ]
    ]);

    $response->assertStatus(422);

    expect($response->json())->toBeArray($response->__tostring());
});

test('register: testing with random data 2', function () {
    $data = [
        'email' => '2345ss678',
        'password' => '678',
        'name' => 232
    ];

    $response = $this->postJson('api/register', $data);
    
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'name',
            'email',
            'password'
        ]
    ]);

    $response->assertStatus(422);

    expect($response->json())->toBeArray($response->__tostring());
});

test('register: testing with random data 3', function () {
    $data = [
        'email' => '2345ss678',
        'password' => '678',
        'name' => '1wxxw2'
    ];

    $response = $this->postJson('api/register', $data);
    
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'email',
            'password'
        ]
    ]);

    $response->assertStatus(422);

    expect($response->json())->toBeArray($response->__tostring());
});

test('register: register a user', function () {
    $data = [
        'email' => 'aaxaqwqwcfhaha@gmail.com',
        'password' => 'd3f4g50jdjc',
        'name' => 'frank'
    ];

    $response = $this->postJson('api/register', $data);
    
    $response->assertJsonStructure(['key']);

    $response->assertStatus(200);

    $this->assertDatabaseHas('users', [
        'email' => 'aaxaqwqwcfhaha@gmail.com'
    ]);

    $token = $response->json('key');

    $this->withHeader('Authorization', "Bearer" . $token)
        ->getJson('api/countries');
    
    $this->withHeader('Authorization', "Bearer" . $token)
        ->getJson('api/warehouses');

    expect($response->json())->toBeArray($response->__tostring());
});

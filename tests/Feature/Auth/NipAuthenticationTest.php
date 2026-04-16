<?php

use App\Models\User;

test('users can authenticate using email and password', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can authenticate using username and password', function () {
    $user = User::factory()->create(['name' => 'John Doe']);

    $response = $this->post('/login', [
        'email' => 'John Doe', // using name as identifier
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can authenticate using nip and password', function () {
    $plainNip = '12345678901234567890'; // 20 digits
    $user = User::factory()->create([
        'nip' => $plainNip,
    ]);

    $response = $this->post('/login', [
        'email' => $plainNip,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users cannot authenticate with invalid nip format', function () {
    $user = User::factory()->create([
        'nip' => 'invalid-nip',
    ]);

    $this->post('/login', [
        'email' => 'invalid-nip',
        'password' => 'password',
    ]);

    $this->assertGuest();
});

test('nip format validation requires 16-20 digits', function () {
    // Test too short
    $shortNip = '123456789012345'; // 15 digits
    $user = User::factory()->create([
        'nip' => $shortNip,
    ]);

    $this->post('/login', [
        'email' => $shortNip,
        'password' => 'password',
    ]);

    $this->assertGuest();

    // Test too long
    $longNip = '123456789012345678901'; // 21 digits
    $this->post('/login', [
        'email' => $longNip,
        'password' => 'password',
    ]);

    $this->assertGuest();
});

test('users cannot authenticate with wrong password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});


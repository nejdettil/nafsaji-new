<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\Hash;
use App\Models\User;

$user = User::create([
    'name' => 'Admin',
    'email' => 'admin@nafsaji.com',
    'password' => Hash::make('Nafsaji2025!'),
]);

echo "User created successfully!\n";

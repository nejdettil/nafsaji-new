<?php

return [
    'brand' => [
        'name' => 'نفسجي',
        'logo' => 'images/logo.svg',
    ],
    'colors' => [
        'primary' => [
            '50' => '#f0f9ff',
            '100' => '#e0f2fe',
            '200' => '#bae6fd',
            '300' => '#7dd3fc',
            '400' => '#38bdf8',
            '500' => '#0ea5e9', // اللون الأساسي
            '600' => '#0284c7',
            '700' => '#0369a1',
            '800' => '#075985',
            '900' => '#0c4a6e',
        ],
        'secondary' => [
            '50' => '#f5f3ff',
            '100' => '#ede9fe',
            '200' => '#ddd6fe',
            '300' => '#c4b5fd',
            '400' => '#a78bfa',
            '500' => '#8b5cf6',
            '600' => '#7c3aed',
            '700' => '#6d28d9',
            '800' => '#5b21b6',
            '900' => '#4c1d95',
        ],
    ],
    'typography' => [
        'font_family' => 'Cairo, sans-serif',
    ],
    'layout' => [
        'sidebar' => [
            'is_dark_mode_enabled' => true,
            'width' => '250px',
        ],
    ],
    'auth' => [
        'guard' => 'web',
        'layout' => 'filament::components.layouts.card',
        'colors' => [
            'background' => '#f0f9ff',
            'form' => 'white',
        ],
    ],
];

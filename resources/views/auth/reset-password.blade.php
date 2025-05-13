@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center mb-6">{{ __('pages.auth.reset_password') }}</h2>
            
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        {{ __('pages.auth.email') }}
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ $email ?? old('email') }}"
                        required 
                        autocomplete="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        {{ __('pages.auth.new_password') }}
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        autocomplete="new-password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    >
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        {{ __('pages.auth.confirm_password') }}
                    </label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    >
                </div>

                <div class="flex items-center justify-between">
                    <button 
                        type="submit" 
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                        {{ __('pages.auth.reset_password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

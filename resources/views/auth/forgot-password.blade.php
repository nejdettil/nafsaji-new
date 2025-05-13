@extends('layouts.app')

@section('title', __('pages.auth.forgot_password.title'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-blue-600">
                {{ __('pages.auth.forgot_password.title') }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ __('pages.auth.forgot_password.subtitle') }}
            </p>
        </div>
        <form class="mt-8 space-y-6" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('pages.auth.login.email') }}
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        required 
                        class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                        placeholder="{{ __('pages.auth.login.email') }}"
                    >
                </div>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    {{ __('pages.auth.forgot_password.send_reset_link') }}
                </button>
            </div>
        </form>

        <div class="text-center">
            <p class="mt-2 text-sm text-gray-600">
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    {{ __('pages.auth.login.submit') }}
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

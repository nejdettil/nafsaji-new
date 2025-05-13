@extends('layouts.app')

@section('content')
<div class="container mx-auto py-12">
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <img src="{{ $specialist->avatar ?? 'default-avatar.png' }}" 
                     class="w-64 h-64 rounded-full mx-auto mb-6 object-cover">
                
                <h1 class="text-3xl font-bold text-purple-800">
                    {{ $specialist->name }}
                </h1>
                
                <p class="text-gray-600 mb-4">
                    {{ $specialist->speciality }}
                </p>
            </div>
            
            <div class="md:col-span-2">
                <h2 class="text-2xl font-semibold text-purple-700 mb-4">
                    {{ __('messages.about_specialist') }}
                </h2>
                
                <p class="text-gray-700 mb-6">
                    {{ $specialist->bio }}
                </p>
                
                <div class="bg-purple-50 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold text-purple-800 mb-4">
                        {{ __('messages.specialization') }}
                    </h3>
                    
                    <ul class="list-disc list-inside text-gray-700">
                        @foreach($specialist->specializations as $spec)
                        <li>{{ $spec }}</li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="mt-8">
                    <a href="{{ route('book.session', $specialist->id) }}" 
                       class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700">
                        {{ __('messages.book_session') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

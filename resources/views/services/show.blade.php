@extends('layouts.app')

@section('content')
<div class="container mx-auto py-12">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold text-purple-800 mb-6">
            {{ $service->name }}
        </h1>
        
        <div class="grid md:grid-cols-2 gap-8">
            <div>
                <p class="text-gray-700 mb-6">
                    {{ $service->description }}
                </p>
                
                <div class="bg-purple-50 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold text-purple-800 mb-4">
                        {{ __('messages.service_details') }}
                    </h3>
                    
                    <ul class="list-disc list-inside text-gray-700">
                        @foreach($service->details as $detail)
                        <li>{{ $detail }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div>
                <div class="bg-white border border-purple-200 rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-purple-700 mb-4">
                        {{ __('messages.pricing') }}
                    </h3>
                    
                    <p class="text-2xl font-bold text-purple-800">
                        {{ $service->price }} {{ __('messages.currency') }}
                    </p>
                    
                    <p class="text-gray-600 mb-4">
                        {{ __('messages.per_session') }}
                    </p>
                    
                    <a href="{{ route('book.service', $service->id) }}" 
                       class="bg-purple-600 text-white w-full block text-center px-6 py-3 rounded-lg hover:bg-purple-700">
                        {{ __('messages.book_now') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

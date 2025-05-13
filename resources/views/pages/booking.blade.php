@extends('layouts.app')

@section('title', __('pages.booking.title'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <header class="text-center mb-12">
        <h1 class="text-4xl font-bold text-purple-700 mb-4">
            {{ __('pages.booking.title') }}
        </h1>
        <p class="text-xl text-gray-600">
            {{ __('pages.booking.subtitle') }}
        </p>
    </header>

    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('booking.store') }}" method="POST">
            @csrf
            
            <!-- معلومات المختص والخدمة -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('pages.booking.select_specialist') }}
                    </label>
                    <select name="specialist_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>{{ request('specialist_name') ?? __('pages.booking.select_specialist') }}</option>
                        <option>د. محمد أحمد</option>
                        <option>د. سارة خالد</option>
                        <option>د. أحمد علي</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('pages.booking.select_service') }}
                    </label>
                    <select name="service_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>{{ request('service_name') ?? __('pages.booking.select_service') }}</option>
                        <option>استشارة نفسية</option>
                        <option>جلسة علاجية</option>
                        <option>جلسة جماعية</option>
                    </select>
                </div>
            </div>

            <!-- نوع الجلسة -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    {{ __('pages.booking.session_type') }}
                </label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="session_type" value="in_person" class="form-radio text-purple-600">
                        <span class="mr-2">{{ __('pages.booking.in_person') }}</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="session_type" value="online" class="form-radio text-purple-600" checked>
                        <span class="mr-2">{{ __('pages.booking.online') }}</span>
                    </label>
                </div>
            </div>

            <!-- التاريخ والوقت -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('pages.booking.select_date') }}
                    </label>
                    <input type="date" name="appointment_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('pages.booking.select_time') }}
                    </label>
                    <input type="time" name="appointment_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <!-- معلومات المستخدم -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('pages.booking.your_name') }}
                    </label>
                    <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('pages.booking.your_phone') }}
                    </label>
                    <input type="tel" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    {{ __('pages.booking.your_email') }}
                </label>
                <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    {{ __('pages.booking.notes') }}
                </label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    {{ __('pages.booking.book_now') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

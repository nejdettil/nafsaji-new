@extends('layouts.admin')

@section('title', __('messages.manage_bookings'))

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-purple-800">{{ __('messages.manage_bookings') }}</h1>
    </div>
    
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="flex justify-between items-center p-4 bg-purple-50">
            <div>
                <h2 class="text-xl font-semibold text-purple-800">{{ __('messages.all_bookings') }}</h2>
                <p class="text-sm text-gray-600">{{ __('messages.total') }}: {{ $bookings->total() }}</p>
            </div>
            
            <!-- فلتر الحجوزات -->
            <div class="flex space-x-4">
                <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="px-3 py-1 rounded-full text-sm {{ request('status') == 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-gray-200 text-gray-800' }}">
                    {{ __('messages.booking_status.pending') }}
                </a>
                <a href="{{ route('admin.bookings.index', ['status' => 'confirmed']) }}" class="px-3 py-1 rounded-full text-sm {{ request('status') == 'confirmed' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                    {{ __('messages.booking_status.confirmed') }}
                </a>
                <a href="{{ route('admin.bookings.index', ['status' => 'cancelled']) }}" class="px-3 py-1 rounded-full text-sm {{ request('status') == 'cancelled' ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-800' }}">
                    {{ __('messages.booking_status.cancelled') }}
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="px-3 py-1 rounded-full text-sm {{ !request('status') ? 'bg-purple-200 text-purple-800' : 'bg-gray-200 text-gray-800' }}">
                    {{ __('messages.all') }}
                </a>
            </div>
        </div>
        
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-purple-100">
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.user') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.specialist') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.service') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.date_time') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.created_at') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $booking)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->specialist->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->service->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ date('Y-m-d', strtotime($booking->booking_date)) }}
                        <span class="text-gray-500">{{ $booking->booking_time }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($booking->status == 'pending')
                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">{{ __('messages.booking_status.pending') }}</span>
                        @elseif($booking->status == 'confirmed')
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">{{ __('messages.booking_status.confirmed') }}</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">{{ __('messages.booking_status.cancelled') }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $booking->created_at->format('Y-m-d') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-purple-600 hover:text-purple-900">{{ __('messages.view') }}</a>
                            <a href="{{ route('admin.bookings.edit', $booking) }}" class="text-blue-600 hover:text-blue-900">{{ __('messages.edit') }}</a>
                            
                            @if($booking->status == 'pending')
                                <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:text-green-900">{{ __('messages.confirm') }}</button>
                                </form>
                            @endif
                            
                            @if($booking->status != 'cancelled')
                                <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('messages.confirm_cancel_booking') }}')">
                                        {{ __('messages.cancel') }}
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('messages.confirm_delete_booking') }}')">
                                    {{ __('messages.delete') }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        {{ __('messages.no_bookings_found') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection

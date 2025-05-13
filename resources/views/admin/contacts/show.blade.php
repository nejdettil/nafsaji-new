@extends('layouts.admin')

@section('title', __('messages.message_details'))

@section('content')
<div class="container mx-auto py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.contacts.index') }}" class="text-purple-600 hover:text-purple-800 mr-2">
            &larr; {{ __('messages.back_to_messages') }}
        </a>
        <h1 class="text-3xl font-bold text-purple-800">{{ __('messages.message_details') }}</h1>
    </div>
    
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                        {{ $contact->subject }}
                    </h2>
                    <p class="text-gray-600">
                        {{ __('messages.from') }}: {{ $contact->name }} &lt;{{ $contact->email }}&gt;
                    </p>
                    <p class="text-gray-600">
                        {{ __('messages.received_at') }}: {{ $contact->created_at->format('Y-m-d H:i') }}
                    </p>
                </div>
                
                <div>
                    @if($contact->is_read)
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                            {{ __('messages.read') }}
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">
                            {{ __('messages.unread') }}
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="bg-purple-50 p-6 rounded mb-6">
                <h3 class="text-lg font-semibold text-purple-700 mb-4">{{ __('messages.message_content') }}</h3>
                <p class="whitespace-pre-line text-gray-700">{{ $contact->message }}</p>
            </div>
        </div>
        
        <div class="bg-gray-50 p-4 border-t border-gray-200 flex justify-between">
            <div class="flex space-x-2">
                <a href="mailto:{{ $contact->email }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    {{ __('messages.reply_by_email') }}
                </a>
                
                @if($contact->is_read)
                    <form action="{{ route('admin.contacts.mark-as-unread', $contact) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                            {{ __('messages.mark_as_unread') }}
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.contacts.mark-as-read', $contact) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            {{ __('messages.mark_as_read') }}
                        </button>
                    </form>
                @endif
            </div>
            
            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" onclick="return confirm('{{ __('messages.confirm_delete_message') }}')">
                    {{ __('messages.delete_message') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

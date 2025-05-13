@extends('layouts.admin')

@section('title', __('messages.manage_contacts'))

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-purple-800">{{ __('messages.manage_contacts') }}</h1>
        
        <form action="{{ route('admin.contacts.mark-all-as-read') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                {{ __('messages.mark_all_as_read') }}
            </button>
        </form>
    </div>
    
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="flex justify-between items-center p-4 bg-purple-50">
            <div>
                <h2 class="text-xl font-semibold text-purple-800">{{ __('messages.all_contact_messages') }}</h2>
                <p class="text-sm text-gray-600">{{ __('messages.total') }}: {{ $contacts->total() }}</p>
            </div>
            
            <!-- فلتر الرسائل -->
            <div class="flex space-x-4">
                <a href="{{ route('admin.contacts.index', ['status' => 'unread']) }}" class="px-3 py-1 rounded-full text-sm {{ request('status') == 'unread' ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-800' }}">
                    {{ __('messages.unread') }}
                </a>
                <a href="{{ route('admin.contacts.index', ['status' => 'read']) }}" class="px-3 py-1 rounded-full text-sm {{ request('status') == 'read' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                    {{ __('messages.read') }}
                </a>
                <a href="{{ route('admin.contacts.index') }}" class="px-3 py-1 rounded-full text-sm {{ !request('status') ? 'bg-purple-200 text-purple-800' : 'bg-gray-200 text-gray-800' }}">
                    {{ __('messages.all') }}
                </a>
            </div>
        </div>
        
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-purple-100">
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.name') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.email') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.subject') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.message_preview') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($contacts as $contact)
                <tr class="{{ $contact->is_read ? '' : 'bg-purple-50' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contact->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contact->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contact->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contact->subject }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $contact->getPreview() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($contact->is_read)
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">{{ __('messages.read') }}</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">{{ __('messages.unread') }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $contact->created_at->format('Y-m-d') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="text-purple-600 hover:text-purple-900">{{ __('messages.view') }}</a>
                            
                            @if($contact->is_read)
                                <form action="{{ route('admin.contacts.mark-as-unread', $contact) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-blue-600 hover:text-blue-900">{{ __('messages.mark_as_unread') }}</button>
                                </form>
                            @else
                                <form action="{{ route('admin.contacts.mark-as-read', $contact) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:text-green-900">{{ __('messages.mark_as_read') }}</button>
                                </form>
                            @endif
                            
                            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('messages.confirm_delete_message') }}')">
                                    {{ __('messages.delete') }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        {{ __('messages.no_messages_found') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4">
            {{ $contacts->links() }}
        </div>
    </div>
</div>
@endsection

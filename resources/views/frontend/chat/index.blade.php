@extends('layouts.frontend.chat')

@section('title') Chat @endsection

@section('webpack')
    <script src="{{ asset('assets/js/frontend/chat.bundle.js') }}"></script>
@endsection
@section('content')
    <main class="d-flex"></main>
    <script>
        var routes = {
            getAccounts: '{{route('chat.getAccounts')}}',
            getConversations: '{{route('chat.getConversations')}}',
            addConversation: '{{route('chat.store')}}',
            getMessages: '{{route('chat-messages.index')}}',
            send: '{{route('chat-messages.store')}}'
        }

        var csrf = '{{csrf_token()}}'

        var dates = {
            today: '{{\Carbon\Carbon::today()}}',
            yesterday: '{{\Carbon\Carbon::yesterday()}}'
        }
    </script>
@endsection

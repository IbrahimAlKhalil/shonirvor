@extends('layouts.frontend.chat')

@section('title') Chat @endsection

@section('webpack')
    <script src="{{ asset('assets/js/frontend/chat.bundle.js') }}"></script>
@endsection

@section('content')
    <main class="d-flex"></main>
    <script>
        var profilePic = '{{ asset('storage/default/user-photo/person.jpg') }}'
    </script>
@endsection

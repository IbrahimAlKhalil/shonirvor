@extends('layouts.backend.master')

@section('title', 'ইউজার')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center mt-3 bg-white">
                    <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="70%">নাম</th>
                        <th>মোবাইল</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        @php($serial = $users->perPage() * ($users->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td class="align-middle">{{ en2bnNumber($serial) }}</td>
                            <td class="align-middle">
                                <a href="{{ route('backend.users.show', $user->id) }}">{{ $user->name }}</a>
                            </td>
                            <td class="align-middle">{{ $user->mobile }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">

            </div>
        </div>
    </div>
@endsection
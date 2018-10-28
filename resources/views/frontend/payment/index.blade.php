@extends('layouts.frontend.master')

@section('title', 'Payments')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ক্যাটাগরি</th>
                        <th scope="col">বর্তমান প্যাকেজ</th>
                        <th scope="col">মেয়াদ উত্তীর্ণের তারিখ</th>
                        <th scope="col">পদক্ষেপ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($indServices as $index => $indService)
                        @php($payment = $indService->payments->first())
                        @php($properties = $payment->package->properties->groupBy('name'))
                        <tr>
                            <th scope="row">{{ en2bnNumber($index+1) }}</th>
                            <td>
                                {{ $indService->category->name }}
                            </td>
                            <td>
                                {{ $properties['name'][0]->value }}
                            </td>
                            <td>
                                @php($start = $payment->created_at)
                                @php($end = $start->addDays($properties['duration'][0]->value))
                                {{ en2bnNumber($start->format('d/m/Y')) }}  ({{ $start->diffInDays($payment->created_at) }})
                            </td>
                            <td>
                                <button class="btn btn-secondary">নবীকরণ</button>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5">কোন সেবা খুঁজে পাওয়া যায়নি ।</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))
            </div>
        </div>
    </div>
@endsection
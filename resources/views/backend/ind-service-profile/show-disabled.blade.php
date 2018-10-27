@extends('layouts.backend.master')

@section('title', $provider->user->name)

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <figure class="col-md-3">
                        <img class="img-thumbnail rounded" src="{{ asset('storage/' . $provider->user->photo) }}"
                             alt="{{ $provider->user->name }}">
                        <figcaption class="text-center">{{ $provider->user->name }}</figcaption>
                    </figure>
                    <div class="col-md-9">
                    </div>
                    <div class="badge badge-danger">আপনার প্রোফাইলটি নিষ্ক্রিয়</div>
                    <table class="table table-striped table-bordered table-hover table-sm">
                        <tbody>
                        <tr>
                            <th scope="row">কাজের মোবাইল নম্বর</th>
                            <td>{{ $provider->mobile }}</td>
                        </tr>
                        <tr>
                            <th scope="row">ব্যক্তিগত মোবাইল নম্বর</th>
                            <td>{{ $provider->user->mobile }}</td>
                        </tr>
                        <tr>
                            <th scope="row">কাজের ইমেইল</th>
                            <td>{{ $provider->user->email }}</td>
                        </tr>
                        <tr>
                            <th scope="row">ব্যক্তিগত ইমেইল</th>
                            <td>{{ $provider->email }}</td>
                        </tr>
                        <tr>
                            <th scope="row">বয়স</th>
                            <td>{{ $provider->user->age }}</td>
                        </tr>
                        <tr>
                            <th scope="row">যোগ্যতা/অভিজ্ঞতা</th>
                            <td>{{ $provider->user->qualification }}</td>
                        </tr>
                        <tr>
                            <th scope="row">জাতীয় পরিচয়পত্রের নম্বর</th>
                            <td>{{ $provider->user->nid }}</td>
                        </tr>
                        <tr>
                            <th scope="row">জেলা</th>
                            <td>{{ $provider->district->bn_name}}</td>
                        </tr>
                        <tr>
                            <th scope="row">থানা</th>
                            <td>{{ $provider->thana->bn_name}}</td>
                        </tr>

                        <tr>
                            <th scope="row">ইউনিয়ন</th>
                            <td>{{ $provider->union->bn_name}}</td>
                        </tr>
                        <tr>
                            <th scope="row">ঠিকানা</th>
                            <td>{{ $provider->address }}</td>
                        </tr>
                        <tr>
                            <th scope="row">ক্যাটাগরি</th>
                            <td>{{ $provider->category->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">সাব-ক্যাটাগরি</th>
                            <td>
                                @foreach($provider->subCategories('confirmed')->get() as $subCategory)
                                    <span class="badge badge-success">{{ $subCategory->name }}</span>&nbsp;
                                @endforeach
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <h3 class="my-4 col-12">নথিপত্র</h3>
                    <div class="col-md-3">
                        {{--<span class="text-muted">অভিজ্ঞতা প্রত্যয়ন পত্র</span>--}}
                        <a href="{{ asset('storage/' . $provider->experience_certificate) }}" target="_blank">
                            <img src="{{ asset('storage/' . $provider->experience_certificate) }}"
                                 class="img-responsive img-thumbnail">
                        </a>
                    </div>
                    @foreach($provider->user->identities as $identity)
                        <div class="col-md-3">
                            <a href="{{ asset('storage/' . $identity->path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $identity->path) }}"
                                     class="img-responsive img-thumbnail">
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    <h3 class="my-4 col-12">কাজের ছবি</h3>
                    @forelse($provider->workImages as $image)
                        <div class="col-md-3">
                            <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $image->path) }}" class="img-responsive img-thumbnail">
                            </a>
                        </div>
                    @empty
                        <p class="text-muted col-12">কোন ছবি আপলোড করা হয়নি!</p>
                    @endforelse
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        @include('components.side-nav', compact('navs'))
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.visitor-conuter', ['visitor' => indVisitorCount($provider->id)])
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.feedback-counter', compact('countFeedbacks'))
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
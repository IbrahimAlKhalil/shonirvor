@extends('layouts.frontend.master')

@section('title', 'Service Provider Registration')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h2>সার্ভিস প্রভাইডার এর নিয়মাবলী</h2>
            </div>
            <div class="col-12 px-4 mt-4">
                {{--{!! getContent('registration-instruction')->data !!}--}}



                <p><strong>সমীকরণ</strong><strong> </strong><br>
                    পাঠকের সুবিধার জন্য এবং পাঠান্তর-প্রণালীর স্বার্থে সব পাঠের প্রতিলিপিতে কিছু সাধারণ নিয়ম প্রয়োগ করা হয়েছে। এর ফলে কখনও-কখনও মূল পৃষ্ঠার বিন্যাস সামান্য পরিবর্তিত হয়েছে। মূল বিন্যাস ছবি দেখলে বোঝা যাবে। </p>
                <ul class="squl">
                    <li>মুদ্রিত বইয়ের গোড়ায় বা শেষে পেশ করা তথ্যের – নামপত্র, উৎসর্গ, সূচী, পুষ্পিকা ইত্যাদি – প্রায়শ প্রতিলিপি করা হয়নি, কারণ তাতে পাঠান্তর-প্রণালীতে অসুবিধা দেখা দিতে পারে।</li>
                    <li>পংক্তি, স্তবক, সংলাপ ইত্যাদির মধ্যবর্তী ফাঁক:</li>
                    <ul>
                        <li>কবিতার দুই স্তবকের মধ্যে এক লাইন ফাঁক রাখা হয়েছে, কিন্তু গদ্যে দুই অনুচ্ছেদের মধ্যে রাখা হয়নি। স্তবক-ভাগ স্পষ্ট না হলে আমাদের বিচারমত ভাগ করা হয়েছে।</li>
                        <li>কোনও নাটকে গদ্য ও পদ্য উভয় ধরণের সংলাপ থাকলে, সংলাপের প্রত্যেক অংশের মধ্যে এক লাইন ফাঁক রাখা হয়েছে, কিন্তু গদ্য নাটকের ক্ষেত্রে হয়নি। <em>শেষের কবিতা </em>প্রভৃতি প্রচুর পদ্য-সম্বলিত দু-একটি গদ্যগ্রন্থের ক্ষেত্রেও ফাঁক রাখা হয়েছে। পাঠান্তর-প্রণালীর স্বার্থে এই ভিন্ন পদ্ধতি ব্যবহার করা হয়েছে।</li>
                        <li>শিরোনাম, নির্দেশিকা, মঞ্চনির্দেশ প্রভৃতির ক্ষেত্রে ফাঁকের ব্যবহার কখনও-কখনও পরিবর্তন করা হয়েছে। মূল বিন্যাস ছবি দেখলে বোঝা যাবে।</li>
                    </ul>
                    <li>পংক্তির গোড়ায় বা মাঝখানে ফাঁক:</li>
                    <ul>
                        <li>গদ্য-রচনায় সব অনুচ্ছেদ বাঁদিকের মার্জিন থেকে শুরু করা হয়েছে।</li>
                        <li>পদ্যের সব পংক্তি বাঁদিকের মার্জিন থেকে শুরু করা হয়েছে, মূলে মার্জিন থেকে কিছুটা ফাঁক রেখে (indent করে) ছাপা হলেও।</li>
                        <li>পদ্য-পংক্তিতে পর্বযতি বা মধ্যচ্ছেদ বোঝাতে হ্যাশ-চিহ্ন (#) ব্যবহৃত হয়েছে। পর্বযতির স্থান স্পষ্ট না হলে আমাদের বিচারমত ভাগ করা হয়েছে।</li>
                    </ul>
                    <li>নাটকের মঞ্চনির্দেশ, বক্তার নাম প্রভৃতি:</li>
                    <ul>
                        <li>নাটকের অঙ্ক ও দৃশ্য-সংখ্যা, &nbsp;দৃশ্যের শুরুতে স্থান-কালের উল্লেখ, উপস্থিত পাত্রপাত্রীদের নাম, মঞ্চ-নির্দেশ, সংলাপে বক্তার নাম ইত্যাদির প্রতিলিপিতে কিছু সাধারণ নিয়ম অনুসৃত হয়েছে।&nbsp;</li>
                        <li>বক্তার নাম ও সংলাপের সূচনা সব সময় এক পংক্তিতে রাখা হয়েছে।</li>
                    </ul>
                    <li>রচনার শেষে রচনার স্থান, তারিখ ও সময় সব সময় একই ভাবে বিন্যস্ত হয়েছে।</li>
                    <li>পাঠোদ্ধারে সংশয় থাকলে, অনিশ্চিত পাঠটি « » বন্ধনীর মধ্যে রাখা হয়েছে।</li>
                    <li>সব মুদ্রণপ্রমাদ প্রতিলিপিতে বজায় রাখা হয়েছে, কিন্তু উল্টে-যাওয়া টাইপ সংশোধন করা হয়েছে।</li>
                </ul>




            </div>
            <div class="col-12 my-3">
                <div class="row justify-content-around">
                    <div class="col-6 text-right">
                        <a href="{{ route('individual-service-registration.index') }}"
                           class="btn btn-secondary btn-success" role="button">Individual</a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('organization-service-registration.index') }}"
                           class="btn btn-secondary btn-success" role="button">Organization</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
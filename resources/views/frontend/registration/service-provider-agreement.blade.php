@extends('layouts.frontend.master')

@section('title', 'Service Provicder Registration')

@section('content')
    <div class="container my-4">
        <div class="row">
            <div>
                <p class="mt-4">

                    Our Services display some content that is not Google’s. This content is the sole responsibility
                    of the entity that makes it available. We may review content to determine whether it is illegal
                    or violates our policies, and we may remove or refuse to display content that we reasonably
                    believe violates our policies or the law. But that does not necessarily mean that we review
                    content, so please don’t assume that we do.

                    In connection with your use of the Services, we may send you service announcements,
                    administrative messages, and other information. You may opt out of some of those communications.

                    Some of our Services are available on mobile devices. Do not use such Services in a way that
                    distracts you and prevents you from obeying traffic or safety laws.

                    Your Google Account
                    You may need a Google Account in order to use some of our Services. You may create your own
                    Google Account, or your Google Account may be assigned to you by an administrator, such as your
                    employer or educational institution. If you are using a Google Account assigned to you by an
                    administrator, different or additional terms may apply and your administrator may be able to
                    access or disable your account.

                    To protect your Google Account, keep your password confidential. You are responsible for the
                    activity that happens on or through your Google Account. Try not to reuse your Google Account
                    password on third-party applications. If you learn of any unauthorized use of your password or
                    Google Account, follow these instructions.

                    Privacy and Copyright Protection
                    Google’s privacy policies explain how we treat your personal data and protect your privacy when
                    you use our Services. By using our Services, you agree that Google can use such data in
                    accordance with our privacy policies.

                    We respond to notices of alleged copyright infringement and terminate accounts of repeat
                    infringers according to the process set out in the U.S. Digital Millennium Copyright Act.

                    We provide information to help copyright holders manage their intellectual property online. If
                    you think somebody is violating your copyrights and want to notify us, you can find information
                    about submitting notices and Google’s policy about responding to notices in our Help Center.

                    Your Content in our Services
                    Some of our Services allow you to upload, submit, store, send or receive content. You retain
                    ownership of any intellectual property rights that you hold in that content. In short, what
                    belongs to you stays yours.

                    When you upload, submit, store, send or receive content to or through our Services, you give
                    Google (and those we work with) a worldwide license to use, host, store, reproduce, modify,
                    create derivative works (such as those resulting from translations, adaptations or other changes
                    we make so that your content works better with our Services), communicate, publish, publicly
                    perform, publicly display and distribute such content. The rights you grant in this license are
                    for the limited purpose of operating, promoting, and improving our Services, and to develop new
                    ones. This license continues even if you stop using our Services (for example, for a business
                    listing you have added to Google Maps). Some Services may offer you ways to access and remove
                    content that has been provided to that Service. Also, in some of our Services, there are terms
                    or settings that narrow the scope of our use of the content submitted in those Services. Make
                    sure you have the necessary rights to grant us this license for any content that you submit to
                    our Services.

                    Our automated systems analyze your content (including emails) to provide you personally relevant
                    product features, such as customized search results, tailored advertising, and spam and malware
                    detection. This analysis occurs as the content is sent, received, and when it is stored.

                    If you have a Google Account, we may display your Profile name, Profile photo, and actions you
                    take on Google or on third-party applications connected to your Google Account (such as +1’s,
                    reviews you write and comments you post) in our Services, including displaying in ads and other
                    commercial contexts. We will respect the choices you make to limit sharing or visibility
                    settings in your Google Account. For example, you can choose your settings so your name and
                    ple to you. There may be provisions in the open
                    source license that expressly override some of these terms.
                </p>

                <div class="row">
                    <div class="btn-group mx-auto">
                        <a href="{{ route('registration.individual.index') }}"
                           class="btn btn-secondary btn-success">Individual</a>
                        <a href="{{ route('registration.organization.index') }}"
                           class="btn btn-secondary btn-success">Organization</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
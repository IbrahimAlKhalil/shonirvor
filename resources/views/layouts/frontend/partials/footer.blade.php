        <div class="mt-lg-3"></div>
        <footer class="container-fluid bg-white py-3 border-top mt-auto">
            <div class="row align-items-center">
                <div class="col-md-4">© {{ date('Y') }} {{ config('app.name') }} - সর্বস্বত্ব সংরক্ষিত</div>
                <div class="col-6 col-md-4 align-self-center text-md-center">জরুরি প্রয়োজনেঃ ০১৭৮৯৬৪৭৪৫৭</div>
                <div class="col-6 col-md-4">
                    <a href="#" class="float-right">
                        <button type="button" class="btn btn-primary btn-sm">
                            <span class="fa fa-angle-up"></span>
                        </button>
                    </a>
                </div>
            </div>
            @auth()
                <script>
                    var routes = {
                        getAccounts: '<?php echo e(route('chat.getAccounts')); ?>',
                        getConversations: '<?php echo e(route('chat.getConversations')); ?>',
                        getMessages: '<?php echo e(route('chat-messages.index')); ?>'
                    }
                </script>
                <script src="{{asset('assets/js/frontend/channel.bundle.js')}}"></script>
            @endauth
        </footer>
        </div>
        @yield('script')
    </body>
</html>

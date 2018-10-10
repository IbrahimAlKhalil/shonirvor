        <footer class="container-fluid bg-white py-3 border-top mt-3">
            <p class="float-right go-top mb-0">
                <a href="#">
                    <button type="button" class="btn btn-primary btn-sm">
                        <span class="fa fa-angle-up"></span>
                    </button>
                </a>
            </p>
            <p class="mb-0">© {{ date('Y') }} {{ config('app.name') }} - সর্বস্বত্ব সংরক্ষিত</p>
        </footer>
        @yield('script')
    </body>
</html>
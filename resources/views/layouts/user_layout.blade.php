@auth
    {{-- If user password is not change then redirect to change_pass_view and don't allow to access the dashboard --}}
    @if(Auth::user()->is_password_changed == 0)
        <script type="text/javascript">
            window.location = "{{ url('change_pass_view') }}";
        </script>
    @endif
    
    {{-- 1-active 2-inactive --}}
    {{-- If the user is inactive then return to login(index view) --}}
    @if(Auth::user()->status == 2) 
        <script type="text/javascript">
            window.location = "/";
        </script>
    @endif

    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Online IT Library | @yield('title')</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- <link rel="shortcut icon" type="image/png" href="{{ asset('public/images/favicon.ico') }}"> --}}

        @include('shared.css_links.css_links')
    </head>
        <body class="hold-transition sidebar-mini">
            <div class="container-fluid px-0">
                @include('shared.pages.header')
                {{-- @include('shared.pages.user_nav') --}}
                @yield('content_page')
                @include('shared.pages.footer')
            </div>

            {{-- JS --}}
            @include('shared.js_links.js_links')
            @yield('js_content')
            {{-- @include('shared.pages.common') --}}
        </body>
    </html>
@else
    <script type="text/javascript">
        window.location = "/";
    </script>
@endauth
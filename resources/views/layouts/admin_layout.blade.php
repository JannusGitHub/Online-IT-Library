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
            window.location = "{{ url('login') }}";
        </script>
    @endif
    
    {{-- if user's id is equal to 1 (Administrator) --}}
    {{-- @if(Auth::user()->user_level_id != 2)
        <script type="text/javascript">
            window.location("admin");
        </script>
    @endif --}}

    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Online IT Library | @yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/png" href="{{ asset('public/images/favicon.ico') }}">

        {{-- CSS Styles --}}
        @include('shared.css_links.css_links')

    </head>
        <body class="hold-transition sidebar-mini">
            <div class="wrapper">
                @include('shared.pages.header')
                @include('shared.pages.admin_nav')
                @yield('content_page')
                @include('shared.pages.footer')
            </div>

            {{-- JS --}}
            @include('shared.js_links.js_links')
            @yield('js_content')

        </body>
    </html>
@else
    <script type="text/javascript">
        window.location = "/";
    </script>
@endauth
@auth
    @if(Auth::user()->is_password_changed == 1)
        <script type="text/javascript">
            window.location = "{{ url('user') }}";
        </script>
    @endif
    @if(!isset(Auth::user()->id))
        <script type="text/javascript">
            window.location = "{{ url('/') }}";
        </script>
    @endif
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Online IT Library | Change Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @include('shared.css_links.css_links')
    </head>
    <body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <b>Online IT Library</b>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Change your password</p>

                <form action="{{ route('change_pass') }}" method="post" id="formChangePassword">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" id="txtChangePasswordUsername" value="{{ Auth::user()->username }}" readonly="">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Default Password" id="txtChangePasswordPassword">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="new_password" placeholder="New Password" id="txtChangePasswordNewPassword">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" id="txtChangePasswordConfirmPassword">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" id="btnChangePass"><i class="fa fa-check" id="iBtnChangePassIcon"></i>Change Password</button>
                            <a id="btnLoginAnother" class="btn btn-default btn-block"><i class="fa fa-unlock" id="iBtnChangePassIcon"></i>Sign In</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <form id="formLoginAnother">
        @csrf
    </form>

    @include('shared.js_links.js_links')

    <script type="text/javascript">
        $(document).ready(function(){
            // ChangePassword() function is inside the public/js/my_js/User.js
            $("#formChangePassword").submit(function(event){
                event.preventDefault();
                ChangePassword();
            });

            // LoginAnother() function is inside the public/js/my_js/User.js
            // call the sign_out method inside the Controller
            $("#formLoginAnother").submit(function(event){
                event.preventDefault();
                LoginAnother();
            });

            $("#btnLoginAnother").click(function(){
                $("#formLoginAnother").submit();
            });
        });
    </script>
    </body>
    </html>
@else
    <script type="text/javascript">
        window.location = "/";
    </script>
@endauth

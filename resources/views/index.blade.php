{{-- if user is active return to user dashboard --}}
@if(isset(Auth::user()->id) && Auth::user()->status == 1)
    <script type="text/javascript">
        window.location = "{{ url('user') }}";
    </script>
    
{{-- if user is inactive or the user id is not set then return to this login page(index) --}}
{{-- always use the isset() function to check whether the Auth is set, which means that it has to be declared and is not NULL. --}}
@elseif((isset(Auth::user()->id) && Auth::user()->status == 2) || !isset(Auth::user()->id)) 
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Online IT Library | Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        {{-- CSS Styles --}}
        @include('shared.css_links.css_links')

    </head>
        <body class="hold-transition login-page">
            <div class="login-box">
                <div class="card" style="box-shadow: 1px 1px 10px #808080;">
                    <br>
                    <div class="login-logo">
                        <b>Online IT Library</b>
                    </div>
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">Sign In</p>

                        <form action="{{ route('sign_in') }}" method="post" id="formSignIn">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="username" placeholder="Username" id="txtSignInUsername">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Password" id="txtSignInPassword">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-dark btn-block" id="btnSignIn"><i class="fa fa-check" id="iBtnSignInIcon"></i> Sign In</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @include('shared.js_links.js_links')

            <script type="text/javascript">
                $(document).ready(function(){
                    $("#formSignIn").submit(function(event){
                        event.preventDefault();
                        SignIn();
                    });
                });
            </script>
        </body>
    </html>

@endif

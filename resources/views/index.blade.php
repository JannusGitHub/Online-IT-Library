{{-- if user id is set and active then return to user dashboard --}}
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
        <style>::-webkit-scrollbar {
            display: none !important;
        }</style>

    </head>
        <body>
            <div class="svg-wrapper">
                <div class="blob">
                    <!-- This SVG is from https://codepen.io/Ali_Farooq_/pen/gKOJqx -->
                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 310 350">
                    <path d="M156.4,339.5c31.8-2.5,59.4-26.8,80.2-48.5c28.3-29.5,40.5-47,56.1-85.1c14-34.3,20.7-75.6,2.3-111  c-18.1-34.8-55.7-58-90.4-72.3c-11.7-4.8-24.1-8.8-36.8-11.5l-0.9-0.9l-0.6,0.6c-27.7-5.8-56.6-6-82.4,3c-38.8,13.6-64,48.8-66.8,90.3c-3,43.9,17.8,88.3,33.7,128.8c5.3,13.5,10.4,27.1,14.9,40.9C77.5,309.9,111,343,156.4,339.5z"/>
                    </svg>
                </div>
            </div>

            <div class="wrapper">
                <div class="row d-flex justify-content-center justify-content-sm-center align-items-center" style="min-height: 100vh;">
                    <div class="col-sm-12 col-md-12 col-lg-6 text-center">
                        {!! file_get_contents('images/filing_system.svg') !!}
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-6 d-flex justify-content-around">
                        <div class="card" style="box-shadow: 1px 1px 10px #808080; max-width: 90vw; width: 60%">
                            <h5 class="card-header bg-dark text-center py-4"><b>Online IT Library</b></h5>
                            <div class="card-body my-3">
                                <p class="login-box-msg">Please sign In</p>
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
                                        <div class="col-12 my-3">
                                            <button type="submit" class="btn btn-dark btn-block" id="btnSignIn"><i class="fa fa-check" id="iBtnSignInIcon"></i> Sign In</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
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

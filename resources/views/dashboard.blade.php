@php $layout = 'layouts.user_layout'; @endphp
@auth
    @php
        if(Auth::user()->user_level_id == 1){
            $layout = 'layouts.admin_layout';
        }
        else if(Auth::user()->user_level_id == 2){
            $layout = 'layouts.user_layout';
        }
    @endphp

    {{-- if user's id is not equal to 1 (Administrator) --}}
    @if(Auth::user()->user_level_id != 1)
        <script type="text/javascript">
            window.location = "user"; // user dashboard
        </script>
    @endif
@endauth

@auth
    @extends($layout)

    @section('title', 'Dashboard')

    @section('content_page')
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 mt-4">
                                <div class="card l-bg-blue-dark card-hover">
                                    <div class="card-statistic-3 p-4">
                                        <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                                        <div class="mb-4">
                                            <h5 class="card-title mb-0">Total Users</h5>
                                        </div>
                                        <div class="row align-items-center mb-2 d-flex">
                                            <div class="col-8">
                                                <h2 class="d-flex align-items-center mb-0">
                                                    <span class="totalUsers"></span>
                                                </h2>
                                            </div>
                                            <div class="col-4 text-right">
                                                <a class="text-white" href="/user_management"><i class="fa fa-4x fa-arrow-right"></i></a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 mt-4">
                                <div class="card l-bg-blue-dark card-hover">
                                    <div class="card-statistic-3 p-4">
                                        <div class="card-icon card-icon-large"><i class="fas fa-clipboard-list"></i></div>
                                        <div class="mb-4">
                                            <h5 class="card-title mb-0">Total List of Workloads</h5>
                                        </div>
                                        <div class="row align-items-center mb-2 d-flex">
                                            <div class="col-8">
                                                <h2 class="d-flex align-items-center mb-0">
                                                    <span class="totalWorkloads"></span>
                                                </h2>
                                            </div>
                                            <div class="col-4 text-right">
                                                <a class="text-white" href="/list_of_workloads"><i class="fa fa-4x fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                </div>
            </section>
        </div>
    @endsection
    
    <!--     {{-- JS CONTENT --}} -->
    @section('js_content')
    <script type="text/javascript">
        $(document).ready(function () {
            //============================== GET TOTAL WORKLOADS FOR DASHBOARD ==============================
            function totalWorkloads(){
                $.ajax({
                    url: "get_total_workloads",
                    method: "get",
                    dataType: "json",
                    success: function (response) {
                        $('.totalWorkloads').text(response['totalWorkloads']);
                        console.log(response['totalWorkloads']);
                    }
                });
            }
            totalWorkloads();


            //============================== GET TOTAL USERS FOR DASHBOARD ==============================
            function totalUsers(){
                $.ajax({
                    url: "get_total_users",
                    method: "get",
                    dataType: "json",
                    success: function (response) {
                        $('.totalUsers').text(response['totalUsers']);
                        console.log(response['totalUsers']);
                    }
                });
            }
            totalUsers();


            //============================== SIGN OUT ==============================
            $(document).ready(function(){
                $("#formSignOut").submit(function(event){
                    event.preventDefault();
                    SignOut();
                });
            });
        });
    </script>
    @endsection
@endauth
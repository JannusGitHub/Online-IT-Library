<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-danger elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <img src="{{ asset('/images/pricon_logo2.png') }}"
            alt="CNPTS"
            class="brand-image img-circle elevation-3"
            style="opacity: .8">
            {{-- <i class="brand-image elevation-3 mt-2 fas fa-book-reader"></i> --}}
        <span class="brand-text font-weight-light">Online IT Library</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item has-treeview">
                    <a href="/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">ADMINISTRATOR</li>
                <li class="nav-item has-treeview">
                    <a href="/user_management" class="nav-link">
                        <i class="nav-icon fa fa-users"></i>
                        <p>User Management</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="/list_of_workloads" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>List of  Workloads</p>
                    </a>
                </li>
                {{-- @if(Auth::user()->position == 4||Auth::user()->position == 5||Auth::user()->position == 1||Auth::user()->position == 2)
                    <li class="nav-header">PACKING AND SHIPPING</li>
                    <li class="nav-item">
                        <a href="{{ route('packingandshippingalias') }}" class="nav-link">
                        <i class="fa fa-file-excel"></i>
                        <p>
                            Packing and Shipping
                        </p>
                        </a>
                    </li>
                @endif --}}
            </ul>
        </nav>
    </div>
</aside>
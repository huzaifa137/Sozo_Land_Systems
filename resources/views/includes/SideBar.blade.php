<ul class="nav">
    <li class="nav-item profile">
        <div class="profile-desc">
            <div class="profile-pic">
                <div class="count-indicator">
                    <img class="img-xs rounded-circle " src="/assets/images/faces/face15.jpg" alt="">
                    <span class="count bg-success"></span>
                </div>
                <?php
                use App\Models\AdminRegister;
                $user = DB::table('admin_registers')->where('id', Session('LoggedAdmin'))->first();
                ?>
                <div class="profile-name">
                    <h5 class="mb-0 font-weight-normal">{{ $user->username }}</h5>
                    <span>{{ $user->admin_category }}</span>
                </div>
            </div>
            <a href="#" id="profile-dropdown" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>

        </div>
    </li>


    <?php
    
    $user_id = Session('LoggedAdmin');
    $User_access_right = AdminRegister::where('id', '=', $user_id)->value('admin_category');
    
    ?>

    <li class="nav-item nav-category">
        <span class="nav-link text-white">Sozo Properties Limited</span>
    </li>

    @if ($User_access_right == 'SuperAdmin')
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('admin-dashboard') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-speedometer"></i>
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
    @elseif($User_access_right == 'Admin')
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('admin-dashboard') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-speedometer"></i>
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
    @else
    @endif

    <li class="nav-item menu-items">
        <a class="nav-link " data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-icon">
                <i class="mdi mdi-laptop"></i>
            </span>
            <span class="menu-title">Land</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu">

                @if ($User_access_right == 'SuperAdmin')
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin-buyer') }}">Sell Plot</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('estates') }}">Estates</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('plots') }}">Add Plot</a></li>
                @elseif($User_access_right == 'Admin')
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin-buyer') }}">Sell Plot</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.sell.house.fetch') }}">Sell
                            House</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('estates') }}">Estates</a></li>
                @else
                    <li class="nav-item"> <a class="nav-link" href="{{ route('estates') }}">Estates</a></li>
                    {{-- <li class="nav-item"> <a class="nav-link" href="{{ route('plots') }}">Plots and House</a></li> --}}
                @endif


            </ul>
        </div>
    </li>

    <?php
    $pendingApproval = DB::table('houses')->where('status', 1)->count();
    ?>
    <li class="nav-item menu-items">
        <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#ui-house"
            aria-expanded="false" aria-controls="ui-house">
            <span class="menu-icon">
                <i class="mdi mdi-home"></i>
            </span>
            <span class="menu-title d-flex align-items-center">
                Houses

                @if ($User_access_right == 'SuperAdmin')
                    @if ($pendingApproval > 0)
                        <span class="badge bg-danger rounded-pill ms-2">{{ $pendingApproval }}</span>
                    @endif
                @else
                    <span class="badge bg-danger rounded-pill ms-2"></span>
                @endif

            </span>
            <i class="menu-arrow"></i>
        </a>

        <div class="collapse" id="ui-house">
            <ul class="nav flex-column sub-menu">
                @if ($User_access_right == 'SuperAdmin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('add-house') }}">Add House</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.sell.house.fetch') }}">Sell House</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex justify-content-between align-items-center"
                            href="{{ route('approval.house.sell') }}">
                            Pending Approval
                            @if ($pendingApproval > 0)
                                <span class="badge bg-danger rounded-pill ms-2">{{ $pendingApproval }}</span>
                            @endif
                        </a>
                    </li>
                @elseif($User_access_right == 'Admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('add-house') }}">Add House</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.sell.house.fetch') }}">Sell House</a>
                    </li>
                @elseif($User_access_right == 'Sales')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.sell.house.fetch') }}">Houses</a>
                    </li>
                @endif
            </ul>
        </div>
    </li>



    @if ($User_access_right == 'SuperAdmin')
        <li class="nav-item menu-items">
            <a class="nav-link " data-bs-toggle="collapse" href="#ui-reciepts" aria-expanded="false"
                aria-controls="ui-basic">
                <span class="menu-icon">
                    <i class="mdi mdi-animation"></i>
                </span>
                <span class="menu-title">Reciepts</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-reciepts">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('accomplished') }}">Full purchased</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('pending-buyers') }}">Under
                            payments</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('pending-receipts') }}">Pending
                            Receipts</a>
                    </li>
                </ul>
            </div>
        </li>
    @elseif($User_access_right == 'Admin')
        <li class="nav-item menu-items">
            <a class="nav-link " data-bs-toggle="collapse" href="#ui-reciepts" aria-expanded="false"
                aria-controls="ui-basic">
                <span class="menu-icon">
                    <i class="mdi mdi-animation"></i>
                </span>
                <span class="menu-title">Reciepts</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-reciepts">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('accomplished') }}">Full purchased</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('pending-buyers') }}">Under
                            payments</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('pending-receipts') }}">Pending
                            Receipts</a>
                    </li>
                </ul>
            </div>
        </li>
    @else
    @endif


    @if ($User_access_right == 'SuperAdmin')
        <li class="nav-item menu-items">
            <a class="nav-link " data-bs-toggle="collapse" href="#ui-sales" aria-expanded="false"
                aria-controls="ui-basic">
                <span class="menu-icon">
                    <i class="mdi mdi-chart-bar"></i>
                </span>
                <span class="menu-title">Sales</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-sales">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('all-sales') }}">All Sales</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('current-sales') }}">Today's Sales</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('weekly-records') }}">weekly Sales</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('monthly-records') }}">Monthly
                            Sales</a></li>
                </ul>
            </div>
        </li>
    @elseif($User_access_right == 'Admin')
        <li class="nav-item menu-items">
            <a class="nav-link " data-bs-toggle="collapse" href="#ui-sales" aria-expanded="false"
                aria-controls="ui-basic">
                <span class="menu-icon">
                    <i class="mdi mdi-chart-bar"></i>
                </span>
                <span class="menu-title">Sales</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-sales">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('current-sales') }}">Today's Sales</a>
                    </li>
                </ul>
            </div>
        </li>
    @else
    @endif






    @if ($User_access_right == 'SuperAdmin')
        <li class="nav-item menu-items">
            <a class="nav-link " data-bs-toggle="collapse" href="#ui-resales" aria-expanded="false"
                aria-controls="ui-basic">
                <span class="menu-icon">
                    <i class="mdi mdi-cards"></i>
                </span>
                <span class="menu-title">Resales</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-resales">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('search-land') }}">Resale</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('back-on-market') }}">Back on
                            market</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('back-on-market-all') }}">All Back on Market</a></li>
                </ul>
            </div>
        </li>
    @else
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('back-on-market-all') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-file-document"></i>
                </span>
                <span class="menu-title">Back on Market</span>
            </a>
        </li>
    @endif


    @if ($User_access_right == 'SuperAdmin')
        <li class="nav-item menu-items">
            <a class="nav-link " data-bs-toggle="collapse" href="#ui-alerts" aria-expanded="false"
                aria-controls="ui-basic">
                <span class="menu-icon">
                    <i class="mdi mdi-security"></i>
                </span>
                <span class="menu-title">Reminders</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-alerts">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('payment-reminder') }}">Today's
                            reminders</a></li>
                </ul>
            </div>
        </li>
    @else
    @endif



    @if ($User_access_right == 'SuperAdmin')
        <li class="nav-item menu-items">
            <a class="nav-link " data-bs-toggle="collapse" href="#ui-expenditure" aria-expanded="false"
                aria-controls="ui-basic">
                <span class="menu-icon">
                    <i class="mdi mdi-barcode-scan"></i>
                </span>
                <span class="menu-title">Expenditures</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-expenditure">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('add-expenditure') }}">Add
                            Expenditure</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('expense-today') }}">Today's
                            Expenditures</a></li>
                    <!-- <li class="nav-item"> <a class="nav-link" href="{{ route('payment-reminder') }}">All Expenditures</a></li> -->
                </ul>
            </div>
        </li>
    @else
    @endif


    <li class="nav-item menu-items">
        <a class="nav-link" href="{{ route('search-module') }}">
            <span class="menu-icon">
                <i class="mdi mdi-magnify"></i>
            </span>
            <span class="menu-title">Search</span>
        </a>
    </li>


    @if ($User_access_right == 'SuperAdmin')
        <li class="nav-item menu-items">
            <a class="nav-link " data-bs-toggle="collapse" href="#ui-user-module" aria-expanded="false"
                aria-controls="ui-basic">
                <span class="menu-icon">
                    <i class="mdi mdi-animation"></i>
                </span>
                <span class="menu-title">Users</span>
                <i class="menu-arrow">
                </i>
            </a>
            <div class="collapse" id="ui-user-module">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('user-information') }}">View Users</a>
                    </li>
                </ul>
            </div>
        </li>
    @else
    @endif

    @if ($User_access_right == 'SuperAdmin')
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('all-posters') }}">
                <span class="menu-icon">
                    <i class="icon mdi mdi-message-processing"></i>
                </span>
                <span class="menu-title">Posters</span>
            </a>
        </li>
    @elseif($User_access_right == 'Admin')
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('all-posters') }}">
                <span class="menu-icon">
                    <i class="icon mdi mdi-message-processing"></i>
                </span>
                <span class="menu-title">Posters</span>
            </a>
        </li>
    @else
    @endif

</ul>

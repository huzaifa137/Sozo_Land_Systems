<ul class="nav">
    <li class="nav-item profile">
        <div class="profile-desc">
            <div class="profile-pic">
                <div class="count-indicator">
                    <img class="img-xs rounded-circle " src="/assets/images/faces/face15.jpg" alt="">
                    <span class="count bg-success"></span>
                </div>
                <div class="profile-name">
                    <h5 class="mb-0 font-weight-normal">{{ $LoggedAdminInfo['username'] }}</h5>
                    <span>{{ $LoggedAdminInfo['admin_category'] }}</span>
                </div>
            </div>
            <a href="#" id="profile-dropdown" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>

        </div>
    </li>
    <li class="nav-item nav-category">
        <span class="nav-link text-white">Sozo Properties Limited</span>
    </li>
    <li class="nav-item menu-items">
        <a class="nav-link" href="{{ route('admin-dashboard') }}">
            <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
            </span>
            <span class="menu-title">Dashboard</span>
        </a>
    </li>

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
                <li class="nav-item"> <a class="nav-link" href="{{ route('admin-buyer') }}">Sell</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('estates') }}">Estates</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('plots') }}">Plots and House</a></li>
                {{-- <li class="nav-item"> <a class="nav-link" href="{{route('add-house')}}">Houses</a></li> --}}
            </ul>
        </div>
    </li>

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
                <li class="nav-item"> <a class="nav-link" href="{{ route('accomplished') }}">Full purchased</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('pending-buyers') }}">Under payments</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('pending-receipts') }}">Pending Receipts</a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item menu-items">
        <a class="nav-link " data-bs-toggle="collapse" href="#ui-sales" aria-expanded="false" aria-controls="ui-basic">
            <span class="menu-icon">
                <i class="mdi mdi-chart-bar"></i>
            </span>
            <span class="menu-title">Sales</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-sales">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('all-sales') }}">All Sales</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('current-sales') }}">Today's Sales</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('weekly-records') }}">weekly Sales</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('monthly-records') }}">Monthly Sales</a></li>
            </ul>
        </div>
    </li>


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
                <li class="nav-item"> <a class="nav-link" href="{{ route('back-on-market') }}">Back on market</a>
                </li>
            </ul>
        </div>
    </li>


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
                <li class="nav-item"> <a class="nav-link" href="{{ route('add-expenditure') }}">Add Expenditure</a>
                </li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('expense-today') }}">Today's
                        Expenditures</a></li>
                <!-- <li class="nav-item"> <a class="nav-link" href="{{ route('payment-reminder') }}">All Expenditures</a></li> -->
            </ul>
        </div>
    </li>


    <li class="nav-item menu-items">
        <a class="nav-link " data-bs-toggle="collapse" href="#ui-search-module" aria-expanded="false"
            aria-controls="ui-basic">
            <span class="menu-icon">
                <i class="mdi mdi-animation"></i>
            </span>
            <span class="menu-title">Search</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-search-module">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('search-module') }}">Search</a></li>
            </ul>
        </div>
    </li>


    <li class="nav-item menu-items">
        <a class="nav-link " data-bs-toggle="collapse" href="#ui-user-module" aria-expanded="false"
            aria-controls="ui-basic">
            <span class="menu-icon">
                <i class="mdi mdi-animation"></i>
            </span>
            <span class="menu-title">Users</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-user-module">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('user-information') }}">View Users</a></li>
                {{-- <li class="nav-item"> <a class="nav-link" href="{{ route('add-user') }}">Add Users</a></li> --}}
            </ul>
        </div>
    </li>
</ul>

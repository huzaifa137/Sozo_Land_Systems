<ul class="nav">
    <li class="nav-item profile">
      <div class="profile-desc">
        <div class="profile-pic">
          <div class="count-indicator">
            <img class="img-xs rounded-circle " src="/assets/images/faces/face15.jpg" alt="">
            <span class="count bg-success"></span>
          </div>
          <div class="profile-name">
            <h5 class="mb-0 font-weight-normal">{{$LoggedAdminInfo['username']}}</h5>
            <span>Super Admin</span>
          </div>
        </div>
        <a href="#" id="profile-dropdown" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
        
      </div>
    </li>
    <li class="nav-item nav-category">
      <span class="nav-link text-white">Sozo Properties Limited</span>
    </li>
    <li class="nav-item menu-items">
      <a class="nav-link" href="{{route('admin-dashboard')}}">
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
          <li class="nav-item"> <a class="nav-link" href="/admin-buyer">Buyer</a></li>
          {{-- <li class="nav-item"> <a class="nav-link" href="/admin-buyer">Search</a></li> --}}
          <li class="nav-item"> <a class="nav-link" href="{{route('customer-sales')}}">Customer Sales</a></li>
        </ul>
      </div>
    </li>
    
  </ul>
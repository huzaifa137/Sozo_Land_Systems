<ul class="navbar-nav navbar-nav-right">
    
            <?php  
                
                use App\Models\AdminRegister;

                $user_id = Session('LoggedAdmin');
                $User_access_right = AdminRegister::where('id', '=', $user_id)->value('admin_category');
                
              ?>
              
              
        @if($User_access_right == 'SuperAdmin')
                    <li class="nav-item dropdown d-none d-lg-block">
      <a class="nav-link btn btn-success create-new-button" id="createbuttonDropdown" data-bs-toggle="dropdown" aria-expanded="false" >+ Manage Admins</a>
      <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="createbuttonDropdown">
        <h6 class="p-3 mb-0" style="text-align: center">Sozo Properties</h6>
        <div class="dropdown-divider"></div>

        <a class="dropdown-item preview-item" href="{{'admin-register'}}">
          <div class="preview-thumbnail">
            <div class="preview-icon bg-dark rounded-circle">
              <i class="mdi mdi-plus text-primary"></i>
            </div>
          </div>
          <div class="preview-item-content">
            <p class="preview-subject ellipsis mb-1"> Add new Admin</p>
          </div>
        </a>
        <div class="dropdown-divider"></div>
    </li>
    
             @endif
    
   
    <li class="nav-item dropdown">
      <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
        <div class="navbar-profile">
          <img class="img-xs rounded-circle" src="/assets/images/faces/face15.jpg" alt="">
          <p class="mb-0 d-none d-sm-block navbar-profile-name">{{$LoggedAdminInfo['username']}}</p>
          <i class="mdi mdi-menu-down d-none d-sm-block"></i>
        </div>
      </a>
      <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
        <h6 class="p-3 mb-0">Profile</h6>
        <div class="dropdown-divider"></div>
        {{-- <a class="dropdown-item preview-item">
          <div class="preview-thumbnail">
            <div class="preview-icon bg-dark rounded-circle">
              <i class="mdi mdi-settings text-success"></i>
            </div>
          </div>
          <div class="preview-item-content">
            <p class="preview-subject mb-1">Settings</p>
          </div>
        </a> --}}
        <div class="dropdown-divider"></div>
        <a class="dropdown-item preview-item" href="{{route('admin-logout')}}">
          <div class="preview-thumbnail">
            <div class="preview-icon bg-dark rounded-circle">
              <i class="mdi mdi-logout text-danger"></i>
            </div>
          </div>
          <div class="preview-item-content">
            <p class="preview-subject mb-1" >Log out</p>
          </div>
        </a>
      </div>
    </li>
  </ul>
<aside class="main-sidebar sidebar-dark-lime elevation-4 bg-sidebar">
<!-- sidebar-dark-primary -->
    <!-- Brand Logo -->
    <a href="<?php echo base_url; ?>users/admin" class="brand-link bg-sidebar">
      <img src="<?php echo base_url; ?>dist/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><?=$_SESSION['system_info']['name'];?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url . $_settings->userdata('image_path');?> " class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $_settings->userdata('full_name');?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline d-none">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item ">
            <a href="./" class="nav-link nav-dashboard">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <!-- <i class="right fas fa-angle-left"></i> -->
              </p>
            </a>
          </li>
          
          <li class="nav-header">OPTIONS</li>
        
          <li class="nav-item">
            <a href="./?page=myprofile" class="nav-link nav-myprofile">
              <i class="nav-icon fas fa-user"></i>
              <p>
                My Profile
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?php echo base_url.'classes/Login.php?f=logout';?>" class="nav-link nav-signout">
              <i class="nav-icon fas fa-power-off"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
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
          <li class="nav-header">COLLECTIONS</li>
          <li class="nav-item">
            <a href="./?page=category" class="nav-link nav-category">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Category
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="./?page=measurements" class="nav-link nav-measurements">
              <i class="nav-icon fas fa-balance-scale"></i>
              <p>
                Measurements
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="./?page=products" class="nav-link nav-products">
              <i class="nav-icon fas fa-th-list"></i>
              <p>
                Products
              </p>
            </a>
          </li>

          <li class="nav-header">TRANSACTIONS</li>
          <li class="nav-item">
            <a href="./?page=orders" class="nav-link nav-orders">
              <i class="nav-icon fas fa-cart-arrow-down"></i>
              <p>
                Orders
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="./?page=deliveries" class="nav-link nav-deliveries">
              <i class="nav-icon fas fa-truck"></i>
              <p>
                Deliveries
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="./?page=sales" class="nav-link nav-sales">
              <i class="nav-icon fas fa-hand-holding-usd"></i>
              <p>
                Sales
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="./?page=returns" class="nav-link nav-returns">
              <i class="nav-icon fas fa-unlink"></i>
              <p>
                Returns
              </p>
            </a>
          </li>

          <li class="nav-header">PERSONS</li>
          <li class="nav-item">
            <a href="./?page=customers" class="nav-link nav-customers">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>
                Customers
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="./?page=suppliers" class="nav-link nav-suppliers">
              <i class="nav-icon fas fa-hands-helping"></i>
              <p>
                Suppliers
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="./?page=accounts" class="nav-link nav-accounts">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                Employees
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="./?page=online-users" class="nav-link nav-online-users">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Online Users
              </p>
            </a>
          </li>

          <li class="nav-header">OPTIONS</li>
          <li class="nav-item">
            <a href="./?page=system_info" class="nav-link nav-system_info">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Settings
              </p>
            </a>
          </li>
          
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
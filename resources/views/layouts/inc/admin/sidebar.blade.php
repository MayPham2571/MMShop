<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/dashboard') }}">
          <i class="mdi mdi-camera-timer menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>    
      <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/orders') }}">
          <i class="mdi mdi-sale menu-icon"></i>
          <span class="menu-title">Orders</span>
        </a>
        
      </li>    
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#category">
          <i class="mdi mdi-view-list menu-icon"></i>
          <span class="menu-title">Category</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="category">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/category/create') }}">Add Category</a></li>  
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/category') }}">View Category</a></li>              
          </ul>
        </div>
      </li>    
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#products" >
          <i class="mdi mdi-plus-circle menu-icon"></i>
          <span class="menu-title">Products</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="products" >
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="{{ url('admin/products/create') }}">Add Product</a></li>
            <li class="nav-item"> <a class="nav-link" href="{{ url('admin/products') }}">View Product</a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/brands') }}">
          <i class="mdi mdi-tag-multiple menu-icon"></i>
          <span class="menu-title">Brands</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/colors') }}">
          <i class="mdi mdi-invert-colors menu-icon"></i>
          <span class="menu-title">Colors</span>
        </a>
      </li>       

      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="collapse" href="#users" aria-expanded="false" >
          <i class="mdi mdi-account-multiple-plus menu-icon"></i>
          <span class="menu-title">Users</span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="users">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item"> <a class="nav-link" href="#"> Add User </a></li>
            <li class="nav-item"> <a class="nav-link" href="#"> View Users </a></li>                  
          </ul>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/sliders') }}">
          <i class="mdi mdi-view-carousel menu-icon"></i>
          <span class="menu-title">Home Slider</span>
        </a>
      </li>    
      
      <li class="nav-item">
        <a class="nav-link" href="{{ url('admin/settings') }}">
          <i class="mdi mdi-cog menu-icon"></i>
          <span class="menu-title">Site Setting</span>
        </a>
      </li>
    </ul>
  </nav>
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Sales and Monitoring</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

  
      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Transaction -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#transaction" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-list"></i>
          <span>Transaction</span>
        </a>
        <div id="transaction" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ url('/purchaseorder') }}">Purchase Order (PO)</a>
            <a class="collapse-item" href="{{ url('/stockreceive') }}">Stock Receive (RR)</a>
            <a class="collapse-item" href="{{ url('/cash') }}">Cash Transaction</a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

    <!-- Management -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#management" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>Managment</span>
        </a>
        <div id="management" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ url('/masterfile') }}">Stock Master</a>
            <a class="collapse-item" href="{{ url('/client') }}">Client</a>
            <a class="collapse-item" href="{{ url('/supplier') }}">Supplier</a>
          </div>
        </div>
      </li>

    </ul>
    <!-- End of Sidebar -->
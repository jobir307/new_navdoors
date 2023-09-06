<style type="text/css">
  .active, nav ul li a:hover {
    color: #696cff !important;
    font-size: 14px;
    font-weight: bold;
  }
</style>
<nav
  class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
  id="layout-navbar"
>
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>
  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">    
    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <?php 
        $first_segment = [];
        $first_segment = ['accountant-reconciliation-act', 'accountant-customer-reconciliation-act'];
        $active = "";
        if (in_array(Request::segment(1), $first_segment))
          $active = "active";
      ?>
      <li class="nav-item">
        <a class="nav-link {{ $active }}" href="{{ route('reconciliation-act') }}">Akt sverka</a>
      </li>
      <?php 
        $first_segment = [];
        $first_segment = ['accountant-orders', 'show-order-payment-histories'];
        $active = "";
        if (in_array(Request::segment(1), $first_segment))
          $active = "active";
      ?>
      <li class="nav-item">
        <a class="nav-link {{ $active }}" href="{{ route('accountant') }}">Shartnomalar</a>
      </li>
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
            {{ Auth::user()->username }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block">{{ Auth::user()->role() }}</span>
                </div>
              </div>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bx bx-power-off me-2"></i>
              <span class="align-middle">Chiqish</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }} </form>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
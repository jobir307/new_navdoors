<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Buxgalteriya bo'limi</title>

  @include('accountant.style')

</head>
<body>
<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">

    <div class="layout-page">

      @include('accountant.navbar')
    
      <div class="content-wrapper">

        @yield('content')

        <div class="content-backdrop fade"></div>
      </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
</div>

@yield('scripts')
      
</body>
</html>
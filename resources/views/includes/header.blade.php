@php
    $szVersion = @filemtime(public_path('assets/sozo/sozo.css')) ?: 1;
    $szLoaderVersion = max(@filemtime(public_path('assets/sozo/loader.css')) ?: 1, @filemtime(public_path('assets/sozo/loader.js')) ?: 1);
    $szUser = \Illuminate\Support\Facades\DB::table('admin_registers')->where('id', session('LoggedAdmin'))->first();
    $szRole = $szUser->admin_category ?? '';
    $szName = $szUser->username ?? 'Account';

    // Friendly page title for the top bar, keyed by route name.
    $szTitles = [
        'admin-dashboard' => ['Overview', 'Dashboard'],
        'estates' => ['Land', 'Estates'],
        'available-estates' => ['Land', 'Available estates'],
        'add-estate' => ['Land', 'Create an estate'],
        'view-estate' => ['Land', 'Estate overview'],
        'plots' => ['Land', 'Add plots'],
        'admin-buyer' => ['Land', 'Sell a plot'],
        'add-house' => ['Houses', 'Add a house'],
        'admin.sell.house.fetch' => ['Houses', 'Houses for sale'],
        'approval.house.sell' => ['Houses', 'Pending approval'],
        'house.details' => ['Houses', 'House details'],
        'accomplished' => ['Receipts', 'Fully purchased'],
        'pending-buyers' => ['Receipts', 'Under payments'],
        'grant-agreement-permission' => ['Receipts', 'Grant access'],
        'all-sales' => ['Sales', 'All sales'],
        'current-sales' => ['Sales', 'Today\'s sales'],
        'weekly-records' => ['Sales', 'Weekly sales'],
        'monthly-records' => ['Sales', 'Monthly sales'],
        'search-land' => ['Resales', 'Resale'],
        'back-on-market' => ['Resales', 'Back on market'],
        'back-on-market-all' => ['Resales', 'All back on market'],
        'assign-empty-plot' => ['Transfers', 'Transfer to a new plot'],
        'set-reminder' => ['Reminders', 'Set a reminder'],
        'payment-reminder' => ['Reminders', 'Today\'s reminders'],
        'add-expenditure' => ['Expenditures', 'Add an expenditure'],
        'expense-today' => ['Expenditures', 'Today\'s expenditures'],
        'user-information' => ['Users', 'All users'],
        'search-module' => ['Tools', 'Search'],
        'all-posters' => ['Tools', 'Posters'],
        'backup.uploads' => ['Tools', 'Backup'],
    ];
    $szRouteName = optional(request()->route())->getName();
    if (isset($szTitles[$szRouteName])) {
        [$szSection, $szTitle] = $szTitles[$szRouteName];
    } else {
        $szSegment = collect(explode('/', trim(request()->path(), '/')))->first() ?: 'dashboard';
        $szSection = 'Sozo Land Systems';
        $szTitle = ucfirst(str_replace(['-', '_'], ' ', $szSegment));
    }
@endphp
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0d1a16">
    <title>{{ $szTitle }} | Sozo Land Systems</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Instrument+Sans:wght@400;500;600;700&display=swap">
    <!-- plugins:css -->
    <link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="/assets/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- Base template styles, then the Sozo theme on top -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/sozo/sozo.css?v={{ $szVersion }}">
    <link rel="stylesheet" href="/assets/sozo/loader.css?v={{ $szLoaderVersion }}">
    <link rel="icon" type="image/png" href="/assets/sozo/favicon.png" />
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <script>try { if (localStorage.getItem('sz-sidebar') === '1') document.documentElement.classList.add('sz-collapsed'); } catch (e) {}</script>
    <script src="/assets/sozo/sozo.js?v={{ $szVersion }}" defer></script>
    <script src="/assets/sozo/loader.js?v={{ $szLoaderVersion }}" defer></script>
  </head>
  <body>
    <div class="container-scroller">
      <nav class="sidebar sz-side" id="sidebar" aria-label="Main navigation">
        @include('includes.SideBar')
      </nav>
      <div class="sz-scrim" aria-hidden="true"></div>
      <div class="container-fluid page-body-wrapper">
        <nav class="navbar sz-top" aria-label="Top bar">
          @include('includes.TopNav')
        </nav>

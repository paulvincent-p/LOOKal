<nav class="subnav" aria-label="Admin">
  <a href="{{ route('admin.dashboard') }}" @class(['on' => request()->routeIs('admin.dashboard')])>Dashboard</a>
  <a href="{{ route('admin.businesses.index') }}" @class(['on' => request()->routeIs('admin.businesses.*')])>Listings</a>
  <a href="{{ route('admin.reviews.index') }}" @class(['on' => request()->routeIs('admin.reviews.*')])>Reviews</a>
  <a href="{{ route('admin.reports.index') }}" @class(['on' => request()->routeIs('admin.reports.*')])>Reports</a>
  <a href="{{ route('admin.lookups.index', 'categories') }}" @class(['on' => request()->is('admin/lookups/categories')])>Categories</a>
  <a href="{{ route('admin.lookups.index', 'barangays') }}" @class(['on' => request()->is('admin/lookups/barangays')])>Barangays</a>
  <a href="{{ route('admin.emergency.index') }}" @class(['on' => request()->routeIs('admin.emergency.*')])>Emergency</a>
  <a href="{{ route('admin.users.index') }}" @class(['on' => request()->routeIs('admin.users.*')])>Users</a>
</nav>

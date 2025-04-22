@auth
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
        <a href="">FlexyLite </a>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ Request::is('home') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('home') }}"><span>Dashboard</span></a>
            </li>
           
            @if (Auth::user()->role == 'superadmin')

            <li class="{{ Request::is('product') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('products.index') }}"><span>Produk</span></a>
            </li>
            <li class="{{ Request::is('sales') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sales.index') }}"><span>Penjualan</span></a>
            </li>
           
            <li class="{{ Request::is('user') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.index')}}"><span>User</span></a>
            </li>
            <li class="{{ Request::is('members') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('members.index')}}"> <span>Member</span></a>
            </li>

            @endif
            @if (Auth::user()->role == 'user')
           
            <li class="{{ Request::is('product') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('products.index') }}"> <span>Produk</span></a>
            </li>
            <li class="{{ Request::is('sales') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('sales.index') }}"><span>Penjualan</span></a>
            </li>
            <li class="{{ Request::is('members') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('members.index')}}"><span>Member</span></a>
            </li>
            @endif
        </ul>
    </aside>
</div>
@endauth
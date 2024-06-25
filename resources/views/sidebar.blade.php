<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="#">WIN</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        {{-- <a href="#">W</a> --}}
        <img src="/assets/img/LogoWin-Shop.png" style="width: 40px; height: 40px;" alt="">
      </div>
      <ul class="sidebar-menu">
        @if (Auth::user()->role_id == 1)    
        <li class="menu-header">Banner Win Belanja</li>
          <li class="dropdown {{ request()->routeIs(['superadmin.banner-utama','superadmin.banner-diskon','superadmin.banner-spesial']) ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-th-large"></i> <span>Banner E-commerce</span></a>
            <ul class="dropdown-menu">
              <li class="{{ request()->routeIs('superadmin.banner-utama') ? 'active' : '' }}"><a class="nav-link " href="{{route('superadmin.banner-utama')}}">Banner Utama</a></li>
            <li class="{{ request()->routeIs('superadmin.banner-diskon') ? 'active' : '' }}"><a class="nav-link" href="{{route('superadmin.banner-diskon')}}">Banner Diskon</a></li>
            <li class="{{ request()->routeIs('superadmin.banner-spesial') ? 'active' : '' }}"><a class="nav-link" href="{{route('superadmin.banner-spesial')}}">Banner Spesial</a></li>
          </ul>
        </li>
          <li class="menu-header">Win Belanja</li>
          <li class="{{ request()->routeIs('superadmin.toko') ? 'active' : '' }}"><a href="{{route('superadmin.toko')}}"><i class="fas fa-store"></i><span>Toko</span></a></li>
          <li class="{{ request()->routeIs('superadmin.kategori-produk') ? 'active' : '' }}"><a href="{{route('superadmin.kategori-produk')}}"><i class="fas fa-th-large"></i><span>Kategori Produk</span></a></li>
          <li class="{{ request()->routeIs('superadmin.produk') ? 'active' : '' }}"><a href="{{route('superadmin.produk')}}"><i class="fas fa-shopping-bag"></i><span>Produk</span></a></li>
          <li class="{{ request()->routeIs('promosi') ? 'active' : '' }}"><a href="{{route('promosi')}}"><i class="fas fa-tags"></i><span>Promo Produk</span></a></li>
        <li class="{{ request()->routeIs('pesanan') ? 'active' : '' }}"><a href="{{route('pesanan')}}"><i class="fas fa-credit-card"></i><span>Pesanan</span></a></li>
        <li class="{{ request()->routeIs('pengiriman') ? 'active' : '' }}"><a href="{{route('pengiriman')}}"><i class="fas fa-shipping-fast"></i><span>Pengiriman</span></a></li>
        
        <!-- {{-- <li><a class="nav-link {{ request()->routeIs('superadmin.list-review') ? 'active' : '' }}" href="{{route('superadmin.list-review')}}"><i class="fas fa-comment-alt"></i><span>Review</span></a></li> --}} -->
        <li class="menu-header">Events</li>
        <li class="{{ request()->routeIs('superadmin.event') ? 'active' : '' }}"><a href="{{route('superadmin.event')}}"><i class="fas fa-calendar-alt"></i><span>Services</span></a></li>
        <li class="{{ request()->routeIs('superadmin.event-lelang') ? 'active' : '' }}"><a href="{{route('superadmin.event-lelang')}}"><i class="fas fa-calendar-alt"></i><span>Lelang</span></a></li>
        <li class="menu-header">Win Lelang</li>
        <li class="{{ request()->routeIs('superadmin.banner-lelang') ? 'active' : '' }}"><a href="{{route('superadmin.banner-lelang')}}"><i class="fas fa-images"></i><span>Banner Web Lelang</span></a></li>
        <li class="{{ request()->routeIs('superadmin.kategori-lelang') ? 'active' : '' }}"><a href="{{route('superadmin.kategori-lelang')}}"><i class="fas fa-th-large"></i><span>Kategori Lelang</span></a></li>
        <li class="{{ request()->routeIs('superadmin.barang-lelang') ? 'active' : '' }}"><a href="{{route('superadmin.barang-lelang')}}"><i class="fas fa-car"></i><span>Barang Lelang</span></a></li>
        <li class="{{ request()->routeIs('superadmin.lot') ? 'active' : '' }}"><a href="{{route('superadmin.lot')}}"><i class="fas fa-star"></i><span>Lot</span></a></li>
        <li class="{{ request()->routeIs('superadmin.peserta-npl') ? 'active' : '' }}"><a href="{{route('superadmin.peserta-npl')}}"><i class="fas fa-users"></i><span>Peserta & NPL Lelang</span></a></li>
        <li class="{{ request()->routeIs('superadmin.pemenang') ? 'active' : '' }}"><a href="{{route('superadmin.pemenang')}}"><i class="fas fa-trophy"></i><span>Pemenang</span></a></li>
        <li class="{{ request()->routeIs('superadmin.ulasan') ? 'active' : '' }}"><a href="{{route('superadmin.ulasan')}}"><i class="fas fa-comment-alt"></i><span>Ulasan</span></a></li>
        {{-- <ul class="sidebar-menu">
          <li class="menu-header">User Management</li>
          <li class="{{ request()->routeIs('user-cms') ? 'active' : '' }}"><a href="{{route('superadmin.user-cms')}}"><i class="fas fa-users"></i><span>User</span></a></li>
        </ul> --}}
        <ul class="sidebar-menu">
          <li class="menu-header">Setting</li>
          <li class="{{ request()->routeIs('setting') ? 'active' : '' }}"><a href="{{route('superadmin.setting')}}"><i class="fas fa-cogs"></i><span>Setting</span></a></li>
        </ul>
        @else
        <li class="menu-header">Win Belanjan</li>
        <li class="{{ request()->routeIs('admin.profil-toko') ? 'active' : '' }}"><a href="{{route('admin.profil-toko')}}"><i class="fas fa-store"></i><span>Profil Toko</span></a></li>
        <li class="{{ request()->routeIs('admin.kategori-produk') ? 'active' : '' }}"><a href="{{route('admin.kategori-produk')}}"><i class="fas fa-th-large"></i><span>Kategori Produk</span></a></li>
        <li class="{{ request()->routeIs('admin.produk') ? 'active' : '' }}"><a href="{{route('admin.produk')}}"><i class="fas fa-shopping-bag"></i><span>Produk</span></a></li>
        <li class="{{ request()->routeIs('promosi') ? 'active' : '' }}"><a href="{{route('promosi')}}"><i class="fas fa-tags"></i><span>Promo Produk</span></a></li>
      <li class="{{ request()->routeIs('pesanan') ? 'active' : '' }}"><a href="{{route('pesanan')}}"><i class="fas fa-credit-card"></i><span>Pesanan</span></a></li>
      <li class="{{ request()->routeIs('pengiriman') ? 'active' : '' }}"><a href="{{route('pengiriman')}}"><i class="fas fa-credit-card"></i><span>Pengiriman</span></a></li>
            
        @endif
      </ul>
      
    </aside>
  </div>
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="#">WIN</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="#">W</a>
      </div>
      <ul class="sidebar-menu">
        @if (Auth::user()->role_id == 1)    
        <li class="menu-header">Banner Win Belanja</li>
          <li class="dropdown {{ request()->routeIs(['banner-utama','banner-diskon','banner-spesial']) ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-th-large"></i> <span>Banner E-commerce</span></a>
            <ul class="dropdown-menu">
              <li class="{{ request()->routeIs('banner-utama') ? 'active' : '' }}"><a class="nav-link " href="{{route('banner-utama')}}">Banner Utama</a></li>
            <li class="{{ request()->routeIs('banner-diskon') ? 'active' : '' }}"><a class="nav-link" href="{{route('banner-diskon')}}">Banner Diskon</a></li>
            <li class="{{ request()->routeIs('banner-spesial') ? 'active' : '' }}"><a class="nav-link" href="{{route('banner-spesial')}}">Banner Spesial</a></li>
          </ul>
        </li>
          <li class="menu-header">Win Belanja</li>
          <li class="{{ request()->routeIs('toko') ? 'active' : '' }}"><a href="{{route('toko')}}"><i class="fas fa-store"></i><span>Toko</span></a></li>
          <li class="{{ request()->routeIs('kategori-produk') ? 'active' : '' }}"><a href="{{route('kategori-produk')}}"><i class="fas fa-th-large"></i><span>Kategori Produk</span></a></li>
          <li class="{{ request()->routeIs('produk') ? 'active' : '' }}"><a href="{{route('produk')}}"><i class="fas fa-shopping-bag"></i><span>Produk</span></a></li>
          <li class="{{ request()->routeIs('promosi') ? 'active' : '' }}"><a href="{{route('promosi')}}"><i class="fas fa-tags"></i><span>Promo Produk</span></a></li>
        <li class="{{ request()->routeIs('pesanan') ? 'active' : '' }}"><a href="{{route('pesanan')}}"><i class="fas fa-credit-card"></i><span>Pesanan</span></a></li>
        <!-- {{-- <li><a class="nav-link {{ request()->routeIs('list-review') ? 'active' : '' }}" href="{{route('list-review')}}"><i class="fas fa-comment-alt"></i><span>Review</span></a></li> --}} -->
        <li class="menu-header">Win Event</li>
        <li class="{{ request()->routeIs('event') ? 'active' : '' }}"><a href="{{route('event')}}"><i class="fas fa-calendar-alt"></i><span>Event Belanja</span></a></li>
        <li class="menu-header">Win Lelang</li>
        <li class="{{ request()->routeIs('kategori-lelang') ? 'active' : '' }}"><a href="{{route('kategori-lelang')}}"><i class="fas fa-th-large"></i><span>Kategori Lelang</span></a></li>
        <li class="{{ request()->routeIs('barang-lelang') ? 'active' : '' }}"><a href="{{route('barang-lelang')}}"><i class="fas fa-car"></i><span>Barang Lelang</span></a></li>
        <li class="{{ request()->routeIs('event-lelang') ? 'active' : '' }}"><a href="{{route('event-lelang')}}"><i class="fas fa-calendar-alt"></i><span>Event Lelang</span></a></li>
        <li class="{{ request()->routeIs('lot') ? 'active' : '' }}"><a href="{{route('lot')}}"><i class="fas fa-star"></i>Lot</a></li>
        <li class="{{ request()->routeIs('peserta-npl') ? 'active' : '' }}"><a href="{{route('peserta-npl')}}"><i class="fas fa-users"></i>Peserta & NPL Lelang</a></li>


        <!-- {{-- <li class="{{ request()->routeIs('banner-lelang') ? 'active' : '' }}"><a href="{{route('banner-lelang')}}"><i class="fas fa-th-large"></i><span>Banner Lelang</span></a></li> --}} -->
        {{-- <li class="{{ request()->routeIs('user-cms') ? 'active' : '' }}"><a href="{{route('user-cms')}}"><i class="fas fa-users"></i><span>User</span></a></li> --}}
        <!-- {{-- <li class="dropdown  {{ request()->routeIs(['pembelian-npl', 'lot', 'barang-lelang', 'event-lelang','kategori-lelang']) ? 'active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-gavel"></i> <span>Lelang</span></a>
          <ul class="dropdown-menu">
            <li class="{{ request()->routeIs('kategori-lelang') ? 'active' : '' }}"><a class="nav-link " href="{{route('kategori-lelang')}}">Kategori Lelang</a></li>
            <li class="{{ request()->routeIs('barang-lelang') ? 'active' : '' }}"><a class="nav-link " href="{{route('barang-lelang')}}">Barang Lelang</a></li>
            <li class="{{ request()->routeIs('event-lelang') ? 'active' : '' }}"><a class="nav-link " href="{{route('event-lelang')}}">Event Lelang</a></li>
            <li class="{{ request()->routeIs('lot') ? 'active' : '' }}"><a class="nav-link " href="{{route('lot')}}">Lot</a></li>
            <li class="{{ request()->routeIs('pembelian-npl') ? 'active' : '' }}"><a class="nav-link " href="{{route('pembelian-npl')}}">Peserta & NPL Lelang</a></li>
          </ul>
        </li> --}} -->
        @else
        <li class="menu-header">Win Belanja</li>
        <li class="{{ request()->routeIs('profil-toko') ? 'active' : '' }}"><a href="{{route('profil-toko')}}"><i class="fas fa-store"></i><span>Toko Saya</span></a></li>
        <li class="{{ request()->routeIs('kategori-produk') ? 'active' : '' }}"><a href="{{route('kategori-produk')}}"><i class="fas fa-th-large"></i><span>Kategori Produk</span></a></li>
        <li class="{{ request()->routeIs('produk') ? 'active' : '' }}"><a href="{{route('produk')}}"><i class="fas fa-shopping-bag"></i><span>Produk</span></a></li>
        <li class="{{ request()->routeIs('promosi') ? 'active' : '' }}"><a href="{{route('promosi')}}"><i class="fas fa-tags"></i><span>Promo Produk</span></a></li>
      <li class="{{ request()->routeIs('pesanan') ? 'active' : '' }}"><a href="{{route('pesanan')}}"><i class="fas fa-credit-card"></i><span>Pesanan</span></a></li>
            
        @endif
      </ul>
      {{-- <ul class="sidebar-menu">
        <li class="menu-header">Setting</li>
        <li class="{{ request()->routeIs('setting') ? 'active' : '' }}"><a href="{{route('setting')}}"><i class="fas fa-cogs"></i><span>Setting</span></a></li>
      </ul> --}}
    </aside>
  </div>
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="#">WIN</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="#">W</a>
      </div>
      <ul class="sidebar-menu">
        <li class="menu-header">Menu SuperAdmin</li>
        <li class="dropdown {{ request()->routeIs(['banner-utama','banner-diskon','banner-spesial']) ? 'active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-th-large"></i> <span>Banner E-commerce</span></a>
          <ul class="dropdown-menu">
            <li class="{{ request()->routeIs('banner-utama') ? 'active' : '' }}"><a class="nav-link " href="{{route('banner-utama')}}">Banner Utama</a></li>
            <li class="{{ request()->routeIs('banner-diskon') ? 'active' : '' }}"><a class="nav-link" href="{{route('banner-diskon')}}">Banner Diskon</a></li>
            <li class="{{ request()->routeIs('banner-spesial') ? 'active' : '' }}"><a class="nav-link" href="{{route('banner-spesial')}}">Banner Spesial</a></li>
          </ul>
        </li>
        <li class="dropdown {{ request()->routeIs(['toko', 'kategori-produk', 'produk', 'pembayaran', 'pengiriman','promosi','detail-pesanan']) ? 'active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-shopping-cart"></i> <span>E-commerce</span></a>
          <ul class="dropdown-menu">
            <li class="{{ request()->routeIs('toko') ? 'active' : '' }}"><a class="nav-link " href="{{route('toko')}}">Toko</a></li>
            <li class="{{ request()->routeIs('kategori-produk') ? 'active' : '' }}"><a class="nav-link" href="{{route('kategori-produk')}}">Kategori Produk</a></li>
            <li class="{{ request()->routeIs('produk') ? 'active' : '' }}"><a class="nav-link klik_menu" id="produks" href="{{route('produk')}}">Produk</a></li>
            <li class="{{ request()->routeIs('promosi') ? 'active' : '' }}"><a class="nav-link" href="{{route('promosi')}}">Promo Produk</a></li>
          </ul>
        </li>
        <li class="{{ request()->routeIs('pesanan') ? 'active' : '' }}"><a href="{{route('pesanan')}}"><i class="fas fa-credit-card"></i><span>Pesanan</span></a></li>
        {{-- <li><a class="nav-link {{ request()->routeIs('list-review') ? 'active' : '' }}" href="{{route('list-review')}}"><i class="fas fa-comment-alt"></i><span>Review</span></a></li> --}}
        {{-- <li class="{{ request()->routeIs('event') ? 'active' : '' }}"><a href="{{route('event')}}"><i class="fas fa-calendar-alt"></i><span>Event Belanja</span></a></li> --}}
        {{-- <li class="{{ request()->routeIs('banner-lelang') ? 'active' : '' }}"><a href="{{route('banner-lelang')}}"><i class="fas fa-th-large"></i><span>Banner Lelang</span></a></li> --}}
        {{-- <li class="{{ request()->routeIs('user-cms') ? 'active' : '' }}"><a href="{{route('user-cms')}}"><i class="fas fa-users"></i><span>User CMS</span></a></li> --}}
        <li class="dropdown  {{ request()->routeIs(['pembelian-npl', 'lot', 'barang-lelang', 'event-lelang','kategori-lelang']) ? 'active' : '' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-gavel"></i> <span>Lelang</span></a>
          <ul class="dropdown-menu">
            <li class="{{ request()->routeIs('kategori-lelang') ? 'active' : '' }}"><a class="nav-link " href="{{route('kategori-lelang')}}">Kategori Lelang</a></li>
            <li class="{{ request()->routeIs('barang-lelang') ? 'active' : '' }}"><a class="nav-link " href="{{route('barang-lelang')}}">Barang Lelang</a></li>
            <li class="{{ request()->routeIs('event-lelang') ? 'active' : '' }}"><a class="nav-link " href="{{route('event-lelang')}}">Event Lelang</a></li>
            {{-- <li class="{{ request()->routeIs('lot') ? 'active' : '' }}"><a class="nav-link " href="{{route('lot')}}">Lot</a></li> --}}
            {{-- <li class="{{ request()->routeIs('pembelian-npl') ? 'active' : '' }}"><a class="nav-link " href="{{route('pembelian-npl')}}">Peserta & NPL Lelang</a></li> --}}
          </ul>
        </li>
        
      </ul>
      {{-- <ul class="sidebar-menu">
        <li class="menu-header">Setting</li>
        <li class="{{ request()->routeIs('setting') ? 'active' : '' }}"><a href="{{route('setting')}}"><i class="fas fa-cogs"></i><span>Setting</span></a></li>
      </ul> --}}
    </aside>
  </div>
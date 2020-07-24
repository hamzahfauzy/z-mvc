<div class="header">
    <div class="site-title">
        <center>
        <img src="<?= asset('assets/logo.png') ?>" height="80px" width="100%" style="object-fit: none;">
        <h3>INDONESIA</h3>
        </center>
    </div>
    <ul class="nav-menu">
        <li>
            <a href="<?= base_url()?>" class="<?= $this->visited == 'beranda' ? 'active' : '' ?>"><i class="fa fa-home"></i> Beranda</a>
        </li>
        <li>
            <a href="<?= base_url()?>/home/profil" class="<?= $this->visited == 'profil' ? 'active' : '' ?>"><i class="fa fa-user"></i> Profil</a>
        </li>
    <?php if(!session()->get('id') || (session()->get('id') && session()->user()->level == 'customer')): ?>
        <li>
            <a href="<?= base_url()?>/home/produk" class="<?= $this->visited == 'semua-produk' ? 'active' : '' ?>"><i class="fa fa-cube"></i> Semua Produk</a>
        </li>
        <li>
            <a href="<?= base_url()?>/home/keranjang" class="<?= $this->visited == 'keranjang' ? 'active' : '' ?>"><i class="fa fa-shopping-cart"></i> Keranjang Belanja <?= session()->get('cart') ? '('.count(session()->get('cart')).')' : '' ?></a>
        </li>
    <?php 
    endif;
    if(session()->get('id')): 
        if(session()->user()->level == 'customer'):
    ?>
        <li>
            <a href="<?= base_url()?>/transaksi" class="<?= $this->visited == 'transaksi' ? 'active' : '' ?>"><i class="fa fa-shopping-bag"></i> Transaksi</a>
        </li>
        
    <?php else: ?>
        <li>
            <a href="<?= base_url()?>/admin/member" class="<?= $this->visited == 'member' ? 'active' : '' ?>"><i class="fa fa-users"></i> Member</a>
        </li>
        <li>
            <a href="<?= base_url()?>/admin/kategori" class="<?= $this->visited == 'kategori' ? 'active' : '' ?>"><i class="fa fa-archive"></i> Kategori</a>
        </li>
        <li>
            <a href="<?= base_url()?>/admin/produk" class="<?= $this->visited == 'produk' ? 'active' : '' ?>"><i class="fa fa-cubes"></i> Produk</a>
        </li>
        <li>
            <a href="<?= base_url()?>/admin/shipping" class="<?= $this->visited == 'shipping' ? 'active' : '' ?>"><i class="fa fa-truck"></i> Shipping</a>
        </li>
        <li>
            <a href="<?= base_url()?>/admin/order" class="<?= $this->visited == 'order' ? 'active' : '' ?>"><i class="fa fa-shopping-bag"></i> Order</a>
        </li>
        <li>
            <a href="<?= base_url()?>/admin/konsultasi" class="<?= $this->visited == 'konsultasi' ? 'active' : '' ?>"><i class="fa fa-comment"></i> Konsultasi</a>
        </li>
    <?php endif; ?>
        <li>
            <a href="<?= base_url()?>/auth/logout"><i class="fa fa-sign-out"></i> Log Out</a>
        </li>
    <?php else: ?>
        <li>
            <a href="<?= base_url()?>/auth/login"><i class="fa fa-sign-in"></i> Log In</a>
        </li>
    <?php endif ?>
    <?php if((session()->get('id') && session()->user()->level == 'customer') || !session()->get('id')): ?>
        <li>
            <a href="<?= base_url()?>/home/cek" class="<?= $this->visited == 'cek' ? 'active' : '' ?>"><i class="fa fa-check"></i> Cek Transaksi</a>
        </li>
    <?php endif ?>
    <?php if(!session()->get('id') || (session()->get('id') && session()->user()->level == "customer")): ?>
        <li>
            <a href="<?= base_url()?>/home/konsultasi" class="<?= $this->visited == 'konsultasi' ? 'active' : '' ?>"><i class="fa fa-comment"></i> Konsultasi</a>
        </li>
    <?php endif ?>
    </ul>
</div>
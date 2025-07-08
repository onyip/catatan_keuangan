 <div id="app" style="display: none;">
     <!-- Sidebar (Hanya tampil di desktop) -->
     <aside id="sidebar" class="d-none d-lg-flex flex-column p-3">
         <div class="sidebar-header">
             <i class="bi bi-wallet2"></i> <?= $appName; ?>
         </div>
         <hr>
         <ul class="nav nav-pills flex-column mb-auto">
             <li class="nav-item">
                 <a href="<?= base_url(); ?>/transaksi" class="nav-link active" data-page="transaksi" onclick="showPage('transaksi', this)">
                     <i class="bi bi-arrow-down-up"></i> Transaksi
                 </a>
             </li>
             <li>
                 <a href="<?= base_url(); ?>/kategori" class="nav-link" data-page="kategori" onclick="showPage('kategori', this)">
                     <i class="bi bi-tags-fill"></i> Kategori
                 </a>
             </li>
             <li>
                 <a href="<?= base_url(); ?>/laporan" class="nav-link" data-page="laporan" onclick="showPage('laporan', this)">
                     <i class="bi bi-pie-chart-fill"></i> Laporan
                 </a>
             </li>
             <li>
                 <a href="<?= base_url(); ?>/profil" class="nav-link" data-page="profil" onclick="showPage('profil', this)">
                     <i class="bi bi-person-circle"></i> Profil
                 </a>
             </li>
         </ul>
         <hr>
         <div class="dropdown">
             <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                 <img src="https://placehold.co/32x32/0d6efd/ffffff?text=A" alt="" width="32" height="32" class="rounded-circle me-2">
                 <strong>Admin User</strong>
             </a>
             <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                 <li>
                     <hr class="dropdown-divider">
                 </li>
                 <li><a class="dropdown-item" href="<?= base_url(); ?>/auth/logout">Logout</a></li>
             </ul>
         </div>
     </aside>

     <?= $this->renderSection('content') ?>

 </div>

 <!-- Bottom Navigation (Hanya tampil di mobile) -->
 <nav id="bottom-nav" class="d-lg-none fixed-bottom">
     <div class="d-flex justify-content-around">
         <a href="<?= base_url(); ?>/transaksi" class="nav-link active" data-page="transaksi"><i class="bi bi-arrow-down-up"></i><span>Transaksi</span></a>
         <a href="<?= base_url(); ?>/kategori" class="nav-link" data-page="kategori"><i class="bi bi-tags-fill"></i><span>Kategori</span></a>
         <a href="<?= base_url(); ?>/laporan" class="nav-link" data-page="laporan"><i class="bi bi-pie-chart-fill"></i><span>Laporan</span></a>
         <a href="<?= base_url(); ?>/profil" class="nav-link" data-page="profil"><i class="bi bi-person-circle"></i><span>Profil</span></a>
     </div>
 </nav>
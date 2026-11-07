<?php
$_current = basename($_SERVER['PHP_SELF']);
?>
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="light">
        <div class="sidebar-brand">
          <a href="home.php" class="brand-link">
            <span class="brand-text fw-light">CRUD Test</span>
          </a>
        </div>
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation">
              <li class="nav-item">
                <a href="home.php" class="nav-link <?php echo ($_current === 'home.php' || $_current === 'tambah.php' || $_current === 'edit.php') ? 'active' : ''; ?>">
                  <i class="nav-icon bi bi-people"></i>
                  <p>Karyawan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="divisi.php" class="nav-link <?php echo ($_current === 'divisi.php' || $_current === 'divisi_tambah.php' || $_current === 'divisi_edit.php') ? 'active' : ''; ?>">
                  <i class="nav-icon bi bi-diagram-3"></i>
                  <p>Divisi</p>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </aside>

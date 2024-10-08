<nav class="navbar bg-body-tertiary fixed-top">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu Dashboard</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link <?= (current_url() == site_url('/')) ? 'active' : ''; ?>" aria-current="page" href="<?= base_url('/') ?>">Summary</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= (current_url() == site_url('dashboard/stocks')) ? 'active' : ''; ?>" href="<?= base_url('dashboard/stocks') ?>">Stock</a>
          </li>
        </ul>
      </div>
    </div>
    <div class="mx-auto">
      <strong><span class="navbar-text ">Dashboard <?php echo session()->get('region') ?></span></strong>
    </div>

    <div class="justify-content-end">
      <span class="nav-item">Welcome <?php echo session()->get('username') ?></span>
      <a class="btn btn-danger" href="<?= base_url('/logout') ?>" role="button" aria-controls="offcanvasExample">Logout</a>
    </div>
    
  </div>
</nav>
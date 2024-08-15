<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="mx-auto order-0">
            <a class="navbar-brand mx-auto" href="#">Dashboard</a>
        </div>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="nav-link">Hello, <?= session()->get('username'); ?>!</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('logout'); ?>">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Offset Canvas Navbar
<nav class="navbar bg-body-tertiary fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Local Channel Portal Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="dashboard.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="dashboard.php?page=new-channel">New Channel - Done</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="dashboard.php?page=new-bill">New Bill</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="dashboard.php?page=gen-bill">Generate Bill - Done</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="dashboard.php?page=new-bill">Cancel Bill</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Action
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Active</a></li>
              <li><a class="dropdown-item" href="#">Due Report</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="dashboard.php?page=generate-bill">Generate Bill</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav> -->


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Local Channel Portal</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Billing
          </button>
          <ul class="dropdown-menu dropdown-menu-dark w-120">
          <li><a class="dropdown-item" href="dashboard.php?page=new-bill">New Bill</a></li>
          <li><a class="dropdown-item" href="dashboard.php?page=gen-bill">Generate Bill</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
        <a href="dashboard.php?page=new-bill"><button class="btn btn-dark">
            New Billing
          </button></a>
    </div>
    <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
        <a href="dashboard.php?page=new-channel"><button class="btn btn-dark">
            New Channel
          </button></a>
    </div>
    <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Report
          </button>
          <ul class="dropdown-menu dropdown-menu-dark w-120">
          <li><a class="dropdown-item" href="dashboard.php?page=rpt-channels">Channels List</a></li>
          <li><a class="dropdown-item" href="dashboard.php?page=rpt-loc-bills">LOC Bills</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>


<br/>
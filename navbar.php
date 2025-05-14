<nav class="navbar navbar-expand-lg navbar-dark bg-primary topbar fixed-top">
    <div class="container-fluid">
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
            aria-expanded="false" aria-controls="sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand fw-bold" href="#">QuizzBuzz</a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3 d-none d-md-block">Welcome, Student!</span>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                    id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-4"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                    <li>
                        <div class="dropdown-item d-flex align-items-center">
                            <i class="bi bi-moon-stars me-2"></i> Dark Mode
                            <div class="form-check form-switch ms-auto">
                                <input class="form-check-input" type="checkbox" id="darkModeSwitch" checked>
                            </div>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i> Sign
                            out</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
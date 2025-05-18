<nav class="navbar navbar-expand-lg bg-dark border-bottom mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">QuizzBuzz</a>
        <button class="bg-primary text-white navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="width: 40px; height: 40px;">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item mx-2">
                    <a class="nav-link d-flex align-items-center gap-2 <?php echo $currentPage === 'profile' ? 'active' : ''; ?>" href="/profile">
                        <i class="bi bi-person-circle"></i> Profile
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav gap-3">
                <li class="nav-item">
                    <button class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle"></i> Create Quiz
                    </button>
                </li>
                <li class="nav-item">
                    <button class="btn btn-outline-danger d-flex align-items-center justify-content-center" style="width: 40px; height: 38px;">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>
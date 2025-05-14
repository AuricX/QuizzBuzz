<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="UTF-8">
  <title>Student Dashboard - QuizzBuzz</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="./styles.css">
</head>

<body>
  <?php
  // Top Navigation
  include 'navbar.php';
  ?>

  <div class="container-fluid px-0">
    <div class="row g-0">
      <!-- Sidebar -->
      <nav id="sidebarMenu" class="col-lg-3 col-xl-2 d-lg-block sidebar collapse">
        <div class="position-sticky pt-3 h-100">
          <div class="d-flex flex-column h-100">
            <ul class="nav flex-column mb-auto">
              <li class="nav-item">
                <a class="nav-link active" href="#" aria-current="page">
                  <i class="bi bi-speedometer2"></i> Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-book"></i> My Courses
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-question-circle"></i> Available Quizzes
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-bar-chart"></i> My Results
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-person"></i> Profile
                </a>
              </li>
            </ul>

            <div class="stats-section mt-auto">
              <h6><i class="bi bi-graph-up me-2"></i>Quick Stats</h6>
              <p class="mb-1">Enrolled Courses: <strong>3</strong></p>
              <p class="mb-3">Avg. Progress: <strong>55%</strong></p>

              <h6><i class="bi bi-calendar-check me-2"></i>Upcoming Deadlines</h6>
              <div class="deadline-item">Quiz on Algebra – May 12</div>
              <div class="deadline-item">Essay due – May 15</div>
              <div class="deadline-item">Midterm Registration – May 20</div>
            </div>

            <div class="mt-4 px-2">
              <a href="#" class="btn btn-outline-light w-100">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
              </a>
            </div>
          </div>
        </div>
      </nav>

      <!-- Main Content -->
      <main class="col-lg-9 col-xl-10 main-content">
        <div class="container-fluid py-4change">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 fw-bold mb-0">My Enrolled Courses</h1>
            <button class="btn btn-primary">
              <i class="bi bi-plus-lg me-1"></i> Enroll New
            </button>
          </div>

          <div class="row g-4">
            <!-- Course Card #1 -->
            <div class="col-md-6 col-lg-4">
              <div class="card course-card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0">Math 101</h5>
                    <span class="badge bg-primary">Active</span>
                  </div>
                  <p class="card-text text-muted mb-3">
                    Basics of algebra, geometry, and trigonometry.
                  </p>
                  <div class="mt-auto">
                    <div class="d-flex justify-content-between small mb-1">
                      <span>Progress</span>
                      <span class="fw-bold">75%</span>
                    </div>
                    <div class="progress mb-3">
                      <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75"
                        aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-grid">
                      <a href="#" class="btn btn-outline-primary">View Course</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Course Card #2 -->
            <div class="col-md-6 col-lg-4">
              <div class="card course-card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0">English Literature</h5>
                    <span class="badge bg-primary">Active</span>
                  </div>
                  <p class="card-text text-muted mb-3">
                    Exploring classic and modern works.
                  </p>
                  <div class="mt-auto">
                    <div class="d-flex justify-content-between small mb-1">
                      <span>Progress</span>
                      <span class="fw-bold">40%</span>
                    </div>
                    <div class="progress mb-3">
                      <div class="progress-bar bg-success" role="progressbar" style="width: 40%" aria-valuenow="40"
                        aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-grid">
                      <a href="#" class="btn btn-outline-primary">View Course</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Course Card #3 -->
            <div class="col-md-6 col-lg-4">
              <div class="card course-card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0">Intro to Programming</h5>
                    <span class="badge bg-primary">Active</span>
                  </div>
                  <p class="card-text text-muted mb-3">
                    Fundamentals of JavaScript and web development.
                  </p>
                  <div class="mt-auto">
                    <div class="d-flex justify-content-between small mb-1">
                      <span>Progress</span>
                      <span class="fw-bold">50%</span>
                    </div>
                    <div class="progress mb-3">
                      <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50"
                        aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-grid">
                      <a href="#" class="btn btn-outline-primary">View Course</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Upcoming Quizzes Section -->
          <div class="mt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h2 class="h4 fw-bold mb-0">Upcoming Quizzes</h2>
              <a href="#" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>

            <div class="card">
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th>Quiz</th>
                        <th>Course</th>
                        <th>Date</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Algebra Basics</td>
                        <td>Math 101</td>
                        <td>May 12, 2023</td>
                        <td>30 mins</td>
                        <td><span class="badge bg-warning text-dark">Upcoming</span></td>
                        <td><a href="#" class="btn btn-sm btn-outline-primary">Prepare</a></td>
                      </tr>
                      <tr>
                        <td>Shakespeare Quiz</td>
                        <td>English Literature</td>
                        <td>May 18, 2023</td>
                        <td>45 mins</td>
                        <td><span class="badge bg-warning text-dark">Upcoming</span></td>
                        <td><a href="#" class="btn btn-sm btn-outline-primary">Prepare</a></td>
                      </tr>
                      <tr>
                        <td>JS Fundamentals</td>
                        <td>Intro to Programming</td>
                        <td>May 22, 2023</td>
                        <td>60 mins</td>
                        <td><span class="badge bg-warning text-dark">Upcoming</span></td>
                        <td><a href="#" class="btn btn-sm btn-outline-primary">Prepare</a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap 5 JS bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Improved sidebar toggle functionality
    document.addEventListener('DOMContentLoaded', function () {
      const sidebarToggler = document.querySelector('[data-bs-toggle="collapse"]');
      const sidebar = document.getElementById('sidebarMenu');

      // Close sidebar when clicking outside on mobile
      document.addEventListener('click', function (event) {
        if (window.innerWidth < 992) {
          const isClickInside = sidebar.contains(event.target) ||
            event.target === sidebarToggler;

          if (!isClickInside && sidebar.classList.contains('show')) {
            const bsCollapse = new bootstrap.Collapse(sidebar, {
              toggle: false
            });
            bsCollapse.hide();
          }
        }
      });

      // Theme switcher functionality
      const darkModeSwitch = document.getElementById('darkModeSwitch');

      // Function to update theme classes
      function updateThemeClasses(theme) {
        const navbar = document.querySelector('.navbar');
        const sidebar = document.getElementById('sidebarMenu');
        const body = document.body;
        
        if (theme === 'dark') {
          // Navbar theming
          navbar.classList.remove('navbar-light', 'bg-light');
          navbar.classList.add('navbar-dark', 'bg-primary');
          
          // Body background
          body.classList.add('bg-dark');
          body.classList.remove('bg-light');
          
          // Sidebar - add dark class
          sidebar.classList.add('sidebar-dark');
          sidebar.classList.remove('sidebar-light');
        } else {
          // Navbar theming
          navbar.classList.remove('navbar-dark', 'bg-primary');
          navbar.classList.add('navbar-light', 'bg-info');
          
          // Body background
          body.classList.remove('bg-dark');
          body.classList.add('bg-light');
          
          // Sidebar - add light class
          sidebar.classList.remove('sidebar-dark');
          sidebar.classList.add('sidebar-light');
        }
      }

      // Initialize switch based on current theme
      darkModeSwitch.checked = document.documentElement.getAttribute('data-bs-theme') === 'dark';

      // Handle theme switching
      darkModeSwitch.addEventListener('change', function () {
        const newTheme = this.checked ? 'dark' : 'light';
        document.documentElement.setAttribute('data-bs-theme', newTheme);
        
        // Update theme classes
        updateThemeClasses(newTheme);
        
        // Save preference to localStorage
        localStorage.setItem('preferred-theme', newTheme);
      });

      // Check for saved theme preference on load
      const savedTheme = localStorage.getItem('preferred-theme');
      if (savedTheme) {
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        darkModeSwitch.checked = savedTheme === 'dark';
        
        // Apply theme classes on initial load
        updateThemeClasses(savedTheme);
      } else {
        // Apply default theme classes based on data-bs-theme
        updateThemeClasses(document.documentElement.getAttribute('data-bs-theme'));
      }
    });
  </script>
</body>

</html>
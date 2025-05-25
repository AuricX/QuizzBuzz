<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle ?? 'QuizzBuzz'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/styles.css">
    
    <?php echo $additionalHead ?? ''; ?>
</head>
<body class="vh-100">
    <div class="d-flex vh-100">
        <!-- Sidebar -->
        <?php include_once __DIR__ . '/../components/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-grow-1 d-flex flex-column overflow-hidden" style="background-color:rgb(13, 14, 30);">
            <!-- Navbar -->
            <?php include_once __DIR__ . '/../components/navbar.php'; ?>
            
            <!-- Page Content -->
            <div class="container-fluid px-4 py-3 overflow-auto">
                <?php echo $content ?? ''; ?>
            </div>
        </div>
    </div>
    
    <!-- Dialog Container -->
    <div id="dialogContainer"></div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <!-- <script src="/assets/js/main.js"></script> -->
    <?php echo $additionalScripts ?? ''; ?>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="profileForm">
          <div class="mb-3">
            <label for="profileUsername" class="form-label">Username</label>
            <input type="text" class="form-control" id="profileUsername" name="username" value="<?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>" required>
          </div>
          <div class="mb-3">
            <label for="profileEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="profileEmail" name="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required>
          </div>
          <div class="mb-3">
            <label for="profilePassword" class="form-label">New Password</label>
            <input type="password" class="form-control" id="profilePassword" name="password" placeholder="Leave blank to keep current password">
          </div>
        </form>
        <div id="profileUpdateMsg" class="mt-2"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveProfileBtn">Save Changes</button>
      </div>
    </div>
  </div>
</div>
<script>
document.getElementById('saveProfileBtn').addEventListener('click', function() {
    const form = document.getElementById('profileForm');
    const formData = {
        username: form.username.value,
        email: form.email.value,
        password: form.password.value
    };
    // Determine endpoint based on role
    let endpoint = '/student/profile_update.php';
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'instructor'): ?>
        endpoint = '/instructor/profile_update.php';
    <?php endif; ?>
    fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(data => {
        const msg = document.getElementById('profileUpdateMsg');
        if (data.success) {
            msg.innerHTML = '<div class="alert alert-success">Profile updated successfully.</div>';
            setTimeout(() => window.location.reload(), 1200);
        } else {
            msg.innerHTML = '<div class="alert alert-danger">' + (data.message || 'Update failed.') + '</div>';
        }
    })
    .catch(() => {
        document.getElementById('profileUpdateMsg').innerHTML = '<div class="alert alert-danger">An error occurred.</div>';
    });
});
</script>
</body>
</html> 
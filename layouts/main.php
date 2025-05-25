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
</body>
</html> 
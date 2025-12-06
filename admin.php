..<?php
// admin.php
// Admin login + dashboard for managing reports

session_start();
require 'db.php';

$loginError = "";

// Handle login form submission
if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username !== '' && $password !== '') {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();

        if ($admin) {
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: admin.php");
            exit;
        } else {
            $loginError = "Invalid username or password.";
        }
    } else {
        $loginError = "Please enter both username and password.";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Handle actions (mark as real / fake) only if admin is logged in
if (isset($_SESSION['admin_id']) && isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $id     = (int) $_GET['id'];

    if ($action === 'real') {
        $stmt = $conn->prepare("UPDATE reports SET status = 'real' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'fake') {
        $stmt = $conn->prepare("UPDATE reports SET status = 'fake' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("UPDATE reports SET is_deleted = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin.php");
    exit;
}

// Fetch reports if admin is logged in
$reports       = [];
$currentFilter = $_GET['filter'] ?? 'all';

if ($currentFilter === 'all') {
    $result  = $conn->query("SELECT * FROM reports ORDER BY created_at DESC");
    $reports = $result->fetch_all(MYSQLI_ASSOC);
} elseif ($currentFilter === 'modified') {
    $stmt = $conn->prepare("SELECT * FROM reports WHERE last_modified IS NOT NULL AND is_deleted = 0 ORDER BY last_modified DESC");
    $stmt->execute();
    $result  = $stmt->get_result();
    $reports = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} elseif ($currentFilter === 'deleted') {
    $stmt = $conn->prepare("SELECT * FROM reports WHERE is_deleted = 1 ORDER BY created_at DESC");
    $stmt->execute();
    $result  = $stmt->get_result();
    $reports = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    // real أو fake
    $stmt = $conn->prepare("SELECT * FROM reports WHERE status = ? AND is_deleted = 0 ORDER BY created_at DESC");
    $stmt->bind_param("s", $currentFilter);
    $stmt->execute();
    $result  = $stmt->get_result();
    $reports = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Reports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS (CDN) -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    >

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    >

             <link rel="stylesheet" href="adminCSS.css">

</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-wrapper">
    <div class="glass-card">

        <?php if (!isset($_SESSION['admin_id'])): ?>
            <!-- LOGIN VIEW -->
            <div class="card-header-custom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="badge-tag">
                            <span class="badge-dot"></span>
                            <span>Admin access</span>
                        </div>
                        <h1 class="main-title">Admin Login</h1>
                        <p class="subtitle">
                            Sign in to review reports, filter fake entries, and confirm real lost &amp; found cases.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body-custom">
                <div class="login-wrapper">
                    <?php if ($loginError): ?>
                        <div class="alert alert-danger mb-3">
                            <?php echo htmlspecialchars($loginError); ?>
                        </div>
                    <?php endif; ?>

                    <h2 class="login-title">Welcome back</h2>
                    <p class="login-subtitle">
                        Use your administrator credentials to access the Lost &amp; Found dashboard.
                    </p>

                    <form method="POST" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Username</label>
                            <input
                              type="text"
                              name="username"
                              class="form-control"
                              placeholder="admin"
                              required
                            >
                        </div>
                        <div class="col-12">
                            <label class="form-label">Password</label>
                            <input
                              type="password"
                              name="password"
                              class="form-control"
                              placeholder="••••••"
                              required
                            >
                        </div>
                        <div class="col-12 mt-2">
                            <button type="submit" name="login" class="btn btn-primary w-100">
                                Sign in
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        <?php else: ?>
            <!-- DASHBOARD VIEW -->
            <div class="card-header-custom">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <div class="badge-tag">
                            <span class="badge-dot"></span>
                            <span>Incident dashboard</span>
                        </div>
                        <h1 class="main-title">Reports Management</h1>
                        <p class="subtitle">
                            Review all submitted lost &amp; found reports, confirm verified ones, and hide fake submissions.
                        </p>
                        <div class="filters">
                            <a href="admin.php?filter=all" class="filter-chip <?php echo $currentFilter === 'all' ? 'active' : ''; ?>">
                                All
                            </a>
                            <a href="admin.php?filter=pending" class="filter-chip <?php echo $currentFilter === 'pending' ? 'active' : ''; ?>">
                                Pending
                            </a>
                            <a href="admin.php?filter=real" class="filter-chip <?php echo $currentFilter === 'real' ? 'active' : ''; ?>">
                                Real
                            </a>
                            <a href="admin.php?filter=fake" class="filter-chip <?php echo $currentFilter === 'fake' ? 'active' : ''; ?>">
                                Fake
                            </a>
                            <!-- فلتر البلاغات المعدلة -->
    <a href="admin.php?filter=modified" class="filter-chip <?php echo $currentFilter === 'modified' ? 'active' : ''; ?>">Modified</a>

    <!-- فلتر البلاغات المحذوفة -->
    <a href="admin.php?filter=deleted" class="filter-chip <?php echo $currentFilter === 'deleted' ? 'active' : ''; ?>">Deleted</a>
</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted mb-1">
                            Signed in as <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
                        </div>
                        <a href="admin.php?logout=1" class="btn btn-logout">
                            Log out
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body-custom">
                <?php if (empty($reports)): ?>
                    <div class="empty-state">
                        <span>🕊</span>
                        <div>No reports found for this filter.</div>
                    </div>
                <?php else: ?>
                    <div class="report-grid">
                        <?php foreach ($reports as $report): ?>
                            <div class="report-card">
                                <?php if (!empty($report['image_path'])): ?>
                                    <img
                                      src="<?php echo htmlspecialchars($report['image_path']); ?>"
                                      alt="Report image"
                                      class="report-image"
                                    >
                                <?php endif; ?>

                                <div class="report-content">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h2 class="report-title">
                                            <?php echo htmlspecialchars($report['title']); ?>
                                        </h2>
                                </div>

                                    <div class="report-meta">
                                        <div>
                                            <strong>Reporter:</strong>
                                            <?php echo htmlspecialchars($report['citizen_name']); ?>
                                        </div>
                                        <div>
                                            <strong>Phone:</strong>
                                            <?php echo htmlspecialchars($report['phone']); ?>
                                        </div>
                                        <div>
                                            <strong>Location:</strong>
                                            <?php echo htmlspecialchars($report['location']); ?>
                                        </div>
                                        <div>
                                            <strong>Created at:</strong>
                                            <?php echo htmlspecialchars($report['created_at']); ?>
                                        </div>
                                    </div>

                                    <div class="report-description">
                                        <?php echo nl2br(htmlspecialchars($report['description'])); ?>
                                    </div>
                                </div>

                               <div class="card-footer-custom">
    <a
      href="admin.php?action=real&id=<?php echo $report['id']; ?>&filter=<?php echo urlencode($currentFilter); ?>"
      class="btn btn-sm btn-outline-success-custom btn-sm-action
      <?php echo ($report['status'] === 'real') ? 'active-btn' : ''; ?>"
    >
        Real
    </a>
    <a
      href="admin.php?action=fake&id=<?php echo $report['id']; ?>&filter=<?php echo urlencode($currentFilter); ?>"
      class="btn btn-sm btn-outline-danger-custom btn-sm-action
      <?php echo ($report['status'] === 'fake') ? 'active-btn' : ''; ?>"
    >
        Fake
    </a>
    <a
      href="edit_report.php?id=<?php echo $report['id']; ?>"
      class="btn btn-sm btn-outline-primary-custom btn-sm-action
      <?php echo (!empty($report['last_modified'])) ? 'active-btn' : ''; ?>"
    >
        Edit
    </a>
    <a
      href="admin.php?action=delete&id=<?php echo $report['id']; ?>"
      class="btn btn-sm btn-outline-secondary-custom btn-sm-action
      <?php echo (!empty($report['is_deleted']) && $report['is_deleted']==1) ? 'active-btn' : ''; ?>"
    >
        Delete
    </a>
</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>

    <br><br>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'footer.php'; ?>

</body>

</html>

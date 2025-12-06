<?php
require 'db.php';

$filter = $_GET['filter'] ?? 'all';
$reports = [];

// Fetch reports
$filter = $_GET['filter'] ?? 'all';
$reports = [];

// Fetch reports
if ($filter === 'all') {
    // فقط البلاغات الحقيقية وغير المحذوفة
    $result = $conn->query("
        SELECT * FROM reports
        WHERE status = 'real' AND is_deleted = 0
        ORDER BY created_at DESC
    ");
    $reports = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $stmt = $conn->prepare("
        SELECT * FROM reports
        WHERE report_type = ? AND status = 'real' AND is_deleted = 0
        ORDER BY created_at DESC
    ");
    $stmt->bind_param("s", $filter);
    $stmt->execute();
    $result = $stmt->get_result();
    $reports = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>View Reports</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

         <link rel="stylesheet" href="showCSS.css">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="page-wrapper">
    <div class="glass-card">

        <!-- Header -->
        <div class="card-header-custom">
            <h1>Reports</h1>

            <div class="filters">
                <a href="?filter=all"   class="filter-chip <?php echo $filter==='all'?'active':''; ?>">All</a>
                <a href="?filter=lost"  class="filter-chip <?php echo $filter==='lost'?'active':''; ?>">Lost</a>
                <a href="?filter=found" class="filter-chip <?php echo $filter==='found'?'active':''; ?>">Found</a>
            </div>
        </div>

        <!-- Reports -->
        <div class="card-body-custom">
            <?php if(empty($reports)): ?>
                <div class="no-reports">No reports found.</div>
            <?php else: ?>
                <div class="report-grid">
                    <?php foreach($reports as $report): ?>
                        <div class="report-card">

                            <?php if(!empty($report['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($report['image_path']); ?>"
                                     class="report-image"
                                     data-bs-toggle="modal"
                                     data-bs-target="#imageModal"
                                     data-src="<?php echo htmlspecialchars($report['image_path']); ?>">
                            <?php endif; ?>

                            <div class="report-title"><?php echo htmlspecialchars($report['title']); ?></div>

                            <div class="report-meta">
                                <div><strong>Reporter:</strong> <?php echo htmlspecialchars($report['citizen_name']); ?></div>
                                <div><strong>Phone:</strong> <?php echo htmlspecialchars($report['phone']); ?></div>
                                <div><strong>Location:</strong> <?php echo htmlspecialchars($report['location']); ?></div>
                                <div><strong>Created at:</strong> <?php echo htmlspecialchars($report['created_at']); ?></div>
                            </div>

                            <div class="report-description">
                                <?php echo nl2br(htmlspecialchars($report['description'])); ?>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img src="" id="modalImage" style="width:100%; border-radius:12px;">
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
var imageModal = document.getElementById('imageModal');
imageModal.addEventListener('show.bs.modal', function (event) {
  var img = event.relatedTarget;
  document.getElementById('modalImage').src = img.getAttribute('data-src');
});

</script>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

    <?php include 'footer.php'; ?>

</body>
</html>

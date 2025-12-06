<?php
require 'db.php';

// جلب آخر 5 بلاغات
$result = $conn->query("
    SELECT * FROM reports 
    WHERE status = 'real' 
      AND is_deleted = 0
    ORDER BY created_at DESC 
    LIMIT 5
");$latest_reports = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// إحصائيات بسيطة
$totalReports    = 0;
$totalLost       = 0;
$totalFound      = 0;
$totalVerified   = 0;

// إجمالي البلاغات
$resTotal = $conn->query("
    SELECT COUNT(*) AS c 
    FROM reports
");
if ($resTotal && $row = $resTotal->fetch_assoc()) {
    $totalReports = (int)$row['c'];
}

// إجمالي Lost
$resLost = $conn->query("
    SELECT COUNT(*) AS c 
    FROM reports
    WHERE report_type = 'lost' 
      AND status = 'real' 
      AND is_deleted = 0
");
if ($resLost && $row = $resLost->fetch_assoc()) {
    $totalLost = (int)$row['c'];
}

// إجمالي Found
$resFound = $conn->query("
    SELECT COUNT(*) AS c 
    FROM reports
    WHERE report_type = 'found' 
      AND status = 'real' 
      AND is_deleted = 0
");
if ($resFound && $row = $resFound->fetch_assoc()) {
    $totalFound = (int)$row['c'];
}

// إجمالي verified (real)
$resVerified = $conn->query("
    SELECT COUNT(*) AS c 
    FROM reports
    WHERE status = 'real' 
      AND is_deleted = 0
");
if ($resVerified && $row = $resVerified->fetch_assoc()) {
    $totalVerified = (int)$row['c'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lost & Found Management System - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="homeCSS.css">
</head>
<body>

    <!-- NAVBAR -->
    <?php include 'navbar.php'; ?>

    <div class="page-wrapper">
        <div class="glass-card">

            <!-- HERO + STATS -->
            <div class="hero-section row align-items-center g-4">
                <div class="col-md-7">
                    <h1 class="page-title">Lost &amp; Found – University of Tabuk</h1>
                    <p class="hero-subtitle">
                        A simple system that helps students and staff report lost and found items on campus. 
                        Every report is tracked by the administration so belongings can be returned to their owners safely.
                    </p>

                    <div class="hero-actions">
                        <a href="add_report.php" class="btn-main">+ Submit a Lost/Found Report</a>
                        <a href="show_report.php" class="btn-ghost">View all reports</a>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-label">Total reports</div>
                            <div class="stat-value"><?php echo $totalReports; ?></div>
                            <div class="stat-pill">All lost &amp; found items</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Lost items</div>
                            <div class="stat-value"><?php echo $totalLost; ?></div>
                            <div class="stat-pill">Submitted as “lost”</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Found items</div>
                            <div class="stat-value"><?php echo $totalFound; ?></div>
                            <div class="stat-pill">Submitted as “found”</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Verified by admin</div>
                            <div class="stat-value"><?php echo $totalVerified; ?></div>
                            <div class="stat-pill">Marked as real reports</div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="section-divider">

            <!-- LATEST REPORTS -->
            <p class="subtitle">Latest reports</p>

            <div class="latest-wrapper">
                <div class="row g-4">
                    <?php if (empty($latest_reports)): ?>
                        <div class="col-12 text-center no-reports">No reports found.</div>
                    <?php else: ?>
                        <?php foreach ($latest_reports as $r): ?>
                            <div class="col-md-4">
                                <div class="report-card">
                                    <?php if (!empty($r['image_path']) && file_exists($r['image_path'])): ?>
                                        <img src="<?php echo htmlspecialchars($r['image_path']); ?>" class="report-image"
                                             data-bs-toggle="modal" data-bs-target="#imgModal"
                                             data-src="<?php echo htmlspecialchars($r['image_path']); ?>">
                                    <?php endif; ?>

                                    <div class="report-title">
                                        <?php echo htmlspecialchars($r['title']); ?>
                                    </div>

                                    <div class="report-meta">
                                        <div><strong>Type:</strong>
                                            <?php echo ($r['report_type'] === 'lost') ? 'Lost' : 'Found'; ?>
                                        </div>
                                        <div><strong>Location:</strong>
                                            <?php echo htmlspecialchars($r['location']); ?>
                                        </div>
                                        <div><strong>Date:</strong>
                                            <?php echo htmlspecialchars($r['created_at']); ?>
                                        </div>
                                    </div>

                                    <div class="report-desc">
                                        <?php echo nl2br(htmlspecialchars($r['description'])); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <hr class="section-divider">

            <!-- HOW IT WORKS + TIPS -->
            <div class="info-section row g-3">
                <div class="col-md-7">
                    <p class="subtitle">How it works</p>
                    <div class="steps-row">
                        <div class="step-card">
                            <div class="step-number">1</div>
                            <div class="step-title">Submit a report</div>
                            <div>Use the form to describe the item, where and when it was lost or found, and attach a photo if possible.</div>
                        </div>
                        <div class="step-card">
                            <div class="step-number">2</div>
                            <div class="step-title">Admin review</div>
                            <div>The admin team reviews each report, checks the information, and marks it as pending, real, or fake.</div>
                        </div>
                        <div class="step-card">
                            <div class="step-number">3</div>
                            <div class="step-title">Match &amp; contact</div>
                            <div>When a lost item matches a found report, the administration contacts the student using the provided phone number.</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <p class="subtitle">Tips for better reports</p>
                    <div class="tips-card">
                        <ul>
                            <li>Use a clear title (e.g. “Lost blue notebook – Building C”).</li>
                            <li>Add details like color, brand, or any unique marks.</li>
                            <li>Write the exact building or area where it was lost/found.</li>
                            <li>Make sure your phone number is active and correct.</li>
                        </ul>

                        <div class="pill-links">
                            <a href="add_report.php" class="pill-link">Create a new report</a>
                            <a href="show_report.php" class="pill-link">Browse all reports</a>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="section-divider">

            <!-- FAQ + SUPPORT -->
            <div class="faq-section row g-3">
                <div class="col-md-7">
                    <div class="faq-title">FAQ – common questions</div>

                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq1Body"
                                        aria-expanded="false" aria-controls="faq1Body">
                                    Who can use the Lost &amp; Found system?
                                </button>
                            </h2>
                            <div id="faq1Body" class="accordion-collapse collapse" aria-labelledby="faq1"
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    The system is mainly designed for University of Tabuk students and staff
                                    to report items lost or found on campus. External visitors can also submit a report
                                    if the item is related to the university campus.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq2Body"
                                        aria-expanded="false" aria-controls="faq2Body">
                                    Are all reports visible to everyone?
                                </button>
                            </h2>
                            <div id="faq2Body" class="accordion-collapse collapse" aria-labelledby="faq2"
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Basic information about each report (title, location, date and description)
                                    is visible in the reports page. However, phone numbers are used only by the 
                                    administration to contact the owner or the person who found the item.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#faq3Body"
                                        aria-expanded="false" aria-controls="faq3Body">
                                    What should I do if I already recovered my item?
                                </button>
                            </h2>
                            <div id="faq3Body" class="accordion-collapse collapse" aria-labelledby="faq3"
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    If you recover your lost item, please contact the administration so they can 
                                    update or close your report. This helps keep the system clean and accurate
                                    for other students.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support / Contact -->
                <div class="col-md-5">
                    <div class="faq-title">Need more help?</div>
                    <div class="support-card">
                        <p>
                            For any questions about lost &amp; found procedures or to manually update a report,
                            you can contact the campus administration team.
                        </p>
                        <p class="support-meta mb-1">
                            <strong>Lost &amp; Found Office</strong><br>
                            Main Campus – Student Affairs Building
                        </p>
                        <p class="support-meta mb-2">
                            Working hours: Sunday–Thursday, 8:00 AM – 2:00 PM
                        </p>

                        <div class="support-links">
                            <a href="mailto:contact@lostfound.com" class="support-link">📧 Email support</a>
                            <a href="admin.php" class="support-link">🔐 Admin login</a>
                            <a href="show_report.php" class="support-link">🔍 Check existing reports</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <br><br>

    </div>

    <!-- Image modal -->
    <div class="modal fade" id="imgModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
          <img src="" id="modalImage" style="width:100%; border-radius:12px;">
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    var imgModal = document.getElementById('imgModal');
    imgModal.addEventListener('show.bs.modal', function (event) {
      var img = event.relatedTarget;
      var src = img.getAttribute('data-src');
      document.getElementById('modalImage').src = src;
    });
    </script>
            <?php include 'footer.php'; ?>

</body>

</html>

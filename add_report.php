<?php
// Add report page (Lost & Found)

require 'db.php';

$successMessage = "";
$errorMessage   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $citizen_name = trim($_POST['citizen_name'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');
    $title        = trim($_POST['title'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $location     = trim($_POST['location'] ?? '');
    $report_type  = trim($_POST['report_type'] ?? 'lost');
    $image_path   = null;

    if ($citizen_name === '' || $phone === '' || $title === '' || $description === '' || $location === '') {
        $errorMessage = "Please fill in all required fields.";
    } else {
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = 'uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName   = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $image_path = $targetFile;
            } else {
                $errorMessage = "Failed to upload the image.";
            }
        }

        if ($errorMessage === "") {
            $stmt = $conn->prepare("INSERT INTO reports (citizen_name, phone, title, description, location, image_path, report_type, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("sssssss", $citizen_name, $phone, $title, $description, $location, $image_path, $report_type);

            if ($stmt->execute()) {
                $successMessage = "Your report has been submitted successfully and is pending review.";
            } else {
                $errorMessage = "An error occurred while saving your report.";
            }

            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Lost &amp; Found Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    >

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    >

         <link rel="stylesheet" href="add_report_CSS.css">

</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-wrapper">
    <div class="glass-card">
        <!-- Header -->
        <div class="glass-card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <div class="badge-tag">
                        <span class="badge-dot"></span>
                        <span>Lost &amp; Found report</span>
                    </div>
                    <h1 class="main-title">Submit a Lost or Found Item</h1>
                    <p class="subtitle">
                        Help other students and the university recover lost items. Please provide clear details and a valid
                        phone number so the administration can contact you if needed.
                    </p>
                </div>
                <div class="pill-stats">
                    <div class="pill">
                        <strong>Items only</strong> • Campus lost &amp; found
                    </div>
                    <div class="pill">
                        <strong>Admin review</strong> • Status: pending → verified
                    </div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body-custom">
            <div class="row g-4">
                <!-- Left info panel -->
                <div class="col-md-4 info-panel">
                    <h2 class="info-title">Before you submit</h2>
                    <p class="info-text mb-3">
                        Use this form only for items lost or found on campus. Clear information helps the
                        administration match reports and return items to their owners.
                    </p>
                    <ul class="info-text mb-3">
                        <li>Use a short, clear title (e.g. “Lost student ID card”).</li>
                        <li>Describe the item, color, and any details or marks.</li>
                        <li>Mention the exact building, hall, or area.</li>
                        <li>Attach a photo of the item if possible.</li>
                    </ul>
                    <p class="info-text mb-0">
                        After submission, your report will appear as <strong>pending</strong> until it is reviewed
                        by the admin team.
                    </p>
                </div>

                <!-- Right form -->
                <div class="col-md-8">
                    <?php if ($successMessage): ?>
                        <div class="alert alert-success mb-3">
                            <?php echo htmlspecialchars($successMessage); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($errorMessage): ?>
                        <div class="alert alert-danger mb-3">
                            <?php echo htmlspecialchars($errorMessage); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                Full Name<span class="required-star">*</span>
                            </label>
                            <input type="text" name="citizen_name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Phone Number<span class="required-star">*</span>
                            </label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                Report Title<span class="required-star">*</span>
                            </label>
                            <input
                              type="text"
                              name="title"
                              class="form-control"
                              placeholder="Example: Lost black backpack in Building A"
                              required
                            >
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                Report Description<span class="required-star">*</span>
                            </label>
                            <textarea
                              name="description"
                              class="form-control"
                              rows="4"
                              placeholder="Describe the item, when it was lost/found, and any helpful details."
                              required
                            ></textarea>
                        </div>

       <div class="col-12">
    <label class="form-label">
        Report Type<span class="required-star">*</span>
    </label>
    <select name="report_type" class="form-control" required>
        <option value="" disabled selected>Select Lost or Found</option>
        <option value="lost" <?php echo (isset($report_type) && $report_type === 'lost') ? 'selected' : ''; ?>>Lost Item</option>
        <option value="found" <?php echo (isset($report_type) && $report_type === 'found') ? 'selected' : ''; ?>>Found Item</option>
    </select>
</div>

                        <div class="col-12">
                            <label class="form-label">
                                Location / Building<span class="required-star">*</span>
                            </label>
                            <input
                              type="text"
                              name="location"
                              class="form-control"
                              placeholder="Example: Main library entrance, Building B, Ground floor"
                              required
                            >
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                Attach Image (optional)
                            </label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <div class="form-text">
                                You can upload a photo of the lost/found item if available.
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary w-100">
                                Submit Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- /card-body-custom -->
    </div> <!-- /glass-card -->

    <br><br>
</div> <!-- /page-wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'footer.php'; ?>

</body>

</html>

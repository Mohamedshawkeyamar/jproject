<?php
// edit_report.php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    exit("Report ID not provided.");
}

$id = (int)$_GET['id'];

// Fetch existing report
$stmt = $conn->prepare("SELECT * FROM reports WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$report = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$report) exit("Report not found.");

$successMessage = "";
$errorMessage   = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $citizen_name = trim($_POST['citizen_name'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');
    $title        = trim($_POST['title'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $location     = trim($_POST['location'] ?? '');
    $report_type  = trim($_POST['report_type'] ?? 'lost');
    $image_path   = $report['image_path']; // keep old image if not replaced

    if ($citizen_name === '' || $phone === '' ||  $title === '' ||  $description === '' || $location === '') {
        $errorMessage = "Please fill in all required fields.";
    } else {
        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $image_path = $targetFile;
            } else {
                $errorMessage = "Failed to upload the image.";
            }
        }

        if ($errorMessage === "") {
            $stmt = $conn->prepare("UPDATE reports SET citizen_name=?, phone=?, title=?, description=?, location=?, report_type=?, image_path=?, last_modified=NOW() WHERE id=?");
            $stmt->bind_param("sssssssi", $citizen_name, $phone, $title, $description, $location, $report_type, $image_path, $id);

            if ($stmt->execute()) {
                $successMessage = "Report updated successfully.";
            } else {
                $errorMessage = "An error occurred while updating the report.";
            }

            $stmt->close();

            if ($errorMessage === "") {
                header("Location: admin.php?filter=modified");
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    >
    <link rel="stylesheet" href="add_report_CSS.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-wrapper">
    <div class="glass-card">
        <div class="glass-card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <div class="badge-tag">
                        <span class="badge-dot"></span>
                        <span>Edit Lost &amp; Found Report</span>
                    </div>
                    <h1 class="main-title">Edit Report</h1>
                    <p class="subtitle">
                        Update the report details. Changes will be visible after saving.
                    </p>
                </div>
            </div>
        </div>

        <div class="card-body-custom">
            <?php if ($successMessage): ?>
                <div class="alert alert-success mb-3">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

            <?php if ($errorMessage): ?>
                <div class="alert alert-danger mb-3">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?><form method="POST" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name<span class="required-star">*</span></label>
                    <input type="text" name="citizen_name" class="form-control" value="<?php echo htmlspecialchars($report['citizen_name']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phone Number<span class="required-star">*</span></label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($report['phone']); ?>" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Report Title<span class="required-star">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($report['title']); ?>" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Report Description<span class="required-star">*</span></label>
                    <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($report['description']); ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Report Type<span class="required-star">*</span></label>
                    <select name="report_type" class="form-control" required>
                        <option value="lost" <?php echo ($report['report_type']==='lost') ? 'selected' : ''; ?>>Lost Item</option>
                        <option value="found" <?php echo ($report['report_type']==='found') ? 'selected' : ''; ?>>Found Item</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Location / Building<span class="required-star">*</span></label>
                    <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($report['location']); ?>" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Attach Image (optional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <?php if (!empty($report['image_path'])): ?>
                        <img src="<?php echo htmlspecialchars($report['image_path']); ?>" style="max-width:150px; margin-top:8px;" alt="Current Image">
                    <?php endif; ?>
                    <div class="form-text">You can upload a new image to replace the existing one.</div>
                </div>

                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>

</body>
</html>



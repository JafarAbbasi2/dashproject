
<?php
session_start();

include("../config/db.php");

header("Content-Type: application/json");

if (
    !isset($_SESSION['id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'user'
) {
    echo json_encode([
        "success" => false,
        "message" => "You must be logged in as a user."
    ]);
    exit;
}

$userId = (int) $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

$contractId = filter_input(
    INPUT_POST,
    'contract_id',
    FILTER_VALIDATE_INT
);

$title = trim($_POST['submission_title'] ?? '');
$description = trim($_POST['submission_description'] ?? '');

if (!$contractId || $title === '' || $description === '') {
    echo json_encode([
        "success" => false,
        "message" => "Please provide a valid contract, title, and description."
    ]);
    exit;
}

$uploadDir = "../uploads/submissions/";
$workFile = null;

if (
    isset($_FILES['work_file']) &&
    $_FILES['work_file']['error'] !== UPLOAD_ERR_NO_FILE
) {
    $file = $_FILES['work_file'];

    // Check whether PHP received the file successfully.
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode([
            "success" => false,
            "message" => "The file upload failed."
        ]);
        exit;
    }

    // Limit the upload to 10 MB.
    if ($file['size'] > 10 * 1024 * 1024) {
        echo json_encode([
            "success" => false,
            "message" => "The file must be 10 MB or smaller."
        ]);
        exit;
    }

    // Allow only these file extensions.
    $allowedExtensions = ['pdf', 'zip', 'jpg', 'jpeg', 'png', 'txt', 'doc', 'docx'];

    $originalName = $file['name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions, true)) {
        echo json_encode([
            "success" => false,
            "message" => "This file type is not allowed."
        ]);
        exit;
    }

    // Check the actual file MIME type too.
    $mimeTypes = [
        'pdf'  => ['application/pdf'],
        'zip'  => ['application/zip', 'application/x-zip-compressed', 'application/octet-stream'],
        'jpg'  => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png'  => ['image/png'],
        'txt'  => ['text/plain'],
        'doc'  => ['application/msword', 'application/x-ole-storage'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document']
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (!in_array($mimeType, $mimeTypes[$extension], true)) {
        echo json_encode([
            "success" => false,
            "message" => "The file content does not match its extension."
        ]);
        exit;
    }

    // Generate a unique filename instead of trusting the original name.
    $newFileName = bin2hex(random_bytes(16)) . "." . $extension;

    if (!is_dir($uploadDir) &&
        !mkdir($uploadDir, 0755, true) &&
        !is_dir($uploadDir)
    ) {
        echo json_encode([
            "success" => false,
            "message" => "Could not create the upload directory."
        ]);
        exit;
    }

    if (!move_uploaded_file(
        $file['tmp_name'],
        $uploadDir . $newFileName
    )) {
        echo json_encode([
            "success" => false,
            "message" => "Could not save the uploaded file."
        ]);
        exit;
    }

    // Save a relative path in the database.
    $workFile = "uploads/submissions/" . $newFileName;
    }

    
$con->begin_transaction();

try {
    // 1. Save the submission.
    $sql = "INSERT INTO contract_submissions
            (contract_id, user_id, submission_title,
             submission_description, work_file)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);

    if (!$stmt) {
        throw new Exception("Could not prepare submission.");
    }

    $stmt->bind_param(
        "iisss",
        $contractId,
        $userId,
        $title,
        $description,
        $workFile
    );

    $stmt->execute();
    $stmt->close();


    // 2. Change the contract status to under_review.
    $updateSql = "UPDATE contracts
                  SET status = 'under_review'
                  WHERE id = ?
                    AND userID = ?
                    AND status = 'ongoing'";

    $updateStmt = $con->prepare($updateSql);

    if (!$updateStmt) {
        throw new Exception("Could not prepare contract update.");
    }

    $updateStmt->bind_param("ii", $contractId, $userId);
    $updateStmt->execute();

    if ($updateStmt->affected_rows !== 1) {
        $updateStmt->close();
        throw new Exception("The contract status could not be updated.");
    }

    $updateStmt->close();


    // 3. Record the activity using your existing table columns.
    $action = "work_submitted";
    $details = "Worker submitted work for admin review.";

    $activitySql = "INSERT INTO contract_activity
                    (contract_id, user_id, action, details)
                    VALUES (?, ?, ?, ?)";

    $activityStmt = $con->prepare($activitySql);

    if (!$activityStmt) {
        throw new Exception("Could not prepare activity record.");
    }

    $activityStmt->bind_param(
        "iiss",
        $contractId,
        $userId,
        $action,
        $details
    );

    $activityStmt->execute();
    $activityStmt->close();


    // All three database operations succeeded.
    $con->commit();

    echo json_encode([
        "success" => true,
        "message" => "Your work has been submitted successfully."
    ]);

} catch (Throwable $e) {
    $con->rollback();

    error_log("Submit work error: " . $e->getMessage());

    echo json_encode([
        "success" => false,
        "message" => "Could not submit your work. Please try again."
    ]);
}


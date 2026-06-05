<?php
// config.php - Configuration and Core Database Functions
ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('DB_FILE', __DIR__ . '/db.json');
define('UPLOAD_DIR', __DIR__ . '/files/');

// Initialize database if not exists
if (!file_exists(DB_FILE)) {
    $defaultData = [
        "biodata" => [
            "name" => "",
            "role" => "",
            "description" => "",
            "photo" => ""
        ],
        "experiences" => [],
        "certificates" => [],
        "portfolio" => [],
        "socmed" => [
            [
                "id" => uniqid(),
                "platform" => "Instagram",
                "url" => "",
                "icon" => "fab fa-instagram"
            ],
            [
                "id" => uniqid(),
                "platform" => "LinkedIn",
                "url" => "",
                "icon" => "fab fa-linkedin"
            ],
            [
                "id" => uniqid(),
                "platform" => "GitHub",
                "url" => "",
                "icon" => "fab fa-github"
            ]
        ],
        "settings" => [
            "password" => password_hash("password", PASSWORD_DEFAULT)
        ]
    ];
    
    file_put_contents(DB_FILE, json_encode($defaultData, JSON_PRETTY_PRINT));
}

// Ensure upload directory exists
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// DB helper functions
function get_db_data() {
    if (!file_exists(DB_FILE)) {
        return [];
    }
    $content = file_get_contents(DB_FILE);
    $data = json_decode($content, true) ?: [];
    if (!isset($data['portfolio'])) {
        $data['portfolio'] = [];
    }
    return $data;
}

function save_db_data($data) {
    return file_put_contents(DB_FILE, json_encode($data, JSON_PRETTY_PRINT));
}

// File upload helper
function upload_file($file, $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf']) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'No file uploaded or error occurred.'];
    }

    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTmpPath = $file['tmp_name'];
    
    $fileParts = explode('.', $fileName);
    $fileExtension = strtolower(end($fileParts));

    if (!in_array($fileExtension, $allowed_extensions)) {
        return ['success' => false, 'message' => 'Invalid file type. Only ' . implode(', ', $allowed_extensions) . ' are allowed.'];
    }

    // if ($fileSize > $max_size) {
    //     return ['success' => false, 'message' => 'File size exceeds 2MB limit.'];
    // }

    // Double validation on MIME type to be secure
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($fileTmpPath);

    $allowed_mimes = [
        'image/jpeg', 'image/png', 'image/jpg', 'application/pdf'
    ];
    if (!in_array($mime, $allowed_mimes)) {
        return ['success' => false, 'message' => 'Suspicious file content detected. Upload rejected.'];
    }

    $newFileName = uniqid() . '.' . $fileExtension;
    $destPath = UPLOAD_DIR . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        return ['success' => true, 'filename' => $newFileName];
    }

    return ['success' => false, 'message' => 'Failed to move uploaded file.'];
}

// Delete file helper
function delete_file($filename) {
    if (empty($filename)) return false;
    $filePath = UPLOAD_DIR . $filename;
    if (file_exists($filePath)) {
        return unlink($filePath);
    }
    return false;
}

// Authentication helpers
function check_login() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: index.php");
        exit;
    }
}
?>

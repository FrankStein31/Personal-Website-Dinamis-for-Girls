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
            "name" => "Heidy Stein",
            "role" => "Creative UI/UX Designer & Web Developer",
            "description" => "I am a passionate creator who blends aesthetics with code to build memorable digital experiences. Specializing in elegant frontend interfaces, high-end branding, and robust backend logic. Let's create something beautiful together.",
            "photo" => ""
        ],
        "experiences" => [
            [
                "id" => uniqid(),
                "company" => "Aura Creative Studio",
                "position" => "Lead Frontend Developer",
                "year" => "2024 - Present",
                "description" => "Crafting immersive, high-end web experiences, interactive portfolios, and digital brand identities using modern technologies."
            ],
            [
                "id" => uniqid(),
                "company" => "Innovatech Solutions",
                "position" => "Junior Web Developer",
                "year" => "2022 - 2024",
                "description" => "Developed responsive, dynamic web applications with PHP and clean database management system integrations."
            ]
        ],
        "certificates" => [
            [
                "id" => uniqid(),
                "name" => "Advanced UI/UX Certification",
                "issuer" => "Design Guild International",
                "file" => ""
            ],
            [
                "id" => uniqid(),
                "name" => "Web Application Security Associate",
                "issuer" => "TechAcademy",
                "file" => ""
            ]
        ],
        "socmed" => [
            [
                "id" => uniqid(),
                "platform" => "Instagram",
                "url" => "https://instagram.com/username",
                "icon" => "fab fa-instagram"
            ],
            [
                "id" => uniqid(),
                "platform" => "LinkedIn",
                "url" => "https://linkedin.com/in/username",
                "icon" => "fab fa-linkedin"
            ],
            [
                "id" => uniqid(),
                "platform" => "GitHub",
                "url" => "https://github.com/username",
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
    return json_decode($content, true) ?: [];
}

function save_db_data($data) {
    return file_put_contents(DB_FILE, json_encode($data, JSON_PRETTY_PRINT));
}

// File upload helper
function upload_file($file, $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'], $max_size = 2097152) {
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
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $fileTmpPath);
    if (is_resource($finfo)) {
        finfo_close($finfo);
    }

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

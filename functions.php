<?php
session_start();

define('DB_FILE', __DIR__ . '/db.json');
define('UPLOAD_DIR', __DIR__ . '/files/');

// Inisialisasi database jika belum ada
function initDB() {
    if (!file_exists(DB_FILE)) {
        $default_data = [
            'biodata' => [
                'name' => '',
                'role' => '',
                'description' => '',
                'photo' => ''
            ],
            'experiences' => [],
            'certificates' => [],
            'social_media' => [],
            'admin' => [
                'password' => 'password'
            ]
        ];
        writeDB($default_data);
    }
}

// Membaca data dari JSON
function readDB() {
    initDB();
    $json = file_get_contents(DB_FILE);
    return json_decode($json, true);
}

// Menulis data ke JSON
function writeDB($data) {
    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents(DB_FILE, $json);
}

// Fungsi untuk upload file dengan aman
function uploadFile($file, $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'], $max_size = 2097152) {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'message' => 'Parameter tidak valid.'];
    }

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            return ['success' => false, 'message' => 'Tidak ada file yang diunggah.'];
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return ['success' => false, 'message' => 'Ukuran file melebihi batas (Max 2MB).'];
        default:
            return ['success' => false, 'message' => 'Terjadi kesalahan tidak diketahui.'];
    }

    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar.'];
    }

    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_extensions)) {
        return ['success' => false, 'message' => 'Format file tidak diizinkan.'];
    }

    // Hindari path traversal dan generate nama unik
    $new_filename = uniqid() . '.' . $file_ext;
    $dest = UPLOAD_DIR . $new_filename;

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return ['success' => true, 'filename' => $new_filename];
    } else {
        return ['success' => false, 'message' => 'Gagal menyimpan file.'];
    }
}
?>

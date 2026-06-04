<?php
// biodata.php - Admin Biodata Editor
require_once __DIR__ . '/layout_header.php';

$biodata = isset($db_data['biodata']) ? $db_data['biodata'] : [];
$name = isset($biodata['name']) ? $biodata['name'] : '';
$role = isset($biodata['role']) ? $biodata['role'] : '';
$description = isset($biodata['description']) ? $biodata['description'] : '';
$photo = isset($biodata['photo']) ? $biodata['photo'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $description = trim($_POST['description']);
    
    $update_error = '';
    $uploaded_photo = $photo;

    // Check if new photo was uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_res = upload_file($_FILES['photo'], ['jpg', 'jpeg', 'png'], 2097152);
        
        if ($upload_res['success']) {
            // Delete previous photo if it exists
            if (!empty($photo)) {
                delete_file($photo);
            }
            $uploaded_photo = $upload_res['filename'];
        } else {
            $update_error = $upload_res['message'];
        }
    }

    if (empty($update_error)) {
        $db_data['biodata'] = [
            "name" => $name,
            "role" => $role,
            "description" => $description,
            "photo" => $uploaded_photo
        ];
        
        if (save_db_data($db_data)) {
            $_SESSION['success_msg'] = 'Biodata berhasil diperbarui!';
            header("Location: biodata.php");
            exit;
        } else {
            $_SESSION['error_msg'] = 'Gagal menyimpan data ke database.';
        }
    } else {
        $_SESSION['error_msg'] = $update_error;
    }
}
?>

<div class="form-card">
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>" placeholder="Masukkan nama lengkap Anda" required>
        </div>
        
        <div class="form-group">
            <label for="role">Jabatan / Role Profesional</label>
            <input type="text" id="role" name="role" class="form-control" value="<?= htmlspecialchars($role) ?>" placeholder="Contoh: Creative UI/UX Designer & Web Developer" required>
        </div>
        
        <div class="form-group">
            <label for="description">Deskripsi Diri</label>
            <textarea id="description" name="description" class="form-control" placeholder="Tuliskan deskripsi profesional diri Anda..." required><?= htmlspecialchars($description) ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="photo">Foto Profil Setengah Badan (Maks 2MB)</label>
            <input type="file" id="photo" name="photo" class="form-control" accept="image/png, image/jpeg, image/jpg">
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 6px;">Format yang didukung: JPG, JPEG, PNG. Direkomendasikan rasio potret / setengah badan.</p>
            
            <div class="photo-preview-container">
                <?php if (!empty($photo) && file_exists(__DIR__ . '/../files/' . $photo)): ?>
                    <div>
                        <p style="font-size: 0.8rem; font-weight: 600; margin-bottom: 8px; color: var(--dark);">Foto Saat Ini:</p>
                        <img src="../files/<?= htmlspecialchars($photo) ?>" alt="Profil Preview" class="photo-preview">
                    </div>
                <?php else: ?>
                    <div>
                        <p style="font-size: 0.8rem; font-weight: 600; margin-bottom: 8px; color: var(--dark);">Belum Ada Foto:</p>
                        <div class="no-photo">
                            <i class="fa-solid fa-image"></i>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div style="margin-top: 30px; border-top: 1px solid var(--border); padding-top: 20px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>

<?php
require_once __DIR__ . '/layout_footer.php';
?>

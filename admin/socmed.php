<?php
// socmed.php - Admin CRUD Social Media Links
require_once __DIR__ . '/layout_header.php';

$socmed = isset($db_data['socmed']) ? $db_data['socmed'] : [];

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$edit_id = isset($_GET['id']) ? $_GET['id'] : '';

// Handle Delete Action
if ($action === 'delete' && !empty($edit_id)) {
    $filtered_socmed = [];
    $found = false;
    foreach ($socmed as $s) {
        if ($s['id'] === $edit_id) {
            $found = true;
            continue; // Skip the item to delete
        }
        $filtered_socmed[] = $s;
    }
    
    if ($found) {
        $db_data['socmed'] = $filtered_socmed;
        if (save_db_data($db_data)) {
            $_SESSION['success_msg'] = 'Tautan media sosial berhasil dihapus!';
        } else {
            $_SESSION['error_msg'] = 'Gagal menyimpan perubahan ke database.';
        }
    } else {
        $_SESSION['error_msg'] = 'Data media sosial tidak ditemukan.';
    }
    header("Location: socmed.php");
    exit;
}

// Handle Add / Edit Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $platform = trim($_POST['platform']);
    $url = trim($_POST['url']);
    $icon = trim($_POST['icon']);
    
    if (empty($platform) || empty($url) || empty($icon)) {
        $_SESSION['error_msg'] = 'Semua field wajib diisi!';
    } else {
        if ($action === 'add') {
            $new_socmed = [
                'id' => uniqid(),
                'platform' => $platform,
                'url' => $url,
                'icon' => $icon
            ];
            $db_data['socmed'][] = $new_socmed;
            
            if (save_db_data($db_data)) {
                $_SESSION['success_msg'] = 'Media sosial baru berhasil ditambahkan!';
                header("Location: socmed.php");
                exit;
            } else {
                $_SESSION['error_msg'] = 'Gagal menyimpan data ke database.';
            }
        } elseif ($action === 'edit' && !empty($edit_id)) {
            $updated = false;
            foreach ($db_data['socmed'] as &$s) {
                if ($s['id'] === $edit_id) {
                    $s['platform'] = $platform;
                    $s['url'] = $url;
                    $s['icon'] = $icon;
                    $updated = true;
                    break;
                }
            }
            
            if ($updated) {
                if (save_db_data($db_data)) {
                    $_SESSION['success_msg'] = 'Tautan media sosial berhasil diperbarui!';
                    header("Location: socmed.php");
                    exit;
                } else {
                    $_SESSION['error_msg'] = 'Gagal menyimpan perubahan ke database.';
                }
            } else {
                $_SESSION['error_msg'] = 'Data media sosial tidak ditemukan.';
            }
        }
    }
}

// Load data for editing
$edit_s = null;
if ($action === 'edit' && !empty($edit_id)) {
    foreach ($socmed as $s) {
        if ($s['id'] === $edit_id) {
            $edit_s = $s;
            break;
        }
    }
    if (!$edit_s) {
        $_SESSION['error_msg'] = 'Data media sosial tidak ditemukan.';
        header("Location: socmed.php");
        exit;
    }
}
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <!-- Add or Edit Form -->
    <div style="margin-bottom: 25px;">
        <a href="socmed.php" class="file-link" style="font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="form-card">
        <h2 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--dark); margin-bottom: 25px;">
            <?= ($action === 'add') ? 'Tambah Media Sosial Baru' : 'Edit Media Sosial' ?>
        </h2>
        
        <form action="" method="POST" id="socmedForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="platformSelect">Platform Populer</label>
                    <select id="platformSelect" class="form-control" onchange="autoFillIcon()">
                        <option value="">-- Pilih Platform (Opsi Cepat) --</option>
                        <option value="Instagram" data-icon="fab fa-instagram">Instagram</option>
                        <option value="LinkedIn" data-icon="fab fa-linkedin">LinkedIn</option>
                        <option value="GitHub" data-icon="fab fa-github">GitHub</option>
                        <option value="Facebook" data-icon="fab fa-facebook">Facebook</option>
                        <option value="Twitter" data-icon="fab fa-twitter">Twitter / X</option>
                        <option value="Tiktok" data-icon="fab fa-tiktok">TikTok</option>
                        <option value="YouTube" data-icon="fab fa-youtube">YouTube</option>
                        <option value="Email" data-icon="fa-solid fa-envelope">Email</option>
                        <option value="WhatsApp" data-icon="fab fa-whatsapp">WhatsApp</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="platform">Nama Platform</label>
                    <input type="text" id="platform" name="platform" class="form-control" value="<?= htmlspecialchars($edit_s ? $edit_s['platform'] : '') ?>" placeholder="Contoh: Instagram" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="icon">Class Icon FontAwesome</label>
                    <input type="text" id="icon" name="icon" class="form-control" value="<?= htmlspecialchars($edit_s ? $edit_s['icon'] : '') ?>" placeholder="Contoh: fab fa-instagram" required>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 6px;">
                        Gunakan class dari <a href="https://fontawesome.com/icons" target="_blank" class="file-link">FontAwesome v6</a>
                    </p>
                </div>
                
                <div class="form-group">
                    <label for="url">URL Tautan Lengkap</label>
                    <input type="url" id="url" name="url" class="form-control" value="<?= htmlspecialchars($edit_s ? $edit_s['url'] : '') ?>" placeholder="Contoh: https://instagram.com/namauser" required>
                </div>
            </div>
            
            <div style="margin-top: 30px; border-top: 1px solid var(--border); padding-top: 20px; display: flex; justify-content: flex-end; gap: 12px;">
                <a href="socmed.php" class="btn-primary" style="background-color: transparent; border: 1.5px solid var(--primary); color: var(--primary); box-shadow: none; text-decoration: none; display: inline-flex; align-items: center;">
                    Batal
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Media Sosial</span>
                </button>
            </div>
        </form>
    </div>

    <script>
    function autoFillIcon() {
        const select = document.getElementById('platformSelect');
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption.value) {
            document.getElementById('platform').value = selectedOption.value;
            document.getElementById('icon').value = selectedOption.getAttribute('data-icon');
        }
    }
    </script>

<?php else: ?>
    <!-- List of Social Media Links -->
    <div style="display: flex; justify-content: flex-end;">
        <a href="socmed.php?action=add" class="btn-primary" style="text-decoration: none;">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Media Sosial</span>
        </a>
    </div>
    
    <div class="table-card">
        <div class="table-header">
            <h2>Daftar Media Sosial</h2>
        </div>
        
        <?php if (empty($socmed)): ?>
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                <i class="fa-solid fa-share-nodes" style="font-size: 3rem; color: var(--primary-light); margin-bottom: 15px; display: block;"></i>
                <p>Belum ada data media sosial. Klik tombol di atas untuk menambahkan.</p>
            </div>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Platform</th>
                        <th style="width: 20%; text-align: center;">Icon</th>
                        <th style="width: 40%;">URL</th>
                        <th style="width: 15%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($socmed as $s): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--dark);">
                                <?= htmlspecialchars($s['platform']) ?>
                            </td>
                            <td style="text-align: center; font-size: 1.2rem; color: var(--primary);">
                                <i class="<?= htmlspecialchars($s['icon']) ?>"></i>
                            </td>
                            <td>
                                <a href="<?= htmlspecialchars($s['url']) ?>" target="_blank" class="file-link">
                                    <?= htmlspecialchars($s['url']) ?>
                                </a>
                            </td>
                            <td>
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="socmed.php?action=edit&id=<?= $s['id'] ?>" class="btn-icon btn-edit" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="socmed.php?action=delete&id=<?= $s['id'] ?>" class="btn-icon btn-delete" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus media sosial <?= htmlspecialchars(addslashes($s['platform'])) ?>?');">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/layout_footer.php';
?>

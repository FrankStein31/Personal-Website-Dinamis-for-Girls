<?php
// pengalaman.php - Admin CRUD Experience
require_once __DIR__ . '/layout_header.php';

$experiences = isset($db_data['experiences']) ? $db_data['experiences'] : [];

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$edit_id = isset($_GET['id']) ? $_GET['id'] : '';

// Handle Delete Action
if ($action === 'delete' && !empty($edit_id)) {
    $filtered_experiences = [];
    $found = false;
    foreach ($experiences as $exp) {
        if ($exp['id'] === $edit_id) {
            $found = true;
            continue; // Skip the item to delete
        }
        $filtered_experiences[] = $exp;
    }
    
    if ($found) {
        $db_data['experiences'] = $filtered_experiences;
        if (save_db_data($db_data)) {
            $_SESSION['success_msg'] = 'Pengalaman kerja berhasil dihapus!';
        } else {
            $_SESSION['error_msg'] = 'Gagal menyimpan perubahan ke database.';
        }
    } else {
        $_SESSION['error_msg'] = 'Data pengalaman tidak ditemukan.';
    }
    header("Location: pengalaman.php");
    exit;
}

// Handle Add / Edit Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company = trim($_POST['company']);
    $position = trim($_POST['position']);
    $year = trim($_POST['year']);
    $description = trim($_POST['description']);
    
    if (empty($company) || empty($position) || empty($year)) {
        $_SESSION['error_msg'] = 'Perusahaan, Jabatan, dan Tahun wajib diisi!';
    } else {
        if ($action === 'add') {
            // Create new experience
            $new_exp = [
                'id' => uniqid(),
                'company' => $company,
                'position' => $position,
                'year' => $year,
                'description' => $description
            ];
            $db_data['experiences'][] = $new_exp;
            
            if (save_db_data($db_data)) {
                $_SESSION['success_msg'] = 'Pengalaman kerja baru berhasil ditambahkan!';
                header("Location: pengalaman.php");
                exit;
            } else {
                $_SESSION['error_msg'] = 'Gagal menyimpan data ke database.';
            }
        } elseif ($action === 'edit' && !empty($edit_id)) {
            // Edit existing experience
            $updated = false;
            foreach ($db_data['experiences'] as &$exp) {
                if ($exp['id'] === $edit_id) {
                    $exp['company'] = $company;
                    $exp['position'] = $position;
                    $exp['year'] = $year;
                    $exp['description'] = $description;
                    $updated = true;
                    break;
                }
            }
            
            if ($updated) {
                if (save_db_data($db_data)) {
                    $_SESSION['success_msg'] = 'Pengalaman kerja berhasil diperbarui!';
                    header("Location: pengalaman.php");
                    exit;
                } else {
                    $_SESSION['error_msg'] = 'Gagal menyimpan perubahan ke database.';
                }
            } else {
                $_SESSION['error_msg'] = 'Data pengalaman tidak ditemukan.';
            }
        }
    }
}

// Load data for editing
$edit_exp = null;
if ($action === 'edit' && !empty($edit_id)) {
    foreach ($experiences as $exp) {
        if ($exp['id'] === $edit_id) {
            $edit_exp = $exp;
            break;
        }
    }
    if (!$edit_exp) {
        $_SESSION['error_msg'] = 'Data pengalaman tidak ditemukan.';
        header("Location: pengalaman.php");
        exit;
    }
}
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <!-- Add or Edit Form -->
    <div style="margin-bottom: 25px;">
        <a href="pengalaman.php" class="file-link" style="font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="form-card">
        <h2 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--dark); margin-bottom: 25px;">
            <?= ($action === 'add') ? 'Tambah Pengalaman Baru' : 'Edit Pengalaman Kerja' ?>
        </h2>
        
        <form action="" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="company">Nama Perusahaan / Organisasi</label>
                    <input type="text" id="company" name="company" class="form-control" value="<?= htmlspecialchars($edit_exp ? $edit_exp['company'] : '') ?>" placeholder="Contoh: PT. Digital Nusantara" required>
                </div>
                
                <div class="form-group">
                    <label for="position">Jabatan / Posisi</label>
                    <input type="text" id="position" name="position" class="form-control" value="<?= htmlspecialchars($edit_exp ? $edit_exp['position'] : '') ?>" placeholder="Contoh: Frontend Developer" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="year">Periode / Tahun</label>
                <input type="text" id="year" name="year" class="form-control" value="<?= htmlspecialchars($edit_exp ? $edit_exp['year'] : '') ?>" placeholder="Contoh: 2022 - 2024 atau 2023 (6 Bulan)" required>
            </div>
            
            <div class="form-group">
                <label for="description">Deskripsi Tugas & Pencapaian (Opsional)</label>
                <textarea id="description" name="description" class="form-control" placeholder="Jelaskan peran, tanggung jawab, dan pencapaian Anda..."><?= htmlspecialchars($edit_exp ? $edit_exp['description'] : '') ?></textarea>
            </div>
            
            <div style="margin-top: 30px; border-top: 1px solid var(--border); padding-top: 20px; display: flex; justify-content: flex-end; gap: 12px;">
                <a href="pengalaman.php" class="btn-primary" style="background-color: transparent; border: 1.5px solid var(--primary); color: var(--primary); box-shadow: none; text-decoration: none; display: inline-flex; align-items: center;">
                    Batal
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Data</span>
                </button>
            </div>
        </form>
    </div>

<?php else: ?>
    <!-- List of Experiences -->
    <div style="display: flex; justify-content: flex-end;">
        <a href="pengalaman.php?action=add" class="btn-primary" style="text-decoration: none;">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Pengalaman</span>
        </a>
    </div>
    
    <div class="table-card">
        <div class="table-header">
            <h2>Daftar Riwayat Kerja</h2>
        </div>
        
        <?php if (empty($experiences)): ?>
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                <i class="fa-solid fa-folder-open" style="font-size: 3rem; color: var(--primary-light); margin-bottom: 15px; display: block;"></i>
                <p>Belum ada data pengalaman kerja. Klik tombol di atas untuk menambahkan.</p>
            </div>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Perusahaan</th>
                        <th style="width: 25%;">Posisi</th>
                        <th style="width: 15%;">Tahun</th>
                        <th style="width: 20%;">Deskripsi</th>
                        <th style="width: 15%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($experiences as $exp): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--dark);">
                                <?= htmlspecialchars($exp['company']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($exp['position']) ?>
                            </td>
                            <td>
                                <span style="background-color: var(--primary-light); color: var(--primary); padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">
                                    <?= htmlspecialchars($exp['year']) ?>
                                </span>
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-muted); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?= htmlspecialchars($exp['description']) ?>
                            </td>
                            <td>
                                <div class="action-buttons" style="justify-content: center;">
                                    <a href="pengalaman.php?action=edit&id=<?= $exp['id'] ?>" class="btn-icon btn-edit" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="pengalaman.php?action=delete&id=<?= $exp['id'] ?>" class="btn-icon btn-delete" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus pengalaman di <?= htmlspecialchars(addslashes($exp['company'])) ?>?');">
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

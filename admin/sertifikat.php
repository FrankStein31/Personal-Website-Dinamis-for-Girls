<?php
// sertifikat.php - Admin CRUD Certificates
require_once __DIR__ . '/layout_header.php';

$certificates = isset($db_data['certificates']) ? $db_data['certificates'] : [];

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$edit_id = isset($_GET['id']) ? $_GET['id'] : '';

// Handle Delete Action
if ($action === 'delete' && !empty($edit_id)) {
    $filtered_certs = [];
    $found = false;
    $file_to_delete = '';
    
    foreach ($certificates as $cert) {
        if ($cert['id'] === $edit_id) {
            $found = true;
            $file_to_delete = isset($cert['file']) ? $cert['file'] : '';
            continue; // Skip the item to delete
        }
        $filtered_certs[] = $cert;
    }
    
    if ($found) {
        $db_data['certificates'] = $filtered_certs;
        if (save_db_data($db_data)) {
            if (!empty($file_to_delete)) {
                delete_file($file_to_delete);
            }
            $_SESSION['success_msg'] = 'Certificate deleted successfully!';
        } else {
            $_SESSION['error_msg'] = 'Failed to save changes to database.';
        }
    } else {
        $_SESSION['error_msg'] = 'Certificate not found.';
    }
    header("Location: sertifikat.php");
    exit;
}

// Handle Add / Edit Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $issuer = trim($_POST['issuer']);
    
    if (empty($name) || empty($issuer)) {
        $_SESSION['error_msg'] = 'Certificate Name and Issuer are required fields!';
    } else {
        $upload_error = '';
        $uploaded_file = '';
        
        // Find existing certificate if editing
        $existing_cert = null;
        if ($action === 'edit' && !empty($edit_id)) {
            foreach ($certificates as $c) {
                if ($c['id'] === $edit_id) {
                    $existing_cert = $c;
                    $uploaded_file = isset($c['file']) ? $c['file'] : '';
                    break;
                }
            }
        }
        
        // Check file upload
        $file_uploaded = isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE;
        
        if ($action === 'add' && !$file_uploaded) {
            $upload_error = 'Certificate file/document upload is required!';
        } elseif ($file_uploaded) {
            // Upload new file (allows jpg, jpeg, png, pdf) - removed 2MB size limit parameter
            $upload_res = upload_file($_FILES['file'], ['jpg', 'jpeg', 'png', 'pdf']);
            
            if ($upload_res['success']) {
                // Delete previous file if editing and new file uploaded successfully
                if ($action === 'edit' && !empty($uploaded_file)) {
                    delete_file($uploaded_file);
                }
                $uploaded_file = $upload_res['filename'];
            } else {
                $upload_error = $upload_res['message'];
            }
        }
        
        if (empty($upload_error)) {
            if ($action === 'add') {
                $new_cert = [
                    'id' => uniqid(),
                    'name' => $name,
                    'issuer' => $issuer,
                    'file' => $uploaded_file
                ];
                $db_data['certificates'][] = $new_cert;
                
                if (save_db_data($db_data)) {
                    $_SESSION['success_msg'] = 'New certificate added successfully!';
                    header("Location: sertifikat.php");
                    exit;
                } else {
                    $_SESSION['error_msg'] = 'Failed to save data to database.';
                }
            } elseif ($action === 'edit' && !empty($edit_id)) {
                $updated = false;
                foreach ($db_data['certificates'] as &$c) {
                    if ($c['id'] === $edit_id) {
                        $c['name'] = $name;
                        $c['issuer'] = $issuer;
                        $c['file'] = $uploaded_file;
                        $updated = true;
                        break;
                    }
                }
                
                if ($updated) {
                    if (save_db_data($db_data)) {
                        $_SESSION['success_msg'] = 'Certificate updated successfully!';
                        header("Location: sertifikat.php");
                        exit;
                    } else {
                        $_SESSION['error_msg'] = 'Failed to save changes to database.';
                    }
                } else {
                    $_SESSION['error_msg'] = 'Certificate not found.';
                }
            }
        } else {
            $_SESSION['error_msg'] = $upload_error;
        }
    }
}

// Load data for editing
$edit_cert = null;
if ($action === 'edit' && !empty($edit_id)) {
    foreach ($certificates as $cert) {
        if ($cert['id'] === $edit_id) {
            $edit_cert = $cert;
            break;
        }
    }
    if (!$edit_cert) {
        $_SESSION['error_msg'] = 'Certificate not found.';
        header("Location: sertifikat.php");
        exit;
    }
}
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <!-- Add or Edit Form -->
    <div style="margin-bottom: 25px;">
        <a href="sertifikat.php" class="file-link" style="font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="form-card">
        <h2 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--dark); margin-bottom: 25px;">
            <?= ($action === 'add') ? 'Add New Certificate' : 'Edit Certificate' ?>
        </h2>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Certificate Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($edit_cert ? $edit_cert['name'] : '') ?>" placeholder="e.g. Advanced UI/UX Certification" required>
            </div>
            
            <div class="form-group">
                <label for="issuer">Issuing Organization / Institution</label>
                <input type="text" id="issuer" name="issuer" class="form-control" value="<?= htmlspecialchars($edit_cert ? $edit_cert['issuer'] : '') ?>" placeholder="e.g. Design Guild International" required>
            </div>
            
            <div class="form-group">
                <label for="file">Certificate Document / Image</label>
                <input type="file" id="file" name="file" class="form-control" accept="image/png, image/jpeg, image/jpg, application/pdf" <?= ($action === 'add') ? 'required' : '' ?>>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 6px;">Supported formats: JPG, JPEG, PNG, PDF. No upload size restriction.</p>
                
                <?php if ($edit_cert && !empty($edit_cert['file'])): ?>
                    <div style="margin-top: 15px; background-color: var(--light-bg); padding: 12px; border-radius: 8px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 0.85rem; color: var(--dark);">
                            <i class="fa-solid <?= (pathinfo($edit_cert['file'], PATHINFO_EXTENSION) === 'pdf') ? 'fa-file-pdf' : 'fa-file-image' ?>" style="color: var(--primary); margin-right: 6px;"></i>
                            Current file: <strong><?= htmlspecialchars($edit_cert['file']) ?></strong>
                        </span>
                        <button type="button" class="btn-primary" style="padding: 6px 12px; font-size: 0.8rem; box-shadow: none;" onclick="previewFile('../files/<?= htmlspecialchars($edit_cert['file']) ?>')">
                            View File
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 30px; border-top: 1px solid var(--border); padding-top: 20px; display: flex; justify-content: flex-end; gap: 12px;">
                <a href="sertifikat.php" class="btn-primary" style="background-color: transparent; border: 1.5px solid var(--primary); color: var(--primary); box-shadow: none; text-decoration: none; display: inline-flex; align-items: center;">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Certificate</span>
                </button>
            </div>
        </form>
    </div>

<?php else: ?>
    <!-- List of Certificates -->
    <div style="display: flex; justify-content: flex-end;">
        <a href="sertifikat.php?action=add" class="btn-primary" style="text-decoration: none;">
            <i class="fa-solid fa-plus"></i>
            <span>Add Certificate</span>
        </a>
    </div>
    
    <div class="table-card">
        <div class="table-header">
            <h2>Certificates & Credentials List</h2>
        </div>
        
        <?php if (empty($certificates)): ?>
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                <i class="fa-solid fa-award" style="font-size: 3rem; color: var(--primary-light); margin-bottom: 15px; display: block;"></i>
                <p>No certificates recorded yet. Click the button above to add one.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 35%;">Certificate Title</th>
                            <th style="width: 30%;">Issuer</th>
                            <th style="width: 20%;">Document</th>
                            <th style="width: 15%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($certificates as $cert): ?>
                            <tr>
                                <td style="font-weight: 600; color: var(--dark);">
                                    <?= htmlspecialchars($cert['name']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($cert['issuer']) ?>
                                </td>
                                <td>
                                    <?php if (!empty($cert['file']) && file_exists(__DIR__ . '/../files/' . $cert['file'])): ?>
                                        <?php 
                                            $ext = strtolower(pathinfo($cert['file'], PATHINFO_EXTENSION));
                                            $isPdf = ($ext === 'pdf');
                                        ?>
                                        <button type="button" class="file-link" style="background: none; border: none; cursor: pointer; font-family: inherit; font-size: inherit;" onclick="previewFile('../files/<?= htmlspecialchars($cert['file']) ?>')">
                                            <i class="fa-solid <?= $isPdf ? 'fa-file-pdf' : 'fa-file-image' ?>"></i>
                                            <span>Preview</span>
                                        </button>
                                    <?php else: ?>
                                        <span style="color: var(--danger); font-size: 0.85rem;"><i class="fa-solid fa-triangle-exclamation"></i> File missing</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons" style="justify-content: center;">
                                        <a href="sertifikat.php?action=edit&id=<?= $cert['id'] ?>" class="btn-icon btn-edit" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="sertifikat.php?action=delete&id=<?= $cert['id'] ?>" class="btn-icon btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete certificate: <?= htmlspecialchars(addslashes($cert['name'])) ?>?');">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<script>
function previewFile(fileUrl) {
    const ext = fileUrl.split('.').pop().toLowerCase();
    let content = '';
    
    if (ext === 'pdf') {
        content = `<iframe src="${fileUrl}" width="100%" height="500px" style="border: none; border-radius: var(--radius-md);"></iframe>`;
    } else if (['jpg', 'jpeg', 'png'].includes(ext)) {
        content = `<img src="${fileUrl}" alt="Certificate Preview" style="width: 100%; height: auto; max-height: 80vh; object-fit: contain; border-radius: var(--radius-md);">`;
    } else {
        content = `<p style="padding: 20px; text-align: center; color: var(--danger);">File format not supported for direct preview.</p>`;
    }
    
    showAdminModal(content);
}
</script>

<?php
require_once __DIR__ . '/layout_footer.php';
?>

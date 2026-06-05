<?php
// portfolio.php - Admin CRUD Projects Portfolio
require_once __DIR__ . '/layout_header.php';

$projects = isset($db_data['portfolio']) ? $db_data['portfolio'] : [];

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$edit_id = isset($_GET['id']) ? $_GET['id'] : '';

// Handle Delete Action
if ($action === 'delete' && !empty($edit_id)) {
    $filtered_projects = [];
    $found = false;
    $file_to_delete = '';
    
    foreach ($projects as $proj) {
        if ($proj['id'] === $edit_id) {
            $found = true;
            $file_to_delete = isset($proj['file']) ? $proj['file'] : '';
            continue; // Skip the item to delete
        }
        $filtered_projects[] = $proj;
    }
    
    if ($found) {
        $db_data['portfolio'] = $filtered_projects;
        if (save_db_data($db_data)) {
            if (!empty($file_to_delete)) {
                delete_file($file_to_delete);
            }
            $_SESSION['success_msg'] = 'Project deleted successfully!';
        } else {
            $_SESSION['error_msg'] = 'Failed to save changes to the database.';
        }
    } else {
        $_SESSION['error_msg'] = 'Project not found.';
    }
    header("Location: portfolio.php");
    exit;
}

// Handle Add / Edit Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $link = trim($_POST['link']);
    
    if (empty($title)) {
        $_SESSION['error_msg'] = 'Project title is required!';
    } else {
        $upload_error = '';
        $uploaded_file = '';
        
        // Find existing project if editing
        $existing_proj = null;
        if ($action === 'edit' && !empty($edit_id)) {
            foreach ($projects as $p) {
                if ($p['id'] === $edit_id) {
                    $existing_proj = $p;
                    $uploaded_file = isset($p['file']) ? $p['file'] : '';
                    break;
                }
            }
        }
        
        // Check file upload
        $file_uploaded = isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE;
        
        if ($file_uploaded) {
            // Upload new file (allows jpg, jpeg, png, pdf)
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
                $new_proj = [
                    'id' => uniqid(),
                    'title' => $title,
                    'description' => $description,
                    'link' => $link,
                    'file' => $uploaded_file
                ];
                $db_data['portfolio'][] = $new_proj;
                
                if (save_db_data($db_data)) {
                    $_SESSION['success_msg'] = 'New project added successfully!';
                    header("Location: portfolio.php");
                    exit;
                } else {
                    $_SESSION['error_msg'] = 'Failed to save data to the database.';
                }
            } elseif ($action === 'edit' && !empty($edit_id)) {
                $updated = false;
                foreach ($db_data['portfolio'] as &$proj) {
                    if ($proj['id'] === $edit_id) {
                        $proj['title'] = $title;
                        $proj['description'] = $description;
                        $proj['link'] = $link;
                        $proj['file'] = $uploaded_file;
                        $updated = true;
                        break;
                    }
                }
                
                if ($updated) {
                    if (save_db_data($db_data)) {
                        $_SESSION['success_msg'] = 'Project updated successfully!';
                        header("Location: portfolio.php");
                        exit;
                    } else {
                        $_SESSION['error_msg'] = 'Failed to save changes to the database.';
                    }
                } else {
                    $_SESSION['error_msg'] = 'Project not found.';
                }
            }
        } else {
            $_SESSION['error_msg'] = $upload_error;
        }
    }
}

// Load data for editing
$edit_proj = null;
if ($action === 'edit' && !empty($edit_id)) {
    foreach ($projects as $p) {
        if ($p['id'] === $edit_id) {
            $edit_proj = $p;
            break;
        }
    }
    if (!$edit_proj) {
        $_SESSION['error_msg'] = 'Project not found.';
        header("Location: portfolio.php");
        exit;
    }
}
?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <!-- Add or Edit Form -->
    <div style="margin-bottom: 25px;">
        <a href="portfolio.php" class="file-link" style="font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="form-card">
        <h2 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--dark); margin-bottom: 25px;">
            <?= ($action === 'add') ? 'Add New Project' : 'Edit Project' ?>
        </h2>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Project Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($edit_proj ? $edit_proj['title'] : '') ?>" placeholder="Enter project title" required>
            </div>
            
            <div class="form-group">
                <label for="description">Project Description</label>
                <textarea id="description" name="description" class="form-control" placeholder="Describe the project details, technologies used, or your contribution..." style="min-height: 120px;"><?= htmlspecialchars($edit_proj ? $edit_proj['description'] : '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="link">Project URL / Link</label>
                <input type="url" id="link" name="link" class="form-control" value="<?= htmlspecialchars($edit_proj ? $edit_proj['link'] : '') ?>" placeholder="e.g. https://github.com/... or https://myproject.com">
            </div>
            
            <div class="form-group">
                <label for="file">Project Document / Image</label>
                <input type="file" id="file" name="file" class="form-control" accept="image/png, image/jpeg, image/jpg, application/pdf">
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 6px;">Supported formats: JPG, JPEG, PNG, PDF. Leave blank to keep current file if editing.</p>
                
                <?php if ($edit_proj && !empty($edit_proj['file'])): ?>
                    <div class="photo-preview-container">
                        <div>
                            <p style="font-size: 0.8rem; font-weight: 600; margin-bottom: 8px; color: var(--dark);">Current Attachment:</p>
                            <?php 
                            $ext = strtolower(pathinfo($edit_proj['file'], PATHINFO_EXTENSION));
                            if (in_array($ext, ['jpg', 'jpeg', 'png'])):
                            ?>
                                <img src="../files/<?= htmlspecialchars($edit_proj['file']) ?>" alt="Preview" class="photo-preview">
                            <?php else: ?>
                                <div class="no-photo" style="font-size: 1rem; border-style: solid; height: auto; padding: 15px;">
                                    <i class="fa-solid fa-file-pdf" style="font-size: 1.5rem; margin-bottom: 5px;"></i>
                                    <span><?= htmlspecialchars($edit_proj['file']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div style="margin-top: 30px; border-top: 1px solid var(--border); padding-top: 20px; display: flex; justify-content: flex-end; gap: 12px;">
                <a href="portfolio.php" class="btn-primary" style="background-color: transparent; border: 1.5px solid var(--primary); color: var(--primary); box-shadow: none; text-decoration: none; display: inline-flex; align-items: center;">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Project</span>
                </button>
            </div>
        </form>
    </div>

<?php else: ?>
    <!-- List of Projects -->
    <div style="display: flex; justify-content: flex-end;">
        <a href="portfolio.php?action=add" class="btn-primary" style="text-decoration: none;">
            <i class="fa-solid fa-plus"></i>
            <span>Add Project</span>
        </a>
    </div>
    
    <div class="table-card">
        <div class="table-header">
            <h2>Project List</h2>
        </div>
        
        <?php if (empty($projects)): ?>
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                <i class="fa-solid fa-gem" style="font-size: 3rem; color: var(--primary-light); margin-bottom: 15px; display: block;"></i>
                <p>No projects uploaded yet. Click the button above to add your first project.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Project Title</th>
                            <th style="width: 35%;">Description</th>
                            <th style="width: 15%;">Attachment</th>
                            <th style="width: 15%;">Link</th>
                            <th style="width: 10%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $p): ?>
                            <tr>
                                <td style="font-weight: 600; color: var(--dark);">
                                    <?= htmlspecialchars($p['title']) ?>
                                </td>
                                <td style="font-size: 0.9rem; line-height: 1.5;">
                                    <?= htmlspecialchars($p['description'] ?: '-') ?>
                                </td>
                                <td>
                                    <?php if (!empty($p['file']) && file_exists(__DIR__ . '/../files/' . $p['file'])): ?>
                                        <a href="#" class="file-link" onclick="showAdminDoc('../files/<?= htmlspecialchars($p['file']) ?>', '<?= htmlspecialchars(addslashes($p['title'])) ?>'); return false;">
                                            <i class="fa-solid fa-eye"></i> View File
                                        </a>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 0.85rem;">No file</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($p['link'])): ?>
                                        <a href="<?= htmlspecialchars($p['link']) ?>" target="_blank" class="file-link">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Visit
                                        </a>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 0.85rem;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons" style="justify-content: center;">
                                        <a href="portfolio.php?action=edit&id=<?= $p['id'] ?>" class="btn-icon btn-edit" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="portfolio.php?action=delete&id=<?= $p['id'] ?>" class="btn-icon btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars(addslashes($p['title'])) ?>?');">
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
function showAdminDoc(fileUrl, title) {
    const ext = fileUrl.split('.').pop().toLowerCase();
    let content = '';
    
    if (ext === 'pdf') {
        content = `<iframe src="${fileUrl}" style="width:100%; height:500px; border:none;" title="${title}"></iframe>`;
    } else if (['jpg', 'jpeg', 'png'].includes(ext)) {
        content = `<img src="${fileUrl}" style="width:100%; max-height:500px; object-fit:contain; border-radius:8px;" alt="${title}">`;
    } else {
        content = `<p>Cannot preview this format. <a href="${fileUrl}" target="_blank" class="file-link">Download instead</a></p>`;
    }
    
    showAdminModal(content);
}
</script>

<?php
require_once __DIR__ . '/layout_footer.php';
?>

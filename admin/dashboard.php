<?php
// dashboard.php - Admin Dashboard Summary
require_once __DIR__ . '/layout_header.php';

$experiences_count = isset($db_data['experiences']) ? count($db_data['experiences']) : 0;
$certificates_count = isset($db_data['certificates']) ? count($db_data['certificates']) : 0;
$socmed_count = isset($db_data['socmed']) ? count($db_data['socmed']) : 0;
$portfolio_count = isset($db_data['portfolio']) ? count($db_data['portfolio']) : 0;

$name = isset($db_data['biodata']['name']) ? $db_data['biodata']['name'] : '';
$role = isset($db_data['biodata']['role']) ? $db_data['biodata']['role'] : '';
$desc = isset($db_data['biodata']['description']) ? $db_data['biodata']['description'] : '';
$photo = isset($db_data['biodata']['photo']) ? $db_data['biodata']['photo'] : '';

// Calculate profile completion percentage
$completion = 0;
if (!empty($name)) $completion += 25;
if (!empty($role)) $completion += 25;
if (!empty($desc)) $completion += 25;
if (!empty($photo)) $completion += 25;
?>

<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-info">
            <h3>Work Experiences</h3>
            <p><?= $experiences_count ?></p>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-briefcase"></i>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <h3>Certificates</h3>
            <p><?= $certificates_count ?></p>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-award"></i>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <h3>Projects Portfolio</h3>
            <p><?= $portfolio_count ?></p>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-gem"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3>Social Accounts</h3>
            <p><?= $socmed_count ?></p>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-share-nodes"></i>
        </div>
    </div>
</div>

<div class="admin-grid-layout">
    <!-- Profile Completion & Summary Card -->
    <div class="form-card" style="max-width: 100%; margin: 0;">
        <h2 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--dark); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-sparkles" style="color: var(--primary);"></i>
            <span>Your Profile Summary</span>
        </h2>
        
        <div style="display: flex; gap: 24px; align-items: flex-start; margin-bottom: 25px; flex-wrap: wrap;">
            <?php if (!empty($photo) && file_exists(__DIR__ . '/../files/' . $photo)): ?>
                <img src="../files/<?= htmlspecialchars($photo) ?>" alt="Profile Photo" style="width: 120px; height: 120px; border-radius: var(--radius-md); object-fit: cover; border: 3px solid var(--primary-light);">
            <?php else: ?>
                <div class="no-photo" style="width: 120px; height: 120px; border-radius: var(--radius-md); flex-shrink: 0;">
                    <i class="fa-solid fa-image"></i>
                </div>
            <?php endif; ?>
            
            <div style="flex: 1; min-width: 250px;">
                <h3 style="font-size: 1.25rem; color: var(--dark); margin-bottom: 6px;"><?= htmlspecialchars($name ?: 'Not configured') ?></h3>
                <p style="color: var(--primary); font-weight: 600; font-size: 0.95rem; margin-bottom: 12px;"><?= htmlspecialchars($role ?: 'Not configured') ?></p>
                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;"><?= htmlspecialchars($desc ?: 'Profile bio description is currently empty.') ?></p>
            </div>
        </div>
        
        <div style="background-color: var(--light-bg); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="font-size: 0.85rem; font-weight: 600; color: var(--dark);">Profile Completion Status</span>
                <span style="font-size: 0.85rem; font-weight: 700; color: var(--primary);"><?= $completion ?>%</span>
            </div>
            <div style="width: 100%; height: 8px; background-color: #EBE0E2; border-radius: 4px; overflow: hidden;">
                <div style="width: <?= $completion ?>%; height: 100%; background-color: var(--primary); border-radius: 4px; transition: width 0.5s ease;"></div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions Card -->
    <div class="form-card" style="max-width: 100%; margin: 0; display: flex; flex-direction: column; gap: 15px; background: linear-gradient(135deg, #FFFFFF 0%, #FAF5F6 100%);">
        <h2 style="font-family: var(--font-heading); font-size: 1.25rem; color: var(--dark); margin-bottom: 10px;">Quick Actions</h2>
        
        <a href="biodata.php" class="btn-primary" style="text-decoration: none; justify-content: center; width: 100%;">
            <i class="fa-solid fa-user-pen"></i>
            <span>Edit Profile Info</span>
        </a>
        
        <a href="portfolio.php" class="btn-primary" style="text-decoration: none; justify-content: center; width: 100%; background-color: var(--primary-hover); box-shadow: none;">
            <i class="fa-solid fa-gem"></i>
            <span>Manage Portfolio</span>
        </a>

        <a href="pengalaman.php" class="btn-primary" style="text-decoration: none; justify-content: center; width: 100%; background-color: var(--dark); box-shadow: none;">
            <i class="fa-solid fa-briefcase"></i>
            <span>Manage Experiences</span>
        </a>
        
        <a href="sertifikat.php" class="btn-primary" style="text-decoration: none; justify-content: center; width: 100%; background-color: var(--dark); box-shadow: none;">
            <i class="fa-solid fa-award"></i>
            <span>Manage Certificates</span>
        </a>

        <a href="../index.php" target="_blank" class="btn-primary" style="text-decoration: none; justify-content: center; width: 100%; background-color: transparent; border: 1.5px solid var(--primary); color: var(--primary); box-shadow: none;">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
            <span>View Public Website</span>
        </a>
    </div>
</div>

<?php
require_once __DIR__ . '/layout_footer.php';
?>

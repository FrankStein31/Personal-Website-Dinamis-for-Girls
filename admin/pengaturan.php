<?php
// pengaturan.php - Admin Security Settings (Change Password)
require_once __DIR__ . '/layout_header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_pass = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $new_pass = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_pass = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        $_SESSION['error_msg'] = 'All fields are required!';
    } elseif ($new_pass !== $confirm_pass) {
        $_SESSION['error_msg'] = 'New password and confirmation password do not match!';
    } else {
        $db_data = get_db_data();
        $db_hashed_password = isset($db_data['settings']['password']) ? $db_data['settings']['password'] : '';
        
        if (!empty($db_hashed_password) && password_verify($current_pass, $db_hashed_password)) {
            // Update password
            $db_data['settings']['password'] = password_hash($new_pass, PASSWORD_DEFAULT);
            
            if (save_db_data($db_data)) {
                $_SESSION['success_msg'] = 'Administrator password updated successfully!';
                header("Location: pengaturan.php");
                exit;
            } else {
                $_SESSION['error_msg'] = 'Failed to save new password to database.';
            }
        } else {
            $_SESSION['error_msg'] = 'The current password you entered is incorrect!';
        }
    }
}
?>

<div class="form-card">
    <h2 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--dark); margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
        <i class="fa-solid fa-shield-halved" style="color: var(--primary);"></i>
        <span>Change Administrator Password</span>
    </h2>
    
    <form action="" method="POST">
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter current password" required>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Repeat new password" required>
            </div>
        </div>
        
        <div style="margin-top: 30px; border-top: 1px solid var(--border); padding-top: 20px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-key"></i>
                <span>Change Password</span>
            </button>
        </div>
    </form>
</div>

<?php
require_once __DIR__ . '/layout_footer.php';
?>

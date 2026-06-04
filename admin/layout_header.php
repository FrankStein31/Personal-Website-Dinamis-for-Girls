<?php
// layout_header.php - Shared Header and Sidebar for Admin Panel
require_once __DIR__ . '/../config.php';
check_login();

$active_page = basename($_SERVER['PHP_SELF']);
$db_data = get_db_data();
$admin_name = !empty($db_data['biodata']['name']) ? $db_data['biodata']['name'] : 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Portfolio</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Admin Custom CSS -->
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

    <!-- Mobile Admin Top Bar -->
    <div class="admin-mobile-header">
        <button class="admin-mobile-toggle" onclick="toggleSidebar()">
            <i class="fa-solid fa-bars"></i>
        </button>
        <span style="font-family: var(--font-heading); font-size: 1.15rem; font-weight: 700; color: var(--dark); letter-spacing: 0.5px;">Heidy Admin</span>
        <div class="admin-profile" style="padding: 4px 10px; margin: 0; box-shadow: none; border: none; background: transparent;">
            <div class="admin-avatar" style="width: 28px; height: 28px; font-size: 0.8rem; background-color: var(--primary-light); color: var(--primary);">
                <i class="fa-solid fa-user-tie"></i>
            </div>
        </div>
    </div>

    <!-- Sidebar Overlay Backdrop for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Fixed Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-gem"></i>
            <h2>Heidy Portfolio</h2>
            <button class="sidebar-close-mobile" onclick="toggleSidebar()" style="display: none; background: none; border: none; font-size: 1.25rem; color: var(--text-muted); cursor: pointer; margin-left: auto;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        
        <ul class="sidebar-menu">
            <li class="sidebar-item <?= ($active_page == 'dashboard.php') ? 'active' : '' ?>">
                <a href="dashboard.php">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($active_page == 'biodata.php') ? 'active' : '' ?>">
                <a href="biodata.php">
                    <i class="fa-solid fa-user-pen"></i>
                    <span>Biodata</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($active_page == 'pengalaman.php') ? 'active' : '' ?>">
                <a href="pengalaman.php">
                    <i class="fa-solid fa-briefcase"></i>
                    <span>Pengalaman</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($active_page == 'sertifikat.php') ? 'active' : '' ?>">
                <a href="sertifikat.php">
                    <i class="fa-solid fa-award"></i>
                    <span>Sertifikat</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($active_page == 'socmed.php') ? 'active' : '' ?>">
                <a href="socmed.php">
                    <i class="fa-solid fa-share-nodes"></i>
                    <span>Media Sosial</span>
                </a>
            </li>
            <li class="sidebar-item <?= ($active_page == 'pengaturan.php') ? 'active' : '' ?>">
                <a href="pengaturan.php">
                    <i class="fa-solid fa-gears"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
        </ul>
        
        <div class="sidebar-footer">
            <form action="logout.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar?');">
                <button type="submit" class="btn-logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Top bar (Desktop Header) -->
        <div class="header-top">
            <div class="page-title">
                <?php if ($active_page == 'dashboard.php'): ?>
                    <h1>Ringkasan Dashboard</h1>
                    <p>Selamat datang kembali di panel administrasi Anda.</p>
                <?php elseif ($active_page == 'biodata.php'): ?>
                    <h1>Edit Biodata</h1>
                    <p>Perbarui informasi profil profesional Anda.</p>
                <?php elseif ($active_page == 'pengalaman.php'): ?>
                    <h1>Kelola Pengalaman Kerja</h1>
                    <p>Atur riwayat karir Anda yang akan ditampilkan di timeline.</p>
                <?php elseif ($active_page == 'sertifikat.php'): ?>
                    <h1>Kelola Sertifikat</h1>
                    <p>Upload dan kelola dokumen pencapaian dan sertifikat Anda.</p>
                <?php elseif ($active_page == 'socmed.php'): ?>
                    <h1>Kelola Media Sosial</h1>
                    <p>Atur tautan media sosial yang aktif di halaman utama.</p>
                <?php elseif ($active_page == 'pengaturan.php'): ?>
                    <h1>Pengaturan Akun</h1>
                    <p>Kelola keamanan dan ganti password administrator.</p>
                <?php endif; ?>
            </div>
            
            <div class="admin-profile desktop-profile">
                <div class="admin-avatar">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div class="admin-name"><?= htmlspecialchars($admin_name) ?></div>
            </div>
        </div>
        
        <!-- Flash Message Notification -->
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <div><?= htmlspecialchars($_SESSION['success_msg']); unset($_SESSION['success_msg']); ?></div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-xmark"></i>
                <div><?= htmlspecialchars($_SESSION['error_msg']); unset($_SESSION['error_msg']); ?></div>
            </div>
        <?php endif; ?>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }
    </script>

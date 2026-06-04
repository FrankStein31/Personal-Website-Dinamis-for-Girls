<?php
// index.php - Main Frontend Portfolio
require_once __DIR__ . '/config.php';

$db_data = get_db_data();

$biodata = isset($db_data['biodata']) ? $db_data['biodata'] : [];
$experiences = isset($db_data['experiences']) ? $db_data['experiences'] : [];
$certificates = isset($db_data['certificates']) ? $db_data['certificates'] : [];
$socmed = isset($db_data['socmed']) ? $db_data['socmed'] : [];

$name = isset($biodata['name']) ? $biodata['name'] : '';
$role = isset($biodata['role']) ? $biodata['role'] : '';
$description = isset($biodata['description']) ? $biodata['description'] : '';
$photo = isset($biodata['photo']) ? $biodata['photo'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !empty($name) ? htmlspecialchars($name) . ' - Personal Portfolio' : 'Personal Portfolio' ?></title>
    <!-- SEO Meta Tags -->
    <meta name="description" content="Personal Portfolio Website. Explore professional qualifications, certificates, work experiences, and social media platforms.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS (Animate on Scroll) CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    
    <style>
        /* CSS Custom Variables for Elegant Feminine Theme */
        :root {
            --rose-gold: #B76E79;
            --rose-gold-dark: #9E5560;
            --rose-gold-light: #E8C5C8;
            --soft-blush: #FFECEF;
            --ivory-white: #FFFDF9;
            --charcoal: #2C1E21;
            --text-dark: #3F3033;
            --text-muted: #837073;
            --border-color: rgba(183, 110, 121, 0.15);
            --font-heading: 'Playfair Display', serif;
            --font-body: 'Poppins', sans-serif;
            --glass-bg: rgba(255, 255, 255, 0.45);
            --glass-border: rgba(255, 255, 255, 0.5);
            --glass-shadow: 0 10px 30px rgba(183, 110, 121, 0.08);
            --glow-color: rgba(183, 110, 121, 0.35);
        }

        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-body);
            background: linear-gradient(135deg, #FFFDF9 0%, #FAF0F2 50%, #F5E3E6 100%);
            color: var(--text-dark);
            line-height: 1.6;
            overflow-x: hidden;
            position: relative;
        }

        /* Decorative Background Ornaments */
        .decor-blob-1 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,204,213,0.3) 0%, rgba(255,255,255,0) 70%);
            top: -150px;
            right: -100px;
            z-index: -1;
            pointer-events: none;
        }

        .decor-blob-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(232,197,200,0.2) 0%, rgba(255,255,255,0) 75%);
            bottom: 20%;
            left: -200px;
            z-index: -1;
            pointer-events: none;
        }

        /* Floating Navbar */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 20px 0;
            transition: all 0.4s ease;
        }

        header.scrolled {
            background: rgba(255, 253, 249, 0.85);
            backdrop-filter: blur(15px);
            padding: 14px 0;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(183, 110, 121, 0.05);
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: var(--font-heading);
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--charcoal);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.5px;
        }

        .logo i {
            color: var(--rose-gold);
            font-size: 1.25rem;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-size: 0.95rem;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: var(--rose-gold);
            transition: width 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--rose-gold);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .btn-admin {
            background-color: var(--rose-gold);
            color: #FFFFFF !important;
            padding: 10px 22px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600 !important;
            box-shadow: 0 4px 15px rgba(183, 110, 121, 0.25);
            transition: all 0.3s ease !important;
        }

        .btn-admin::after {
            display: none !important;
        }

        .btn-admin:hover {
            background-color: var(--rose-gold-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(183, 110, 121, 0.35);
        }

        /* Sections General Layout */
        section {
            padding: 120px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 70px;
        }

        .section-header h2 {
            font-family: var(--font-heading);
            font-size: 2.5rem;
            color: var(--charcoal);
            font-weight: 700;
            margin-bottom: 12px;
            position: relative;
            display: inline-block;
        }

        .section-header h2::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background-color: var(--rose-gold);
            margin: 10px auto 0 auto;
            border-radius: 2px;
        }

        .section-header p {
            color: var(--text-muted);
            font-size: 1rem;
            max-width: 500px;
            margin: 0 auto;
        }

        /* HERO SECTION (LAYOUT RESUME PREMIUM) */
        #hero {
            padding-top: 180px;
            padding-bottom: 100px;
            min-height: 90vh;
            display: flex;
            align-items: center;
        }

        .hero-layout {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 60px;
            align-items: center;
        }

        .hero-text {
            display: flex;
            flex-direction: column;
        }

        .badge {
            align-self: flex-start;
            background-color: var(--soft-blush);
            color: var(--rose-gold);
            padding: 8px 18px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border: 1px solid rgba(183, 110, 121, 0.2);
            box-shadow: 0 4px 10px rgba(183, 110, 121, 0.03);
        }

        .hero-text h1 {
            font-family: var(--font-heading);
            font-size: 3.8rem;
            color: var(--charcoal);
            line-height: 1.15;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .hero-text .title {
            font-size: 1.5rem;
            color: var(--rose-gold);
            font-weight: 500;
            margin-bottom: 24px;
            letter-spacing: 0.5px;
        }

        .hero-text .description {
            font-size: 1.05rem;
            color: var(--text-muted);
            margin-bottom: 35px;
            line-height: 1.8;
            max-width: 580px;
        }

        /* Dynamic profile image layout */
        .hero-image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .profile-frame {
            position: relative;
            width: 380px;
            height: 480px;
            border-radius: 50px 15px 50px 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(183, 110, 121, 0.12);
            border: 5px solid #FFFFFF;
            z-index: 2;
            transition: all 0.5s ease;
        }

        .profile-frame:hover {
            transform: scale(1.02);
            box-shadow: 0 25px 50px rgba(183, 110, 121, 0.18);
        }

        .profile-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: scale 0.5s ease;
        }

        .profile-frame:hover img {
            scale: 1.05;
        }

        /* Organic blob behind the photo frame */
        .blob-backdrop {
            position: absolute;
            width: 440px;
            height: 520px;
            background: linear-gradient(135deg, var(--rose-gold-light) 0%, var(--soft-blush) 100%);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            z-index: 1;
            top: -20px;
            left: -20px;
            animation: morphBlob 15s ease-in-out infinite alternate;
            opacity: 0.7;
        }

        @keyframes morphBlob {
            0% {
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            }
            50% {
                border-radius: 50% 50% 30% 70% / 50% 60% 40% 50%;
            }
            100% {
                border-radius: 70% 30% 50% 50% / 40% 40% 60% 60%;
            }
        }

        /* VISUAL MEDIA SOSIAL YANG MENONJOL */
        .social-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .social-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--charcoal);
            margin-right: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .social-btn {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--rose-gold);
            font-size: 1.15rem;
            text-decoration: none;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(183, 110, 121, 0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .social-btn:hover {
            color: #FFFFFF;
            background-color: var(--rose-gold);
            transform: translateY(-4px) rotate(12deg);
            box-shadow: 0 8px 20px var(--glow-color);
        }

        /* EXPERIENCES SECTION */
        #experiences {
            background-color: rgba(255, 253, 249, 0.5);
            position: relative;
        }

        .timeline {
            position: relative;
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            width: 3px;
            background: linear-gradient(to bottom, var(--soft-blush) 0%, var(--rose-gold) 50%, var(--soft-blush) 100%);
            top: 0;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .timeline-item {
            margin-bottom: 60px;
            width: 100%;
            position: relative;
        }

        .timeline-item::after {
            content: '';
            display: table;
            clear: both;
        }

        .timeline-dot {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #FFFFFF;
            border: 4px solid var(--rose-gold);
            position: absolute;
            left: 50%;
            top: 24px;
            transform: translateX(-50%);
            z-index: 5;
            box-shadow: 0 0 0 6px var(--soft-blush);
            transition: all 0.3s ease;
        }

        .timeline-item:hover .timeline-dot {
            background-color: var(--rose-gold);
            scale: 1.2;
        }

        .timeline-content {
            width: 44%;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px;
            backdrop-filter: blur(10px);
            box-shadow: var(--glass-shadow);
            position: relative;
            transition: all 0.4s ease;
        }

        .timeline-item:hover .timeline-content {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(183, 110, 121, 0.12);
            border-color: rgba(183, 110, 121, 0.3);
        }

        .timeline-item:nth-child(even) .timeline-content {
            float: right;
        }

        .timeline-item:nth-child(odd) .timeline-content {
            float: left;
        }

        .timeline-year {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--rose-gold);
            background-color: var(--soft-blush);
            padding: 5px 12px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .timeline-title {
            font-family: var(--font-heading);
            font-size: 1.4rem;
            color: var(--charcoal);
            font-weight: 700;
            margin-bottom: 6px;
        }

        .timeline-company {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-dark);
            margin-bottom: 15px;
            opacity: 0.85;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .timeline-company i {
            color: var(--rose-gold);
            font-size: 0.85rem;
        }

        .timeline-desc {
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* CERTIFICATES SECTION (GLASSMORPHISM GRID CARD) */
        .cert-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .cert-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 35px;
            backdrop-filter: blur(12px);
            box-shadow: var(--glass-shadow);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .cert-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, var(--rose-gold-light), var(--rose-gold));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .cert-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(183, 110, 121, 0.14);
            border-color: rgba(183, 110, 121, 0.3);
        }

        .cert-card:hover::before {
            opacity: 1;
        }

        .cert-icon {
            width: 50px;
            height: 50px;
            background-color: var(--soft-blush);
            color: var(--rose-gold);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 24px;
            transition: rotate 0.5s ease;
        }

        .cert-card:hover .cert-icon {
            rotate: 360deg;
        }

        .cert-info h3 {
            font-family: var(--font-heading);
            font-size: 1.35rem;
            color: var(--charcoal);
            margin-bottom: 8px;
            font-weight: 700;
        }

        .cert-info p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .cert-info p i {
            color: var(--rose-gold);
        }

        .btn-view-doc {
            background-color: var(--rose-gold);
            color: #FFFFFF;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-family: var(--font-body);
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(183, 110, 121, 0.15);
            text-decoration: none;
            width: 100%;
        }

        .btn-view-doc:hover {
            background-color: var(--rose-gold-dark);
            box-shadow: 0 6px 18px rgba(183, 110, 121, 0.25);
            transform: translateY(-1px);
        }

        /* POP-UP MODAL MULTI-FORMAT */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(44, 30, 33, 0.6);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
            padding: 20px;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-box {
            background-color: var(--ivory-white);
            border-radius: 28px;
            width: 100%;
            max-width: 850px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(44, 30, 33, 0.2);
            transform: scale(0.9);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.15);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .modal-overlay.active .modal-box {
            transform: scale(1);
        }

        .modal-header {
            padding: 24px 30px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #FFFFFF;
        }

        .modal-title-container h3 {
            font-family: var(--font-heading);
            font-size: 1.4rem;
            color: var(--charcoal);
            font-weight: 700;
        }

        .modal-title-container p {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 2px;
        }

        .modal-close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.3s;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close-btn:hover {
            background-color: var(--soft-blush);
            color: var(--rose-gold);
        }

        .modal-body {
            padding: 30px;
            overflow-y: auto;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(250, 246, 247, 0.5);
            min-height: 400px;
        }

        .modal-body img {
            max-width: 100%;
            height: auto;
            max-height: 70vh;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            object-fit: contain;
        }

        .modal-body iframe, .modal-body object {
            width: 100%;
            height: 60vh;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        /* Footer */
        footer {
            background-color: var(--charcoal);
            color: #FFFFFF;
            padding: 60px 0;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        footer .logo {
            color: #FFFFFF;
            justify-content: center;
            margin-bottom: 20px;
        }

        footer p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        footer a {
            color: var(--rose-gold-light);
            text-decoration: none;
            transition: color 0.3s;
        }

        footer a:hover {
            color: #FFFFFF;
        }

        /* Responsive Layouts */
        @media (max-width: 991px) {
            .hero-layout {
                grid-template-columns: 1fr;
                gap: 50px;
                text-align: center;
            }

            .badge {
                align-self: center;
            }

            .hero-text h1 {
                font-size: 3rem;
            }

            .hero-text .description {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-image-container {
                order: -1;
            }

            .profile-frame {
                width: 320px;
                height: 400px;
            }

            .blob-backdrop {
                width: 360px;
                height: 430px;
            }

            .social-row {
                justify-content: center;
            }

            .timeline::before {
                left: 30px;
            }

            .timeline-dot {
                left: 30px;
            }

            .timeline-content {
                width: calc(100% - 60px);
                float: right !important;
            }
        }

        @media (max-width: 576px) {
            .hero-text h1 {
                font-size: 2.3rem;
            }

            .section-header h2 {
                font-size: 2rem;
            }

            .cert-grid {
                grid-template-columns: 1fr;
            }

            .nav-links {
                display: none; /* simple display solution for complex nav bar mobile header */
            }

            .btn-admin-mobile {
                display: inline-block;
            }
        }
    </style>
</head>
<body>

    <div class="decor-blob-1"></div>
    <div class="decor-blob-2"></div>

    <!-- Navigation Bar -->
    <header id="navbar">
        <div class="container">
            <nav>
                <a href="#hero" class="logo">
                    <i class="fa-solid fa-gem"></i>
                    <span><?= !empty($name) ? htmlspecialchars($name) : 'Heidy' ?></span>
                </a>
                
                <ul class="nav-links">
                    <li><a href="#hero">Beranda</a></li>
                    <?php if (!empty($experiences)): ?>
                        <li><a href="#experiences">Pengalaman</a></li>
                    <?php endif; ?>
                    <?php if (!empty($certificates)): ?>
                        <li><a href="#certificates">Sertifikat</a></li>
                    <?php endif; ?>
                    <li hidden><a href="admin/index.php" class="btn-admin"><i class="fa-solid fa-user-lock"></i> Admin Panel</a></li>
                </ul>

                <!-- Mobile Admin Button only (hidden in desktop) -->
                <a href="admin/index.php" class="btn-admin btn-admin-mobile" style="display: none; padding: 6px 14px; font-size: 0.8rem;">
                    <i class="fa-solid fa-user-lock"></i>
                </a>
            </nav>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section id="hero">
        <div class="container">
            <div class="hero-layout">
                <div class="hero-text" data-aos="fade-right" data-aos-duration="1000">
                    <span class="badge">Selamat Datang</span>
                    
                    <?php if (!empty($name)): ?>
                        <h1>Hello, I'm <span style="color: var(--rose-gold);"><?= htmlspecialchars($name) ?></span></h1>
                    <?php endif; ?>
                    
                    <?php if (!empty($role)): ?>
                        <div class="title"><?= htmlspecialchars($role) ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($description)): ?>
                        <p class="description"><?= nl2br(htmlspecialchars($description)) ?></p>
                    <?php endif; ?>
                    
                    <!-- PROMINENT SOCIAL MEDIA BUTTONS -->
                    <?php if (!empty($socmed)): ?>
                        <div class="social-row">
                            <span class="social-label">Temukan Saya:</span>
                            <?php foreach ($socmed as $s): ?>
                                <?php if (!empty($s['url']) && !empty($s['icon'])): ?>
                                    <a href="<?= htmlspecialchars($s['url']) ?>" target="_blank" class="social-btn" title="<?= htmlspecialchars($s['platform']) ?>">
                                        <i class="<?= htmlspecialchars($s['icon']) ?>"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="hero-image-container" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <div class="blob-backdrop"></div>
                    <div class="profile-frame">
                        <?php if (!empty($photo) && file_exists(__DIR__ . '/files/' . $photo)): ?>
                            <img src="files/<?= htmlspecialchars($photo) ?>" alt="Heidy Portrait Portrait">
                        <?php else: ?>
                            <!-- Fallback premium illustration if image is missing -->
                            <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: radial-gradient(circle at 30% 20%, #FAF0F2 0%, #E8C5C8 100%); color: var(--rose-gold); font-size: 3rem;">
                                <i class="fa-solid fa-crown" style="margin-bottom: 10px;"></i>
                                <span style="font-size: 0.95rem; font-family: var(--font-body); font-weight: 500; text-transform: uppercase; letter-spacing: 2px;">Premium Portfolio</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- EXPERIENCES SECTION -->
    <?php if (!empty($experiences)): ?>
        <section id="experiences">
            <div class="container">
                <div class="section-header" data-aos="fade-up" data-aos-duration="1000">
                    <h2>Pengalaman Kerja</h2>
                    <p>Jejak karir profesional dan riwayat kontribusi pekerjaan saya.</p>
                </div>
                
                <div class="timeline">
                    <?php foreach ($experiences as $index => $exp): ?>
                        <div class="timeline-item" data-aos="<?= ($index % 2 === 0) ? 'fade-right' : 'fade-left' ?>" data-aos-duration="1000" data-aos-delay="100">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <?php if (!empty($exp['year'])): ?>
                                    <span class="timeline-year"><?= htmlspecialchars($exp['year']) ?></span>
                                <?php endif; ?>
                                
                                <?php if (!empty($exp['position'])): ?>
                                    <h3 class="timeline-title"><?= htmlspecialchars($exp['position']) ?></h3>
                                <?php endif; ?>
                                
                                <?php if (!empty($exp['company'])): ?>
                                    <div class="timeline-company">
                                        <i class="fa-solid fa-building"></i>
                                        <span><?= htmlspecialchars($exp['company']) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($exp['description'])): ?>
                                    <p class="timeline-desc"><?= nl2br(htmlspecialchars($exp['description'])) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- CERTIFICATES SECTION -->
    <?php if (!empty($certificates)): ?>
        <section id="certificates">
            <div class="container">
                <div class="section-header" data-aos="fade-up" data-aos-duration="1000">
                    <h2>Sertifikasi & Penghargaan</h2>
                    <p>Validasi kompetensi dan pelatihan profesional yang telah saya selesaikan.</p>
                </div>
                
                <div class="cert-grid">
                    <?php foreach ($certificates as $cert): ?>
                        <div class="cert-card" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                            <div class="cert-info">
                                <div class="cert-icon">
                                    <i class="fa-solid fa-award"></i>
                                </div>
                                <?php if (!empty($cert['name'])): ?>
                                    <h3><?= htmlspecialchars($cert['name']) ?></h3>
                                <?php endif; ?>
                                
                                <?php if (!empty($cert['issuer'])): ?>
                                    <p>
                                        <i class="fa-solid fa-bookmark"></i>
                                        <span><?= htmlspecialchars($cert['issuer']) ?></span>
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($cert['file']) && file_exists(__DIR__ . '/files/' . $cert['file'])): ?>
                                <button class="btn-view-doc" onclick="openDocModal('files/<?= htmlspecialchars($cert['file']) ?>', '<?= htmlspecialchars(addslashes($cert['name'])) ?>', '<?= htmlspecialchars(addslashes($cert['issuer'])) ?>')">
                                    <i class="fa-solid fa-eye"></i>
                                    <span>Lihat Dokumen</span>
                                </button>
                            <?php else: ?>
                                <button class="btn-view-doc" style="background-color: var(--text-muted); cursor: not-allowed; box-shadow: none;" disabled>
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    <span>Dokumen Tidak Tersedia</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <a href="#hero" class="logo">
                <i class="fa-solid fa-gem"></i>
                <span><?= !empty($name) ? htmlspecialchars($name) : 'Heidy' ?></span>
            </a>
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($name) ?>. All Rights Reserved.</p>
            <p style="font-size: 0.8rem; opacity: 0.7;"><a href="admin/index.php">Panel Administrator</a>.</p>
        </div>
    </footer>

    <!-- POP-UP MODAL MULTI-FORMAT -->
    <div class="modal-overlay" id="documentModal" onclick="closeDocModal(event)">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div class="modal-title-container">
                    <h3 id="modalDocTitle">Nama Dokumen</h3>
                    <p id="modalDocIssuer">Penerbit Dokumen</p>
                </div>
                <button class="modal-close-btn" onclick="hideDocModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalDocBody">
                <!-- Content will be injected dynamically via JS -->
            </div>
        </div>
    </div>

    <!-- AOS Animation JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <script>
        // Initialize AOS animations
        AOS.init({
            once: true,
            offset: 120
        });

        // Navbar scrolled class toggle
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Pop-up Modal Multi-format Detection and Rendering
        function openDocModal(fileUrl, docTitle, docIssuer) {
            const modal = document.getElementById('documentModal');
            const titleElem = document.getElementById('modalDocTitle');
            const issuerElem = document.getElementById('modalDocIssuer');
            const bodyElem = document.getElementById('modalDocBody');
            
            titleElem.textContent = docTitle;
            issuerElem.textContent = "Penerbit: " + docIssuer;
            
            // Get file extension
            const extension = fileUrl.split('.').pop().toLowerCase();
            
            let contentHtml = '';
            
            if (extension === 'pdf') {
                // Render PDF inline inside an iframe / object tag
                contentHtml = `<iframe src="${fileUrl}" title="${docTitle}" type="application/pdf"></iframe>`;
            } else if (['jpg', 'jpeg', 'png'].includes(extension)) {
                // Render Image full size
                contentHtml = `<img src="${fileUrl}" alt="${docTitle}">`;
            } else {
                contentHtml = `<p style="color: var(--charcoal); font-weight: 500;">Format berkas tidak dapat dipreview secara langsung. Silakan hubungi administrator.</p>`;
            }
            
            bodyElem.innerHTML = contentHtml;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Disable scroll under modal
        }

        function hideDocModal() {
            const modal = document.getElementById('documentModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto'; // Enable scroll
            
            // Clear content after animation
            setTimeout(() => {
                document.getElementById('modalDocBody').innerHTML = '';
            }, 400);
        }

        function closeDocModal(event) {
            if (event.target.id === 'documentModal') {
                hideDocModal();
            }
        }

        // Close modal on Escape key press
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hideDocModal();
            }
        });
    </script>
</body>
</html>

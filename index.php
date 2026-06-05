<?php
// index.php - Main Frontend Portfolio
require_once __DIR__ . '/config.php';

$db_data = get_db_data();

$biodata = isset($db_data['biodata']) ? $db_data['biodata'] : [];
$experiences = isset($db_data['experiences']) ? $db_data['experiences'] : [];
$certificates = isset($db_data['certificates']) ? $db_data['certificates'] : [];
$socmed = isset($db_data['socmed']) ? $db_data['socmed'] : [];
$portfolio = isset($db_data['portfolio']) ? $db_data['portfolio'] : [];

$name = isset($biodata['name']) ? $biodata['name'] : '';
$role = isset($biodata['role']) ? $biodata['role'] : '';
$description = isset($biodata['description']) ? $biodata['description'] : '';
$photo = isset($biodata['photo']) ? $biodata['photo'] : '';
?>
<!DOCTYPE html>
<html lang="en">
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

        html, body {
            overflow-x: hidden;
            width: 100%;
        }

        body {
            font-family: var(--font-body);
            background: linear-gradient(135deg, #FFFDF9 0%, #FAF0F2 50%, #F5E3E6 100%);
            color: var(--text-dark);
            line-height: 1.6;
            position: relative;
        }

        /* Background Decor Container to prevent horizontal overflow */
        .bg-decor-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: -1;
        }

        /* Decorative Background Ornaments */
        .decor-blob-1 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,204,213,0.3) 0%, rgba(255,255,255,0) 70%);
            top: -150px;
            right: -100px;
            z-index: 1;
            pointer-events: none;
        }

        .decor-blob-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(232,197,200,0.2) 0%, rgba(255,255,255,0) 75%);
            bottom: 20%;
            left: -200px;
            z-index: 1;
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

        /* Dynamic profile image layout - Heart Shaped */
        .hero-image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .profile-frame-wrap {
            position: relative;
            width: 450px;
            aspect-ratio: 1 / 1.13;
            padding: 6px;
            background: #FFFFFF;
            clip-path: url(#heart-clip);
            z-index: 2;
            transition: all 0.5s ease;
            filter: drop-shadow(0 15px 30px rgba(183, 110, 121, 0.16));
        }

        .profile-frame-wrap:hover {
            transform: scale(1.03);
            filter: drop-shadow(0 20px 40px rgba(183, 110, 121, 0.25));
        }

        .profile-frame {
            width: 100%;
            height: 100%;
            clip-path: url(#heart-clip);
            overflow: hidden;
            background: var(--soft-blush);
        }

        .profile-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 20%;
            transform: scale(1.08);
            transition: transform 0.5s ease;
        }

        .profile-frame-wrap:hover img {
            transform: scale(1.15);
        }

        /* Organic blob behind the photo frame */
        .blob-backdrop {
            position: absolute;
            width: 500px;
            height: 580px;
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

        /* CERTIFICATES SECTION (PREMIUM CAROUSEL SLIDER) */
        .cert-carousel-wrapper {
            position: relative;
            width: 100%;
        }

        .cert-carousel-container {
            overflow: hidden;
            width: 100%;
            padding: 20px 0;
        }

        .cert-carousel-inner {
            display: flex;
            gap: 30px;
            transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            width: 100%;
        }

        .cert-carousel-inner .cert-card {
            flex: 0 0 calc((100% - 60px) / 3);
            margin-bottom: 0;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 25px;
            backdrop-filter: blur(12px);
            box-shadow: var(--glass-shadow);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        @media (max-width: 991px) {
            .cert-carousel-inner .cert-card {
                flex: 0 0 calc((100% - 30px) / 2);
            }
        }

        @media (max-width: 767px) {
            .cert-carousel-inner .cert-card {
                flex: 0 0 100%;
            }
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

        /* Certificate Doc Preview Box */
        .cert-preview-box {
            width: 100%;
            height: 200px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 0 20px rgba(183, 110, 121, 0.02);
            cursor: pointer;
        }

        .cert-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .cert-preview-box iframe {
            width: 100%;
            height: 100%;
            border: none;
            pointer-events: none;
        }

        .cert-preview-box .iframe-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent;
            z-index: 2;
        }

        .cert-preview-box .no-preview {
            font-size: 3rem;
            color: var(--rose-gold-light);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .cert-card:hover .cert-preview-box img {
            transform: scale(1.05);
        }

        .cert-info h3 {
            font-family: var(--font-heading);
            font-size: 1.25rem;
            color: var(--charcoal);
            margin-bottom: 8px;
            font-weight: 700;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.8em; /* fixed height for alignment */
        }

        .cert-info p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 20px;
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

        /* Controls styling */
        .cert-carousel-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-top: 35px;
        }

        .cert-nav-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #FFFFFF;
            border: 1px solid var(--border-color);
            color: var(--rose-gold);
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(183, 110, 121, 0.08);
            transition: all 0.3s ease;
            z-index: 10;
        }

        .cert-nav-btn:hover {
            background-color: var(--rose-gold);
            color: #FFFFFF;
            box-shadow: 0 6px 18px rgba(183, 110, 121, 0.25);
            transform: scale(1.05);
        }

        .cert-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .cert-dot {
            width: 24px;
            height: 5px;
            border-radius: 3px;
            background-color: var(--rose-gold-light);
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0.4;
        }

        .cert-dot.active {
            width: 40px;
            background-color: var(--rose-gold);
            opacity: 1;
        }

        /* PORTFOLIO SECTION */
        #portfolio {
            background-color: rgba(255, 255, 255, 0.3);
            position: relative;
        }

        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .portfolio-card {
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

        .portfolio-card::before {
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

        .portfolio-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(183, 110, 121, 0.14);
            border-color: rgba(183, 110, 121, 0.3);
        }

        .portfolio-card:hover::before {
            opacity: 1;
        }

        /* Project File Preview Box */
        .project-preview-box {
            width: 100%;
            height: 210px;
            background-color: rgba(255, 255, 255, 0.75);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 22px;
            border: 1px solid var(--border-color);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 0 20px rgba(183, 110, 121, 0.02);
            cursor: pointer;
        }

        .project-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .project-preview-box iframe {
            width: 100%;
            height: 100%;
            border: none;
            pointer-events: none;
        }

        .project-preview-box .iframe-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent;
            z-index: 2;
        }

        .project-preview-box .no-preview {
            font-size: 3rem;
            color: var(--rose-gold-light);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .portfolio-card:hover .project-preview-box img {
            transform: scale(1.05);
        }

        .portfolio-body h3 {
            font-family: var(--font-heading);
            font-size: 1.35rem;
            color: var(--charcoal);
            margin-bottom: 12px;
            font-weight: 700;
        }

        .portfolio-body p {
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .portfolio-footer {
            display: flex;
            gap: 12px;
            margin-top: auto;
        }

        .btn-portfolio-action {
            flex: 1;
            padding: 12px;
            border-radius: 12px;
            font-family: var(--font-body);
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
        }

        .btn-portfolio-primary {
            background-color: var(--rose-gold);
            color: #FFFFFF;
            border: none;
            box-shadow: 0 4px 12px rgba(183, 110, 121, 0.15);
        }

        .btn-portfolio-primary:hover {
            background-color: var(--rose-gold-dark);
            box-shadow: 0 6px 18px rgba(183, 110, 121, 0.25);
            transform: translateY(-1px);
        }

        .btn-portfolio-outline {
            background-color: transparent;
            color: var(--rose-gold);
            border: 1px solid var(--rose-gold);
        }

        .btn-portfolio-outline:hover {
            background-color: var(--soft-blush);
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

        /* Mobile Hamburger Icon Styling */
        .mobile-nav-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.4rem;
            color: var(--charcoal);
            cursor: pointer;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            z-index: 1002;
        }

        .mobile-nav-toggle:hover {
            background-color: var(--soft-blush);
            color: var(--rose-gold);
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
                font-size: 2.8rem;
            }

            .hero-text .description {
                margin-left: auto;
                margin-right: auto;
                font-size: 1rem;
            }

            .hero-image-container {
                order: -1;
            }

            .profile-frame-wrap {
                width: 360px;
            }

            .blob-backdrop {
                width: 400px;
                height: 480px;
                max-width: 110%;
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

        @media (max-width: 768px) {
            section {
                padding: 80px 0;
            }

            .section-header {
                margin-bottom: 50px;
            }

            .section-header h2 {
                font-size: 2.2rem;
            }

            .mobile-nav-toggle {
                display: flex;
            }

            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 75%;
                max-width: 300px;
                height: 100vh;
                background-color: rgba(255, 253, 249, 0.98);
                backdrop-filter: blur(20px);
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 30px;
                padding: 80px 40px;
                box-shadow: -10px 0 30px rgba(183, 110, 121, 0.1);
                transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1001;
                border-left: 1px solid var(--border-color);
            }

            .nav-links.active {
                right: 0;
            }

            .nav-links li[hidden] {
                display: block !important;
                visibility: visible;
            }

            .btn-admin {
                width: 100%;
                text-align: center;
            }

            .timeline-content {
                padding: 22px;
            }
            
            .timeline-title {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 576px) {
            .hero-text h1 {
                font-size: 2.2rem;
            }

            .hero-text .title {
                font-size: 1.2rem;
                margin-bottom: 16px;
            }

            .hero-text .description {
                font-size: 0.92rem;
                line-height: 1.7;
            }

            .profile-frame-wrap {
                width: 300px;
            }

            .blob-backdrop {
                width: 340px;
                height: 410px;
            }

            .social-row {
                gap: 12px;
            }

            .social-label {
                width: 100%;
                text-align: center;
                margin-right: 0;
                margin-bottom: 8px;
            }

            .cert-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .cert-card {
                padding: 24px;
            }

            .modal-box {
                border-radius: 20px;
            }

            .modal-header {
                padding: 16px 20px;
            }

            .modal-body {
                padding: 20px;
                min-height: 300px;
            }

            .modal-body iframe, .modal-body object {
                height: 50vh;
            }
        }

        @media (max-width: 400px) {
            .hero-text h1 {
                font-size: 1.85rem;
            }

            .profile-frame-wrap {
                width: 260px;
            }

            .blob-backdrop {
                width: 290px;
                height: 350px;
            }

            .timeline-content {
                padding: 16px;
                width: calc(100% - 45px);
            }

            .timeline::before {
                left: 20px;
            }

            .timeline-dot {
                left: 20px;
                width: 16px;
                height: 16px;
                border-width: 3px;
            }
        }
    </style>
</head>
<body>
    <!-- Background decorations container to prevent horizontal scroll/whitespaces -->
    <div class="bg-decor-container">
        <div class="decor-blob-1"></div>
        <div class="decor-blob-2"></div>
    </div>

    <!-- SVG Clip Path for Heart Profile Image -->
    <svg width="0" height="0" style="position: absolute; pointer-events: none;">
        <defs>
            <clipPath id="heart-clip" clipPathUnits="objectBoundingBox">
                <path d="M 0.5,0.95 C 0.15,0.65 0,0.45 0,0.28 C 0,0.1 0.12,0.02 0.28,0.02 C 0.38,0.02 0.46,0.1 0.5,0.14 C 0.54,0.1 0.62,0.02 0.72,0.02 C 0.88,0.02 1,0.1 1,0.28 C 1,0.45 0.85,0.65 0.5,0.95 Z" />
            </clipPath>
        </defs>
    </svg>

    <!-- Navigation Bar -->
    <header id="navbar">
        <div class="container">
            <nav>
                <a href="#hero" class="logo">
                    <i class="fa-solid fa-gem"></i>
                    <span><?= !empty($name) ? htmlspecialchars($name) : 'Personal Website' ?></span>
                </a>
                
                <ul class="nav-links">
                    <li><a href="#hero">Home</a></li>
                    <?php if (!empty($experiences)): ?>
                        <li><a href="#experiences">Experiences</a></li>
                    <?php endif; ?>
                    <?php if (!empty($certificates)): ?>
                        <li><a href="#certificates">Certificates</a></li>
                    <?php endif; ?>
                    <?php if (!empty($portfolio)): ?>
                        <li><a href="#portfolio">Portfolio</a></li>
                    <?php endif; ?>
                </ul>

                <!-- Mobile Hamburger Toggle -->
                <button class="mobile-nav-toggle" onclick="toggleMobileNav()" aria-label="Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section id="hero">
        <div class="container">
            <div class="hero-layout">
                <div class="hero-text" data-aos="fade-right" data-aos-duration="1000">
                    <span class="badge">Personal Website</span>
                    
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
                            <span class="social-label">Connect with me:</span>
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
                    <div class="profile-frame-wrap">
                        <div class="profile-frame">
                            <?php if (!empty($photo) && file_exists(__DIR__ . '/files/' . $photo)): ?>
                                <img src="files/<?= htmlspecialchars($photo) ?>" alt="Portrait">
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
        </div>
    </section>

    <!-- EXPERIENCES SECTION -->
    <?php if (!empty($experiences)): ?>
        <section id="experiences">
            <div class="container">
                <div class="section-header" data-aos="fade-up" data-aos-duration="1000">
                    <h2>Work Experience</h2>
                    <p>Explore my professional career path and work history.</p>
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
                    <h2>Certificates & Awards</h2>
                    <p>Validation of my qualifications and professional achievements.</p>
                </div>
                
                <div class="cert-carousel-wrapper" data-aos="fade-up" data-aos-duration="1000">
                    <div class="cert-carousel-container">
                        <div class="cert-carousel-inner">
                            <?php foreach ($certificates as $cert): ?>
                                <div class="cert-card">
                                    <div>
                                        <div class="cert-preview-box" onclick="openDocModal('files/<?= htmlspecialchars($cert['file']) ?>', '<?= htmlspecialchars(addslashes($cert['name'])) ?>', '<?= htmlspecialchars(addslashes($cert['issuer'])) ?>')">
                                            <?php if (!empty($cert['file']) && file_exists(__DIR__ . '/files/' . $cert['file'])): 
                                                $ext = strtolower(pathinfo($cert['file'], PATHINFO_EXTENSION));
                                                if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                                                    <img src="files/<?= htmlspecialchars($cert['file']) ?>" alt="<?= htmlspecialchars($cert['name']) ?>">
                                                <?php elseif ($ext === 'pdf'): ?>
                                                    <iframe src="files/<?= htmlspecialchars($cert['file']) ?>#page=1&toolbar=0&navpanes=0&scrollbar=0" scrolling="no"></iframe>
                                                    <div class="iframe-overlay"></div>
                                                <?php else: ?>
                                                    <div class="no-preview"><i class="fa-solid fa-file-invoice"></i></div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <div class="no-preview"><i class="fa-solid fa-file-circle-exclamation"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="cert-info">
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
                                    </div>
                                    
                                    <?php if (!empty($cert['file']) && file_exists(__DIR__ . '/files/' . $cert['file'])): ?>
                                        <button class="btn-view-doc" onclick="openDocModal('files/<?= htmlspecialchars($cert['file']) ?>', '<?= htmlspecialchars(addslashes($cert['name'])) ?>', '<?= htmlspecialchars(addslashes($cert['issuer'])) ?>')">
                                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                                            <span>VIEW & ZOOM</span>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn-view-doc" style="background-color: var(--text-muted); cursor: not-allowed; box-shadow: none;" disabled>
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            <span>UNAVAILABLE</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="cert-carousel-controls">
                        <button class="cert-nav-btn prev" onclick="moveCertSlider(-1)" aria-label="Previous Slide">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <div class="cert-dots"></div>
                        <button class="cert-nav-btn next" onclick="moveCertSlider(1)" aria-label="Next Slide">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- PORTFOLIO SECTION -->
    <?php if (!empty($portfolio)): ?>
        <section id="portfolio">
            <div class="container">
                <div class="section-header" data-aos="fade-up" data-aos-duration="1000">
                    <h2>Featured Projects</h2>
                    <p>Explore some of the recent projects I've developed and worked on.</p>
                </div>
                
                <div class="portfolio-grid">
                    <?php 
                    $featured_projects = array_slice($portfolio, 0, 3);
                    foreach ($featured_projects as $proj): 
                    ?>
                        <div class="portfolio-card" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                            <div>
                                <div class="project-preview-box" onclick="openDocModal('files/<?= htmlspecialchars($proj['file']) ?>', '<?= htmlspecialchars(addslashes($proj['title'])) ?>', 'Project documentation')">
                                    <?php if (!empty($proj['file']) && file_exists(__DIR__ . '/files/' . $proj['file'])): 
                                        $ext = strtolower(pathinfo($proj['file'], PATHINFO_EXTENSION));
                                        if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                                            <img src="files/<?= htmlspecialchars($proj['file']) ?>" alt="<?= htmlspecialchars($proj['title']) ?>">
                                        <?php elseif ($ext === 'pdf'): ?>
                                            <iframe src="files/<?= htmlspecialchars($proj['file']) ?>#page=1&toolbar=0&navpanes=0&scrollbar=0" scrolling="no"></iframe>
                                            <div class="iframe-overlay"></div>
                                        <?php else: ?>
                                            <div class="no-preview"><i class="fa-solid fa-file-invoice"></i></div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="no-preview"><i class="fa-solid fa-file-circle-exclamation"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="portfolio-body">
                                    <h3><?= htmlspecialchars($proj['title']) ?></h3>
                                    <p><?= nl2br(htmlspecialchars($proj['description'] ?: '')) ?></p>
                                </div>
                            </div>
                            
                            <div class="portfolio-footer">
                                <?php if (!empty($proj['file']) && file_exists(__DIR__ . '/files/' . $proj['file'])): ?>
                                    <button class="btn-portfolio-action btn-portfolio-primary" onclick="openDocModal('files/<?= htmlspecialchars($proj['file']) ?>', '<?= htmlspecialchars(addslashes($proj['title'])) ?>', 'Project documentation')">
                                        <i class="fa-solid fa-file-invoice"></i>
                                        <span>Documentation</span>
                                    </button>
                                <?php endif; ?>
                                
                                <?php if (!empty($proj['link'])): ?>
                                    <a href="<?= htmlspecialchars($proj['link']) ?>" target="_blank" class="btn-portfolio-action btn-portfolio-outline">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        <span>Visit Link</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="display: flex; justify-content: center; margin-top: 50px;" data-aos="fade-up" data-aos-duration="1000">
                    <a href="projects.php" class="btn-portfolio-action btn-portfolio-primary" style="max-width: 250px; padding: 14px 35px; border-radius: 50px; font-size: 0.95rem; box-shadow: 0 4px 15px rgba(183, 110, 121, 0.25); text-decoration: none;">
                        <span>View All Projects</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <a href="#hero" class="logo">
                <i class="fa-solid fa-gem"></i>
                <span><?= !empty($name) ? htmlspecialchars($name) : 'Personal Website' ?></span>
            </a>
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($name) ?>. All Rights Reserved.</p>
            <p style="font-size: 0.8rem; opacity: 0.7;"><a href="admin/index.php">Administrator Panel</a>.</p>
        </div>
    </footer>

    <!-- POP-UP MODAL MULTI-FORMAT -->
    <div class="modal-overlay" id="documentModal" onclick="closeDocModal(event)">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div class="modal-title-container">
                    <h3 id="modalDocTitle">Document Preview</h3>
                    <p id="modalDocIssuer">Document Issuer</p>
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
            issuerElem.textContent = "Issuer: " + docIssuer;
            
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
                contentHtml = `<p style="color: var(--charcoal); font-weight: 500;">Direct preview is not supported for this file format.</p>`;
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

        // Mobile Nav Drawer Toggle
        function toggleMobileNav() {
            const navLinks = document.querySelector('.nav-links');
            const toggleIcon = document.querySelector('.mobile-nav-toggle i');
            navLinks.classList.toggle('active');
            
            if (navLinks.classList.contains('active')) {
                toggleIcon.className = 'fa-solid fa-xmark';
            } else {
                toggleIcon.className = 'fa-solid fa-bars';
            }
        }

        // Close mobile nav when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                const navLinks = document.querySelector('.nav-links');
                const toggleIcon = document.querySelector('.mobile-nav-toggle i');
                if (navLinks.classList.contains('active')) {
                    navLinks.classList.remove('active');
                    toggleIcon.className = 'fa-solid fa-bars';
                }
            });
        });

        // Certificates horizontal slider implementation
        let currentCertIndex = 0;
        
        function getCardsPerPage() {
            if (window.innerWidth < 768) return 1;
            if (window.innerWidth < 992) return 2;
            return 3;
        }
        
        function moveCertSlider(direction) {
            const inner = document.querySelector('.cert-carousel-inner');
            const cards = document.querySelectorAll('.cert-card');
            if (cards.length === 0) return;
            
            const cardsPerPage = getCardsPerPage();
            const maxIndex = Math.max(0, cards.length - cardsPerPage);
            
            currentCertIndex += direction;
            if (currentCertIndex < 0) currentCertIndex = 0;
            if (currentCertIndex > maxIndex) currentCertIndex = maxIndex;
            
            updateCertSlider();
        }
        
        function updateCertSlider() {
            const inner = document.querySelector('.cert-carousel-inner');
            const cards = document.querySelectorAll('.cert-card');
            if (cards.length === 0) return;
            
            const cardsPerPage = getCardsPerPage();
            const cardWidth = cards[0].getBoundingClientRect().width;
            const gap = 30; // Matches CSS gap
            const translateVal = currentCertIndex * (cardWidth + gap);
            
            inner.style.transform = `translateX(-${translateVal}px)`;
            
            // Update dots
            const dots = document.querySelectorAll('.cert-dot');
            dots.forEach((dot, i) => {
                if (i === currentCertIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
            
            // Update arrows styling and pointer events
            const prevBtn = document.querySelector('.cert-nav-btn.prev');
            const nextBtn = document.querySelector('.cert-nav-btn.next');
            
            if (prevBtn && nextBtn) {
                prevBtn.style.opacity = currentCertIndex === 0 ? '0.3' : '1';
                prevBtn.style.pointerEvents = currentCertIndex === 0 ? 'none' : 'auto';
                
                const maxIndex = Math.max(0, cards.length - cardsPerPage);
                nextBtn.style.opacity = currentCertIndex === maxIndex ? '0.3' : '1';
                nextBtn.style.pointerEvents = currentCertIndex === maxIndex ? 'none' : 'auto';
            }
        }
        
        function initCertSlider() {
            const container = document.querySelector('.cert-carousel-container');
            if (!container) return;
            
            const inner = document.querySelector('.cert-carousel-inner');
            const cards = document.querySelectorAll('.cert-card');
            const dotsContainer = document.querySelector('.cert-dots');
            if (!dotsContainer) return;
            
            dotsContainer.innerHTML = '';
            const cardsPerPage = getCardsPerPage();
            const maxIndex = Math.max(0, cards.length - cardsPerPage + 1);
            
            const controls = document.querySelector('.cert-carousel-controls');
            if (cards.length <= cardsPerPage) {
                if (controls) controls.style.display = 'none';
                inner.style.transform = 'translateX(0px)';
                return;
            } else {
                if (controls) controls.style.display = 'flex';
            }
            
            for (let i = 0; i < maxIndex; i++) {
                const dot = document.createElement('div');
                dot.className = 'cert-dot' + (i === 0 ? ' active' : '');
                dot.addEventListener('click', () => {
                    currentCertIndex = i;
                    updateCertSlider();
                });
                dotsContainer.appendChild(dot);
            }
            
            currentCertIndex = 0;
            updateCertSlider();
        }
        
        // Run initializer
        window.addEventListener('DOMContentLoaded', initCertSlider);
        window.addEventListener('resize', () => {
            initCertSlider();
        });
    </script>
</body>
</html>

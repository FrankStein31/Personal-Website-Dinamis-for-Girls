<?php
// projects.php - All Projects Portfolio Showcase Page
require_once __DIR__ . '/config.php';

$db_data = get_db_data();

$biodata = isset($db_data['biodata']) ? $db_data['biodata'] : [];
$portfolio = isset($db_data['portfolio']) ? $db_data['portfolio'] : [];
$socmed = isset($db_data['socmed']) ? $db_data['socmed'] : [];

$name = isset($biodata['name']) ? $biodata['name'] : '';
$role = isset($biodata['role']) ? $biodata['role'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Projects - <?= !empty($name) ? htmlspecialchars($name) : 'Personal Portfolio' ?></title>
    <!-- SEO Meta Tags -->
    <meta name="description" content="All projects portfolio showcase. Explore complete project details, attachment files, and visit links.">
    
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

        /* Floating Navbar */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 20px 0;
            transition: all 0.4s ease;
            background: rgba(255, 253, 249, 0.85);
            backdrop-filter: blur(15px);
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

        .mobile-nav-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--charcoal);
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* All Projects Section */
        #all-projects-section {
            padding: 150px 0 100px 0;
            min-height: 85vh;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .back-btn-container {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 30px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--rose-gold);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            color: var(--rose-gold-dark);
            transform: translateX(-5px);
        }

        .section-header h1 {
            font-family: var(--font-heading);
            font-size: 2.8rem;
            color: var(--charcoal);
            font-weight: 800;
            margin-bottom: 12px;
            position: relative;
            display: inline-block;
        }

        .section-header h1::after {
            content: '';
            display: block;
            width: 80px;
            height: 3px;
            background-color: var(--rose-gold);
            margin: 12px auto 0 auto;
            border-radius: 2px;
        }

        .section-header p {
            color: var(--text-muted);
            font-size: 1.05rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Projects Grid and Cards */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 35px;
        }

        .project-card {
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

        .project-card::before {
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

        .project-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(183, 110, 121, 0.14);
            border-color: rgba(183, 110, 121, 0.3);
        }

        .project-card:hover::before {
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

        .project-card:hover .project-preview-box img {
            transform: scale(1.05);
        }

        .project-info h3 {
            font-family: var(--font-heading);
            font-size: 1.35rem;
            color: var(--charcoal);
            margin-bottom: 10px;
            font-weight: 700;
            line-height: 1.4;
        }

        .project-info p {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.65;
            margin-bottom: 25px;
        }

        .project-footer {
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
            box-shadow: 0 4px 10px rgba(183, 110, 121, 0.15);
        }

        .btn-portfolio-primary:hover {
            background-color: var(--rose-gold-dark);
            box-shadow: 0 6px 15px rgba(183, 110, 121, 0.25);
            transform: translateY(-1px);
        }

        .btn-portfolio-outline {
            background-color: transparent;
            color: var(--rose-gold);
            border: 1px solid var(--rose-gold);
        }

        .btn-portfolio-outline:hover {
            background-color: var(--rose-gold);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(183, 110, 121, 0.15);
            transform: translateY(-1px);
        }

        /* POP-UP MODAL MULTI-FORMAT */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(44, 30, 33, 0.7);
            backdrop-filter: blur(8px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-box {
            background-color: var(--ivory-white);
            border-radius: 28px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 20px 50px rgba(44, 30, 33, 0.25);
            border: 1px solid rgba(183, 110, 121, 0.15);
            overflow: hidden;
            transform: scale(0.9);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.15);
        }

        .modal-overlay.active .modal-box {
            transform: scale(1);
        }

        .modal-header {
            padding: 20px 30px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(255, 252, 248, 0.8);
        }

        .modal-title-container h3 {
            font-family: var(--font-heading);
            font-size: 1.4rem;
            color: var(--charcoal);
            font-weight: 700;
            margin-bottom: 4px;
        }

        .modal-title-container p {
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 500;
        }

        .modal-close-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 2rem;
            cursor: pointer;
            transition: color 0.3s ease;
            line-height: 1;
        }

        .modal-close-btn:hover {
            color: var(--rose-gold);
        }

        .modal-body {
            padding: 30px;
            max-height: 70vh;
            overflow-y: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #FFFDFB;
        }

        .modal-body img {
            max-width: 100%;
            max-height: 60vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(183, 110, 121, 0.1);
        }

        .modal-body iframe {
            width: 100%;
            height: 60vh;
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(183, 110, 121, 0.1);
        }

        /* FOOTER */
        footer {
            background-color: var(--charcoal);
            color: var(--ivory-white);
            padding: 60px 0;
            text-align: center;
            border-top: 2px solid var(--rose-gold);
        }

        footer .logo {
            color: var(--ivory-white);
            justify-content: center;
            margin-bottom: 15px;
            font-size: 1.8rem;
        }

        footer p {
            font-size: 0.95rem;
            opacity: 0.8;
            margin-bottom: 10px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991px) {
            .nav-links {
                display: none;
            }
            .mobile-nav-toggle {
                display: block;
            }
            .section-header h1 {
                font-size: 2.3rem;
            }
        }

        @media (max-width: 767px) {
            .projects-grid {
                grid-template-columns: 1fr;
            }
            .project-card {
                padding: 20px;
            }
            .project-preview-box {
                height: 180px;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <header id="navbar">
        <div class="container">
            <nav>
                <a href="index.php" class="logo">
                    <i class="fa-solid fa-gem"></i>
                    <span><?= !empty($name) ? htmlspecialchars($name) : 'Personal Website' ?></span>
                </a>
                
                <ul class="nav-links">
                    <li><a href="index.php#hero">Home</a></li>
                    <li><a href="index.php#experiences">Experiences</a></li>
                    <li><a href="index.php#certificates">Certificates</a></li>
                    <li><a href="index.php#portfolio">Portfolio</a></li>
                </ul>

                <!-- Mobile Hamburger Toggle -->
                <button class="mobile-nav-toggle" onclick="window.location.href='index.php'" aria-label="Home">
                    <i class="fa-solid fa-house"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- ALL PROJECTS SHOWCASE -->
    <section id="all-projects-section">
        <div class="container">
            <div class="back-btn-container" data-aos="fade-right" data-aos-duration="1000">
                <a href="index.php#portfolio" class="btn-back">
                    <i class="fa-solid fa-arrow-left-long"></i>
                    <span>Back to Home</span>
                </a>
            </div>

            <div class="section-header" data-aos="fade-up" data-aos-duration="1000">
                <h1>Featured Projects Showcase</h1>
                <p>A comprehensive documentation and overview of all projects, tools, and platforms I have built.</p>
            </div>
            
            <?php if (empty($portfolio)): ?>
                <div style="text-align: center; padding: 50px 0;" data-aos="fade-up" data-aos-duration="1000">
                    <i class="fa-solid fa-folder-open" style="font-size: 4rem; color: var(--rose-gold-light); margin-bottom: 20px;"></i>
                    <p style="color: var(--text-muted); font-size: 1.1rem;">No projects uploaded yet.</p>
                </div>
            <?php else: ?>
                <div class="projects-grid">
                    <?php foreach ($portfolio as $proj): ?>
                        <div class="project-card" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
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
                                
                                <div class="project-info">
                                    <h3><?= htmlspecialchars($proj['title']) ?></h3>
                                    <p><?= nl2br(htmlspecialchars($proj['description'] ?: '')) ?></p>
                                </div>
                            </div>
                            
                            <div class="project-footer">
                                <?php if (!empty($proj['file']) && file_exists(__DIR__ . '/files/' . $proj['file'])): ?>
                                    <button class="btn-portfolio-action btn-portfolio-primary" onclick="openDocModal('files/<?= htmlspecialchars($proj['file']) ?>', '<?= htmlspecialchars(addslashes($proj['title'])) ?>', 'Project documentation')">
                                        <i class="fa-solid fa-eye"></i>
                                        <span>View Document</span>
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
            <?php endif; ?>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <a href="index.php" class="logo">
                <i class="fa-solid fa-gem"></i>
                <span><?= !empty($name) ? htmlspecialchars($name) : 'Personal Website' ?></span>
            </a>
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($name) ?>. All Rights Reserved.</p>
            <p style="font-size: 0.8rem; opacity: 0.7;"><a href="admin/index.php" style="color: var(--ivory-white); text-decoration: underline;">Administrator Panel</a>.</p>
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
            offset: 80
        });

        // Pop-up Modal Multi-format Detection and Rendering
        function openDocModal(fileUrl, docTitle, docIssuer) {
            const modal = document.getElementById('documentModal');
            const titleElem = document.getElementById('modalDocTitle');
            const issuerElem = document.getElementById('modalDocIssuer');
            const bodyElem = document.getElementById('modalDocBody');
            
            titleElem.textContent = docTitle;
            issuerElem.textContent = docIssuer;
            
            // Get file extension
            const extension = fileUrl.split('.').pop().toLowerCase();
            
            let contentHtml = '';
            
            if (extension === 'pdf') {
                contentHtml = `<iframe src="${fileUrl}" title="${docTitle}" type="application/pdf"></iframe>`;
            } else if (['jpg', 'jpeg', 'png'].includes(extension)) {
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
    </script>
</body>
</html>

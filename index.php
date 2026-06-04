<?php
require_once 'functions.php';
$db = readDB();

$biodata = $db['biodata'] ?? [];
$experiences = $db['experiences'] ?? [];
$certificates = $db['certificates'] ?? [];
$social_media = $db['social_media'] ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($biodata['name'] ?: 'Portfolio') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rosegold: '#B76E79',
                        softpink: '#FFD1DC',
                        blush: '#F3CFC6',
                        darkpink: '#9A5B65'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #FFF5F6; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="text-gray-800 antialiased font-sans transition-all duration-300">

    <!-- Header / Hero Section -->
    <header class="relative overflow-hidden pt-20 pb-16 lg:pt-32 lg:pb-24">
        <div class="absolute inset-0 z-0">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-softpink rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute top-32 -left-24 w-72 h-72 bg-blush rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10 flex flex-col md:flex-row items-center justify-center gap-10">
            <?php if (!empty($biodata['photo'])): ?>
            <div class="w-48 h-48 md:w-64 md:h-64 flex-shrink-0">
                <img src="files/<?= htmlspecialchars($biodata['photo']) ?>" alt="Profile" class="w-full h-full object-cover rounded-full border-4 border-white shadow-xl shadow-rosegold/20 transition-transform hover:scale-105 duration-300">
            </div>
            <?php endif; ?>
            
            <div class="text-center md:text-left max-w-lg">
                <?php if (!empty($biodata['name'])): ?>
                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-darkpink mb-2"><?= htmlspecialchars($biodata['name']) ?></h1>
                <?php endif; ?>
                
                <?php if (!empty($biodata['role'])): ?>
                    <h2 class="text-xl md:text-2xl text-rosegold font-medium mb-4"><?= htmlspecialchars($biodata['role']) ?></h2>
                <?php endif; ?>
                
                <?php if (!empty($biodata['description'])): ?>
                    <p class="text-gray-600 leading-relaxed mb-6"><?= nl2br(htmlspecialchars($biodata['description'])) ?></p>
                <?php endif; ?>

                <?php if (!empty($social_media)): ?>
                <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                    <?php foreach ($social_media as $socmed): ?>
                        <?php if (!empty($socmed['link'])): ?>
                        <a href="<?= htmlspecialchars($socmed['link']) ?>" target="_blank" class="w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center text-rosegold hover:bg-rosegold hover:text-white transition-colors duration-300">
                            <?php 
                                $iconClass = 'fa-link';
                                $platform = strtolower($socmed['platform'] ?? '');
                                if (strpos($platform, 'instagram') !== false) $iconClass = 'fa-instagram';
                                elseif (strpos($platform, 'linkedin') !== false) $iconClass = 'fa-linkedin-in';
                                elseif (strpos($platform, 'github') !== false) $iconClass = 'fa-github';
                                elseif (strpos($platform, 'twitter') !== false || strpos($platform, 'x') !== false) $iconClass = 'fa-x-twitter';
                                elseif (strpos($platform, 'facebook') !== false) $iconClass = 'fa-facebook-f';
                            ?>
                            <i class="fa-brands <?= $iconClass ?> text-lg"></i>
                        </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Experience Section -->
    <?php if (!empty($experiences)): ?>
    <section class="py-16 bg-white/50 relative z-10">
        <div class="container mx-auto px-6 max-w-4xl">
            <h3 class="text-3xl font-serif font-bold text-center text-darkpink mb-12">Pengalaman</h3>
            <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-rosegold/50 before:to-transparent">
                
                <?php foreach ($experiences as $index => $exp): ?>
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-softpink text-darkpink shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                        <i class="fa-solid fa-briefcase text-sm"></i>
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] glass p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-2">
                            <h4 class="text-lg font-bold text-darkpink"><?= htmlspecialchars($exp['position'] ?? '') ?></h4>
                            <span class="text-sm font-medium text-rosegold bg-white px-3 py-1 rounded-full w-max mt-2 md:mt-0"><?= htmlspecialchars($exp['year'] ?? '') ?></span>
                        </div>
                        <h5 class="text-md font-semibold text-gray-700 mb-2"><?= htmlspecialchars($exp['company'] ?? '') ?></h5>
                        <p class="text-gray-600 text-sm"><?= nl2br(htmlspecialchars($exp['description'] ?? '')) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Certificates Section -->
    <?php if (!empty($certificates)): ?>
    <section class="py-16 relative z-10">
        <div class="container mx-auto px-6 max-w-5xl">
            <h3 class="text-3xl font-serif font-bold text-center text-darkpink mb-12">Sertifikat</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($certificates as $cert): ?>
                <div class="glass rounded-2xl overflow-hidden group hover:-translate-y-1 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-rosegold/20 flex flex-col">
                    <?php if (!empty($cert['file'])): ?>
                        <?php $ext = strtolower(pathinfo($cert['file'], PATHINFO_EXTENSION)); ?>
                        <?php if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
                            <img src="files/<?= htmlspecialchars($cert['file']) ?>" alt="<?= htmlspecialchars($cert['name'] ?? 'Sertifikat') ?>" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <div class="w-full h-48 bg-softpink/30 flex flex-col items-center justify-center text-rosegold group-hover:bg-softpink/50 transition-colors">
                                <i class="fa-solid fa-file-pdf text-5xl mb-2"></i>
                                <span class="text-sm font-medium">Dokumen PDF</span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="p-6 flex-1 flex flex-col">
                        <h4 class="font-bold text-lg text-darkpink mb-1"><?= htmlspecialchars($cert['name'] ?? '') ?></h4>
                        <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars($cert['issuer'] ?? '') ?></p>
                        <?php if (!empty($cert['file'])): ?>
                        <div class="mt-auto pt-4 border-t border-gray-100">
                            <a href="files/<?= htmlspecialchars($cert['file']) ?>" target="_blank" class="inline-flex items-center gap-2 text-sm text-rosegold font-semibold hover:text-darkpink transition-colors">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Dokumen
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-white/80 border-t border-softpink py-8 mt-10">
        <div class="container mx-auto px-6 text-center">
            <p class="text-gray-500 text-sm">
                &copy; <?= date('Y') ?> <?= htmlspecialchars($biodata['name'] ?? 'Portfolio') ?>. All rights reserved.
            </p>
            <a href="admin/" class="inline-block mt-2 text-xs text-rosegold/50 hover:text-rosegold transition-colors">Admin Login</a>
        </div>
    </footer>

</body>
</html>

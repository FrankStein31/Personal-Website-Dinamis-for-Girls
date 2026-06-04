<?php
require_once '../functions.php';

// Cek autentikasi
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$db = readDB();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Update Biodata
    if ($action === 'update_biodata') {
        $db['biodata']['name'] = $_POST['name'] ?? '';
        $db['biodata']['role'] = $_POST['role'] ?? '';
        $db['biodata']['description'] = $_POST['description'] ?? '';

        if (!empty($_FILES['photo']['name'])) {
            $upload = uploadFile($_FILES['photo'], ['jpg', 'jpeg', 'png']);
            if ($upload['success']) {
                // Hapus foto lama jika ada
                if (!empty($db['biodata']['photo']) && file_exists(UPLOAD_DIR . $db['biodata']['photo'])) {
                    unlink(UPLOAD_DIR . $db['biodata']['photo']);
                }
                $db['biodata']['photo'] = $upload['filename'];
            } else {
                $message = $upload['message'];
            }
        }
        writeDB($db);
        if (!$message) $message = "Biodata berhasil diupdate.";
    }
    
    // Tambah Pengalaman
    elseif ($action === 'add_experience') {
        $exp = [
            'id' => uniqid(),
            'company' => $_POST['company'] ?? '',
            'position' => $_POST['position'] ?? '',
            'year' => $_POST['year'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];
        $db['experiences'][] = $exp;
        writeDB($db);
        $message = "Pengalaman berhasil ditambahkan.";
    }
    
    // Hapus Pengalaman
    elseif ($action === 'delete_experience') {
        $id = $_POST['id'] ?? '';
        $db['experiences'] = array_filter($db['experiences'], function($e) use ($id) {
            return ($e['id'] ?? '') !== $id;
        });
        $db['experiences'] = array_values($db['experiences']); // reindex
        writeDB($db);
        $message = "Pengalaman berhasil dihapus.";
    }

    // Tambah Sertifikat
    elseif ($action === 'add_certificate') {
        if (!empty($_FILES['cert_file']['name'])) {
            $upload = uploadFile($_FILES['cert_file'], ['jpg', 'jpeg', 'png', 'pdf']);
            if ($upload['success']) {
                $cert = [
                    'id' => uniqid(),
                    'name' => $_POST['name'] ?? '',
                    'issuer' => $_POST['issuer'] ?? '',
                    'file' => $upload['filename']
                ];
                $db['certificates'][] = $cert;
                writeDB($db);
                $message = "Sertifikat berhasil ditambahkan.";
            } else {
                $message = $upload['message'];
            }
        } else {
            $message = "Harap unggah file sertifikat.";
        }
    }
    
    // Hapus Sertifikat
    elseif ($action === 'delete_certificate') {
        $id = $_POST['id'] ?? '';
        foreach ($db['certificates'] as $key => $cert) {
            if (($cert['id'] ?? '') === $id) {
                if (!empty($cert['file']) && file_exists(UPLOAD_DIR . $cert['file'])) {
                    unlink(UPLOAD_DIR . $cert['file']);
                }
                unset($db['certificates'][$key]);
            }
        }
        $db['certificates'] = array_values($db['certificates']);
        writeDB($db);
        $message = "Sertifikat berhasil dihapus.";
    }

    // Tambah Social Media
    elseif ($action === 'add_social') {
        $soc = [
            'id' => uniqid(),
            'platform' => $_POST['platform'] ?? '',
            'link' => $_POST['link'] ?? ''
        ];
        $db['social_media'][] = $soc;
        writeDB($db);
        $message = "Social media berhasil ditambahkan.";
    }
    
    // Hapus Social Media
    elseif ($action === 'delete_social') {
        $id = $_POST['id'] ?? '';
        $db['social_media'] = array_filter($db['social_media'], function($s) use ($id) {
            return ($s['id'] ?? '') !== $id;
        });
        $db['social_media'] = array_values($db['social_media']);
        writeDB($db);
        $message = "Social media berhasil dihapus.";
    }

    // Update Password
    elseif ($action === 'update_password') {
        $new_pass = $_POST['new_password'] ?? '';
        if (!empty($new_pass)) {
            $db['admin']['password'] = $new_pass;
            writeDB($db);
            $message = "Password berhasil diubah.";
        }
    }

    // Refresh DB
    $db = readDB();
}

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
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { rosegold: '#B76E79', softpink: '#FFD1DC' }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { background-color: #f9fafb; } </style>
</head>
<body class="text-gray-800 antialiased font-sans flex flex-col md:flex-row min-h-screen">

    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-white border-r border-gray-200 flex-shrink-0">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-rosegold mb-2">Panel Admin</h2>
            <p class="text-xs text-gray-500">Kelola Portfolio Anda</p>
        </div>
        <nav class="mt-2 flex flex-col px-4 gap-1">
            <a href="#biodata" class="px-4 py-2.5 rounded-lg text-gray-600 hover:bg-softpink/30 hover:text-rosegold transition-colors font-medium"><i class="fa-solid fa-user w-6"></i> Biodata</a>
            <a href="#experiences" class="px-4 py-2.5 rounded-lg text-gray-600 hover:bg-softpink/30 hover:text-rosegold transition-colors font-medium"><i class="fa-solid fa-briefcase w-6"></i> Pengalaman</a>
            <a href="#certificates" class="px-4 py-2.5 rounded-lg text-gray-600 hover:bg-softpink/30 hover:text-rosegold transition-colors font-medium"><i class="fa-solid fa-certificate w-6"></i> Sertifikat</a>
            <a href="#socials" class="px-4 py-2.5 rounded-lg text-gray-600 hover:bg-softpink/30 hover:text-rosegold transition-colors font-medium"><i class="fa-solid fa-hashtag w-6"></i> Social Media</a>
            <a href="#settings" class="px-4 py-2.5 rounded-lg text-gray-600 hover:bg-softpink/30 hover:text-rosegold transition-colors font-medium"><i class="fa-solid fa-cog w-6"></i> Pengaturan</a>
            <hr class="my-4 border-gray-100">
            <a href="../" target="_blank" class="px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors font-medium"><i class="fa-solid fa-globe w-6"></i> Lihat Web</a>
            <a href="logout.php" class="px-4 py-2.5 rounded-lg text-red-600 hover:bg-red-50 transition-colors font-medium"><i class="fa-solid fa-sign-out-alt w-6"></i> Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
        
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 shadow-sm">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Biodata -->
        <section id="biodata" class="mb-12 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">Biodata Diri</h3>
            </div>
            <div class="p-6">
                <form action="" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-2xl">
                    <input type="hidden" name="action" value="update_biodata">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($biodata['name'] ?? '') ?>" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-rosegold focus:ring focus:ring-rosegold/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan / Role</label>
                            <input type="text" name="role" value="<?= htmlspecialchars($biodata['role'] ?? '') ?>" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-rosegold focus:ring focus:ring-rosegold/20 outline-none">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Diri</label>
                        <textarea name="description" rows="4" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-rosegold focus:ring focus:ring-rosegold/20 outline-none"><?= htmlspecialchars($biodata['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil (Kosongkan jika tidak ingin mengubah)</label>
                        <?php if (!empty($biodata['photo'])): ?>
                            <img src="../files/<?= htmlspecialchars($biodata['photo']) ?>" class="w-20 h-20 object-cover rounded-xl mb-2 shadow-sm">
                        <?php endif; ?>
                        <input type="file" name="photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-softpink/30 file:text-rosegold hover:file:bg-softpink/50">
                    </div>
                    
                    <button type="submit" class="bg-rosegold hover:bg-[#9A5B65] text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm">Simpan Biodata</button>
                </form>
            </div>
        </section>

        <!-- Experiences -->
        <section id="experiences" class="mb-12 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Pengalaman Kerja</h3>
            </div>
            <div class="p-6">
                <!-- List -->
                <div class="mb-8 space-y-4">
                    <?php if (empty($experiences)): ?>
                        <p class="text-gray-500 text-sm">Belum ada data pengalaman.</p>
                    <?php else: ?>
                        <?php foreach ($experiences as $exp): ?>
                            <div class="flex items-start justify-between p-4 border border-gray-100 rounded-xl bg-gray-50/30">
                                <div>
                                    <h4 class="font-bold text-gray-800"><?= htmlspecialchars($exp['position'] ?? '') ?> di <?= htmlspecialchars($exp['company'] ?? '') ?></h4>
                                    <span class="text-sm text-rosegold font-medium inline-block mb-1"><?= htmlspecialchars($exp['year'] ?? '') ?></span>
                                    <p class="text-sm text-gray-600"><?= nl2br(htmlspecialchars($exp['description'] ?? '')) ?></p>
                                </div>
                                <form action="" method="POST" onsubmit="return confirm('Hapus pengalaman ini?');">
                                    <input type="hidden" name="action" value="delete_experience">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($exp['id'] ?? '') ?>">
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-2"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Add Form -->
                <div class="border-t border-gray-100 pt-6">
                    <h4 class="font-bold text-gray-700 mb-4 text-sm uppercase tracking-wider">Tambah Pengalaman Baru</h4>
                    <form action="" method="POST" class="space-y-4 max-w-2xl">
                        <input type="hidden" name="action" value="add_experience">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Perusahaan</label>
                                <input type="text" name="company" required class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-rosegold focus:ring focus:ring-rosegold/20 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Posisi</label>
                                <input type="text" name="position" required class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-rosegold focus:ring focus:ring-rosegold/20 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun (Contoh: 2021 - 2023)</label>
                            <input type="text" name="year" required class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-rosegold focus:ring focus:ring-rosegold/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-rosegold focus:ring focus:ring-rosegold/20 outline-none"></textarea>
                        </div>
                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm">Tambah Pengalaman</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Certificates -->
        <section id="certificates" class="mb-12 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">Sertifikat</h3>
            </div>
            <div class="p-6">
                <!-- List -->
                <div class="mb-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    <?php if (empty($certificates)): ?>
                        <p class="text-gray-500 text-sm col-span-full">Belum ada data sertifikat.</p>
                    <?php else: ?>
                        <?php foreach ($certificates as $cert): ?>
                            <div class="border border-gray-100 rounded-xl p-4 flex gap-4 bg-gray-50/30 items-center">
                                <div class="w-12 h-12 rounded-lg bg-softpink/30 flex items-center justify-center text-rosegold shrink-0">
                                    <i class="fa-solid fa-file-alt text-xl"></i>
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <h4 class="font-bold text-gray-800 text-sm truncate"><?= htmlspecialchars($cert['name'] ?? '') ?></h4>
                                    <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($cert['issuer'] ?? '') ?></p>
                                    <a href="../files/<?= htmlspecialchars($cert['file'] ?? '') ?>" target="_blank" class="text-xs text-rosegold hover:underline mt-1 inline-block">Lihat File</a>
                                </div>
                                <form action="" method="POST" onsubmit="return confirm('Hapus sertifikat ini?');">
                                    <input type="hidden" name="action" value="delete_certificate">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($cert['id'] ?? '') ?>">
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-2 bg-white rounded-md shadow-sm border border-gray-100"><i class="fa-solid fa-trash text-sm"></i></button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Add Form -->
                <div class="border-t border-gray-100 pt-6">
                    <h4 class="font-bold text-gray-700 mb-4 text-sm uppercase tracking-wider">Tambah Sertifikat Baru</h4>
                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-2xl">
                        <input type="hidden" name="action" value="add_certificate">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sertifikat</label>
                                <input type="text" name="name" required class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-rosegold focus:ring focus:ring-rosegold/20 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Penerbit</label>
                                <input type="text" name="issuer" required class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-rosegold focus:ring focus:ring-rosegold/20 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">File (JPG/PNG/PDF, Max 2MB)</label>
                            <input type="file" name="cert_file" required accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-softpink/30 file:text-rosegold hover:file:bg-softpink/50">
                        </div>
                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm">Upload Sertifikat</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Social Media -->
        <section id="socials" class="mb-12 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">Social Media</h3>
            </div>
            <div class="p-6">
                <!-- List -->
                <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <?php if (empty($social_media)): ?>
                        <p class="text-gray-500 text-sm col-span-full">Belum ada data social media.</p>
                    <?php else: ?>
                        <?php foreach ($social_media as $soc): ?>
                            <div class="border border-gray-100 rounded-xl p-4 flex gap-4 bg-gray-50/30 items-center justify-between">
                                <div class="overflow-hidden">
                                    <h4 class="font-bold text-gray-800 text-sm capitalize"><?= htmlspecialchars($soc['platform'] ?? '') ?></h4>
                                    <a href="<?= htmlspecialchars($soc['link'] ?? '') ?>" target="_blank" class="text-xs text-gray-500 truncate block hover:text-rosegold"><?= htmlspecialchars($soc['link'] ?? '') ?></a>
                                </div>
                                <form action="" method="POST" onsubmit="return confirm('Hapus akun ini?');">
                                    <input type="hidden" name="action" value="delete_social">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($soc['id'] ?? '') ?>">
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-2"><i class="fa-solid fa-trash text-sm"></i></button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Add Form -->
                <div class="border-t border-gray-100 pt-6">
                    <h4 class="font-bold text-gray-700 mb-4 text-sm uppercase tracking-wider">Tambah Akun Baru</h4>
                    <form action="" method="POST" class="space-y-4 max-w-2xl flex flex-col md:flex-row gap-4 items-end">
                        <input type="hidden" name="action" value="add_social">
                        <div class="w-full md:w-1/3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Platform</label>
                            <select name="platform" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-rosegold focus:ring focus:ring-rosegold/20 outline-none">
                                <option value="Instagram">Instagram</option>
                                <option value="LinkedIn">LinkedIn</option>
                                <option value="GitHub">GitHub</option>
                                <option value="Twitter">Twitter / X</option>
                                <option value="Facebook">Facebook</option>
                            </select>
                        </div>
                        <div class="w-full md:flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL Profil</label>
                            <input type="url" name="link" required placeholder="https://" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-rosegold focus:ring focus:ring-rosegold/20 outline-none">
                        </div>
                        <div class="w-full md:w-auto pb-0 mb-0 space-y-0 shrink-0">
                            <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Settings -->
        <section id="settings" class="mb-12 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-800">Ubah Password Admin</h3>
            </div>
            <div class="p-6">
                <form action="" method="POST" class="max-w-md space-y-4">
                    <input type="hidden" name="action" value="update_password">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="new_password" required minlength="4" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-rosegold focus:ring focus:ring-rosegold/20 outline-none" placeholder="Masukkan password baru">
                    </div>
                    <button type="submit" class="bg-rosegold hover:bg-[#9A5B65] text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm">Update Password</button>
                </form>
            </div>
        </section>

    </main>

</body>
</html>

<?php
require_once '../functions.php';

// Redirect to dashboard if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $db = readDB();
    
    $admin_password = $db['admin']['password'] ?? 'password';
    
    if ($password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = 'Password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rosegold: '#B76E79',
                        softpink: '#FFD1DC'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#FFF5F6] min-h-screen flex items-center justify-center p-6">
    <div class="bg-white/80 backdrop-blur-md p-8 rounded-3xl shadow-xl shadow-rosegold/10 w-full max-w-md border border-white">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Admin Panel</h1>
            <p class="text-gray-500">Silakan login untuk mengelola portfolio</p>
        </div>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" required 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-rosegold focus:ring focus:ring-rosegold/20 transition-all outline-none"
                       placeholder="Masukkan password admin">
            </div>
            
            <button type="submit" class="w-full bg-rosegold hover:bg-[#9A5B65] text-white font-semibold py-3 px-4 rounded-xl transition-colors duration-300 shadow-md shadow-rosegold/30">
                Login ke Dashboard
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="../" class="text-sm text-gray-500 hover:text-rosegold transition-colors">&larr; Kembali ke Website</a>
        </div>
    </div>
</body>
</html>

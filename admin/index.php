<?php
// index.php - Admin Login Page
require_once __DIR__ . '/../config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    if (empty($password)) {
        $error = 'Password is required!';
    } else {
        $db_data = get_db_data();
        $hashed_password = isset($db_data['settings']['password']) ? $db_data['settings']['password'] : '';
        
        if (!empty($hashed_password) && password_verify($password, $hashed_password)) {
            $_SESSION['admin_logged_in'] = true;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = 'Incorrect password! Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - Portfolio</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #B76E79; /* Rose Gold */
            --primary-hover: #A05C66;
            --dark: #2C1E21;
            --light-bg: #FAF6F6; /* Ivory Soft Pink */
            --card-bg: rgba(255, 255, 255, 0.85);
            --border: #F3E5E7;
            --text-main: #332225;
            --text-muted: #8A7276;
            --danger: #D96B6B;
            --radius-md: 12px;
            --radius-lg: 20px;
            --font-heading: 'Playfair Display', serif;
            --font-body: 'Poppins', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            background: radial-gradient(circle at 10% 20%, rgba(254, 237, 238, 0.7) 0%, rgba(252, 240, 241, 0.7) 90%), 
                        linear-gradient(135deg, #FFFDF9 0%, #F5E5E8 100%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Abstract decorative shapes */
        .shape-1 {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(244, 194, 194, 0.3) 0%, rgba(229, 178, 178, 0.3) 100%);
            top: -100px;
            right: -100px;
            filter: blur(50px);
            z-index: 1;
        }

        .shape-2 {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(183, 110, 121, 0.1) 0%, rgba(212, 165, 165, 0.1) 100%);
            bottom: -150px;
            left: -150px;
            filter: blur(80px);
            z-index: 1;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: var(--radius-lg);
            padding: 45px 40px;
            box-shadow: 0 15px 35px rgba(183, 110, 121, 0.08), 
                        0 5px 15px rgba(0, 0, 0, 0.02);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 10;
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background-color: #FFF0F1;
            border-radius: 50%;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 20px auto;
            border: 1px solid var(--border);
            box-shadow: 0 4px 10px rgba(183, 110, 121, 0.1);
        }

        .login-header h1 {
            font-family: var(--font-heading);
            font-size: 1.85rem;
            color: var(--dark);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        .alert-error {
            background-color: #FFF0F0;
            color: var(--danger);
            border: 1px solid #FCD4D4;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 46px;
            font-family: var(--font-body);
            font-size: 0.95rem;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            background-color: rgba(250, 246, 246, 0.5);
            color: var(--dark);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background-color: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(183, 110, 121, 0.1);
        }

        .form-control:focus + .input-icon {
            color: var(--primary);
        }

        .btn-submit {
            width: 100%;
            background-color: var(--primary);
            color: #FFFFFF;
            padding: 14px;
            border: none;
            border-radius: var(--radius-md);
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 5px 15px rgba(183, 110, 121, 0.25);
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
            box-shadow: 0 8px 20px rgba(183, 110, 121, 0.35);
            transform: translateY(-1px);
        }

        .back-to-site {
            text-align: center;
            margin-top: 25px;
        }

        .back-to-site a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-to-site a:hover {
            color: var(--primary);
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
                margin: 10px;
            }
            .login-header h1 {
                font-size: 1.5rem;
            }
            .login-header {
                margin-bottom: 25px;
            }
            .logo-icon {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="shape-1"></div>
    <div class="shape-2"></div>

    <div class="login-container">
        <div class="login-header">
            <div class="logo-icon">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1>Admin Login</h1>
            <p>Enter the password to access the admin panel</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required autofocus>
                    <i class="fa-solid fa-key input-icon"></i>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <span>Login</span>
                <i class="fa-solid fa-right-to-bracket"></i>
            </button>
        </form>

        <div class="back-to-site">
            <a href="../index.php">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Website</span>
            </a>
        </div>
    </div>

</body>
</html>

<?php
session_start();
include "config/db.php";
$message = "";
if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
        $_SESSION['user'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Username ose password gabim!";
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — NovaNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0f1117;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-grid {
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(26,122,74,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(26,122,74,0.06) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 0;
        }
        .bg-glow {
            position: fixed;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(26,122,74,0.12) 0%, transparent 70%);
            top: -80px; left: 50%;
            transform: translateX(-50%);
            z-index: 0; pointer-events: none;
        }

        .login-wrap {
            position: relative; z-index: 5;
            width: 100%; max-width: 420px;
            padding: 1.5rem;
        }

        /* Logo */
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo-icon {
            width: 52px; height: 52px; border-radius: 14px;
            background: linear-gradient(135deg, #1a7a4a, #22c55e);
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: #fff;
            margin: 0 auto 12px;
            box-shadow: 0 4px 20px rgba(26,122,74,0.35);
        }
        .logo-name {
            font-size: 1.4rem; font-weight: 700; color: #fff;
        }
        .logo-name span { color: #4ade80; }
        .logo-sub {
            font-size: 13px; color: rgba(255,255,255,0.35);
            margin-top: 4px;
        }

        /* Card */
        .login-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 18px;
            padding: 2rem;
            backdrop-filter: blur(10px);
        }

        .card-title {
            font-size: 1.1rem; font-weight: 600;
            color: #fff; margin-bottom: 4px;
        }
        .card-sub {
            font-size: 13px; color: rgba(255,255,255,0.35);
            margin-bottom: 1.75rem;
        }

        /* Alert */
        .alert-error {
            display: flex; align-items: center; gap: 8px;
            background: rgba(220,38,38,0.12);
            border: 1px solid rgba(220,38,38,0.3);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px; color: #f87171;
            margin-bottom: 1.25rem;
        }

        /* Fields */
        .field { margin-bottom: 1rem; }
        .field label {
            display: block;
            font-size: 12px; font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase; letter-spacing: 0.05em;
            margin-bottom: 6px;
        }
        .input-wrap { position: relative; }
        .input-wrap i {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.25); font-size: 17px;
            pointer-events: none;
        }
        .input-wrap input {
            width: 100%;
            padding: 11px 12px 11px 38px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 9px;
            font-size: 14px; color: #fff;
            outline: none;
            transition: border 0.15s, background 0.15s;
        }
        .input-wrap input::placeholder { color: rgba(255,255,255,0.2); }
        .input-wrap input:focus {
            border-color: #1a7a4a;
            background: rgba(26,122,74,0.08);
            box-shadow: 0 0 0 3px rgba(26,122,74,0.12);
        }

        /* Button */
        .btn-login {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 12px;
            background: linear-gradient(135deg, #1a7a4a, #22c55e);
            color: #fff; font-size: 15px; font-weight: 600;
            border: none; border-radius: 9px;
            cursor: pointer; margin-top: 1.5rem;
            box-shadow: 0 4px 16px rgba(26,122,74,0.3);
            transition: all 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(26,122,74,0.4);
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 12px; color: rgba(255,255,255,0.2);
        }
        .login-footer a {
            color: rgba(255,255,255,0.3);
            text-decoration: none;
        }
        .login-footer a:hover { color: #4ade80; }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-glow"></div>

<div class="login-wrap">

    <!-- Logo -->
    <div class="login-logo">
        <div class="logo-icon">
            <i class="ti ti-globe"></i>
        </div>
        <div class="logo-name">Nova<span>Net</span></div>
        <div class="logo-sub">ISP Management System</div>
    </div>

    <!-- Card -->
    <div class="login-card">
        <div class="card-title">Mirësevini përsëri</div>
        <div class="card-sub">Hyni në llogarinë tuaj për të vazhduar</div>

        <?php if($message): ?>
        <div class="alert-error">
            <i class="ti ti-alert-circle"></i>
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="login.php">

            <div class="field">
                <label>Username</label>
                <div class="input-wrap">
                    <i class="ti ti-user"></i>
                    <input type="text" name="username"
                           placeholder="Shkruaj username-in"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                           autocomplete="username" required>
                </div>
            </div>

            <div class="field">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="ti ti-lock"></i>
                    <input type="password" name="password"
                           placeholder="Shkruaj passwordin"
                           autocomplete="current-password" required>
                </div>
            </div>

            <button type="submit" name="login" class="btn-login">
                <i class="ti ti-login"></i> Hyr në sistem
            </button>

        </form>
    </div>

    <div class="login-footer">
        <a href="index.php"><i class="ti ti-arrow-left"></i> Kthehu në faqen kryesore</a>
    </div>

</div>

<script src="assets/js/script.js"></script>
</body>
</html>
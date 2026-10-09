<?php
require_once "config/database.php";
require_once "includes/auth.php";

if (!empty($_SESSION['employee_id'])) {
    header('Location: employee/dashboard.php');
    exit;
}

$error = '';
$success = '';
$name = '';
$mobile = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $mobile = preg_replace('/\D+/', '', trim($_POST['mobile'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || strlen($name) < 2) {
        $error = 'Please enter your full name.';
    } elseif (!preg_match('/^[6-9]\d{9}$/', $mobile)) {
        $error = 'Please enter a valid 10-digit mobile number.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM employees WHERE phone=? LIMIT 1');
        $stmt->execute([$mobile]);

        if ($stmt->fetch()) {
            $error = 'This mobile number is already registered. Please login.';
        } else {
            // The existing employees table requires an email. Generate an internal
            // unique value because registration only asks the employee for mobile.
            $internalEmail = $mobile . '@ftaxi.local';
            $hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $stmt = $pdo->prepare(
                    'INSERT INTO employees (name,email,phone,password,status) VALUES (?,?,?,?,\'active\')'
                );
                $stmt->execute([$name, $internalEmail, $mobile, $hash]);

                $success = 'Registration successful. You can login now.';
                $name = '';
                $mobile = '';
            } catch (PDOException $e) {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>F-Taxi Employee Registration</title>
<style>
*{box-sizing:border-box}
html,body{width:100%;height:100%;margin:0;padding:0;overflow:hidden}
body{font-family:Arial,Helvetica,sans-serif;color:#18365d;background:#edf7ff}
.page{position:fixed;inset:0;background:#edf7ff linear-gradient(135deg, #eaf5ff 0%, #f8fbff 55%, #fff4b8 100%);display:flex;align-items:center;justify-content:center;padding:20px}
.stage{width:min(500px,calc(100vw - 34px));position:relative}
.logo{position:absolute;z-index:2;left:50%;top:-55px;transform:translateX(-50%);width:190px;height:95px;object-fit:contain;filter:drop-shadow(0 7px 8px rgba(0,42,105,.16))}
.card{width:100%;background:rgba(255,255,255,.99);border:1px solid rgba(255,255,255,.95);border-radius:22px;padding:62px 34px 25px;box-shadow:0 12px 34px rgba(41,91,145,.12)}
h1{margin:0;text-align:center;color:#17375f;font-size:29px;line-height:1.15}
.sub{margin:8px 0 23px;text-align:center;color:#6a7d98;font-size:18px}
.alert{margin:0 0 14px;padding:10px 13px;border-radius:9px;background:#fee2e2;color:#991b1b;font-size:14px;text-align:center}
.success{margin:0 0 14px;padding:10px 13px;border-radius:9px;background:#dcfce7;color:#166534;font-size:14px;text-align:center}
.field{margin-bottom:16px}.field label{display:block;margin:0 0 8px;color:#1b365a;font-size:16px;font-weight:600}
input{width:100%;height:50px;border:1.5px solid #cbd5e1;border-radius:11px;background:#fff;color:#233c60;font-size:16px;padding:0 16px;outline:none}
input:focus{border-color:#1762b9;box-shadow:0 0 0 3px rgba(23,98,185,.10)}
.btn{width:100%;height:52px;margin-top:3px;border:0;border-radius:11px;background:linear-gradient(90deg,#0c4b9f,#0754bd);color:#fff;font-size:17px;font-weight:700;cursor:pointer}
.login-link{text-align:center;margin-top:17px;color:#6a7d98;font-size:14px}.login-link a{margin-left:5px;color:#143ed7;font-weight:700;text-decoration:none}.login-link a:hover{text-decoration:underline}
@media(max-width:620px){.stage{width:calc(100vw - 28px)}.card{padding:66px 24px 22px;border-radius:20px}.logo{width:175px;height:88px;top:-52px}h1{font-size:25px}.sub{font-size:16px;margin-bottom:20px}}
</style>
</head>
<body>
<div class="page">
  <div class="stage">
    <img class="logo" src="assets/images/ftaxi-logo.webp" alt="F-Taxi">
    <div class="card">
      <h1>Employee Registration</h1>
      <div class="sub">F-Taxi Telecaller Assessment Portal</div>

      <?php if ($error): ?><div class="alert"><?=htmlspecialchars($error)?></div><?php endif; ?>
      <?php if ($success): ?><div class="success"><?=htmlspecialchars($success)?></div><?php endif; ?>

      <form method="post" autocomplete="off">
        <div class="field">
          <label for="name">Full Name</label>
          <input id="name" name="name" type="text" value="<?=htmlspecialchars($name)?>" placeholder="Enter your full name" required>
        </div>
        <div class="field">
          <label for="mobile">Mobile Number</label>
          <input id="mobile" name="mobile" type="tel" inputmode="numeric" maxlength="10" value="<?=htmlspecialchars($mobile)?>" placeholder="Enter your mobile number" required>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="Create a password" required>
        </div>
        <div class="field">
          <label for="confirm_password">Confirm Password</label>
          <input id="confirm_password" name="confirm_password" type="password" placeholder="Confirm your password" required>
        </div>
        <button class="btn" type="submit">Register <span>→</span></button>
      </form>

      <div class="login-link">Already registered?<a href="login.php">Login here</a></div>
    </div>
  </div>
</div>
</body>
</html>

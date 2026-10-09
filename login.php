<?php
require_once __DIR__ . "/database.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $phone = preg_replace('/\D+/', '', $login);
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare(
        "SELECT * FROM employees WHERE (email=? OR phone=?) AND status='active' LIMIT 1"
    );
    $stmt->execute([$login, $phone]);
    $emp = $stmt->fetch();

    if ($emp && password_verify($password, $emp['password'])) {
        $_SESSION['employee_id'] = $emp['id'];
        $_SESSION['employee_name'] = $emp['name'];

        header("Location: employee/dashboard.php");
        exit;
    }

    $error = "Invalid employee login details.";
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>F-Taxi Employee Login</title>

<style>
* {
    box-sizing: border-box;
}

html,
body {
    width: 100%;
    height: 100%;
    height: 100dvh;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    color: #18365d;
    background: #edf7ff;
}

/* =========================
   LOGIN BACKGROUND
========================= */

.login-page {
    position: fixed;
    inset: 0;
    width: 100%;
    height: 100%;
    height: 100dvh;
    overflow: hidden;

    background:
        #edf7ff
        url('assets/images/login-reference.png')
        center center / 100% 100%
        no-repeat;
}

/* =========================
   LOGIN CONTAINER
========================= */

.login-stage {
    position: absolute;

    left: 50%;
    top: 50%;

    transform: translate(-50%, -50%);

    width: min(500px, calc(100vw - 34px));

    z-index: 5;
}

/* =========================
   LOGO
========================= */

.login-logo {
    position: absolute;

    z-index: 10;

    left: 50%;
    top: -55px;

    transform: translateX(-50%);

    width: 190px;
    height: 95px;

    object-fit: contain;
    object-position: center;

    filter: drop-shadow(
        0 7px 8px rgba(0, 42, 105, 0.16)
    );
}

/* =========================
   LOGIN CARD
========================= */

.login-card {
    width: 100%;

    min-height: 455px;

    background: rgba(255, 255, 255, 0.98);

    border: 1px solid rgba(255, 255, 255, 0.95);

    border-radius: 22px;

    padding: 62px 34px 24px;

    box-shadow:
        0 12px 34px rgba(41, 91, 145, 0.12);
}

/* =========================
   HEADING
========================= */

.login-card h1 {
    margin: 0;

    text-align: center;

    color: #17375f;

    font-size: 29px;

    line-height: 1.15;

    font-weight: 700;

    letter-spacing: -0.4px;
}

.login-card .subtitle {
    margin: 8px 0 25px;

    text-align: center;

    color: #6a7d98;

    font-size: 20px;

    line-height: 1.2;
}

/* =========================
   ALERT
========================= */

.alert {
    margin: 0 0 14px;

    padding: 10px 13px;

    border-radius: 9px;

    background: #fee2e2;

    color: #991b1b;

    font-size: 14px;

    text-align: center;
}

/* =========================
   FORM FIELD
========================= */

.field {
    margin-bottom: 19px;
}

.field label {
    display: block;

    margin: 0 0 10px;

    color: #1b365a;

    font-size: 17px;

    font-weight: 500;
}

/* =========================
   INPUT WRAPPER
========================= */

.input-wrap {
    position: relative;
}

/* =========================
   INPUT ICON
========================= */

.input-wrap .field-icon {
    position: absolute;

    left: 22px;
    top: 50%;

    transform: translateY(-50%);

    width: 24px;
    height: 24px;

    color: #788aa1;

    pointer-events: none;
}

/* =========================
   INPUT
========================= */

.login-card input {
    width: 100%;

    height: 50px;

    border: 1.5px solid #cbd5e1;

    border-radius: 11px;

    background: #fff;

    color: #233c60;

    font-size: 16px;

    padding: 0 48px;

    outline: none;

    box-shadow:
        inset 0 1px 2px rgba(0, 0, 0, 0.02);
}

.login-card input::placeholder {
    color: #a5b1c1;
    opacity: 1;
}

.login-card input:focus {
    border-color: #1762b9;

    box-shadow:
        0 0 0 3px rgba(23, 98, 185, 0.10);
}

/* =========================
   PASSWORD EYE BUTTON
========================= */

.input-wrap .eye-btn {
    position: absolute;

    right: 18px;
    top: 50%;

    transform: translateY(-50%);

    width: 30px;
    height: 30px;

    border: 0;

    background: transparent;

    color: #788aa1;

    cursor: pointer;

    padding: 2px;

    display: grid;

    place-items: center;
}

/* =========================
   LOGIN BUTTON
========================= */

.login-btn {
    width: 100%;

    height: 52px;

    margin-top: 4px;

    border: 0;

    border-radius: 11px;

    background:
        linear-gradient(
            90deg,
            #0c4b9f,
            #0754bd
        );

    color: #fff;

    font-size: 17px;

    font-weight: 700;

    cursor: pointer;
}

.login-btn:hover {
    filter: brightness(1.04);
}

.login-btn span {
    font-size: 22px;

    font-weight: 400;

    vertical-align: -1px;

    margin-left: 5px;
}

/* =========================
   EMPLOYEE REGISTRATION
========================= */

.register-link {
    text-align: center;
    margin-top: 15px;
    color: #6a7d98;
    font-size: 14px;
}

.register-link a {
    margin-left: 5px;
    color: #143ed7;
    font-weight: 700;
    text-decoration: none;
}

.register-link a:hover {
    text-decoration: underline;
}

/* =========================
   ADMIN LOGIN
========================= */

.admin-link {
    text-align: center;

    margin-top: 18px;
}

.admin-link a {
    display: inline-flex;

    align-items: center;

    gap: 10px;

    color: #143ed7;

    font-size: 16px;

    text-decoration: underline;
}

.admin-link svg {
    width: 24px;
    height: 24px;
}

/* =========================
   TABLET
========================= */

@media (max-width: 900px) {

    .login-page {
        background-size: auto 100%;
        background-position: center top;
    }

    .login-stage {
        width: min(
            500px,
            calc(100vw - 28px)
        );
    }

    .login-card {
        min-height: 455px;
    }
}

/* =========================
   MOBILE
========================= */

@media (max-width: 620px) {

    .login-page {
        background-size: auto 100%;
        background-position: center top;
    }

    .login-stage {
        width: calc(100vw - 28px);

        left: 50%;
        top: 50%;

        transform:
            translate(-50%, -50%);
    }

    .login-card {
        min-height: 475px;

        padding:
            68px
            24px
            24px;

        border-radius: 20px;
    }

    .login-logo {
        width: 175px;
        height: 88px;

        top: -52px;
    }

    .login-card h1 {
        font-size: 25px;
    }

    .login-card .subtitle {
        font-size: 16px;

        margin-bottom: 27px;
    }

    .field label {
        font-size: 16px;
    }

    .login-card input {
        height: 52px;

        font-size: 16px;
    }

    .login-btn {
        height: 54px;

        font-size: 17px;
    }

    .admin-link a {
        font-size: 16px;
    }
}

/* =========================
   VERY SMALL SCREEN
========================= */

@media (max-height: 650px) {

    .login-logo {
        width: 155px;
        height: 78px;

        top: -42px;
    }

    .login-card {
        min-height: 420px;

        padding:
            52px
            28px
            18px;
    }

    .login-card h1 {
        font-size: 24px;
    }

    .login-card .subtitle {
        margin-bottom: 18px;
        font-size: 15px;
    }

    .field {
        margin-bottom: 12px;
    }

    .field label {
        margin-bottom: 6px;
    }

    .login-card input {
        height: 44px;
    }

    .login-btn {
        height: 46px;
    }

    .admin-link {
        margin-top: 12px;
    }
}
</style>
</head>

<body>

<div class="login-page">

    <div class="login-stage">

        <!-- SINGLE F-TAXI LOGO -->
        <img
            class="login-logo"
            src="assets/images/ftaxi-logo.webp"
            alt="F-Taxi"
        >

        <div class="login-card">

            <h1>
                F-Taxi Employee Login
            </h1>

            <p class="subtitle">
                Telecaller assessment portal
            </p>

            <?php if ($error): ?>

                <div class="alert">
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>

            <form
                method="post"
                autocomplete="on"
            >

                <!-- EMAIL OR MOBILE -->

                <div class="field">

                    <label for="login">
                        Email or Mobile Number
                    </label>

                    <div class="input-wrap">

                        <svg
                            class="field-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <rect
                                x="3"
                                y="5"
                                width="18"
                                height="14"
                                rx="2"
                            />

                            <path d="m4 7 8 6 8-6"/>
                        </svg>

                        <input
                            id="login"
                            type="text"
                            name="login"
                            placeholder="Enter your email or mobile number"
                            autocomplete="username"
                            required
                        >

                    </div>

                </div>

                <!-- PASSWORD -->

                <div class="field">

                    <label for="password">
                        Password
                    </label>

                    <div class="input-wrap">

                        <svg
                            class="field-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <rect
                                x="5"
                                y="10"
                                width="14"
                                height="10"
                                rx="2"
                            />

                            <path
                                d="M8 10V7a4 4 0 0 1 8 0v3"
                            />

                            <circle
                                cx="12"
                                cy="15"
                                r="1"
                            />
                        </svg>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="eye-btn"
                            id="togglePassword"
                            aria-label="Show password"
                        >

                            <svg
                                id="eyeIcon"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    d="M2.5 12s3.5-6 9.5-6
                                    9.5 6 9.5 6-3.5 6-9.5 6
                                    -9.5-6-9.5-6Z"
                                />

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="2.5"
                                />

                                <path
                                    d="m4 4 16 16"
                                />
                            </svg>

                        </button>

                    </div>

                </div>

                <!-- LOGIN -->

                <button
                    class="login-btn"
                    type="submit"
                >
                    Login <span>→</span>
                </button>

            </form>

            <!-- EMPLOYEE REGISTRATION -->

            <div class="register-link">
                <span>New employee?</span>
                <a href="register.php">Register here</a>
            </div>

            <!-- ADMIN LOGIN -->

            <div class="admin-link">

                <a href="admin/login.php">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >

                        <circle
                            cx="9"
                            cy="8"
                            r="3"
                        />

                        <path
                            d="M3 20c0-3.3 2.7-6 6-6
                            s6 2.7 6 6"
                        />

                        <path
                            d="M16 5.5a3 3 0 0 1 0 5.8
                            M18 14c1.8.8 3 2.7 3 5"
                        />

                    </svg>

                    Admin Login

                </a>

            </div>

        </div>

    </div>

</div>

<script>

const toggle =
    document.getElementById(
        'togglePassword'
    );

const password =
    document.getElementById(
        'password'
    );

const eyeIcon =
    document.getElementById(
        'eyeIcon'
    );

if (toggle) {

    toggle.addEventListener(
        'click',
        () => {

            const showing =
                password.type === 'text';

            password.type =
                showing
                    ? 'password'
                    : 'text';

            toggle.setAttribute(
                'aria-label',
                showing
                    ? 'Show password'
                    : 'Hide password'
            );

            eyeIcon.innerHTML =
                showing

                ? `
                    <path
                        d="M2.5 12s3.5-6 9.5-6
                        9.5 6 9.5 6-3.5 6-9.5 6
                        -9.5-6-9.5-6Z"
                    />

                    <circle
                        cx="12"
                        cy="12"
                        r="2.5"
                    />

                    <path
                        d="m4 4 16 16"
                    />
                  `

                : `
                    <path
                        d="M2.5 12s3.5-6 9.5-6
                        9.5 6 9.5 6-3.5 6-9.5 6
                        -9.5-6-9.5-6Z"
                    />

                    <circle
                        cx="12"
                        cy="12"
                        r="2.5"
                    />
                  `;
        }
    );

}

</script>

</body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Security headers
if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

// Robust database and autoloader bootstrap
$possibleRoots = [
    dirname(__DIR__),
    __DIR__,
    dirname(__DIR__) . '/public_html',
    dirname($_SERVER['DOCUMENT_ROOT'] ?? ''),
    ($_SERVER['DOCUMENT_ROOT'] ?? ''),
];

$root = dirname(__DIR__);
foreach ($possibleRoots as $cand) {
    if ($cand && is_dir($cand . '/app') && file_exists($cand . '/config/database.php')) {
        $root = realpath($cand) ?: $cand;
        break;
    }
}

if (file_exists($root . '/backend/db.php')) {
    require_once $root . '/backend/db.php';
} elseif (file_exists(__DIR__ . '/../backend/db.php')) {
    require_once __DIR__ . '/../backend/db.php';
}

if (file_exists($root . '/backend/functions.php')) {
    require_once $root . '/backend/functions.php';
} elseif (file_exists(__DIR__ . '/../backend/functions.php')) {
    require_once __DIR__ . '/../backend/functions.php';
}

// Register PSR-4 Autoloader safely
if (!class_exists(\App\Core\Autoloader::class)) {
    if (file_exists($root . '/app/Core/Autoloader.php')) {
        require_once $root . '/app/Core/Autoloader.php';
    } elseif (file_exists(__DIR__ . '/../app/Core/Autoloader.php')) {
        require_once __DIR__ . '/../app/Core/Autoloader.php';
    } elseif (file_exists(__DIR__ . '/app/Core/Autoloader.php')) {
        require_once __DIR__ . '/app/Core/Autoloader.php';
    }
}
if (class_exists(\App\Core\Autoloader::class)) {
    \App\Core\Autoloader::register();
}

if (!isset($pdo) || !$pdo) {
    if (file_exists($root . '/config/env.php')) {
        require_once $root . '/config/env.php';
    }
    if (class_exists(\App\Core\Database::class)) {
        $pdo = \App\Core\Database::getConnection();
    }
}
$GLOBALS['pdo'] = $pdo ?? null;

// Authentication Config (Loaded from .env / config/app.php with fallback)
if (file_exists($root . '/config/env.php')) {
    require_once $root . '/config/env.php';
} elseif (file_exists(__DIR__ . '/../config/env.php')) {
    require_once __DIR__ . '/../config/env.php';
} elseif (!function_exists('env')) {
    function env($key, $default = null) {
        return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;
    }
}
$app_config = file_exists($root . '/config/app.php') ? require $root . '/config/app.php' : (file_exists(__DIR__ . '/../config/app.php') ? require __DIR__ . '/../config/app.php' : []);
if (!defined('ADMIN_USER')) {
    define('ADMIN_USER', $app_config['admin']['user'] ?? env('ADMIN_USERNAME', 'admin'));
}
if (!defined('ADMIN_PASS')) {
    define('ADMIN_PASS', $app_config['admin']['pass'] ?? env('ADMIN_PASSWORD', 'admin123'));
}

// Determine clean self URL for redirects and form actions
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/admin', PHP_URL_PATH);
$adminSelfUrl = !empty($requestPath) && $requestPath !== '/' && $requestPath !== '/index.php' ? $requestPath : '/admin';

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['admin_logged_in'], $_SESSION['admin_user'], $_SESSION['admin_csrf_token']);
    session_destroy();
    header('Location: ' . $adminSelfUrl);
    exit;
}

// Generate CSRF token for login form
if (empty($_SESSION['admin_login_csrf'])) {
    $_SESSION['admin_login_csrf'] = bin2hex(random_bytes(32));
}

// Rate Limiting settings (5 max attempts, 15 minutes lockout)
$max_attempts = 5;
$lockout_seconds = 15 * 60;
$login_error = '';

// Check if currently locked out
$is_locked = false;
if (isset($_SESSION['login_locked_until'])) {
    if (time() < $_SESSION['login_locked_until']) {
        $is_locked = true;
        $minutes_left = max(1, (int)ceil(($_SESSION['login_locked_until'] - time()) / 60));
        $login_error = "Too many failed attempts. Account temporarily locked. Please try again in {$minutes_left} minute(s).";
    } else {
        unset($_SESSION['login_locked_until'], $_SESSION['login_attempts']);
    }
}

// Handle Login Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $trimmed_pass = trim($password);

    // Case-insensitive username check, also allow admin email
    $allowed_usernames = [
        strtolower(ADMIN_USER),
        'admin',
        'info@gangasagar.com.np'
    ];
    $user_matches = in_array(strtolower($username), $allowed_usernames, true);

    // Password verification: supports plaintext from .env as well as bcrypt/argon2 hashes
    $pass_matches = hash_equals(ADMIN_PASS, $password) 
                 || hash_equals(ADMIN_PASS, $trimmed_pass)
                 || (@password_verify($password, ADMIN_PASS))
                 || (@password_verify($trimmed_pass, ADMIN_PASS));

    // CSRF token check (safe fallback if session refreshed)
    $submitted_csrf = $_POST['csrf_token'] ?? '';
    $csrf_matches = !empty($submitted_csrf) && !empty($_SESSION['admin_login_csrf']) && hash_equals($_SESSION['admin_login_csrf'], $submitted_csrf);

    if ($user_matches && $pass_matches) {
        // Valid credentials! Always let the real admin in and clear any previous lockouts
        unset($_SESSION['login_attempts'], $_SESSION['login_locked_until'], $_SESSION['admin_login_csrf']);
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = ADMIN_USER;
        $_SESSION['admin_logged_at'] = time();
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));

        // Commit session data before redirect to prevent race condition on Windows
        session_write_close();

        header('Location: ' . $adminSelfUrl);
        exit;
    } elseif ($is_locked) {
        // Kept locked if wrong credentials entered while under lockout
        $minutes_left = max(1, (int)ceil(($_SESSION['login_locked_until'] - time()) / 60));
        $login_error = "Too many failed attempts. Account temporarily locked. Please try again in {$minutes_left} minute(s).";
    } elseif (!$csrf_matches && !empty($_POST['csrf_token'])) {
        $login_error = 'Session expired. Please refresh the page and try again.';
    } else {
        // Failed attempt with invalid credentials
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        $remaining = $max_attempts - $_SESSION['login_attempts'];
        if ($remaining <= 0) {
            $_SESSION['login_locked_until'] = time() + $lockout_seconds;
            $login_error = "Too many failed login attempts. Account locked for 15 minutes.";
        } else {
            $login_error = "Invalid username or password. ({$remaining} attempt(s) remaining)";
        }
    }
}

// If not logged in, show clean modern Login Screen
if (empty($_SESSION['admin_logged_in'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title>Admin Login — Workspace</title>
    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { 
            background: #0b1329; 
            color: #f8fafc; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            min-height: 100vh; 
            padding: 1.5rem; 
        }
        .login-card { 
            background: #131d36; 
            border: 1px solid #1e293b; 
            border-radius: 18px; 
            padding: 2.25rem 2rem; 
            width: 100%; 
            max-width: 390px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.45); 
        }
        .login-header { 
            text-align: center; 
            margin-bottom: 1.75rem; 
        }
        .login-badge-icon { 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            width: 44px; 
            height: 44px; 
            border-radius: 12px; 
            background: rgba(75, 134, 165, 0.15); 
            color: #4B86A5; 
            font-size: 1.35rem; 
            margin-bottom: 0.75rem; 
            border: 1px solid rgba(75, 134, 165, 0.25); 
        }
        .login-heading { 
            font-family: 'Poppins', sans-serif; 
            font-size: 1.35rem; 
            font-weight: 700; 
            color: #ffffff; 
            letter-spacing: -0.01em; 
            margin-bottom: 0.35rem; 
        }
        .login-subtitle { 
            font-size: 0.86rem; 
            color: #94a3b8; 
        }
        .form-group { 
            margin-bottom: 1.25rem; 
        }
        label { 
            display: block; 
            font-size: 0.83rem; 
            font-weight: 600; 
            color: #cbd5e1; 
            margin-bottom: 0.45rem; 
        }
        .input-wrap { 
            position: relative; 
            display: flex; 
            align-items: center; 
        }
        .input-icon { 
            position: absolute; 
            left: 1rem; 
            color: #64748b; 
            font-size: 1.15rem; 
            pointer-events: none; 
        }
        input[type="text"], input[type="password"] { 
            width: 100%; 
            padding: 0.75rem 2.5rem 0.75rem 2.65rem; 
            background: #0b1329; 
            border: 1px solid #28354f; 
            border-radius: 10px; 
            font-size: 0.92rem; 
            color: #f8fafc; 
            outline: none; 
            transition: all 0.2s ease; 
        }
        input:focus { 
            border-color: #4B86A5; 
            box-shadow: 0 0 0 3px rgba(75, 134, 165, 0.22); 
        }
        .btn-toggle-pass { 
            position: absolute; 
            right: 0.85rem; 
            background: none; 
            border: none; 
            color: #64748b; 
            font-size: 1.15rem; 
            cursor: pointer; 
            padding: 0.2rem; 
            display: flex; 
            align-items: center; 
            transition: color 0.2s; 
        }
        .btn-toggle-pass:hover { 
            color: #cbd5e1; 
        }
        .btn-submit { 
            width: 100%; 
            padding: 0.8rem; 
            background: linear-gradient(135deg, #4B86A5 0%, #2563eb 100%); 
            color: #ffffff; 
            border: none; 
            border-radius: 10px; 
            font-weight: 600; 
            font-size: 0.92rem; 
            cursor: pointer; 
            transition: all 0.2s ease; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            gap: 0.5rem; 
            margin-top: 0.6rem; 
            box-shadow: 0 4px 14px rgba(75, 134, 165, 0.28); 
        }
        .btn-submit:hover { 
            filter: brightness(1.1); 
            transform: translateY(-1px); 
        }
        .alert-error { 
            background: rgba(239,68,68,0.12); 
            border: 1px solid rgba(239,68,68,0.4); 
            color: #fca5a5; 
            padding: 0.7rem 0.9rem; 
            border-radius: 10px; 
            font-size: 0.83rem; 
            margin-bottom: 1.25rem; 
            display: flex; 
            align-items: center; 
            gap: 0.55rem; 
            line-height: 1.4; 
        }
        .alert-error i { 
            font-size: 1.1rem; 
            flex-shrink: 0; 
        }
        .back-link { 
            display: block; 
            text-align: center; 
            margin-top: 1.5rem; 
            font-size: 0.84rem; 
            color: #64748b; 
            text-decoration: none; 
            transition: color 0.2s; 
        }
        .back-link:hover { 
            color: #4B86A5; 
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="login-badge-icon"><i class="bx bx-shield-quarter"></i></div>
            <h1 class="login-heading">Admin Login</h1>
            <p class="login-subtitle">Sign in to manage your portfolio</p>
        </div>

        <?php if (!empty($login_error)): ?>
            <div class="alert-error" role="alert">
                <i class="bx bx-error-circle"></i>
                <span><?= htmlspecialchars($login_error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= htmlspecialchars($adminSelfUrl) ?>" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_login_csrf']) ?>">

            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <i class="bx bx-user input-icon"></i>
                    <input type="text" id="username" name="username" placeholder="Enter username" required autofocus autocomplete="username">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="bx bx-lock-alt input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Enter password" required autocomplete="current-password">
                    <button type="button" class="btn-toggle-pass" id="togglePass" aria-label="Toggle password visibility">
                        <i class="bx bx-show" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="login_submit" class="btn-submit">
                <span>Sign In</span>
                <i class="bx bx-right-arrow-alt"></i>
            </button>
        </form>

        <a href="/" class="back-link">&larr; Back to Portfolio Website</a>
    </div>

    <script>
        const togglePass = document.getElementById('togglePass');
        const passInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        if (togglePass && passInput) {
            togglePass.addEventListener('click', function() {
                const isPassword = passInput.type === 'password';
                passInput.type = isPassword ? 'text' : 'password';
                toggleIcon.className = isPassword ? 'bx bx-hide' : 'bx bx-show';
            });
        }
    </script>
</body>
</html>
<?php
exit;
}

// ─────────────────────────────────────────────────────────────
// LOGGED IN ACTIONS (CRUD Operations)
// ─────────────────────────────────────────────────────────────
$message = $_SESSION['admin_message'] ?? '';
$error = $_SESSION['admin_error'] ?? '';
unset($_SESSION['admin_message'], $_SESSION['admin_error']);
$message_type = 'success';

if (!function_exists('handle_admin_image_upload')) {
    /**
     * Handle Secure Local File Upload directly to public/images/uploads/ and images/uploads/
     */
    function handle_admin_image_upload(string $file_key, string $fallback_key, string $default_image = 'images/projectcard1.jpg'): string {
        if (isset($_FILES[$file_key]) && is_array($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES[$file_key];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
            
            // Limit file size to 8MB max
            if ($file['size'] <= 8 * 1024 * 1024 && in_array($ext, $allowed_exts, true)) {
                $tmp = $file['tmp_name'];
                $is_safe = false;

                if ($ext === 'svg') {
                    $svg_content = file_get_contents($tmp);
                    // Prevent stored XSS in SVG
                    if ($svg_content !== false && !preg_match('/<script|javascript:|on\w+=/i', $svg_content)) {
                        $is_safe = true;
                    }
                } else {
                    $img_info = @getimagesize($tmp);
                    $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                    if ($img_info !== false && in_array($img_info['mime'], $allowed_mimes, true)) {
                        $is_safe = true;
                    }
                }

                if ($is_safe) {
                    $upload_dir_public = dirname(__DIR__) . '/public/images/uploads';
                    $upload_dir_root = dirname(__DIR__) . '/images/uploads';
                    if (!is_dir($upload_dir_public)) {
                        mkdir($upload_dir_public, 0755, true);
                    }
                    if (!is_dir($upload_dir_root)) {
                        mkdir($upload_dir_root, 0755, true);
                    }

                    $safe_filename = 'upload_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    $dest_public = $upload_dir_public . '/' . $safe_filename;
                    $dest_root = $upload_dir_root . '/' . $safe_filename;

                    if (move_uploaded_file($tmp, $dest_public)) {
                        @copy($dest_public, $dest_root);
                        return 'images/uploads/' . $safe_filename;
                    }
                }
            }
        }
        
        // Check fallback text field / hidden existing image
        $fallback = trim($_POST[$fallback_key] ?? '');
        if (!empty($fallback)) {
            // Prevent path traversal
            $fallback = str_replace(['../', '..\\'], '', $fallback);
            return $fallback;
        }
        
        return $default_image;
    }
}

if (!function_exists('handle_admin_screenshot_uploads')) {
    /**
     * Handle Multiple Desktop Screenshot Uploads + Captions for Featured Projects
     */
    function handle_admin_screenshot_uploads(): array {
        $results = [];
        $captions = $_POST['screenshot_captions'] ?? [];
        $existing = $_POST['screenshot_existing_paths'] ?? [];
        $files = $_FILES['screenshot_files'] ?? null;

        $totalItems = max(
            is_array($captions) ? count($captions) : 0,
            is_array($existing) ? count($existing) : 0,
            (isset($files['name']) && is_array($files['name'])) ? count($files['name']) : 0
        );

        $upload_dir_public = dirname(__DIR__) . '/public/images/uploads';
        $upload_dir_root = dirname(__DIR__) . '/images/uploads';
        if (!is_dir($upload_dir_public)) {
            @mkdir($upload_dir_public, 0755, true);
        }
        if (!is_dir($upload_dir_root)) {
            @mkdir($upload_dir_root, 0755, true);
        }

        for ($i = 0; $i < $totalItems; $i++) {
            $caption = trim($captions[$i] ?? 'Project Screenshot');
            if (empty($caption)) {
                $caption = 'Project Screenshot';
            }
            $savedPath = '';

            // Check if user uploaded a file from desktop for this row
            if (
                isset($files['error'][$i]) &&
                $files['error'][$i] === UPLOAD_ERR_OK &&
                !empty($files['name'][$i])
            ) {
                $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
                if ($files['size'][$i] <= 10 * 1024 * 1024 && in_array($ext, $allowed, true)) {
                    $tmp = $files['tmp_name'][$i];
                    $safe_filename = 'shot_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    $dest_public = $upload_dir_public . '/' . $safe_filename;
                    $dest_root = $upload_dir_root . '/' . $safe_filename;

                    if (move_uploaded_file($tmp, $dest_public)) {
                        @copy($dest_public, $dest_root);
                        $savedPath = 'images/uploads/' . $safe_filename;
                    }
                }
            }

            // If no new file uploaded from desktop, keep existing image path
            if (empty($savedPath) && !empty($existing[$i])) {
                $safeExisting = str_replace(['../', '..\\'], '', trim($existing[$i]));
                if (!empty($safeExisting)) {
                    $savedPath = $safeExisting;
                }
            }

            if (!empty($savedPath)) {
                $results[] = [
                    'src' => $savedPath,
                    'title' => $caption
                ];
            }
        }

        // Fallback: legacy text format if desktop upload rows were not used
        if (empty($results) && !empty($_POST['screenshots'])) {
            require_once __DIR__ . '/../app/Models/FeaturedProject.php';
            return \App\Models\FeaturedProject::parseScreenshots((string)$_POST['screenshots']);
        }

        return $results;
    }
}

// Action: Delete Company Product (Strataflow)
if (isset($_GET['del_prod'])) {
    $del_id = intval($_GET['del_prod']);
    try {
        $stmt = $pdo->prepare("DELETE FROM company_products WHERE id = ?");
        $stmt->execute([$del_id]);
        $_SESSION['admin_message'] = 'Strataflow Product deleted successfully.';
    } catch (\Throwable $e) {
        $_SESSION['admin_error'] = 'Failed to delete product: ' . $e->getMessage();
    }
    header('Location: ' . $adminSelfUrl . '#tab-products');
    exit;
}

// Action: Delete Featured Project
if (isset($_GET['del_feat'])) {
    $del_id = intval($_GET['del_feat']);
    try {
        $stmt = $pdo->prepare("DELETE FROM featured_projects WHERE id = ?");
        $stmt->execute([$del_id]);
        $_SESSION['admin_message'] = 'Featured Project deleted successfully.';
    } catch (\Throwable $e) {
        $_SESSION['admin_error'] = 'Failed to delete featured project: ' . $e->getMessage();
    }
    header('Location: ' . $adminSelfUrl . '#tab-featured');
    exit;
}

// Action: Delete Combined Project (Academic or Other)
if (isset($_GET['del_combined']) && isset($_GET['type'])) {
    $del_id = intval($_GET['del_combined']);
    $type = trim($_GET['type']);
    try {
        if ($type === 'academic') {
            $pdo->prepare("DELETE FROM academic_projects WHERE id = ?")->execute([$del_id]);
            $_SESSION['admin_message'] = 'Academic Project deleted successfully.';
        } else {
            $pdo->prepare("DELETE FROM other_projects WHERE id = ?")->execute([$del_id]);
            $_SESSION['admin_message'] = 'Other Project deleted successfully.';
        }
    } catch (\Throwable $e) {
        $_SESSION['admin_error'] = 'Failed to delete project: ' . $e->getMessage();
    }
    header('Location: ' . $adminSelfUrl . '#tab-academic-other');
    exit;
}

// Action: Delete Academic Project (Legacy fallback)
if (isset($_GET['del_acad'])) {
    $del_id = intval($_GET['del_acad']);
    try {
        $pdo->prepare("DELETE FROM academic_projects WHERE id = ?")->execute([$del_id]);
        $_SESSION['admin_message'] = 'Academic Project deleted successfully.';
    } catch (\Throwable $e) {
        $_SESSION['admin_error'] = 'Failed to delete academic project: ' . $e->getMessage();
    }
    header('Location: ' . $adminSelfUrl . '#tab-academic-other');
    exit;
}

// Action: Delete Other Project (Legacy fallback)
if (isset($_GET['del_other'])) {
    $del_id = intval($_GET['del_other']);
    try {
        $pdo->prepare("DELETE FROM other_projects WHERE id = ?")->execute([$del_id]);
        $_SESSION['admin_message'] = 'Other Project deleted successfully.';
    } catch (\Throwable $e) {
        $_SESSION['admin_error'] = 'Failed to delete other project: ' . $e->getMessage();
    }
    header('Location: ' . $adminSelfUrl . '#tab-academic-other');
    exit;
}

// Action: Delete Blog Post
if (isset($_GET['del_blog'])) {
    $del_id = intval($_GET['del_blog']);
    try {
        $pdo->prepare("DELETE FROM blog_insights WHERE id = ?")->execute([$del_id]);
        $_SESSION['admin_message'] = 'Insight / Blog post deleted successfully.';
    } catch (\Throwable $e) {
        $_SESSION['admin_error'] = 'Failed to delete blog post: ' . $e->getMessage();
    }
    header('Location: ' . $adminSelfUrl . '#tab-blog');
    exit;
}

// Action: Delete Contact Message
if (isset($_GET['del_msg'])) {
    $del_id = intval($_GET['del_msg']);
    try {
        $pdo->prepare("DELETE FROM contact_inquiries WHERE id = ?")->execute([$del_id]);
        $_SESSION['admin_message'] = 'Contact inquiry removed.';
    } catch (\Throwable $e) {
        $_SESSION['admin_error'] = 'Failed to delete inquiry: ' . $e->getMessage();
    }
    header('Location: ' . $adminSelfUrl . '#tab-inquiries');
    exit;
}

// Action: Delete Award / Recognition
if (isset($_GET['del_award'])) {
    $del_id = intval($_GET['del_award']);
    try {
        if (class_exists(\App\Models\Award::class)) {
            \App\Models\Award::ensureTable();
        }
        $stmt = $pdo->prepare("DELETE FROM awards WHERE id = ?");
        $stmt->execute([$del_id]);
        $_SESSION['admin_message'] = 'Award / Recognition deleted successfully.';
    } catch (\Throwable $e) {
        $_SESSION['admin_error'] = 'Failed to delete award: ' . $e->getMessage();
    }
    header('Location: ' . $adminSelfUrl . '#tab-awards');
    exit;
}

// Action: Delete Work Experience
if (isset($_GET['del_exp'])) {
    $del_id = intval($_GET['del_exp']);
    try {
        if (class_exists(\App\Models\Experience::class)) {
            \App\Models\Experience::ensureTable();
        }
        $stmt = $pdo->prepare("DELETE FROM experiences WHERE id = ?");
        $stmt->execute([$del_id]);
        $_SESSION['admin_message'] = 'Work Experience deleted successfully.';
    } catch (\Throwable $e) {
        $_SESSION['admin_error'] = 'Failed to delete experience: ' . $e->getMessage();
    }
    header('Location: ' . $adminSelfUrl . '#tab-experiences');
    exit;
}

// Action: Toggle Promotion Status
if (isset($_GET['toggle_promo'])) {
    $promo_id = intval($_GET['toggle_promo']);
    try {
        if (class_exists(\App\Models\Promotion::class)) {
            \App\Models\Promotion::toggleStatus($promo_id);
        }
        $_SESSION['admin_message'] = 'Promotional Offer status updated.';
    } catch (\Throwable $e) {
        $_SESSION['admin_error'] = 'Failed to update promotion status: ' . $e->getMessage();
    }
    header('Location: ' . $adminSelfUrl . '#tab-promotions');
    exit;
}

// Action: Delete Promotion
if (isset($_GET['del_promo'])) {
    $promo_id = intval($_GET['del_promo']);
    try {
        if (class_exists(\App\Models\Promotion::class)) {
            \App\Models\Promotion::delete($promo_id);
        }
        $_SESSION['admin_message'] = 'Promotional Offer deleted successfully.';
    } catch (\Throwable $e) {
        $_SESSION['admin_error'] = 'Failed to delete promotion: ' . $e->getMessage();
    }
    header('Location: ' . $adminSelfUrl . '#tab-promotions');
    exit;
}

// Action: Add / Update Promotional Offer Pop-up
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_promotion'])) {
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $image = handle_admin_image_upload('promo_image_file', 'image', 'images/projectcard1.jpg');
    $button_text = trim($_POST['button_text'] ?? 'Claim Offer');
    $button_url = trim($_POST['button_url'] ?? '#contact');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $promo_id = intval($_POST['promo_id'] ?? 0);

    if (empty($title)) {
        $error = 'Promotion title is required.';
    } else {
        \App\Models\Promotion::saveItem([
            'id' => $promo_id,
            'title' => $title,
            'description' => $desc,
            'image' => $image,
            'button_text' => $button_text,
            'button_url' => $button_url,
            'is_active' => $is_active
        ]);
        $message = $promo_id > 0 ? "Promotional Offer '{$title}' updated successfully." : "New Promotional Offer '{$title}' uploaded.";
    }
}

// Action: Add / Update Award
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_award'])) {
    $title = trim($_POST['title'] ?? '');
    $issuer = trim($_POST['issuer'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $image = handle_admin_image_upload('award_image_file', 'image', 'images/projectcard1.jpg');
    $desc = trim($_POST['description'] ?? '');
    $order = intval($_POST['display_order'] ?? 0);
    $award_id = intval($_POST['award_id'] ?? 0);

    \App\Models\Award::saveItem([
        'id' => $award_id,
        'title' => $title,
        'issuer' => $issuer,
        'date' => $date,
        'image' => $image,
        'description' => $desc,
        'display_order' => $order
    ]);
    $message = "Award / Recognition '{$title}' saved successfully.";
}

// Action: Add / Update Work Experience
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_experience'])) {
    $company = trim($_POST['company'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $period = trim($_POST['period'] ?? '');
    $location = trim($_POST['location'] ?? 'Kathmandu, Nepal');
    $status = trim($_POST['status'] ?? 'Active');
    $description = trim($_POST['description'] ?? '');
    $responsibilities = trim($_POST['responsibilities'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $order = intval($_POST['display_order'] ?? 0);
    $exp_id = intval($_POST['exp_id'] ?? 0);

    if (empty($company) || empty($role)) {
        $_SESSION['admin_error'] = 'Company name and Role are required.';
    } else {
        $saved = \App\Models\Experience::saveItem([
            'id' => $exp_id,
            'company' => $company,
            'role' => $role,
            'period' => $period,
            'location' => $location,
            'status' => $status,
            'description' => $description,
            'responsibilities' => $responsibilities,
            'skills' => $skills,
            'display_order' => $order
        ]);
        if ($saved) {
            $_SESSION['admin_message'] = $exp_id > 0 ? "Work Experience at '{$company}' updated successfully." : "New Work Experience at '{$company}' added.";
        } else {
            $_SESSION['admin_error'] = "Failed to save work experience.";
        }
    }
    header('Location: ' . $adminSelfUrl . '#tab-experiences');
    exit;
}

// Action: Add / Update Featured Project
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_featured'])) {
    $title = trim($_POST['title'] ?? '');
    $image = handle_admin_image_upload('feat_image_file', 'image', 'images/projectcard1.jpg');
    $screenshotsArray = handle_admin_screenshot_uploads();

    $saveData = [
        'id' => intval($_POST['feat_id'] ?? 0),
        'title' => $title,
        'slug' => trim($_POST['slug'] ?? ''),
        'category_badge' => trim($_POST['category_badge'] ?? 'FEATURED PROJECT'),
        'category' => trim($_POST['category'] ?? 'Web Application & Systems'),
        'official_site' => trim($_POST['official_site'] ?? ''),
        'client_name' => trim($_POST['client_name'] ?? ''),
        'location' => trim($_POST['location'] ?? 'Kathmandu, Nepal'),
        'short_description' => trim($_POST['short_description'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'impact' => trim($_POST['impact'] ?? ''),
        'features' => trim($_POST['features'] ?? ''),
        'screenshots' => $screenshotsArray,
        'image' => $image,
        'skills' => trim($_POST['skills'] ?? ''),
        'live_url' => trim($_POST['live_url'] ?? 'https://github.com/gangasagar12'),
        'display_order' => intval($_POST['display_order'] ?? 0),
    ];

    if (\App\Models\FeaturedProject::saveItem($saveData)) {
        $_SESSION['admin_message'] = "Project '{$title}' saved successfully with complete dynamic details.";
    } else {
        $_SESSION['admin_error'] = "Failed to save project.";
    }
    header('Location: ' . $adminSelfUrl . '#tab-featured');
    exit;
}

// Action: Add / Update Combined Project (Academic & Other Projects Unified)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_combined_project'])) {
    $project_type = trim($_POST['project_type'] ?? 'academic'); // 'academic' or 'other'
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? ($project_type === 'academic' ? 'University Coursework' : 'Engineering Utility'));
    $desc = trim($_POST['description'] ?? '');
    $detailed_desc = trim($_POST['detailed_description'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $github_url = trim($_POST['github_url'] ?? 'https://github.com/gangasagar12');
    $live_url = trim($_POST['live_url'] ?? $github_url);
    $order = intval($_POST['display_order'] ?? 0);
    $record_id = intval($_POST['combined_id'] ?? 0);
    $original_type = trim($_POST['original_type'] ?? $project_type);

    $default_img = ($project_type === 'academic') ? 'images/projectcard2.jpg' : 'images/projectcard3.webp';
    $image = handle_admin_image_upload('image_file', 'existing_image', $default_img);

    if ($record_id > 0) {
        if ($original_type === $project_type) {
            if ($project_type === 'academic') {
                $stmt = $pdo->prepare("UPDATE academic_projects SET title=?, description=?, detailed_description=?, image=?, skills=?, display_order=? WHERE id=?");
                $stmt->execute([$title, $desc, $detailed_desc, $image, $skills, $order, $record_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE other_projects SET title=?, category=?, description=?, detailed_description=?, image=?, skills=?, github_url=?, live_url=?, display_order=? WHERE id=?");
                $stmt->execute([$title, $category, $desc, $detailed_desc, $image, $skills, $github_url, $live_url, $order, $record_id]);
            }
            $message = "Project '{$title}' updated successfully.";
        } else {
            // Type changed: delete from old and insert to new
            if ($original_type === 'academic') {
                $pdo->prepare("DELETE FROM academic_projects WHERE id = ?")->execute([$record_id]);
                $stmt = $pdo->prepare("INSERT INTO other_projects (title, category, description, detailed_description, image, skills, github_url, live_url, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $category, $desc, $detailed_desc, $image, $skills, $github_url, $live_url, $order]);
            } else {
                $pdo->prepare("DELETE FROM other_projects WHERE id = ?")->execute([$record_id]);
                $stmt = $pdo->prepare("INSERT INTO academic_projects (title, description, detailed_description, image, skills, display_order) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $desc, $detailed_desc, $image, $skills, $order]);
            }
            $message = "Project '{$title}' moved to {$project_type} and saved successfully.";
        }
    } else {
        if ($project_type === 'academic') {
            $stmt = $pdo->prepare("INSERT INTO academic_projects (title, description, detailed_description, image, skills, display_order) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $desc, $detailed_desc, $image, $skills, $order]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO other_projects (title, category, description, detailed_description, image, skills, github_url, live_url, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $category, $desc, $detailed_desc, $image, $skills, $github_url, $live_url, $order]);
        }
        $message = "New project '{$title}' added successfully.";
    }
}

// Action: Add / Update Blog Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_blog'])) {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? 'Engineering');
    $image = handle_admin_image_upload('blog_image_file', 'image', 'images/projectcard1.jpg');
    $read_time = trim($_POST['read_time'] ?? '5 min read');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $date = trim($_POST['date'] ?? date('M Y'));
    $order = intval($_POST['display_order'] ?? 0);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $blog_id = intval($_POST['blog_id'] ?? 0);

    if ($blog_id > 0) {
        $stmt = $pdo->prepare("UPDATE blog_insights SET title=?, slug=?, category=?, image=?, read_time=?, excerpt=?, content=?, date=?, display_order=? WHERE id=?");
        $stmt->execute([$title, $slug, $category, $image, $read_time, $excerpt, $content, $date, $order, $blog_id]);
        $message = "Blog Insight '{$title}' updated.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO blog_insights (title, slug, category, image, read_time, excerpt, content, date, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $category, $image, $read_time, $excerpt, $content, $date, $order]);
        $message = "New Insight '{$title}' published.";
    }
}

// Action: Add / Update Company Product (Strataflow Technologies)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_company_product'])) {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? 'Software System');
    $desc = trim($_POST['description'] ?? '');
    $image = handle_admin_image_upload('prod_image_file', 'image', 'images/projectcard1.jpg');
    $status_text = trim($_POST['status_text'] ?? 'Live Product');
    $features = trim($_POST['features'] ?? '');
    $tech_pills = trim($_POST['tech_pills'] ?? '');
    $link_url = trim($_POST['link_url'] ?? 'https://github.com/gangasagar12');
    $link_text = trim($_POST['link_text'] ?? 'View Details');
    $order = intval($_POST['display_order'] ?? 0);
    $prod_id = intval($_POST['prod_id'] ?? 0);

    \App\Models\CompanyProduct::saveItem([
        'title' => $title,
        'category' => $category,
        'description' => $desc,
        'image' => $image,
        'status_text' => $status_text,
        'features' => $features,
        'tech_pills' => $tech_pills,
        'link_url' => $link_url,
        'link_text' => $link_text,
        'display_order' => $order,
    ], $prod_id > 0 ? $prod_id : null);

    $message = $prod_id > 0 ? "Strataflow Product '{$title}' updated." : "New Strataflow Product '{$title}' added.";
}

// Fetch all current data for Admin Dashboard
$featured_list = \App\Models\FeaturedProject::allWithSkills();
$academic_list = \App\Models\AcademicProject::allWithSkills();
$other_projects_list = \App\Models\OtherProject::allWithSkills();
$blog_list = $pdo->query("SELECT * FROM blog_insights ORDER BY display_order ASC, id DESC")->fetchAll();
$inquiries_list = $pdo->query("SELECT * FROM contact_inquiries ORDER BY id DESC")->fetchAll();
$company_products_list = \App\Models\CompanyProduct::allWithFeaturesAndPills();
$experiences_list = \App\Models\Experience::allWithDetails();
$awards_list = \App\Models\Award::allWithDetails();
$promotions_list = \App\Models\Promotion::allOrdered();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title>Admin Workspace — Ganga Sagar Joshi</title>
    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --primary-ring: rgba(37,99,235,0.15);
            --sidebar-bg: #0b1329;
            --sidebar-card: #152243;
            --sidebar-border: #1e293b;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --border-light: #f1f5f9;
            --font-head: 'Poppins', sans-serif;
            --font-body: 'Inter', sans-serif;
            --radius-lg: 16px;
            --radius-md: 10px;
            --radius-sm: 6px;
            --shadow-subtle: 0 1px 3px rgba(15,23,42,0.04), 0 1px 2px rgba(15,23,42,0.02);
            --shadow-card: 0 4px 14px rgba(15,23,42,0.05);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: var(--font-body); }
        body { background: var(--bg); color: var(--text-main); min-height: 100vh; display: flex; overflow-x: hidden; }

        /* ─── SIDEBAR ────────────────────────────────────────── */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid var(--sidebar-border);
            z-index: 1000;
            transition: transform 0.25s ease;
        }
        .sidebar-header {
            padding: 1.4rem 1.4rem 1.1rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .brand-box {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }
        .brand-monogram {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: #ffffff;
            font-family: var(--font-head);
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(37,99,235,0.35);
        }
        .brand-text h4 {
            color: #ffffff;
            font-family: var(--font-head);
            font-size: 0.96rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .brand-text span {
            color: #64748b;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 1.2rem 0.9rem;
        }
        .sidebar-section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #64748b;
            padding: 0 0.75rem;
            margin: 1rem 0 0.5rem;
        }
        .sidebar-section-title:first-child {
            margin-top: 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.65rem 0.85rem;
            border-radius: var(--radius-md);
            color: #94a3b8;
            font-size: 0.88rem;
            font-weight: 500;
            text-decoration: none;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.18s ease;
            text-align: left;
            margin-bottom: 0.25rem;
        }
        .nav-link-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .nav-link i {
            font-size: 1.25rem;
            color: #64748b;
            transition: color 0.18s ease;
        }
        .nav-link:hover {
            color: #f1f5f9;
            background: rgba(255,255,255,0.05);
        }
        .nav-link:hover i {
            color: #60a5fa;
        }
        .nav-link.active {
            color: #ffffff;
            background: linear-gradient(90deg, rgba(37,99,235,0.25), rgba(37,99,235,0.08));
            border-left: 3px solid #3b82f6;
            font-weight: 600;
        }
        .nav-link.active i {
            color: #3b82f6;
        }
        .nav-badge {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            background: rgba(255,255,255,0.08);
            color: #cbd5e1;
        }
        .nav-link.active .nav-badge {
            background: #2563eb;
            color: #ffffff;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            background: rgba(11,19,41,0.5);
        }
        .user-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0.6rem;
            background: var(--sidebar-card);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: var(--radius-md);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #2563eb;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.78rem;
        }
        .user-meta h5 {
            font-size: 0.82rem;
            color: #f1f5f9;
            font-weight: 600;
            line-height: 1.2;
        }
        .user-meta span {
            font-size: 0.7rem;
            color: #10b981;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .user-meta span::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10b981;
        }
        .btn-logout {
            color: #94a3b8;
            background: none;
            border: none;
            font-size: 1.15rem;
            cursor: pointer;
            padding: 0.3rem;
            border-radius: 6px;
            transition: all 0.2s;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .btn-logout:hover {
            color: #ef4444;
            background: rgba(239,68,68,0.1);
        }

        /* ─── MAIN CONTENT ───────────────────────────────────── */
        .admin-main {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--bg);
        }

        /* Top Bar */
        .admin-topbar {
            height: 68px;
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-main);
            cursor: pointer;
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.88rem;
            color: var(--text-muted);
        }
        .breadcrumb a {
            color: var(--text-muted);
            text-decoration: none;
        }
        .breadcrumb-active {
            color: var(--text-main);
            font-weight: 600;
        }
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 1rem;
            border-radius: var(--radius-md);
            font-size: 0.86rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }
        .btn-primary {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(37,99,235,0.25);
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: #ffffff;
            border: 1px solid var(--border);
            color: #334155;
        }
        .btn-secondary:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }
        .btn-success {
            background: #10b981;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(16,185,129,0.25);
        }
        .btn-success:hover {
            background: #059669;
        }
        .btn-danger {
            background: #ef4444;
            color: #ffffff;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .btn-sm {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
            border-radius: var(--radius-sm);
        }

        /* Canvas */
        .admin-canvas {
            padding: 2rem;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }

        /* Alert */
        .alert-bar {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 0.9rem 1.25rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: var(--shadow-subtle);
        }

        /* KPI Cards Row */
        .kpi-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 2rem;
        }
        .kpi-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.35rem 1.5rem;
            box-shadow: var(--shadow-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .kpi-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: var(--shadow-card);
        }
        .kpi-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.4rem;
        }
        .kpi-value {
            font-family: var(--font-head);
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1;
        }
        .kpi-sub {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 0.4rem;
        }
        .kpi-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .icon-blue { background: #eff6ff; color: #2563eb; }
        .icon-emerald { background: #ecfdf5; color: #10b981; }
        .icon-purple { background: #f5f3ff; color: #7c3aed; }
        .icon-amber { background: #fffbeb; color: #d97706; }

        /* Component Panels */
        .tab-panel {
            display: none;
            animation: fadeIn 0.2s ease;
        }
        .tab-panel.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .panel-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.85rem;
            box-shadow: var(--shadow-subtle);
            margin-bottom: 2rem;
        }
        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1.1rem;
            border-bottom: 1px solid var(--border-light);
        }
        .panel-header-info h2 {
            font-family: var(--font-head);
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
        }
        .panel-header-info p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 0.2rem;
        }

        /* Forms */
        .subfilter-bar {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .btn-subfilter {
            background: #ffffff;
            border: 1px solid var(--border);
            color: #64748b;
            padding: 0.38rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-subfilter:hover {
            border-color: #cbd5e1;
            color: #0f172a;
            background: #f8fafc;
        }
        .btn-subfilter.active {
            background: #4B86A5;
            border-color: #4B86A5;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(75,134,165,0.25);
        }
        .form-drawer {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            margin-bottom: 1.8rem;
            box-shadow: inset 0 1px 2px rgba(15,23,42,0.02);
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }
        .form-col-full {
            grid-column: span 2;
        }
        .screenshot-row {
            display: grid;
            grid-template-columns: 56px minmax(180px, 1.1fr) minmax(180px, 1.4fr) auto;
            gap: 0.75rem;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.6rem 0.75rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            margin-bottom: 0.25rem;
        }
        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.4rem;
        }
        .form-control {
            width: 100%;
            padding: 0.65rem 0.9rem;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 0.9rem;
            background: #ffffff;
            color: var(--text-main);
            outline: none;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-ring);
        }
        textarea.form-control {
            resize: vertical;
            min-height: 85px;
        }
        .form-actions {
            margin-top: 1.2rem;
            display: flex;
            gap: 0.6rem;
        }

        /* Tables */
        .table-wrap {
            overflow-x: auto;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            background: #ffffff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th {
            background: #f8fafc;
            color: #64748b;
            font-size: 0.74rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.06em;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border);
        }
        td {
            padding: 0.95rem 1rem;
            border-bottom: 1px solid var(--border-light);
            font-size: 0.88rem;
            vertical-align: middle;
            color: #334155;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover td {
            background: #fbfcfe;
        }
        .img-thumb {
            width: 60px;
            height: 38px;
            object-fit: cover;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            background: #e2e8f0;
        }
        .pill-badge {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            background: rgba(37,99,235,0.08);
            color: var(--primary);
            display: inline-block;
            margin: 0.1rem;
        }
        .status-pill-green {
            background: #ecfdf5;
            color: #065f46;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            display: inline-block;
        }
        .actions-cell {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* Responsive Breakpoints */
        .admin-sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 1040;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }
        @media (max-width: 1024px) {
            .kpi-row { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            :root { --sidebar-width: 0px; }
            .admin-sidebar {
                transform: translateX(-100%);
                width: 270px;
                max-width: 85vw;
                z-index: 1050;
                height: 100vh;
                height: 100dvh;
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .admin-sidebar-backdrop {
                display: block;
            }
            .admin-sidebar-backdrop.open {
                opacity: 1;
                pointer-events: auto;
            }
            .admin-topbar {
                padding: 0 1rem;
            }
            .topbar-actions span {
                display: none;
            }
            .mobile-toggle {
                display: block;
            }
            .admin-main {
                margin-left: 0;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-col-full {
                grid-column: span 1;
            }
            .screenshot-row {
                grid-template-columns: 1fr !important;
                gap: 0.5rem !important;
            }
            .kpi-row {
                grid-template-columns: 1fr;
            }
            .admin-canvas {
                padding: 1rem;
            }
            .panel-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.85rem;
            }
            .panel-header button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <!-- ─── LEFT SIDEBAR ─────────────────────────────────── -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div>
            <!-- Header / Brand -->
            <div class="sidebar-header">
                <a href="<?= htmlspecialchars($adminSelfUrl) ?>" class="brand-box">
                    <div class="brand-monogram" style="overflow:hidden; border:1.5px solid rgba(255,255,255,0.15);">
                        <img src="/images/logo-square.png" alt="Ganga Sagar" style="width:100%; height:100%; object-fit:cover; display:block;" />
                    </div>
                    <div class="brand-text">
                        <h4>Ganga Sagar</h4>
                        <span>Portfolio CMS</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="sidebar-scroll">
                <div class="sidebar-section-title">CMS Management</div>

                <button class="nav-link active" onclick="switchTab('tab-featured', 'Featured Projects', this)">
                    <div class="nav-link-left">
                        <i class="bx bx-folder"></i>
                        <span>Featured Projects</span>
                    </div>
                    <span class="nav-badge"><?= count($featured_list) ?></span>
                </button>

                <button class="nav-link" onclick="switchTab('tab-products', 'Strataflow Products', this)">
                    <div class="nav-link-left">
                        <i class="bx bx-cube-alt"></i>
                        <span>Strataflow Products</span>
                    </div>
                    <span class="nav-badge"><?= count($company_products_list) ?></span>
                </button>

                <button class="nav-link" onclick="switchTab('tab-academic-other', 'Academic & Other Projects', this)">
                    <div class="nav-link-left">
                        <i class="bx bx-collection"></i>
                        <span>Academic &amp; Other Projects</span>
                    </div>
                    <span class="nav-badge"><?= count($academic_list) + count($other_projects_list) ?></span>
                </button>

                <button class="nav-link" onclick="switchTab('tab-experiences', 'Work Experiences', this)">
                    <div class="nav-link-left">
                        <i class="bx bx-briefcase-alt-2"></i>
                        <span>Work Experience</span>
                    </div>
                    <span class="nav-badge"><?= count($experiences_list) ?></span>
                </button>

                <button class="nav-link" onclick="switchTab('tab-blog', 'Insights & Articles', this)">
                    <div class="nav-link-left">
                        <i class="bx bx-news"></i>
                        <span>Insights &amp; Articles</span>
                    </div>
                    <span class="nav-badge"><?= count($blog_list) ?></span>
                </button>

                <button class="nav-link" onclick="switchTab('tab-awards', 'Awards & Gallery', this)">
                    <div class="nav-link-left">
                        <i class="bx bx-trophy"></i>
                        <span>Awards &amp; Gallery</span>
                    </div>
                    <span class="nav-badge"><?= count($awards_list) ?></span>
                </button>

                <button class="nav-link" onclick="switchTab('tab-promotions', 'Offer Pop-up Window', this)">
                    <div class="nav-link-left">
                        <i class="bx bx-gift"></i>
                        <span>Offer Pop-up Window</span>
                    </div>
                    <span class="nav-badge"><?= count($promotions_list) ?></span>
                </button>

                <button class="nav-link" onclick="switchTab('tab-inquiries', 'Contact Inquiries', this)">
                    <div class="nav-link-left">
                        <i class="bx bx-envelope"></i>
                        <span>Contact Inquiries</span>
                    </div>
                    <span class="nav-badge"><?= count($inquiries_list) ?></span>
                </button>
            </div>
        </div>

        <!-- Sidebar Footer User Profile -->
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-info">
                    <div class="user-avatar" style="overflow:hidden; border:1.5px solid rgba(255,255,255,0.25);">
                        <img src="/images/logo-square.png" alt="Ganga Sagar Joshi" style="width:100%; height:100%; object-fit:cover; display:block;" />
                    </div>
                    <div class="user-meta">
                        <h5>Ganga Sagar Joshi</h5>
                        <span>Admin Online</span>
                    </div>
                </div>
                <a href="?action=logout" class="btn-logout" title="Sign Out">
                    <i class="bx bx-log-out"></i>
                </a>
            </div>
        </div>
    </aside>
    <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop" onclick="toggleSidebar()"></div>

    <!-- ─── MAIN CONTENT AREA ────────────────────────────── -->
    <div class="admin-main">

        <!-- Top Header Navigation -->
        <header class="admin-topbar">
            <div class="topbar-left">
                <button class="mobile-toggle" onclick="toggleSidebar()"><i class="bx bx-menu"></i></button>
                <div class="breadcrumb">
                    <a href="<?= htmlspecialchars($adminSelfUrl) ?>">Admin</a>
                    <span>/</span>
                    <span class="breadcrumb-active" id="activeBreadcrumb">Featured Projects</span>
                </div>
            </div>
            <div class="topbar-actions">
                <a href="/" target="_blank" class="btn btn-primary btn-sm">
                    <i class="bx bx-link-external"></i>
                    <span>View Live Site</span>
                </a>
            </div>
        </header>

        <!-- Canvas Area -->
        <main class="admin-canvas">

            <!-- Flash Success / Error Notification -->
            <?php if (!empty($message)): ?>
                <div class="alert-bar" style="background:#059669; color:#fff;">
                    <span><i class="bx bx-check-circle" style="vertical-align:middle; font-size:1.15rem; margin-right:0.3rem;"></i> <?= htmlspecialchars($message) ?></span>
                    <span style="cursor:pointer; font-size:1.2rem;" onclick="this.parentElement.style.display='none';">&times;</span>
                </div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert-bar" style="background:#dc2626; color:#fff;">
                    <span><i class="bx bx-error-circle" style="vertical-align:middle; font-size:1.15rem; margin-right:0.3rem;"></i> <?= htmlspecialchars($error) ?></span>
                    <span style="cursor:pointer; font-size:1.2rem;" onclick="this.parentElement.style.display='none';">&times;</span>
                </div>
            <?php endif; ?>

            <!-- Metric KPI Summary Cards -->
            <div class="kpi-row">
                <div class="kpi-card" onclick="switchTab('tab-featured', 'Featured Projects', document.querySelectorAll('.nav-link')[0])">
                    <div>
                        <div class="kpi-title">Featured Projects</div>
                        <div class="kpi-value"><?= count($featured_list) ?></div>
                        <div class="kpi-sub">Portfolio Showcase Works</div>
                    </div>
                    <div class="kpi-icon-box icon-blue">
                        <i class="bx bx-folder"></i>
                    </div>
                </div>

                <div class="kpi-card" onclick="switchTab('tab-products', 'Strataflow Products', document.querySelectorAll('.nav-link')[1])">
                    <div>
                        <div class="kpi-title">Strataflow Products</div>
                        <div class="kpi-value"><?= count($company_products_list) ?></div>
                        <div class="kpi-sub">Commercial Products &amp; SaaS</div>
                    </div>
                    <div class="kpi-icon-box icon-emerald">
                        <i class="bx bx-cube-alt"></i>
                    </div>
                </div>

                <div class="kpi-card" onclick="switchTab('tab-blog', 'Insights & Articles', document.querySelectorAll('.nav-link')[3])">
                    <div>
                        <div class="kpi-title">Insights &amp; Articles</div>
                        <div class="kpi-value"><?= count($blog_list) ?></div>
                        <div class="kpi-sub">Engineering Publications</div>
                    </div>
                    <div class="kpi-icon-box icon-purple">
                        <i class="bx bx-news"></i>
                    </div>
                </div>

                <div class="kpi-card" onclick="switchTab('tab-inquiries', 'Contact Inquiries')">
                    <div>
                        <div class="kpi-title">Inquiries</div>
                        <div class="kpi-value"><?= count($inquiries_list) ?></div>
                        <div class="kpi-sub">Direct Messages</div>
                    </div>
                    <div class="kpi-icon-box icon-amber">
                        <i class="bx bx-envelope"></i>
                    </div>
                </div>
            </div>

            <!-- ───────────────────────────────────────────────────────────── -->
            <!-- COMPONENT TAB 1: FEATURED PROJECTS -->
            <!-- ───────────────────────────────────────────────────────────── -->
            <section id="tab-featured" class="tab-panel active">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Featured Projects</h2>
                            <p>Manage dynamic projects displayed on the portfolio homepage and project detail pages</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-featured')">
                            <i class="bx bx-plus"></i> Add New Project
                        </button>
                    </div>

                    <!-- Collapsible Add/Edit Form -->
                    <div id="form-add-featured" class="form-drawer" style="display:none;">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <input type="hidden" name="feat_id" id="feat_id" value="0">
                            <div class="form-grid">
                                <div>
                                    <label class="form-label">Project Title</label>
                                    <input type="text" name="title" id="feat_title" class="form-control" required placeholder="e.g. Oralix — Dental Practice OS" onkeyup="autoFillSlug(this.value)">
                                </div>
                                <div>
                                    <label class="form-label">URL Slug </label>
                                    <input type="text" name="slug" id="feat_slug" class="form-control" placeholder="auto-generated-if-empty">
                                </div>
                                <div>
                                    <label class="form-label">Category Badge</label>
                                    <input type="text" name="category_badge" id="feat_category_badge" class="form-control" placeholder="e.g. FEATURED PROJECT or HEALTHCARE">
                                </div>
                                <div>
                                    <label class="form-label">Project Category</label>
                                    <input type="text" name="category" id="feat_category" class="form-control" placeholder="e.g. Healthcare Website & Practice OS">
                                </div>
                                <div>
                                    <label class="form-label">Client / Organization</label>
                                    <input type="text" name="client_name" id="feat_client_name" class="form-control" placeholder="e.g. Niko Dental & Aesthetics">
                                </div>
                                <div>
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" id="feat_location" class="form-control" placeholder="e.g. Kathmandu & Dhangadi, Nepal">
                                </div>
                                <div>
                                    <label class="form-label">Official Site / Domain</label>
                                    <input type="text" name="official_site" id="feat_official_site" class="form-control" placeholder="e.g. nikodentistry.com">
                                </div>
                                <div>
                                    <label class="form-label">Live Site URL</label>
                                    <input type="text" name="live_url" id="feat_live_url" class="form-control" required value="https://github.com/gangasagar12">
                                </div>
                                <div>
                                    <label class="form-label">Cover Image </label>
                                    <div style="display:flex; align-items:center; gap:0.75rem;">
                                        <input type="file" name="feat_image_file" id="feat_image_file" class="form-control" accept="image/*" onchange="previewLocalImage(this, 'feat_preview_img')">
                                        <div style="width:50px; height:38px; border-radius:4px; overflow:hidden; border:1px solid #cbd5e1; background:#f8fafc; flex-shrink:0;">
                                            <img id="feat_preview_img" src="/images/projectcard1.jpg" alt="" style="width:100%; height:100%; object-fit:cover;" onerror="this.src='/images/projectcard1.jpg'">
                                        </div>
                                    </div>
                                    <input type="hidden" name="image" id="feat_image" value="images/projectcard1.jpg">
                                </div>
                                <div>
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="display_order" id="feat_order" class="form-control" value="1">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Technologies &amp; Skills (comma-separated)</label>
                                    <input type="text" name="skills" id="feat_skills" class="form-control" required placeholder="Python, Django, MySQL, HTML5, CSS3, JavaScript, Docker">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Short Summary (Homepage Card)</label>
                                    <textarea name="short_description" id="feat_short_desc" class="form-control" rows="2" placeholder="Brief 1-2 sentence summary displayed on the homepage project card..."></textarea>
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Full Project Introduction (Case Study Section 1)</label>
                                    <textarea name="description" id="feat_desc" class="form-control" rows="4" required placeholder="Comprehensive background, context, client requirements, and solution overview..."></textarea>
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Core Features &amp; System Capabilities </label>
                                    <textarea name="features" id="feat_features" class="form-control" rows="4" placeholder="Real-time chair scheduling and appointment booking&#10;Electronic Health Records (EHR) with digital dental charting&#10;Multi-tier billing and invoice calculation"></textarea>
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Business Impact &amp; Results </label>
                                    <textarea name="impact" id="feat_impact" class="form-control" rows="3" placeholder="Measurable outcomes, improvements, operational results..."></textarea>
                                </div>
                                <div class="form-col-full">
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.4rem;">
                                        <label class="form-label" style="margin-bottom:0; font-weight:600; display:flex; align-items:center; gap:0.4rem;">
                                            <i class="bx bx-images" style="color:#0ea5e9; font-size:1.15rem;"></i>
                                            <span>Demo Screenshots (Upload Images from Desktop)</span>
                                        </label>
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="addScreenshotRow()" style="padding:0.3rem 0.75rem; font-size:0.8rem; display:inline-flex; align-items:center; gap:4px;">
                                            <i class="bx bx-plus"></i> Add Screenshot
                                        </button>
                                    </div>
                                    <p style="font-size:0.8rem; color:#64748b; margin-bottom:0.75rem;">
                                        Select and upload screenshot images directly from your computer / desktop. Add an optional caption / title for each screenshot.
                                    </p>

                                    <!-- Dynamic Screenshot Upload Rows Container -->
                                    <div id="screenshots_container" style="display:flex; flex-direction:column; gap:0.75rem;">
                                        <!-- Dynamically generated rows via JavaScript -->
                                    </div>

                                    <!-- Hidden input for backward compatibility -->
                                    <input type="hidden" name="screenshots" id="feat_screenshots" value="">
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="save_featured" class="btn btn-primary btn-sm">Save Project</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="resetAndCloseForm('form-add-featured', 'feat_id')">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Preview</th>
                                    <th>Project Title &amp; Slug</th>
                                    <th>Category &amp; Client</th>
                                    <th>Technologies</th>
                                    <th>Case Study</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($featured_list as $fp): 
                                    $pSlug = !empty($fp['slug']) ? $fp['slug'] : \App\Models\FeaturedProject::slugify($fp['title']);
                                ?>
                                <tr>
                                    <td><img src="/<?= htmlspecialchars($fp['image']) ?>" class="img-thumb" alt="" onerror="this.onerror=null; this.src='/images/projectcard1.jpg'"></td>
                                    <td>
                                        <strong><?= htmlspecialchars($fp['title']) ?></strong>
                                        <div style="font-size:0.75rem; color:#38bdf8; font-family:monospace; margin-top:2px;">/works/<?= htmlspecialchars($pSlug) ?></div>
                                    </td>
                                    <td>
                                        <div style="font-weight:600; font-size:0.85rem; color:#0f172a;"><?= htmlspecialchars($fp['category'] ?? 'Web App') ?></div>
                                        <div style="font-size:0.75rem; color:#64748b;"><?= htmlspecialchars($fp['client_name'] ?? 'Production Client') ?></div>
                                    </td>
                                    <td>
                                        <?php foreach ($fp['skills_array'] as $s): ?>
                                            <span class="pill-badge"><?= htmlspecialchars($s) ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <a href="/works/<?= htmlspecialchars($pSlug) ?>" target="_blank" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:0.3rem;" title="View Live Dynamic Case Study">
                                            <i class="bx bx-show"></i> View
                                        </a>
                                    </td>
                                    <td><span style="font-weight:600; font-size:0.84rem;"><?= intval($fp['display_order']) ?></span></td>
                                    <td>
                                        <div class="actions-cell">
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="editFeatured(<?= (int)$fp['id'] ?>)" title="Edit"><i class="bx bx-edit"></i></button>
                                            <a href="?del_feat=<?= $fp['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this project?')" title="Delete"><i class="bx bx-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ───────────────────────────────────────────────────────────── -->
            <!-- COMPONENT TAB: WORK EXPERIENCES -->
            <!-- ───────────────────────────────────────────────────────────── -->
            <section id="tab-experiences" class="tab-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Work Experiences &amp; Roles</h2>
                            <p>Professional engineering history, leadership positions, and client consultancy</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-experience')">
                            <i class="bx bx-plus"></i> Add New Experience
                        </button>
                    </div>

                    <!-- Collapsible Add/Edit Form for Experiences -->
                    <div id="form-add-experience" class="form-drawer" style="display:none;">
                        <form method="POST" action="">
                            <input type="hidden" name="save_experience" value="1">
                            <input type="hidden" name="exp_id" id="exp_id" value="0">

                            <div class="form-grid">
                                <div>
                                    <label class="form-label">Company / Organization</label>
                                    <input type="text" name="company" id="exp_company" class="form-control" required placeholder="e.g. Strataflow Technologies Pvt. Ltd.">
                                </div>
                                <div>
                                    <label class="form-label">Role / Job Title</label>
                                    <input type="text" name="role" id="exp_role" class="form-control" required placeholder="e.g. Co-Founder & Technical Lead">
                                </div>
                                <div>
                                    <label class="form-label">Period / Duration</label>
                                    <input type="text" name="period" id="exp_period" class="form-control" required placeholder="e.g. 2024 – Present or 2023 – 2024">
                                </div>
                                <div>
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" id="exp_location" class="form-control" placeholder="e.g. Kathmandu, Nepal or Remote">
                                </div>
                                <div>
                                    <label class="form-label">Status</label>
                                    <select name="status" id="exp_status" class="form-control">
                                        <option value="Active">Active / Current</option>
                                        <option value="Completed">Completed / Previous</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="display_order" id="exp_order" class="form-control" value="0">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Technologies &amp; Key Skills (comma-separated)</label>
                                    <input type="text" name="skills" id="exp_skills" class="form-control" placeholder="e.g. Python, Django, PostgreSQL, REST Framework, MySQL, AWS">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Role Summary &amp; Overview</label>
                                    <textarea name="description" id="exp_description" class="form-control" rows="3" required placeholder="High-level summary of your responsibilities, leadership, and technical focus..."></textarea>
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Key Responsibilities &amp; Impact (1 bullet point per line)</label>
                                    <textarea name="responsibilities" id="exp_responsibilities" class="form-control" rows="4" placeholder="Architected and deployed enterprise clinic management platforms&#10;Engineered normalized relational database schemas handling high traffic&#10;Implemented stateless REST APIs with JWT authentication and RBAC"></textarea>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-sm">Save Experience</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="resetAndCloseForm('form-add-experience', 'exp_id')">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Experiences Table -->
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Company &amp; Role</th>
                                    <th>Period &amp; Status</th>
                                    <th>Location</th>
                                    <th>Skills</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($experiences_list)): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center; padding:2rem; color:#64748b;">
                                        No experiences added yet. Click &quot;Add New Experience&quot; to add your first position.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($experiences_list as $exp): ?>
                                    <tr>
                                        <td>
                                            <div style="font-weight:600; color:var(--text-main); font-size:0.95rem;"><?= htmlspecialchars($exp['company']) ?></div>
                                            <div style="font-size:0.82rem; color:var(--primary); font-weight:500;"><?= htmlspecialchars($exp['role']) ?></div>
                                        </td>
                                        <td>
                                            <div style="font-weight:500; font-size:0.86rem;"><?= htmlspecialchars($exp['period']) ?></div>
                                            <span class="badge <?= ($exp['status'] ?? 'Active') === 'Active' ? 'badge-success' : 'badge-secondary' ?>">
                                                <?= htmlspecialchars($exp['status'] ?? 'Active') ?>
                                            </span>
                                        </td>
                                        <td style="font-size:0.85rem; color:#64748b;"><?= htmlspecialchars($exp['location'] ?? '—') ?></td>
                                        <td style="max-width:240px;">
                                            <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                                <?php 
                                                $skillsArr = !empty($exp['skills']) ? array_slice(array_map('trim', explode(',', $exp['skills'])), 0, 4) : [];
                                                foreach ($skillsArr as $sk): ?>
                                                    <span class="tech-pill" style="font-size:0.7rem; padding:2px 6px;"><?= htmlspecialchars($sk) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td><?= intval($exp['display_order'] ?? 0) ?></td>
                                        <td>
                                            <div class="actions-cell">
                                                <button type="button" class="btn btn-secondary btn-sm" onclick="editExperience(<?= (int)$exp['id'] ?>)" title="Edit"><i class="bx bx-edit"></i></button>
                                                <a href="?del_exp=<?= $exp['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this experience?')" title="Delete"><i class="bx bx-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ───────────────────────────────────────────────────────────── -->
            <!-- COMPONENT TAB 2: STRATAFLOW PRODUCTS -->
            <!-- ───────────────────────────────────────────────────────────── -->
            <section id="tab-products" class="tab-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Strataflow Products &amp; Solutions</h2>
                            <p>Manage products showcased dynamically on the Founder Profile page</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-product')">
                            <i class="bx bx-plus"></i> Add New Product
                        </button>
                    </div>

                    <!-- Collapsible Add/Edit Form -->
                    <div id="form-add-product" class="form-drawer" style="display:none;">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <input type="hidden" name="prod_id" id="prod_id" value="0">
                            <div class="form-grid">
                                <div>
                                    <label class="form-label">Product Title</label>
                                    <input type="text" name="title" id="prod_title" class="form-control" required placeholder="e.g. Dental Clinic Management Software">
                                </div>
                                <div>
                                    <label class="form-label">Category</label>
                                    <input type="text" name="category" id="prod_category" class="form-control" required placeholder="e.g. Healthcare Operating Platform">
                                </div>
                                <div>
                                    <label class="form-label">Status Badge Text</label>
                                    <input type="text" name="status_text" id="prod_status" class="form-control" required value="Live Product" placeholder="Live Product / In Development / Enterprise MVP">
                                </div>
                                <div>
                                    <label class="form-label">Thumbnail </label>
                                    <div style="display:flex; align-items:center; gap:0.75rem;">
                                        <input type="file" name="prod_image_file" id="prod_image_file" class="form-control" accept="image/*" onchange="previewLocalImage(this, 'prod_preview_img')">
                                        <div style="width:50px; height:38px; border-radius:4px; overflow:hidden; border:1px solid #cbd5e1; background:#f8fafc; flex-shrink:0;">
                                            <img id="prod_preview_img" src="/images/projectcard1.jpg" alt="" style="width:100%; height:100%; object-fit:cover;" onerror="this.src='/images/projectcard1.jpg'">
                                        </div>
                                    </div>
                                    <input type="hidden" name="image" id="prod_image" value="images/projectcard1.jpg">
                                </div>
                                <div>
                                    <label class="form-label">Product Website / Demo URL</label>
                                    <input type="text" name="link_url" id="prod_link_url" class="form-control" required value="https://github.com/gangasagar12">
                                </div>
                                <div>
                                    <label class="form-label">Button Label</label>
                                    <input type="text" name="link_text" id="prod_link_text" class="form-control" required value="View Details">
                                </div>
                                <div>
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="display_order" id="prod_order" class="form-control" value="1">
                                </div>
                                <div>
                                    <label class="form-label">Tech Stack Tags </label>
                                    <input type="text" name="tech_pills" id="prod_pills" class="form-control" placeholder="Python, Django, PostgreSQL, REST APIs, Docker">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Short Description</label>
                                    <textarea name="description" id="prod_desc" class="form-control" required placeholder="Detailed overview of what this product solves..."></textarea>
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Key Features (one per line)</label>
                                    <textarea name="features" id="prod_features" class="form-control" style="min-height:95px;" placeholder="Patient Records & Medical History&#10;Real-time Appointment Scheduling&#10;Dental Treatment Records&#10;Automated Billing & Invoicing"></textarea>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="save_company_product" class="btn btn-primary btn-sm">Save Product</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="resetAndCloseForm('form-add-product', 'prod_id')">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Preview</th>
                                    <th>Product &amp; Category</th>
                                    <th>Status</th>
                                    <th>Features Count</th>
                                    <th>Technologies</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($company_products_list as $prod): ?>
                                <tr>
                                    <td><img src="/<?= htmlspecialchars($prod['image']) ?>" class="img-thumb" alt="" onerror="this.src='/images/projectcard1.jpg'"></td>
                                    <td>
                                        <strong><?= htmlspecialchars($prod['title']) ?></strong>
                                        <div style="font-size:0.75rem; color:#94a3b8; margin-top:2px;"><?= htmlspecialchars($prod['category']) ?></div>
                                    </td>
                                    <td><span class="status-pill-green"><?= htmlspecialchars($prod['status_text']) ?></span></td>
                                    <td style="font-size:0.82rem; color:var(--text-muted);">
                                        <?= count($prod['features_array'] ?? []) ?> key feature points
                                    </td>
                                    <td>
                                        <?php foreach (array_slice($prod['pills_array'] ?? [], 0, 3) as $tp): ?>
                                            <span class="pill-badge"><?= htmlspecialchars($tp) ?></span>
                                        <?php endforeach; ?>
                                        <?php if (count($prod['pills_array'] ?? []) > 3): ?>
                                            <span style="font-size:0.74rem; color:var(--text-muted); font-weight:600;">+<?= count($prod['pills_array']) - 3 ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><span style="font-weight:600; font-size:0.84rem;"><?= intval($prod['display_order']) ?></span></td>
                                    <td>
                                        <div class="actions-cell">
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="editProduct(<?= (int)$prod['id'] ?>)" title="Edit"><i class="bx bx-edit"></i></button>
                                            <a href="?del_prod=<?= $prod['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this Strataflow product?')" title="Delete"><i class="bx bx-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ───────────────────────────────────────────────────────────── -->
            <!-- COMPONENT TAB: WORK EXPERIENCES -->
            <!-- ───────────────────────────────────────────────────────────── -->
            <section id="tab-experiences" class="tab-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Work Experiences &amp; Roles</h2>
                            <p>Professional engineering history, leadership positions, and client consultancy</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-experience')">
                            <i class="bx bx-plus"></i> Add New Experience
                        </button>
                    </div>

                    <!-- Collapsible Add/Edit Form for Experiences -->
                    <div id="form-add-experience" class="form-drawer" style="display:none;">
                        <form method="POST" action="">
                            <input type="hidden" name="save_experience" value="1">
                            <input type="hidden" name="exp_id" id="exp_id" value="0">

                            <div class="form-grid">
                                <div>
                                    <label class="form-label">Company / Organization</label>
                                    <input type="text" name="company" id="exp_company" class="form-control" required placeholder="e.g. Strataflow Technologies Pvt. Ltd.">
                                </div>
                                <div>
                                    <label class="form-label">Role / Job Title</label>
                                    <input type="text" name="role" id="exp_role" class="form-control" required placeholder="e.g. Co-Founder & Technical Lead">
                                </div>
                                <div>
                                    <label class="form-label">Period / Duration</label>
                                    <input type="text" name="period" id="exp_period" class="form-control" required placeholder="e.g. 2024 – Present or 2023 – 2024">
                                </div>
                                <div>
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" id="exp_location" class="form-control" placeholder="e.g. Kathmandu, Nepal or Remote">
                                </div>
                                <div>
                                    <label class="form-label">Status</label>
                                    <select name="status" id="exp_status" class="form-control">
                                        <option value="Active">Active / Current</option>
                                        <option value="Completed">Completed / Previous</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="display_order" id="exp_order" class="form-control" value="0">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Technologies &amp; Key Skills (comma-separated)</label>
                                    <input type="text" name="skills" id="exp_skills" class="form-control" placeholder="e.g. Python, Django, PostgreSQL, REST Framework, MySQL, AWS">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Role Summary &amp; Overview</label>
                                    <textarea name="description" id="exp_description" class="form-control" rows="3" required placeholder="High-level summary of your responsibilities, leadership, and technical focus..."></textarea>
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Key Responsibilities &amp; Impact (1 bullet point per line)</label>
                                    <textarea name="responsibilities" id="exp_responsibilities" class="form-control" rows="4" placeholder="Architected and deployed enterprise clinic management platforms&#10;Engineered normalized relational database schemas handling high traffic&#10;Implemented stateless REST APIs with JWT authentication and RBAC"></textarea>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-sm">Save Experience</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="resetAndCloseForm('form-add-experience', 'exp_id')">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Experiences Table -->
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Company &amp; Role</th>
                                    <th>Period &amp; Status</th>
                                    <th>Location</th>
                                    <th>Skills</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($experiences_list)): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center; padding:2rem; color:#64748b;">
                                        No experiences added yet. Click &quot;Add New Experience&quot; to add your first position.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($experiences_list as $exp): ?>
                                    <tr>
                                        <td>
                                            <div style="font-weight:600; color:var(--text-main); font-size:0.95rem;"><?= htmlspecialchars($exp['company']) ?></div>
                                            <div style="font-size:0.82rem; color:var(--primary); font-weight:500;"><?= htmlspecialchars($exp['role']) ?></div>
                                        </td>
                                        <td>
                                            <div style="font-weight:500; font-size:0.86rem;"><?= htmlspecialchars($exp['period']) ?></div>
                                            <span class="badge <?= ($exp['status'] ?? 'Active') === 'Active' ? 'badge-success' : 'badge-secondary' ?>">
                                                <?= htmlspecialchars($exp['status'] ?? 'Active') ?>
                                            </span>
                                        </td>
                                        <td style="font-size:0.85rem; color:#64748b;"><?= htmlspecialchars($exp['location'] ?? '—') ?></td>
                                        <td style="max-width:240px;">
                                            <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                                <?php 
                                                $skillsArr = !empty($exp['skills']) ? array_slice(array_map('trim', explode(',', $exp['skills'])), 0, 4) : [];
                                                foreach ($skillsArr as $sk): ?>
                                                    <span class="tech-pill" style="font-size:0.7rem; padding:2px 6px;"><?= htmlspecialchars($sk) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td><?= intval($exp['display_order'] ?? 0) ?></td>
                                        <td>
                                            <div class="actions-cell">
                                                <button type="button" class="btn btn-secondary btn-sm" onclick="editExperience(<?= (int)$exp['id'] ?>)" title="Edit"><i class="bx bx-edit"></i></button>
                                                <a href="?del_exp=<?= $exp['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this experience?')" title="Delete"><i class="bx bx-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ───────────────────────────────────────────────────────────── -->
            <!-- COMPONENT TAB 3: ACADEMIC & OTHER PROJECTS (COMBINED) -->
            <!-- ───────────────────────────────────────────────────────────── -->
            <section id="tab-academic-other" class="tab-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Academic &amp; Other Projects</h2>
                            <p>Manage undergraduate coursework projects and specialized engineering tools in one place</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="openCombinedForm()">
                            <i class="bx bx-plus"></i> Add New Project
                        </button>
                    </div>

                    <!-- Collapsible Add/Edit Form -->
                    <div id="form-add-combined-project" class="form-drawer" style="display:none;">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <input type="hidden" name="combined_id" id="comb_id" value="0">
                            <input type="hidden" name="original_type" id="comb_original_type" value="academic">
                            <input type="hidden" name="existing_image" id="comb_existing_image" value="images/projectcard1.jpg">

                            <div class="form-grid">
                                <div>
                                    <label class="form-label">Project Type / Category</label>
                                    <select name="project_type" id="comb_project_type" class="form-control" onchange="toggleProjectTypeFields(this.value)">
                                        <option value="academic">Academic Project (Coursework / University)</option>
                                        <option value="other">Some Other Project (DevOps / Tools / Open Source)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Project Title</label>
                                    <input type="text" name="title" id="comb_title" class="form-control" required placeholder="e.g. Barcode -based Student Attendance System">
                                </div>

                                <div class="form-col-full">
                                    <label class="form-label">Project Image </label>
                                    <div style="display:flex; align-items:center; gap:1.25rem;">
                                        <div style="flex:1;">
                                            <input type="file" name="image_file" id="comb_image_file" class="form-control" accept="image/*" onchange="previewLocalImage(this, 'comb_preview_img')">
                                            <small style="display:block; margin-top:0.35rem; color:#64748b; font-size:0.75rem;">
                                                Select image file directly from your local computer (JPG, PNG, WebP, GIF, SVG). It will be saved locally inside public uploads.
                                            </small>
                                        </div>
                                        <div id="comb_preview_wrap" style="width:78px; height:56px; border-radius:6px; overflow:hidden; border:1px solid #cbd5e1; background:#f8fafc; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                                            <img id="comb_preview_img" src="/images/projectcard1.jpg" alt="Preview" style="width:100%; height:100%; object-fit:cover;" onerror="this.src='/images/projectcard1.jpg'">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label">Technologies &amp; Skills </label>
                                    <input type="text" name="skills" id="comb_skills" class="form-control" required placeholder="e.g. Python, Django, MySQL, REST APIs">
                                </div>
                                <div>
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="display_order" id="comb_order" class="form-control" value="1">
                                </div>

                                <div id="comb_wrap_cat" style="display:none;">
                                    <label class="form-label">Category Sub-Tag </label>
                                    <input type="text" name="category" id="comb_cat" class="form-control" placeholder="e.g. DevOps & Infrastructure">
                                </div>
                                <div id="comb_wrap_urls" style="display:none;">
                                    <label class="form-label">GitHub Repository URL (Optional)</label>
                                    <input type="url" name="github_url" id="comb_github" class="form-control" placeholder="https://github.com/gangasagar12">
                                </div>

                                <div class="form-col-full">
                                    <label class="form-label">Card Short Description</label>
                                    <textarea name="description" id="comb_desc" class="form-control" rows="2" required placeholder="Short summary displayed on the card face..."></textarea>
                                </div>

                                <div class="form-col-full">
                                    <label class="form-label">Detailed Project Description (Modal Popup on Click)</label>
                                    <textarea name="detailed_description" id="comb_detailed_desc" class="form-control" rows="4" placeholder="Detailed architectural breakdown, semester context, implementation details displayed when the user clicks the card..."></textarea>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="save_combined_project" id="comb_submit_btn" class="btn btn-primary btn-sm">Save Project</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="resetCombinedForm()">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Subfilter Bar -->
                    <div class="subfilter-bar">
                        <span style="font-size:0.83rem; font-weight:600; color:#475569; margin-right:0.35rem;">Filter by Type:</span>
                        <button type="button" class="btn-subfilter active" id="subfilter-all" onclick="filterCombinedTable('all', this)">
                            All (<?= count($academic_list) + count($other_projects_list) ?>)
                        </button>
                        <button type="button" class="btn-subfilter" id="subfilter-academic" onclick="filterCombinedTable('academic', this)">
                            Academic Projects (<?= count($academic_list) ?>)
                        </button>
                        <button type="button" class="btn-subfilter" id="subfilter-other" onclick="filterCombinedTable('other', this)">
                            Some Other Projects (<?= count($other_projects_list) ?>)
                        </button>
                    </div>

                    <!-- Unified Table -->
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Project Title &amp; Type</th>
                                    <th>Short &amp; Detailed Description</th>
                                    <th>Technologies</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="combined-projects-tbody">
                                <?php foreach ($academic_list as $ap): ?>
                                <tr class="combined-row" data-type="academic">
                                    <td style="width:70px;">
                                        <img src="/<?= ltrim(htmlspecialchars($ap['image'] ?? 'images/projectcard1.jpg'), '/') ?>" alt="" style="width:56px; height:40px; object-fit:cover; border-radius:6px; border:1px solid #e2e8f0;" onerror="this.src='/images/projectcard1.jpg'">
                                    </td>
                                    <td style="width:24%;">
                                        <strong><?= htmlspecialchars($ap['title']) ?></strong>
                                        <div style="margin-top:0.25rem;">
                                            <span class="pill-badge" style="color:#0284c7; background:rgba(2,132,199,0.08); font-size:0.72rem;">Academic Project</span>
                                        </div>
                                    </td>
                                    <td style="font-size:0.84rem; color:var(--text-muted); line-height:1.45;">
                                        <div><?= htmlspecialchars($ap['description']) ?></div>
                                        <?php if (!empty($ap['detailed_description'])): ?>
                                        <div style="margin-top:0.35rem; font-size:0.78rem; color:#64748b; font-style:italic;">
                                            <strong>Detailed:</strong> <?= htmlspecialchars(mb_strimwidth($ap['detailed_description'], 0, 90, '...')) ?>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($ap['skills_array'] as $as): ?>
                                            <span class="pill-badge" style="color:#4B86A5; background:rgba(75,134,165,0.08);"><?= htmlspecialchars($as) ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                    <td><span style="font-weight:600; font-size:0.84rem;"><?= intval($ap['display_order']) ?></span></td>
                                    <td>
                                        <div class="actions-cell">
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="editCombinedProject(<?= (int)$ap['id'] ?>, 'academic')" title="Edit"><i class="bx bx-edit"></i></button>
                                            <a href="?del_combined=<?= $ap['id'] ?>&type=academic" class="btn btn-danger btn-sm" onclick="return confirm('Delete this academic project?')" title="Delete"><i class="bx bx-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>

                                <?php foreach ($other_projects_list as $op): ?>
                                <tr class="combined-row" data-type="other">
                                    <td style="width:70px;">
                                        <img src="/<?= ltrim(htmlspecialchars($op['image'] ?? 'images/projectcard3.webp'), '/') ?>" alt="" style="width:56px; height:40px; object-fit:cover; border-radius:6px; border:1px solid #e2e8f0;" onerror="this.src='/images/projectcard3.webp'">
                                    </td>
                                    <td style="width:24%;">
                                        <strong><?= htmlspecialchars($op['title']) ?></strong>
                                        <div style="margin-top:0.25rem; display:flex; gap:0.25rem; flex-wrap:wrap;">
                                            <span class="pill-badge" style="color:#7c3aed; background:rgba(124,58,237,0.08); font-size:0.72rem;">Other Project</span>
                                            <?php if (!empty($op['category'])): ?>
                                            <span class="pill-badge" style="color:#475569; background:#f1f5f9; font-size:0.72rem;"><?= htmlspecialchars($op['category']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td style="font-size:0.84rem; color:var(--text-muted); line-height:1.45;">
                                        <div><?= htmlspecialchars($op['description']) ?></div>
                                        <?php if (!empty($op['detailed_description'])): ?>
                                        <div style="margin-top:0.35rem; font-size:0.78rem; color:#64748b; font-style:italic;">
                                            <strong>Detailed:</strong> <?= htmlspecialchars(mb_strimwidth($op['detailed_description'], 0, 90, '...')) ?>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($op['skills_array'] as $os): ?>
                                            <span class="pill-badge" style="color:#4B86A5; background:rgba(75,134,165,0.08);"><?= htmlspecialchars($os) ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                    <td><span style="font-weight:600; font-size:0.84rem;"><?= intval($op['display_order']) ?></span></td>
                                    <td>
                                        <div class="actions-cell">
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="editCombinedProject(<?= (int)$op['id'] ?>, 'other')" title="Edit"><i class="bx bx-edit"></i></button>
                                            <a href="?del_combined=<?= $op['id'] ?>&type=other" class="btn btn-danger btn-sm" onclick="return confirm('Delete this other project?')" title="Delete"><i class="bx bx-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ───────────────────────────────────────────────────────────── -->
            <!-- COMPONENT TAB: WORK EXPERIENCES -->
            <!-- ───────────────────────────────────────────────────────────── -->
            <section id="tab-experiences" class="tab-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Work Experiences &amp; Roles</h2>
                            <p>Professional engineering history, leadership positions, and client consultancy</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-experience')">
                            <i class="bx bx-plus"></i> Add New Experience
                        </button>
                    </div>

                    <!-- Collapsible Add/Edit Form for Experiences -->
                    <div id="form-add-experience" class="form-drawer" style="display:none;">
                        <form method="POST" action="">
                            <input type="hidden" name="save_experience" value="1">
                            <input type="hidden" name="exp_id" id="exp_id" value="0">

                            <div class="form-grid">
                                <div>
                                    <label class="form-label">Company / Organization</label>
                                    <input type="text" name="company" id="exp_company" class="form-control" required placeholder="e.g. Strataflow Technologies Pvt. Ltd.">
                                </div>
                                <div>
                                    <label class="form-label">Role / Job Title</label>
                                    <input type="text" name="role" id="exp_role" class="form-control" required placeholder="e.g. Co-Founder & Technical Lead">
                                </div>
                                <div>
                                    <label class="form-label">Period / Duration</label>
                                    <input type="text" name="period" id="exp_period" class="form-control" required placeholder="e.g. 2024 – Present or 2023 – 2024">
                                </div>
                                <div>
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" id="exp_location" class="form-control" placeholder="e.g. Kathmandu, Nepal or Remote">
                                </div>
                                <div>
                                    <label class="form-label">Status</label>
                                    <select name="status" id="exp_status" class="form-control">
                                        <option value="Active">Active / Current</option>
                                        <option value="Completed">Completed / Previous</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="display_order" id="exp_order" class="form-control" value="0">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Technologies &amp; Key Skills (comma-separated)</label>
                                    <input type="text" name="skills" id="exp_skills" class="form-control" placeholder="e.g. Python, Django, PostgreSQL, REST Framework, MySQL, AWS">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Role Summary &amp; Overview</label>
                                    <textarea name="description" id="exp_description" class="form-control" rows="3" required placeholder="High-level summary of your responsibilities, leadership, and technical focus..."></textarea>
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Key Responsibilities &amp; Impact (1 bullet point per line)</label>
                                    <textarea name="responsibilities" id="exp_responsibilities" class="form-control" rows="4" placeholder="Architected and deployed enterprise clinic management platforms&#10;Engineered normalized relational database schemas handling high traffic&#10;Implemented stateless REST APIs with JWT authentication and RBAC"></textarea>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-sm">Save Experience</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="resetAndCloseForm('form-add-experience', 'exp_id')">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Experiences Table -->
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Company &amp; Role</th>
                                    <th>Period &amp; Status</th>
                                    <th>Location</th>
                                    <th>Skills</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($experiences_list)): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center; padding:2rem; color:#64748b;">
                                        No experiences added yet. Click &quot;Add New Experience&quot; to add your first position.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($experiences_list as $exp): ?>
                                    <tr>
                                        <td>
                                            <div style="font-weight:600; color:var(--text-main); font-size:0.95rem;"><?= htmlspecialchars($exp['company']) ?></div>
                                            <div style="font-size:0.82rem; color:var(--primary); font-weight:500;"><?= htmlspecialchars($exp['role']) ?></div>
                                        </td>
                                        <td>
                                            <div style="font-weight:500; font-size:0.86rem;"><?= htmlspecialchars($exp['period']) ?></div>
                                            <span class="badge <?= ($exp['status'] ?? 'Active') === 'Active' ? 'badge-success' : 'badge-secondary' ?>">
                                                <?= htmlspecialchars($exp['status'] ?? 'Active') ?>
                                            </span>
                                        </td>
                                        <td style="font-size:0.85rem; color:#64748b;"><?= htmlspecialchars($exp['location'] ?? '—') ?></td>
                                        <td style="max-width:240px;">
                                            <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                                <?php 
                                                $skillsArr = !empty($exp['skills']) ? array_slice(array_map('trim', explode(',', $exp['skills'])), 0, 4) : [];
                                                foreach ($skillsArr as $sk): ?>
                                                    <span class="tech-pill" style="font-size:0.7rem; padding:2px 6px;"><?= htmlspecialchars($sk) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td><?= intval($exp['display_order'] ?? 0) ?></td>
                                        <td>
                                            <div class="actions-cell">
                                                <button type="button" class="btn btn-secondary btn-sm" onclick="editExperience(<?= (int)$exp['id'] ?>)" title="Edit"><i class="bx bx-edit"></i></button>
                                                <a href="?del_exp=<?= $exp['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this experience?')" title="Delete"><i class="bx bx-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ───────────────────────────────────────────────────────────── -->
            <!-- COMPONENT TAB 4: BLOG & INSIGHTS -->
            <!-- ───────────────────────────────────────────────────────────── -->
            <section id="tab-blog" class="tab-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Engineering Insights &amp; Articles</h2>
                            <p>Technical write-ups, backend case studies, and systems engineering notes</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-blog')">
                            <i class="bx bx-plus"></i> Write New Insight
                        </button>
                    </div>

                    <!-- Collapsible Add/Edit Form -->
                    <div id="form-add-blog" class="form-drawer" style="display:none;">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <input type="hidden" name="blog_id" id="blog_id" value="0">
                            <div class="form-grid">
                                <div class="form-col-full">
                                    <label class="form-label">Article Title</label>
                                    <input type="text" name="title" id="blog_title" class="form-control" required placeholder="e.g. Designing Bulletproof RESTful APIs with Django">
                                </div>
                                <div>
                                    <label class="form-label">Category</label>
                                    <input type="text" name="category" id="blog_cat" class="form-control" required value="Backend Engineering">
                                </div>
                                <div>
                                    <label class="form-label">Read Time</label>
                                    <input type="text" name="read_time" id="blog_read" class="form-control" required value="5 min read">
                                </div>
                                <div>
                                    <label class="form-label">Featured Image (Select from Computer)</label>
                                    <div style="display:flex; align-items:center; gap:0.75rem;">
                                        <input type="file" name="blog_image_file" id="blog_image_file" class="form-control" accept="image/*" onchange="previewLocalImage(this, 'blog_preview_img')">
                                        <div style="width:50px; height:38px; border-radius:4px; overflow:hidden; border:1px solid #cbd5e1; background:#f8fafc; flex-shrink:0;">
                                            <img id="blog_preview_img" src="/images/projectcard1.jpg" alt="" style="width:100%; height:100%; object-fit:cover;" onerror="this.src='/images/projectcard1.jpg'">
                                        </div>
                                    </div>
                                    <input type="hidden" name="image" id="blog_image" value="images/projectcard1.jpg">
                                </div>
                                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.75rem;">
                                    <div>
                                        <label class="form-label">Date String</label>
                                        <input type="text" name="date" id="blog_date" class="form-control" required value="<?= date('M Y') ?>">
                                    </div>
                                    <div>
                                        <label class="form-label">Display Order</label>
                                        <input type="number" name="display_order" id="blog_order" class="form-control" value="1">
                                    </div>
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Short Excerpt (shows on card)</label>
                                    <textarea name="excerpt" id="blog_excerpt" class="form-control" required placeholder="A brief summary for the card..."></textarea>
                                </div>
                                <div class="form-col-full">
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.35rem;">
                                        <label class="form-label" style="margin-bottom:0;">Full Article Content (Markdown / Text supported)</label>
                                        <span style="font-size:0.75rem; color:#64748b;">Supports Headings, Bullets &amp; Paragraphs</span>
                                    </div>
                                    <div class="editor-toolbar" style="display:flex; flex-wrap:wrap; gap:0.35rem; margin-bottom:0.4rem; padding:0.4rem 0.6rem; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:6px;">
                                        <button type="button" class="btn btn-secondary btn-sm" style="padding:0.2rem 0.55rem; font-size:0.75rem;" onclick="insertArticleTag('### ', '')" title="Section Heading"><strong>H3 Title</strong></button>
                                        <button type="button" class="btn btn-secondary btn-sm" style="padding:0.2rem 0.55rem; font-size:0.75rem;" onclick="insertArticleTag('#### ', '')" title="Subheading"><strong>H4 Subtitle</strong></button>
                                        <button type="button" class="btn btn-secondary btn-sm" style="padding:0.2rem 0.55rem; font-size:0.75rem;" onclick="insertArticleTag('**', '**')" title="Bold"><strong>B</strong></button>
                                        <button type="button" class="btn btn-secondary btn-sm" style="padding:0.2rem 0.55rem; font-size:0.75rem;" onclick="insertArticleTag('*', '*')" title="Italic"><em>I</em></button>
                                        <button type="button" class="btn btn-secondary btn-sm" style="padding:0.2rem 0.55rem; font-size:0.75rem;" onclick="insertArticleTag('- ', '')" title="Bullet Point">&bull; Bullet</button>
                                        <button type="button" class="btn btn-secondary btn-sm" style="padding:0.2rem 0.55rem; font-size:0.75rem;" onclick="insertArticleTag('1. ', '')" title="Numbered List">1. Number</button>
                                        <button type="button" class="btn btn-secondary btn-sm" style="padding:0.2rem 0.55rem; font-size:0.75rem;" onclick="insertArticleTag('```\n', '\n```')" title="Code Block">&lt;/&gt; Code</button>
                                        <button type="button" class="btn btn-secondary btn-sm" style="padding:0.2rem 0.55rem; font-size:0.75rem;" onclick="insertArticleTag('> ', '')" title="Quote">&ldquo; Quote</button>
                                    </div>
                                    <textarea name="content" id="blog_content" class="form-control" style="min-height:220px; font-family:monospace; font-size:0.9rem; line-height:1.6;" placeholder="Write your article here...

### 1. Section Title
This is paragraph one. Leave a blank line for a new paragraph.

Key Highlights:
- Bullet item 1
- Bullet item 2

1. Numbered step 1
2. Numbered step 2"></textarea>
                                    <div style="font-size:0.78rem; color:#64748b; margin-top:0.35rem; line-height:1.4;">
                                        💡 <strong>Formatting Guide:</strong> Leave an empty line between paragraphs. Use <code>### Title</code> for section headings, <code>- Item</code> for bullet points, and <code>**bold**</code> for bold text. All formatting will be rendered automatically.
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="save_blog" class="btn btn-primary btn-sm">Publish Insight</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="resetAndCloseForm('form-add-blog', 'blog_id')">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Cover</th>
                                    <th>Category</th>
                                    <th>Article Title</th>
                                    <th>Publication Meta</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($blog_list as $bp): ?>
                                <tr>
                                    <td><img src="/<?= htmlspecialchars($bp['image'] ?? 'images/projectcard1.jpg') ?>" class="img-thumb" alt="" onerror="this.src='/images/projectcard1.jpg'"></td>
                                    <td><span class="pill-badge"><?= htmlspecialchars($bp['category']) ?></span></td>
                                    <td>
                                        <strong><?= htmlspecialchars($bp['title']) ?></strong>
                                        <div style="font-size:0.75rem; color:#94a3b8; margin-top:2px;">slug: <?= htmlspecialchars($bp['slug'] ?? '') ?></div>
                                    </td>
                                    <td style="font-size:0.82rem; color:var(--text-muted);"><?= htmlspecialchars($bp['read_time']) ?> &bull; <?= htmlspecialchars($bp['date']) ?></td>
                                    <td>
                                        <div class="actions-cell">
                                            <a href="/insights/<?= urlencode($bp['slug'] ?? '') ?>" target="_blank" class="btn btn-secondary btn-sm" title="View on Site"><i class="bx bx-show"></i></a>
                                            <button type="button" class="btn btn-secondary btn-sm" onclick="editBlog(<?= (int)$bp['id'] ?>)" title="Edit"><i class="bx bx-edit"></i></button>
                                            <a href="?del_blog=<?= $bp['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this post?')" title="Delete"><i class="bx bx-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ───────────────────────────────────────────────────────────── -->
            <!-- COMPONENT TAB: WORK EXPERIENCES -->
            <!-- ───────────────────────────────────────────────────────────── -->
            <section id="tab-experiences" class="tab-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Work Experiences &amp; Roles</h2>
                            <p>Professional engineering history, leadership positions, and client consultancy</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-experience')">
                            <i class="bx bx-plus"></i> Add New Experience
                        </button>
                    </div>

                    <!-- Collapsible Add/Edit Form for Experiences -->
                    <div id="form-add-experience" class="form-drawer" style="display:none;">
                        <form method="POST" action="">
                            <input type="hidden" name="save_experience" value="1">
                            <input type="hidden" name="exp_id" id="exp_id" value="0">

                            <div class="form-grid">
                                <div>
                                    <label class="form-label">Company / Organization</label>
                                    <input type="text" name="company" id="exp_company" class="form-control" required placeholder="e.g. Strataflow Technologies Pvt. Ltd.">
                                </div>
                                <div>
                                    <label class="form-label">Role / Job Title</label>
                                    <input type="text" name="role" id="exp_role" class="form-control" required placeholder="e.g. Co-Founder & Technical Lead">
                                </div>
                                <div>
                                    <label class="form-label">Period / Duration</label>
                                    <input type="text" name="period" id="exp_period" class="form-control" required placeholder="e.g. 2024 – Present or 2023 – 2024">
                                </div>
                                <div>
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" id="exp_location" class="form-control" placeholder="e.g. Kathmandu, Nepal or Remote">
                                </div>
                                <div>
                                    <label class="form-label">Status</label>
                                    <select name="status" id="exp_status" class="form-control">
                                        <option value="Active">Active / Current</option>
                                        <option value="Completed">Completed / Previous</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="display_order" id="exp_order" class="form-control" value="0">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Technologies &amp; Key Skills (comma-separated)</label>
                                    <input type="text" name="skills" id="exp_skills" class="form-control" placeholder="e.g. Python, Django, PostgreSQL, REST Framework, MySQL, AWS">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Role Summary &amp; Overview</label>
                                    <textarea name="description" id="exp_description" class="form-control" rows="3" required placeholder="High-level summary of your responsibilities, leadership, and technical focus..."></textarea>
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Key Responsibilities &amp; Impact (1 bullet point per line)</label>
                                    <textarea name="responsibilities" id="exp_responsibilities" class="form-control" rows="4" placeholder="Architected and deployed enterprise clinic management platforms&#10;Engineered normalized relational database schemas handling high traffic&#10;Implemented stateless REST APIs with JWT authentication and RBAC"></textarea>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-sm">Save Experience</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="resetAndCloseForm('form-add-experience', 'exp_id')">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Experiences Table -->
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Company &amp; Role</th>
                                    <th>Period &amp; Status</th>
                                    <th>Location</th>
                                    <th>Skills</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($experiences_list)): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center; padding:2rem; color:#64748b;">
                                        No experiences added yet. Click &quot;Add New Experience&quot; to add your first position.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($experiences_list as $exp): ?>
                                    <tr>
                                        <td>
                                            <div style="font-weight:600; color:var(--text-main); font-size:0.95rem;"><?= htmlspecialchars($exp['company']) ?></div>
                                            <div style="font-size:0.82rem; color:var(--primary); font-weight:500;"><?= htmlspecialchars($exp['role']) ?></div>
                                        </td>
                                        <td>
                                            <div style="font-weight:500; font-size:0.86rem;"><?= htmlspecialchars($exp['period']) ?></div>
                                            <span class="badge <?= ($exp['status'] ?? 'Active') === 'Active' ? 'badge-success' : 'badge-secondary' ?>">
                                                <?= htmlspecialchars($exp['status'] ?? 'Active') ?>
                                            </span>
                                        </td>
                                        <td style="font-size:0.85rem; color:#64748b;"><?= htmlspecialchars($exp['location'] ?? '—') ?></td>
                                        <td style="max-width:240px;">
                                            <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                                <?php 
                                                $skillsArr = !empty($exp['skills']) ? array_slice(array_map('trim', explode(',', $exp['skills'])), 0, 4) : [];
                                                foreach ($skillsArr as $sk): ?>
                                                    <span class="tech-pill" style="font-size:0.7rem; padding:2px 6px;"><?= htmlspecialchars($sk) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td><?= intval($exp['display_order'] ?? 0) ?></td>
                                        <td>
                                            <div class="actions-cell">
                                                <button type="button" class="btn btn-secondary btn-sm" onclick="editExperience(<?= (int)$exp['id'] ?>)" title="Edit"><i class="bx bx-edit"></i></button>
                                                <a href="?del_exp=<?= $exp['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this experience?')" title="Delete"><i class="bx bx-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ───────────────────────────────────────────────────────────── -->
            <!-- COMPONENT TAB 5: CONTACT INQUIRIES -->
            <!-- ───────────────────────────────────────────────────────────── -->
            <section id="tab-inquiries" class="tab-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Contact Form Inquiries</h2>
                            <p>Direct incoming correspondence from portfolio visitors and potential clients</p>
                        </div>
                    </div>
                    <?php if (empty($inquiries_list)): ?>
                        <div style="text-align:center; padding:3rem 1rem; color:var(--text-muted);">
                            <i class="bx bx-envelope-open" style="font-size:2.5rem; color:#cbd5e1; margin-bottom:0.75rem; display:block;"></i>
                            <p style="font-size:0.95rem; font-weight:500;">No messages submitted yet</p>
                            <p style="font-size:0.82rem; margin-top:0.25rem;">Visitor submissions from the portfolio "Let's Talk" form will be automatically saved here.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Sender</th>
                                        <th>Contact Details</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Received At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inquiries_list as $inq): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($inq['name']) ?></strong></td>
                                        <td>
                                            <a href="mailto:<?= htmlspecialchars($inq['email']) ?>" style="color:var(--primary); text-decoration:none;"><?= htmlspecialchars($inq['email']) ?></a>
                                            <?php if (!empty($inq['phone'])): ?>
                                                <br><small style="color:var(--text-muted);"><?= htmlspecialchars($inq['phone']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="pill-badge" style="background:#f1f5f9; color:#334155;"><?= htmlspecialchars($inq['subject']) ?></span></td>
                                        <td style="font-size:0.86rem; line-height:1.45; max-width:320px;"><?= nl2br(htmlspecialchars($inq['message'])) ?></td>
                                        <td style="font-size:0.8rem; color:var(--text-muted);"><?= htmlspecialchars($inq['created_at']) ?></td>
                                        <td>
                                            <div class="actions-cell">
                                                <a href="mailto:<?= htmlspecialchars($inq['email']) ?>?subject=Re:%20<?= rawurlencode($inq['subject']) ?>" class="btn btn-secondary btn-sm" title="Reply via Email"><i class="bx bx-reply"></i></a>
                                                <a href="?del_msg=<?= $inq['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove this message permanently?')" title="Delete"><i class="bx bx-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- ───────────────────────────────────────────────────────────── -->
            <!-- COMPONENT TAB: AWARDS & GALLERY -->
            <!-- ───────────────────────────────────────────────────────────── -->
            <section id="tab-awards" class="tab-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Awards, Distinctions &amp; Honors Gallery</h2>
                            <p>Academic excellence, hackathon achievements, and verified industry recognitions</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-award')">
                            <i class="bx bx-plus"></i> Add New Award
                        </button>
                    </div>

                    <!-- Slide-in / Collapsible Drawer Form for Awards -->
                    <div id="form-add-award" class="drawer-form-wrap" style="display:none; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem; margin-bottom:1.5rem;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
                            <h3 id="award_form_title" style="font-size:1.1rem; font-weight:700; color:var(--text-main);">Add New Award / Milestone</h3>
                            <button type="button" class="btn btn-outline btn-sm" onclick="toggleForm('form-add-award')">&times; Close</button>
                        </div>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <input type="hidden" name="save_award" value="1">
                            <input type="hidden" name="award_id" id="award_id" value="0">

                            <div class="form-grid">
                                <div class="form-group full">
                                    <label>Award / Honor Title</label>
                                    <input type="text" name="title" id="award_title" class="form-control" required placeholder="Excellence in Software Engineering">
                                </div>
                                <div class="form-group">
                                    <label>Granting Organization / Issuer</label>
                                    <input type="text" name="issuer" id="award_issuer" class="form-control" placeholder="Kantipur City College &amp; Purbanchal University">
                                </div>
                                <div class="form-group">
                                    <label>Date / Year</label>
                                    <input type="text" name="date" id="award_date" class="form-control" placeholder="2025">
                                </div>
                                <div class="form-group full">
                                    <label class="form-label">Certificate / Showcase Image (Select from Computer)</label>
                                    <div style="display:flex; align-items:center; gap:1.25rem;">
                                        <div style="flex:1;">
                                            <input type="file" name="award_image_file" id="award_image_file" class="form-control" accept="image/*" onchange="previewLocalImage(this, 'award_preview_img')">
                                            <small style="display:block; margin-top:0.35rem; color:#64748b; font-size:0.75rem;">
                                                Select certificate or achievement image directly from your local computer (JPG, PNG, WebP, SVG).
                                            </small>
                                        </div>
                                        <div style="width:78px; height:56px; border-radius:6px; overflow:hidden; border:1px solid #cbd5e1; background:#f8fafc; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                                            <img id="award_preview_img" src="/images/projectcard1.jpg" alt="Preview" style="width:100%; height:100%; object-fit:cover;" onerror="this.src='/images/projectcard1.jpg'">
                                        </div>
                                    </div>
                                    <input type="hidden" name="image" id="award_image" value="images/projectcard1.jpg">
                                </div>
                                <div class="form-group full">
                                    <label>Short Description of Award / Achievement</label>
                                    <textarea name="description" id="award_description" class="form-control" rows="3" placeholder="Brief summary of why this award or honor was granted..."></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Display Order</label>
                                    <input type="number" name="display_order" id="award_display_order" class="form-control" value="0">
                                </div>
                            </div>

                            <div style="display:flex; gap:0.75rem; margin-top:1.25rem;">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-check"></i> Save Award</button>
                                <button type="button" class="btn btn-outline btn-sm" onclick="resetAwardForm()">Reset</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Issuer / Institution</th>
                                    <th>Year</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($awards_list as $aw): ?>
                                <tr>
                                    <td><img src="/<?= htmlspecialchars($aw['image'] ?: 'images/projectcard1.jpg') ?>" class="img-thumb" alt="" onerror="this.src='/images/projectcard1.jpg'"></td>
                                    <td><strong><?= htmlspecialchars($aw['title']) ?></strong></td>
                                    <td><?= htmlspecialchars($aw['issuer'] ?? '—') ?></td>
                                    <td><span class="badge badge-info"><?= htmlspecialchars($aw['date'] ?? '—') ?></span></td>
                                    <td><?= intval($aw['display_order']) ?></td>
                                    <td>
                                        <div class="actions-cell">
                                            <button type="button" class="btn btn-outline btn-sm" onclick="editAward(<?= (int)$aw['id'] ?>)" title="Edit"><i class="bx bx-edit"></i></button>
                                            <a href="?del_award=<?= $aw['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this award?')" title="Delete"><i class="bx bx-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ───────────────────────────────────────────────────────────── -->
            <!-- COMPONENT TAB: WORK EXPERIENCES -->
            <!-- ───────────────────────────────────────────────────────────── -->
            <section id="tab-experiences" class="tab-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Work Experiences &amp; Roles</h2>
                            <p>Professional engineering history, leadership positions, and client consultancy</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-experience')">
                            <i class="bx bx-plus"></i> Add New Experience
                        </button>
                    </div>

                    <!-- Collapsible Add/Edit Form for Experiences -->
                    <div id="form-add-experience" class="form-drawer" style="display:none;">
                        <form method="POST" action="">
                            <input type="hidden" name="save_experience" value="1">
                            <input type="hidden" name="exp_id" id="exp_id" value="0">

                            <div class="form-grid">
                                <div>
                                    <label class="form-label">Company / Organization</label>
                                    <input type="text" name="company" id="exp_company" class="form-control" required placeholder="e.g. Strataflow Technologies Pvt. Ltd.">
                                </div>
                                <div>
                                    <label class="form-label">Role / Job Title</label>
                                    <input type="text" name="role" id="exp_role" class="form-control" required placeholder="e.g. Co-Founder & Technical Lead">
                                </div>
                                <div>
                                    <label class="form-label">Period / Duration</label>
                                    <input type="text" name="period" id="exp_period" class="form-control" required placeholder="e.g. 2024 – Present or 2023 – 2024">
                                </div>
                                <div>
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" id="exp_location" class="form-control" placeholder="e.g. Kathmandu, Nepal or Remote">
                                </div>
                                <div>
                                    <label class="form-label">Status</label>
                                    <select name="status" id="exp_status" class="form-control">
                                        <option value="Active">Active / Current</option>
                                        <option value="Completed">Completed / Previous</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="display_order" id="exp_order" class="form-control" value="0">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Technologies &amp; Key Skills (comma-separated)</label>
                                    <input type="text" name="skills" id="exp_skills" class="form-control" placeholder="e.g. Python, Django, PostgreSQL, REST Framework, MySQL, AWS">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Role Summary &amp; Overview</label>
                                    <textarea name="description" id="exp_description" class="form-control" rows="3" required placeholder="High-level summary of your responsibilities, leadership, and technical focus..."></textarea>
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Key Responsibilities &amp; Impact (1 bullet point per line)</label>
                                    <textarea name="responsibilities" id="exp_responsibilities" class="form-control" rows="4" placeholder="Architected and deployed enterprise clinic management platforms&#10;Engineered normalized relational database schemas handling high traffic&#10;Implemented stateless REST APIs with JWT authentication and RBAC"></textarea>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-sm">Save Experience</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="resetAndCloseForm('form-add-experience', 'exp_id')">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Experiences Table -->
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Company &amp; Role</th>
                                    <th>Period &amp; Status</th>
                                    <th>Location</th>
                                    <th>Skills</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($experiences_list)): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center; padding:2rem; color:#64748b;">
                                        No experiences added yet. Click &quot;Add New Experience&quot; to add your first position.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($experiences_list as $exp): ?>
                                    <tr>
                                        <td>
                                            <div style="font-weight:600; color:var(--text-main); font-size:0.95rem;"><?= htmlspecialchars($exp['company']) ?></div>
                                            <div style="font-size:0.82rem; color:var(--primary); font-weight:500;"><?= htmlspecialchars($exp['role']) ?></div>
                                        </td>
                                        <td>
                                            <div style="font-weight:500; font-size:0.86rem;"><?= htmlspecialchars($exp['period']) ?></div>
                                            <span class="badge <?= ($exp['status'] ?? 'Active') === 'Active' ? 'badge-success' : 'badge-secondary' ?>">
                                                <?= htmlspecialchars($exp['status'] ?? 'Active') ?>
                                            </span>
                                        </td>
                                        <td style="font-size:0.85rem; color:#64748b;"><?= htmlspecialchars($exp['location'] ?? '—') ?></td>
                                        <td style="max-width:240px;">
                                            <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                                <?php 
                                                $skillsArr = !empty($exp['skills']) ? array_slice(array_map('trim', explode(',', $exp['skills'])), 0, 4) : [];
                                                foreach ($skillsArr as $sk): ?>
                                                    <span class="tech-pill" style="font-size:0.7rem; padding:2px 6px;"><?= htmlspecialchars($sk) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td><?= intval($exp['display_order'] ?? 0) ?></td>
                                        <td>
                                            <div class="actions-cell">
                                                <button type="button" class="btn btn-secondary btn-sm" onclick="editExperience(<?= (int)$exp['id'] ?>)" title="Edit"><i class="bx bx-edit"></i></button>
                                                <a href="?del_exp=<?= $exp['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this experience?')" title="Delete"><i class="bx bx-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- ── Promotional Offer Pop-up Panel ── -->
            <section id="tab-promotions" class="tab-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Offer &amp; Promotional Pop-up Window</h2>
                            <p>Upload and manage announcements, discounts, or offers displayed in a pop-up window when visitors open the website.</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-promo')">
                            <i class="bx bx-plus"></i> Upload New Offer
                        </button>
                    </div>

                    <!-- Slide-in Drawer Form for Promotions -->
                    <div id="form-add-promo" class="drawer-form-wrap" style="display:none; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:1.5rem; margin-bottom:1.5rem;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
                            <h3 id="promo_form_title" style="font-size:1.1rem; font-weight:700; color:var(--text-main);">Upload Promotional Offer</h3>
                            <button type="button" class="btn btn-outline btn-sm" onclick="toggleForm('form-add-promo')">&times; Close</button>
                        </div>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <input type="hidden" name="save_promotion" value="1">
                            <input type="hidden" name="promo_id" id="promo_id" value="0">

                            <div class="form-grid">
                                <div class="form-group full">
                                    <label>Promotion / Offer Title <span style="color:#ef4444;">*</span></label>
                                    <input type="text" name="title" id="promo_title" class="form-control" required placeholder="e.g. Special Offer: Custom Web & SaaS Development">
                                </div>

                                <div class="form-group full">
                                    <label class="form-label">Promotional Banner / Image (Select from Computer)</label>
                                    <div style="display:flex; align-items:center; gap:1.25rem;">
                                        <div style="flex:1;">
                                            <input type="file" name="promo_image_file" id="promo_image_file" class="form-control" accept="image/*" onchange="previewLocalImage(this, 'promo_preview_img')">
                                            <small style="display:block; margin-top:0.35rem; color:#64748b; font-size:0.75rem;">
                                                Upload banner or offer graphic directly from your computer (JPG, PNG, WebP, SVG).
                                            </small>
                                        </div>
                                        <div style="width:96px; height:64px; border-radius:8px; overflow:hidden; border:1px solid #cbd5e1; background:#0b1329; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                                            <img id="promo_preview_img" src="/images/projectcard1.jpg" alt="Preview" style="width:100%; height:100%; object-fit:cover;" onerror="this.src='/images/projectcard1.jpg'">
                                        </div>
                                    </div>
                                    <input type="hidden" name="image" id="promo_image" value="images/projectcard1.jpg">
                                </div>

                                <div class="form-group full">
                                    <label>Offer Description <span style="color:#ef4444;">*</span></label>
                                    <textarea name="description" id="promo_description" class="form-control" rows="3" required placeholder="Provide concise details about the offer or promotion..."></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Call to Action Button Text</label>
                                    <input type="text" name="button_text" id="promo_button_text" class="form-control" value="Claim Offer" placeholder="e.g. Claim Offer, Get in Touch">
                                </div>

                                <div class="form-group">
                                    <label>Action Link / Target URL</label>
                                    <input type="text" name="button_url" id="promo_button_url" class="form-control" value="#contact" placeholder="e.g. #contact or /works/oralix">
                                </div>

                                <div class="form-group full">
                                    <label style="display:flex; align-items:center; gap:0.6rem; cursor:pointer;">
                                        <input type="checkbox" name="is_active" id="promo_is_active" value="1" checked style="width:18px; height:18px; accent-color:var(--primary);">
                                        <span style="font-weight:600; color:var(--text-main);">Activate Pop-up Window (Display to visitors when website opens)</span>
                                    </label>
                                </div>
                            </div>

                            <div style="display:flex; gap:0.75rem; margin-top:1.25rem;">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-check"></i> Save Promotional Offer</button>
                                <button type="button" class="btn btn-outline btn-sm" onclick="resetPromoForm()">Reset</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Banner</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Action Button</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($promotions_list)): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center; padding:2rem; color:#64748b;">
                                        No promotional offers uploaded yet. Click <strong>Upload New Offer</strong> above to create one.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($promotions_list as $promo): ?>
                                    <tr>
                                        <td>
                                            <img src="/<?= htmlspecialchars(ltrim($promo['image'] ?: 'images/projectcard1.jpg', '/')) ?>" class="img-thumb" alt="" onerror="this.src='/images/projectcard1.jpg'">
                                        </td>
                                        <td><strong><?= htmlspecialchars($promo['title']) ?></strong></td>
                                        <td style="max-width:280px; font-size:0.84rem; color:#475569; line-height:1.45;">
                                            <?= htmlspecialchars(mb_strimwidth($promo['description'], 0, 100, '...')) ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?= htmlspecialchars($promo['button_text'] ?? 'Claim Offer') ?></span>
                                        </td>
                                        <td>
                                            <?php if ((int)($promo['is_active'] ?? 0) === 1): ?>
                                                <span class="badge" style="background:rgba(16,185,129,0.15); color:#10b981; border:1px solid rgba(16,185,129,0.3); display:inline-flex; align-items:center; gap:0.35rem;">
                                                    <span style="width:7px; height:7px; border-radius:50%; background:#10b981; display:inline-block;"></span> Active (Showing)
                                                </span>
                                            <?php else: ?>
                                                <span class="badge" style="background:rgba(100,116,139,0.15); color:#64748b; border:1px solid rgba(100,116,139,0.3);">
                                                    Inactive (Hidden)
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="actions-cell">
                                                <a href="?toggle_promo=<?= (int)$promo['id'] ?>" class="btn btn-outline btn-sm" title="<?= ((int)$promo['is_active'] === 1) ? 'Turn Off Pop-up' : 'Turn On Pop-up' ?>">
                                                    <i class="bx <?= ((int)$promo['is_active'] === 1) ? 'bx-hide' : 'bx-show' ?>"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline btn-sm" onclick='editPromotion(<?= json_encode($promo, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' title="Edit"><i class="bx bx-edit"></i></button>
                                                <a href="?del_promo=<?= (int)$promo['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this promotional offer?')" title="Delete"><i class="bx bx-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <script>
        function switchTab(tabId, tabName, btnElement) {
            // Update active state in sidebar
            document.querySelectorAll('.nav-link').forEach(btn => btn.classList.remove('active'));
            if (btnElement) {
                btnElement.classList.add('active');
            } else {
                const targetBtn = Array.from(document.querySelectorAll('.nav-link')).find(b => b.getAttribute('onclick')?.includes(tabId));
                if (targetBtn) targetBtn.classList.add('active');
            }

            // Update visible tab panel
            document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));
            const targetPanel = document.getElementById(tabId);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }

            // Update breadcrumb
            const breadcrumbEl = document.getElementById('activeBreadcrumb');
            if (breadcrumbEl && tabName) {
                breadcrumbEl.textContent = tabName;
            }

            // Persist to URL hash without jumping
            if (history.replaceState) {
                history.replaceState(null, null, '#' + tabId);
            } else {
                location.hash = '#' + tabId;
            }

            // Close mobile sidebar and backdrop if open
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            if (sidebar && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
            if (backdrop) {
                backdrop.classList.remove('open');
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            if (!sidebar) return;
            sidebar.classList.toggle('open');
            if (backdrop) {
                backdrop.classList.toggle('open', sidebar.classList.contains('open'));
            }
        }

        function toggleForm(formId) {
            const form = document.getElementById(formId);
            if (!form) return;
            const willShow = form.style.display === 'none';
            form.style.display = willShow ? 'block' : 'none';
            if (willShow && formId === 'form-add-featured') {
                const idInput = document.getElementById('feat_id');
                if (!idInput || idInput.value === '0' || idInput.value === '') {
                    resetScreenshotContainer();
                }
            }
        }

        function resetAndCloseForm(formId, idField) {
            const formContainer = document.getElementById(formId);
            if (!formContainer) return;
            const form = formContainer.querySelector('form');
            if (form) form.reset();
            const idInput = document.getElementById(idField);
            if (idInput) idInput.value = '0';
            formContainer.querySelectorAll('img[id$="_preview_img"]').forEach(img => {
                img.src = '/images/projectcard1.jpg';
            });
            if (formId === 'form-add-featured') {
                resetScreenshotContainer();
            }
            formContainer.style.display = 'none';
        }

        function autoFillSlug(val) {
            const idInput = document.getElementById('feat_id');
            const slugInput = document.getElementById('feat_slug');
            if (idInput && (idInput.value === '0' || idInput.value === '')) {
                slugInput.value = val.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            }
        }

        // ─── DYNAMIC SCREENSHOTS UPLOADER (DESKTOP FILES) ──────────
        function addScreenshotRow(src = '', title = '') {
            const container = document.getElementById('screenshots_container');
            if (!container) return;

            const row = document.createElement('div');
            row.className = 'screenshot-row';
            row.style.cssText = 'display:grid; grid-template-columns: 56px minmax(180px, 1.1fr) minmax(180px, 1.4fr) auto; gap:0.75rem; align-items:center; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:0.6rem 0.75rem; box-shadow:0 1px 2px rgba(0,0,0,0.03); margin-bottom:0.25rem;';

            const cleanSrc = src ? ('/' + src.replace(/^\/+/, '')) : '/images/projectcard1.jpg';
            const cleanTitle = (title || '').replace(/"/g, '&quot;');
            const existingVal = (src || '').replace(/"/g, '&quot;');

            row.innerHTML = `
                <div style="width:56px; height:42px; border-radius:6px; overflow:hidden; border:1px solid #cbd5e1; background:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <img class="shot-preview" src="${cleanSrc}" alt="" style="width:100%; height:100%; object-fit:cover;" onerror="this.src='/images/projectcard1.jpg'">
                </div>
                <div>
                    <label style="font-size:0.72rem; font-weight:600; color:#475569; display:block; margin-bottom:3px;">
                        <i class="bx bx-upload" style="vertical-align:middle;"></i> Choose File (Desktop)
                    </label>
                    <input type="file" name="screenshot_files[]" class="form-control shot-file-input" accept="image/*" onchange="previewScreenshotFile(this)" style="font-size:0.78rem; padding:0.35rem 0.5rem; height:auto;">
                </div>
                <div>
                    <label style="font-size:0.72rem; font-weight:600; color:#475569; display:block; margin-bottom:3px;">
                        <i class="bx bx-text" style="vertical-align:middle;"></i> Caption / Title
                    </label>
                    <input type="text" name="screenshot_captions[]" class="form-control shot-caption-input" placeholder="e.g. Booking Screen or Patient Chart" value="${cleanTitle}" style="font-size:0.82rem;">
                    <input type="hidden" name="screenshot_existing_paths[]" class="shot-existing-path" value="${existingVal}">
                </div>
                <div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeScreenshotRow(this)" title="Remove this screenshot" style="height:36px; padding:0 0.65rem; margin-top:14px; display:inline-flex; align-items:center; justify-content:center;">
                        <i class="bx bx-trash" style="font-size:1.05rem;"></i>
                    </button>
                </div>
            `;

            container.appendChild(row);
        }

        function removeScreenshotRow(btn) {
            const row = btn.closest('.screenshot-row');
            if (row) {
                const container = document.getElementById('screenshots_container');
                row.remove();
                if (container && container.children.length === 0) {
                    addScreenshotRow();
                }
            }
        }

        function previewScreenshotFile(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const row = input.closest('.screenshot-row');
                if (!row) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = row.querySelector('.shot-preview');
                    if (img) img.src = e.target.result;
                };
                reader.readAsDataURL(file);

                // Auto-fill caption if currently empty
                const captionInput = row.querySelector('.shot-caption-input');
                if (captionInput && !captionInput.value.trim()) {
                    let name = file.name.replace(/\.[^/.]+$/, '');
                    name = name.replace(/[_-]+/g, ' ').replace(/\s+/g, ' ').trim();
                    captionInput.value = name.replace(/\b\w/g, c => c.toUpperCase());
                }
            }
        }

        function resetScreenshotContainer() {
            const container = document.getElementById('screenshots_container');
            if (container) {
                container.innerHTML = '';
                addScreenshotRow();
            }
        }

        // Global administrative data registries (avoids HTML attribute escaping issues)
        const adminFeaturedData = <?= json_encode($featured_list, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        const adminProductsData = <?= json_encode($company_products_list, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        const adminAcademicData = <?= json_encode($academic_list, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        const adminOtherData = <?= json_encode($other_projects_list, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        const adminBlogData = <?= json_encode($blog_list, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        const adminAwardsData = <?= json_encode($awards_list, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

        function editFeatured(itemOrId) {
            const item = (typeof itemOrId === 'object' && itemOrId !== null)
                ? itemOrId
                : (adminFeaturedData.find(p => p.id == itemOrId) || {});
            switchTab('tab-featured', 'Featured Projects');
            const drawer = document.getElementById('form-add-featured');
            drawer.style.display = 'block';
            document.getElementById('feat_id').value = item.id || 0;
            document.getElementById('feat_title').value = item.title || '';
            document.getElementById('feat_slug').value = item.slug || '';
            document.getElementById('feat_category_badge').value = item.category_badge || '';
            document.getElementById('feat_category').value = item.category || '';
            document.getElementById('feat_client_name').value = item.client_name || '';
            document.getElementById('feat_location').value = item.location || '';
            document.getElementById('feat_official_site').value = item.official_site || '';
            document.getElementById('feat_live_url').value = item.live_url || '';
            document.getElementById('feat_image').value = item.image || '';
            const featImgPath = '/' + (item.image || 'images/projectcard1.jpg').replace(/^\/+/, '');
            const featImgEl = document.getElementById('feat_preview_img');
            if (featImgEl) featImgEl.src = featImgPath;
            const featFileInput = document.getElementById('feat_image_file');
            if (featFileInput) featFileInput.value = '';

            document.getElementById('feat_order').value = item.display_order || 0;
            document.getElementById('feat_skills').value = item.skills || '';
            document.getElementById('feat_short_desc').value = item.short_description || '';
            document.getElementById('feat_desc').value = item.description || '';
            document.getElementById('feat_impact').value = item.impact || '';

            // Format features as newline-separated
            let featText = '';
            if (Array.isArray(item.features_array) && item.features_array.length > 0) {
                featText = item.features_array.join('\n');
            } else if (typeof item.features === 'string') {
                try {
                    const parsed = JSON.parse(item.features);
                    featText = Array.isArray(parsed) ? parsed.join('\n') : item.features;
                } catch(e) {
                    featText = item.features;
                }
            }
            document.getElementById('feat_features').value = featText;

            // Populate Dynamic Screenshot Rows (Uploadable from Desktop)
            const container = document.getElementById('screenshots_container');
            if (container) {
                container.innerHTML = '';
                let shots = [];
                if (Array.isArray(item.screenshots_array) && item.screenshots_array.length > 0) {
                    shots = item.screenshots_array;
                } else if (Array.isArray(item.screenshots)) {
                    shots = item.screenshots;
                } else if (typeof item.screenshots === 'string') {
                    try {
                        const parsed = JSON.parse(item.screenshots);
                        if (Array.isArray(parsed)) shots = parsed;
                    } catch(e) {}
                    if (shots.length === 0 && item.screenshots.trim()) {
                        shots = item.screenshots.split('\n').map(line => {
                            const parts = line.split('|');
                            return { src: parts[0]?.trim() || '', title: parts[1]?.trim() || 'Project Screenshot' };
                        }).filter(s => s.src);
                    }
                }

                if (shots.length > 0) {
                    shots.forEach(s => {
                        const src = typeof s === 'string' ? s : (s.src || s.image || s.url || '');
                        const title = typeof s === 'string' ? 'Project Screenshot' : (s.title || s.caption || 'Project Screenshot');
                        addScreenshotRow(src, title);
                    });
                } else {
                    addScreenshotRow();
                }
            }

            // Also keep hidden fallback value
            let shotText = '';
            if (Array.isArray(item.screenshots_array) && item.screenshots_array.length > 0) {
                shotText = item.screenshots_array.map(s => (s.src || '') + ' | ' + (s.title || '')).join('\n');
            } else if (typeof item.screenshots === 'string') {
                try {
                    const parsed = JSON.parse(item.screenshots);
                    if (Array.isArray(parsed)) {
                        shotText = parsed.map(s => (s.src || '') + ' | ' + (s.title || '')).join('\n');
                    } else {
                        shotText = item.screenshots;
                    }
                } catch(e) {
                    shotText = item.screenshots;
                }
            }
            const fallbackInput = document.getElementById('feat_screenshots');
            if (fallbackInput) fallbackInput.value = shotText;

            drawer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function editProduct(itemOrId) {
            const item = (typeof itemOrId === 'object' && itemOrId !== null)
                ? itemOrId
                : (adminProductsData.find(p => p.id == itemOrId) || {});
            switchTab('tab-products', 'Strataflow Products');
            const drawer = document.getElementById('form-add-product');
            drawer.style.display = 'block';
            document.getElementById('prod_id').value = item.id || 0;
            document.getElementById('prod_title').value = item.title || '';
            document.getElementById('prod_category').value = item.category || '';
            document.getElementById('prod_status').value = item.status_text || 'Live Product';
            const currentImg = item.image || 'images/projectcard1.jpg';
            document.getElementById('prod_image').value = currentImg;
            const prev = document.getElementById('prod_preview_img');
            if (prev) prev.src = '/' + currentImg.replace(/^\/+/, '');
            const fileInput = document.getElementById('prod_image_file');
            if (fileInput) fileInput.value = '';
            document.getElementById('prod_link_url').value = item.link_url || '';
            document.getElementById('prod_link_text').value = item.link_text || 'View Details';
            document.getElementById('prod_order').value = item.display_order || 1;
            document.getElementById('prod_pills').value = item.tech_pills || '';
            document.getElementById('prod_desc').value = item.description || '';
            document.getElementById('prod_features').value = item.features || '';
            drawer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Local File Live Preview Handler
        function previewLocalImage(input, imgId, wrapId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(imgId);
                    if (img) {
                        img.src = e.target.result;
                    }
                    if (wrapId) {
                        const wrap = document.getElementById(wrapId);
                        if (wrap) wrap.style.display = 'flex';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Toggle Fields for Combined Project Form (Academic vs Other)
        function toggleProjectTypeFields(type) {
            const wrapCat = document.getElementById('comb_wrap_cat');
            const wrapUrls = document.getElementById('comb_wrap_urls');
            if (type === 'other') {
                if (wrapCat) wrapCat.style.display = 'block';
                if (wrapUrls) wrapUrls.style.display = 'block';
            } else {
                if (wrapCat) wrapCat.style.display = 'none';
                if (wrapUrls) wrapUrls.style.display = 'none';
            }
        }

        function openCombinedForm() {
            resetCombinedForm();
            const drawer = document.getElementById('form-add-combined-project');
            drawer.style.display = 'block';
            drawer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function resetCombinedForm() {
            const drawer = document.getElementById('form-add-combined-project');
            if (drawer) drawer.style.display = 'none';
            document.getElementById('comb_id').value = '0';
            document.getElementById('comb_original_type').value = 'academic';
            document.getElementById('comb_project_type').value = 'academic';
            document.getElementById('comb_existing_image').value = 'images/projectcard1.jpg';
            document.getElementById('comb_title').value = '';
            document.getElementById('comb_skills').value = '';
            document.getElementById('comb_order').value = '1';
            document.getElementById('comb_cat').value = '';
            document.getElementById('comb_github').value = '';
            document.getElementById('comb_desc').value = '';
            document.getElementById('comb_detailed_desc').value = '';
            document.getElementById('comb_image_file').value = '';
            document.getElementById('comb_preview_img').src = '/images/projectcard1.jpg';
            document.getElementById('comb_submit_btn').textContent = 'Save Project';
            toggleProjectTypeFields('academic');
        }

        function editCombinedProject(itemOrId, type) {
            let item;
            if (typeof itemOrId === 'object' && itemOrId !== null) {
                item = itemOrId;
            } else {
                const list = (type === 'academic') ? adminAcademicData : adminOtherData;
                item = list.find(p => p.id == itemOrId) || {};
            }
            switchTab('tab-academic-other', 'Academic & Other Projects');
            const drawer = document.getElementById('form-add-combined-project');
            drawer.style.display = 'block';

            document.getElementById('comb_id').value = item.id || 0;
            document.getElementById('comb_original_type').value = type;
            document.getElementById('comb_project_type').value = type;
            document.getElementById('comb_title').value = item.title || '';
            
            const currentImg = item.image || (type === 'academic' ? 'images/projectcard1.jpg' : 'images/projectcard3.webp');
            document.getElementById('comb_existing_image').value = currentImg;
            document.getElementById('comb_preview_img').src = '/' + currentImg.replace(/^\/+/, '');
            document.getElementById('comb_image_file').value = '';

            document.getElementById('comb_skills').value = item.skills || '';
            document.getElementById('comb_order').value = item.display_order || 1;
            document.getElementById('comb_cat').value = item.category || '';
            document.getElementById('comb_github').value = item.github_url || '';
            document.getElementById('comb_desc').value = item.description || '';
            document.getElementById('comb_detailed_desc').value = item.detailed_description || '';

            document.getElementById('comb_submit_btn').textContent = 'Update ' + (type === 'academic' ? 'Academic' : 'Other') + ' Project';
            toggleProjectTypeFields(type);

            drawer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Subfilter for table
        function filterCombinedTable(type, btn) {
            document.querySelectorAll('.btn-subfilter').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');

            const rows = document.querySelectorAll('.combined-row');
            rows.forEach(row => {
                const rowType = row.getAttribute('data-type');
                if (type === 'all' || rowType === type) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Backward compatibility
        function editAcademic(itemOrId) {
            editCombinedProject(itemOrId, 'academic');
        }

        function editOther(itemOrId) {
            editCombinedProject(itemOrId, 'other');
        }

        function editBlog(itemOrId) {
            const item = (typeof itemOrId === 'object' && itemOrId !== null)
                ? itemOrId
                : (adminBlogData.find(b => b.id == itemOrId) || {});
            switchTab('tab-blog', 'Insights & Articles');
            const drawer = document.getElementById('form-add-blog');
            drawer.style.display = 'block';
            document.getElementById('blog_id').value = item.id || 0;
            document.getElementById('blog_title').value = item.title || '';
            document.getElementById('blog_cat').value = item.category || 'Backend Engineering';
            const currentImg = item.image || 'images/projectcard1.jpg';
            document.getElementById('blog_image').value = currentImg;
            const prev = document.getElementById('blog_preview_img');
            if (prev) prev.src = '/' + currentImg.replace(/^\/+/, '');
            const fileInput = document.getElementById('blog_image_file');
            if (fileInput) fileInput.value = '';
            document.getElementById('blog_read').value = item.read_time || '5 min read';
            document.getElementById('blog_date').value = item.date || '';
            document.getElementById('blog_order').value = item.display_order || 1;
            document.getElementById('blog_excerpt').value = item.excerpt || '';
            document.getElementById('blog_content').value = item.content || '';
            drawer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function editAward(itemOrId) {
            const item = (typeof itemOrId === 'object' && itemOrId !== null)
                ? itemOrId
                : (adminAwardsData.find(a => a.id == itemOrId) || {});
            switchTab('tab-awards', 'Awards & Gallery');
            const drawer = document.getElementById('form-add-award');
            drawer.style.display = 'block';
            document.getElementById('award_id').value = item.id || 0;
            document.getElementById('award_title').value = item.title || '';
            document.getElementById('award_issuer').value = item.issuer || '';
            document.getElementById('award_date').value = item.date || '';
            const currentImg = item.image || item.image_path || 'images/projectcard1.jpg';
            document.getElementById('award_image').value = currentImg;
            const prev = document.getElementById('award_preview_img');
            if (prev) prev.src = '/' + currentImg.replace(/^\/+/, '');
            const fileInput = document.getElementById('award_image_file');
            if (fileInput) fileInput.value = '';
            document.getElementById('award_description').value = item.description || '';
            document.getElementById('award_display_order').value = item.display_order || 0;
            document.getElementById('award_form_title').textContent = 'Edit Award: ' + (item.title || '');
            drawer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function resetAwardForm() {
            document.getElementById('award_id').value = 0;
            document.getElementById('award_title').value = '';
            document.getElementById('award_issuer').value = '';
            document.getElementById('award_date').value = '';
            document.getElementById('award_image').value = 'images/projectcard1.jpg';
            const prev = document.getElementById('award_preview_img');
            if (prev) prev.src = '/images/projectcard1.jpg';
            const fileInput = document.getElementById('award_image_file');
            if (fileInput) fileInput.value = '';
            document.getElementById('award_description').value = '';
            document.getElementById('award_display_order').value = 0;
            document.getElementById('award_form_title').textContent = 'Add New Award / Milestone';
        }

        function editPromotion(item) {
            switchTab('tab-promotions', 'Offer Pop-up Window');
            const drawer = document.getElementById('form-add-promo');
            if (drawer) drawer.style.display = 'block';
            document.getElementById('promo_id').value = item.id || 0;
            document.getElementById('promo_title').value = item.title || '';
            document.getElementById('promo_description').value = item.description || '';
            const currentImg = item.image || 'images/projectcard1.jpg';
            document.getElementById('promo_image').value = currentImg;
            const prev = document.getElementById('promo_preview_img');
            if (prev) prev.src = '/' + currentImg.replace(/^\/+/, '');
            const fileInput = document.getElementById('promo_image_file');
            if (fileInput) fileInput.value = '';
            document.getElementById('promo_button_text').value = item.button_text || 'Claim Offer';
            document.getElementById('promo_button_url').value = item.button_url || '#contact';
            document.getElementById('promo_is_active').checked = parseInt(item.is_active ?? 1) === 1;
            document.getElementById('promo_form_title').textContent = 'Edit Promotional Offer: ' + (item.title || '');
            if (drawer) drawer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function resetPromoForm() {
            document.getElementById('promo_id').value = 0;
            document.getElementById('promo_title').value = '';
            document.getElementById('promo_description').value = '';
            document.getElementById('promo_image').value = 'images/projectcard1.jpg';
            const prev = document.getElementById('promo_preview_img');
            if (prev) prev.src = '/images/projectcard1.jpg';
            const fileInput = document.getElementById('promo_image_file');
            if (fileInput) fileInput.value = '';
            document.getElementById('promo_button_text').value = 'Claim Offer';
            document.getElementById('promo_button_url').value = '#contact';
            document.getElementById('promo_is_active').checked = true;
            document.getElementById('promo_form_title').textContent = 'Upload Promotional Offer';
        }

        // Insert markdown or formatting tags into blog_content textarea
        function insertArticleTag(before, after) {
            const textarea = document.getElementById('blog_content');
            if (!textarea) return;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selText = textarea.value.substring(start, end);
            const replacement = before + (selText || '') + (after || '');
            textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
            textarea.focus();
            textarea.selectionStart = start + before.length;
            textarea.selectionEnd = start + before.length + (selText ? selText.length : 0);
        }

        // On Page Load: Restore tab from hash if present & initialize screenshots uploader
        window.addEventListener('DOMContentLoaded', () => {
            const shotContainer = document.getElementById('screenshots_container');
            if (shotContainer && shotContainer.children.length === 0) {
                addScreenshotRow();
            }

            const hash = window.location.hash.replace('#', '');
            if (hash) {
                const map = {
                    'tab-featured': 'Featured Projects',
                    'tab-products': 'Strataflow Products',
                    'tab-academic-other': 'Academic & Other Projects',
                    'tab-academic': 'Academic & Other Projects',
                    'tab-other': 'Academic & Other Projects',
                    'tab-blog': 'Insights & Articles',
                    'tab-awards': 'Awards & Gallery',
                    'tab-promotions': 'Offer Pop-up Window',
                    'tab-inquiries': 'Contact Inquiries'
                };
                if (map[hash]) {
                    switchTab(hash, map[hash]);
                }
            }
        });
    </script>
</body>
</html>

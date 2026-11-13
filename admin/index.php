<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../backend/db.php';
require_once __DIR__ . '/../backend/functions.php';

// Authentication Config (Loaded from .env / config/app.php with fallback)
require_once __DIR__ . '/../config/env.php';
$app_config = file_exists(__DIR__ . '/../config/app.php') ? require __DIR__ . '/../config/app.php' : [];
define('ADMIN_USER', $app_config['admin']['user'] ?? env('ADMIN_USERNAME', 'admin'));
define('ADMIN_PASS', $app_config['admin']['pass'] ?? env('ADMIN_PASSWORD', 'admin123'));

// Determine clean self URL for redirects and form actions
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/admin', PHP_URL_PATH);
$adminSelfUrl = !empty($requestPath) && $requestPath !== '/' && $requestPath !== '/index.php' ? $requestPath : '/admin';

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['admin_logged_in']);
    session_destroy();
    header('Location: ' . $adminSelfUrl);
    exit;
}

// Handle Login Form
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === ADMIN_USER && $password === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: ' . $adminSelfUrl);
        exit;
    } else {
        $login_error = 'Invalid username or password.';
    }
}

// If not logged in, show Login Screen
if (empty($_SESSION['admin_logged_in'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Ganga Sagar Joshi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background: #0f172a; color: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1.5rem; }
        .login-card { background: #1e293b; border: 1px solid #334155; border-radius: 20px; padding: 2.5rem; width: 100%; max-width: 420px; box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .login-logo { font-family: 'Poppins', sans-serif; font-size: 1.4rem; font-weight: 700; color: #3b82f6; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.6rem; }
        .login-title { font-size: 0.95rem; color: #94a3b8; margin-bottom: 1.8rem; }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; font-size: 0.85rem; font-weight: 600; color: #cbd5e1; margin-bottom: 0.4rem; }
        input[type="text"], input[type="password"] { width: 100%; padding: 0.75rem 1rem; background: #0f172a; border: 1px solid #334155; border-radius: 10px; font-size: 0.95rem; color: #f8fafc; outline: none; transition: all 0.2s; }
        input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.25); }
        .btn-submit { width: 100%; padding: 0.8rem; background: linear-gradient(135deg, #2563eb, #7c3aed); color: #fff; border: none; border-radius: 10px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.2s; margin-top: 0.5rem; }
        .btn-submit:hover { filter: brightness(1.1); transform: translateY(-1px); }
        .alert-error { background: rgba(239,68,68,0.15); border: 1px solid #ef4444; color: #fca5a5; padding: 0.65rem 1rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.2rem; }
        .credentials-pill { background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.25); border-radius: 10px; padding: 0.65rem 0.9rem; margin-bottom: 1.2rem; font-size: 0.84rem; color: #93c5fd; display: flex; align-items: center; gap: 0.5rem; }
        .back-link { display: block; text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: #64748b; text-decoration: none; }
        .back-link:hover { color: #3b82f6; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo"><i class="bx bx-shield-quarter"></i> Portfolio Admin</div>
        <p class="login-title">Sign in to manage projects &amp; dynamic content</p>
        
        <div class="credentials-pill">
            <i class="bx bx-info-circle" style="font-size:1.1rem; flex-shrink:0;"></i>
            <span>Default: <strong>admin</strong> / <strong>admin123</strong></span>
        </div>

        <?php if ($login_error): ?>
            <div class="alert-error"><?= htmlspecialchars($login_error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= htmlspecialchars($adminSelfUrl) ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="admin" value="admin" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" value="admin123" required>
            </div>
            <button type="submit" name="login_submit" class="btn-submit">Sign In to Dashboard</button>
        </form>
        <a href="../index.php" class="back-link">&larr; Back to Portfolio Website</a>
    </div>
</body>
</html>
<?php
exit;
}

// ─────────────────────────────────────────────────────────────
// LOGGED IN ACTIONS (CRUD Operations & Static HTML Exporter)
// ─────────────────────────────────────────────────────────────
$message = '';
$message_type = 'success';

// Action: Export Static HTML (Both Portfolio and Entrepreneurship Profile)
if (isset($_GET['action']) && $_GET['action'] === 'export_static') {
    require_once __DIR__ . '/../backend/export_static.php';
    $message = 'Successfully synchronized index.html & profile.html from dynamic database!';
}

// Action: Delete Company Product (Strataflow)
if (isset($_GET['del_prod'])) {
    $del_id = intval($_GET['del_prod']);
    $pdo->prepare("DELETE FROM company_products WHERE id = ?")->execute([$del_id]);
    $message = 'Strataflow Product deleted successfully.';
}

// Action: Delete Featured Project
if (isset($_GET['del_feat'])) {
    $del_id = intval($_GET['del_feat']);
    $pdo->prepare("DELETE FROM featured_projects WHERE id = ?")->execute([$del_id]);
    $message = 'Featured Project deleted successfully.';
}

// Action: Delete Academic Project
if (isset($_GET['del_acad'])) {
    $del_id = intval($_GET['del_acad']);
    $pdo->prepare("DELETE FROM academic_projects WHERE id = ?")->execute([$del_id]);
    $message = 'Academic Project deleted successfully.';
}

// Action: Delete Blog Post
if (isset($_GET['del_blog'])) {
    $del_id = intval($_GET['del_blog']);
    $pdo->prepare("DELETE FROM blog_insights WHERE id = ?")->execute([$del_id]);
    $message = 'Insight / Blog post deleted successfully.';
}

// Action: Delete Contact Message
if (isset($_GET['del_msg'])) {
    $del_id = intval($_GET['del_msg']);
    $pdo->prepare("DELETE FROM contact_inquiries WHERE id = ?")->execute([$del_id]);
    $message = 'Contact inquiry removed.';
}

// Action: Add / Update Featured Project
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_featured'])) {
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? 'images/project1.jpg');
    $skills = trim($_POST['skills'] ?? '');
    $live_url = trim($_POST['live_url'] ?? 'https://github.com/gangasaagar');
    $order = intval($_POST['display_order'] ?? 0);
    $feat_id = intval($_POST['feat_id'] ?? 0);

    if ($feat_id > 0) {
        $stmt = $pdo->prepare("UPDATE featured_projects SET title=?, description=?, image=?, skills=?, live_url=?, display_order=? WHERE id=?");
        $stmt->execute([$title, $desc, $image, $skills, $live_url, $order, $feat_id]);
        $message = "Project '{$title}' updated successfully.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO featured_projects (title, description, image, skills, live_url, display_order) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $desc, $image, $skills, $live_url, $order]);
        $message = "New Project '{$title}' added successfully.";
    }
}

// Action: Add / Update Academic Project
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_academic'])) {
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $order = intval($_POST['display_order'] ?? 0);
    $acad_id = intval($_POST['acad_id'] ?? 0);

    if ($acad_id > 0) {
        $stmt = $pdo->prepare("UPDATE academic_projects SET title=?, description=?, skills=?, display_order=? WHERE id=?");
        $stmt->execute([$title, $desc, $skills, $order, $acad_id]);
        $message = "Academic Project '{$title}' updated.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO academic_projects (title, description, skills, display_order) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $desc, $skills, $order]);
        $message = "New Academic Project added.";
    }
}

// Action: Add / Update Blog Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_blog'])) {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? 'Engineering');
    $image = trim($_POST['image'] ?? 'images/projectcard1.jpg');
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
    $image = trim($_POST['image'] ?? 'images/projectcard1.jpg');
    $status_text = trim($_POST['status_text'] ?? 'Live Product');
    $features = trim($_POST['features'] ?? '');
    $tech_pills = trim($_POST['tech_pills'] ?? '');
    $link_url = trim($_POST['link_url'] ?? 'https://github.com/gangasaagar');
    $link_text = trim($_POST['link_text'] ?? 'View Details');
    $order = intval($_POST['display_order'] ?? 0);
    $prod_id = intval($_POST['prod_id'] ?? 0);

    require_once __DIR__ . '/../app/Core/Autoloader.php';
    \App\Core\Autoloader::register();

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

// Autoloader & fetch all current data
require_once __DIR__ . '/../app/Core/Autoloader.php';
\App\Core\Autoloader::register();

$featured_list = get_featured_projects();
$academic_list = get_academic_projects();
$blog_list = $pdo->query("SELECT * FROM blog_insights ORDER BY display_order ASC, id DESC")->fetchAll();
$inquiries_list = $pdo->query("SELECT * FROM contact_inquiries ORDER BY id DESC")->fetchAll();
$company_products_list = \App\Models\CompanyProduct::allWithFeaturesAndPills();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Workspace — Ganga Sagar Joshi</title>
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
        @media (max-width: 1024px) {
            .kpi-row { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            :root { --sidebar-width: 0px; }
            .admin-sidebar {
                transform: translateX(-100%);
                width: 260px;
            }
            .admin-sidebar.open {
                transform: translateX(0);
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
            .kpi-row {
                grid-template-columns: 1fr;
            }
            .admin-canvas {
                padding: 1.25rem;
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
                    <div class="brand-monogram">GSJ</div>
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

                <button class="nav-link" onclick="switchTab('tab-academic', 'Academic Projects', this)">
                    <div class="nav-link-left">
                        <i class="bx bx-code-curly"></i>
                        <span>Academic Projects</span>
                    </div>
                    <span class="nav-badge"><?= count($academic_list) ?></span>
                </button>

                <button class="nav-link" onclick="switchTab('tab-blog', 'Insights & Articles', this)">
                    <div class="nav-link-left">
                        <i class="bx bx-news"></i>
                        <span>Insights &amp; Articles</span>
                    </div>
                    <span class="nav-badge"><?= count($blog_list) ?></span>
                </button>

                <button class="nav-link" onclick="switchTab('tab-inquiries', 'Contact Inquiries', this)">
                    <div class="nav-link-left">
                        <i class="bx bx-envelope"></i>
                        <span>Contact Inquiries</span>
                    </div>
                    <span class="nav-badge"><?= count($inquiries_list) ?></span>
                </button>

                <div class="sidebar-section-title">Quick Actions &amp; Links</div>

                <a href="?action=export_static" class="nav-link" title="Export database to static index.html and profile.html for GitHub Pages">
                    <div class="nav-link-left">
                        <i class="bx bx-sync"></i>
                        <span>Sync Static HTML</span>
                    </div>
                    <span class="nav-badge" style="background:#10b981; color:#fff;">Build</span>
                </a>

                <a href="../index.php" target="_blank" class="nav-link">
                    <div class="nav-link-left">
                        <i class="bx bx-globe"></i>
                        <span>Live Portfolio</span>
                    </div>
                    <i class="bx bx-link-external" style="font-size:0.95rem;"></i>
                </a>

                <a href="../profile.php" target="_blank" class="nav-link">
                    <div class="nav-link-left">
                        <i class="bx bx-id-card"></i>
                        <span>Founder Profile</span>
                    </div>
                    <i class="bx bx-link-external" style="font-size:0.95rem;"></i>
                </a>
            </div>
        </div>

        <!-- Sidebar Footer User Profile -->
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-info">
                    <div class="user-avatar">GS</div>
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
                <a href="?action=export_static" class="btn btn-success btn-sm" title="Rebuild static files for GitHub Pages">
                    <i class="bx bx-sync"></i>
                    <span>Sync Static</span>
                </a>
                <a href="../index.php" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="bx bx-link-external"></i>
                    <span>View Site</span>
                </a>
            </div>
        </header>

        <!-- Canvas Area -->
        <main class="admin-canvas">

            <!-- Flash Success / Error Notification -->
            <?php if ($message): ?>
                <div class="alert-bar">
                    <span><i class="bx bx-check-circle" style="vertical-align:middle; font-size:1.15rem; margin-right:0.3rem;"></i> <?= htmlspecialchars($message) ?></span>
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

                <div class="kpi-card" onclick="switchTab('tab-inquiries', 'Contact Inquiries', document.querySelectorAll('.nav-link')[4])">
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
                        <form method="POST" action="">
                            <input type="hidden" name="feat_id" id="feat_id" value="0">
                            <div class="form-grid">
                                <div>
                                    <label class="form-label">Project Title</label>
                                    <input type="text" name="title" id="feat_title" class="form-control" required placeholder="e.g. Oralix — Dental Practice OS">
                                </div>
                                <div>
                                    <label class="form-label">Image Path</label>
                                    <input type="text" name="image" id="feat_image" class="form-control" required value="images/projectcard1.jpg">
                                </div>
                                <div>
                                    <label class="form-label">Skills (comma-separated)</label>
                                    <input type="text" name="skills" id="feat_skills" class="form-control" required placeholder="Python, Django, PostgreSQL, Docker">
                                </div>
                                <div>
                                    <label class="form-label">Live Site URL</label>
                                    <input type="text" name="live_url" id="feat_live_url" class="form-control" required value="https://github.com/gangasaagar">
                                </div>
                                <div>
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="display_order" id="feat_order" class="form-control" value="1">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Short Description</label>
                                    <textarea name="description" id="feat_desc" class="form-control" required placeholder="Multi-chair clinical practice platform..."></textarea>
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
                                    <th>Project Title</th>
                                    <th>Technologies &amp; Skills</th>
                                    <th>Live URL</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($featured_list as $fp): ?>
                                <tr>
                                    <td><img src="../<?= htmlspecialchars($fp['image']) ?>" class="img-thumb" alt="" onerror="this.src='../images/projectcard1.jpg'"></td>
                                    <td>
                                        <strong><?= htmlspecialchars($fp['title']) ?></strong>
                                        <div style="font-size:0.75rem; color:#94a3b8; margin-top:2px;"><?= htmlspecialchars($fp['slug'] ?? '') ?></div>
                                    </td>
                                    <td>
                                        <?php foreach ($fp['skills_array'] as $s): ?>
                                            <span class="pill-badge"><?= htmlspecialchars($s) ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <a href="<?= htmlspecialchars($fp['live_url']) ?>" target="_blank" style="color:var(--primary); font-size:0.84rem; text-decoration:none;">
                                            Link <i class="bx bx-link-external" style="font-size:0.75rem;"></i>
                                        </a>
                                    </td>
                                    <td><span style="font-weight:600; font-size:0.84rem;"><?= intval($fp['display_order']) ?></span></td>
                                    <td>
                                        <div class="actions-cell">
                                            <button class="btn btn-secondary btn-sm" onclick='editFeatured(<?= json_encode($fp) ?>)' title="Edit"><i class="bx bx-edit"></i></button>
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
            <!-- COMPONENT TAB 2: STRATAFLOW PRODUCTS -->
            <!-- ───────────────────────────────────────────────────────────── -->
            <section id="tab-products" class="tab-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Strataflow Products &amp; Solutions</h2>
                            <p>Manage products showcased dynamically on the Founder Profile page (/profile.html)</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-product')">
                            <i class="bx bx-plus"></i> Add New Product
                        </button>
                    </div>

                    <!-- Collapsible Add/Edit Form -->
                    <div id="form-add-product" class="form-drawer" style="display:none;">
                        <form method="POST" action="">
                            <input type="hidden" name="prod_id" id="prod_id" value="0">
                            <div class="form-grid">
                                <div>
                                    <label class="form-label">Product Name / Title</label>
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
                                    <label class="form-label">Thumbnail / Preview Image</label>
                                    <input type="text" name="image" id="prod_image" class="form-control" required value="images/projectcard1.jpg">
                                </div>
                                <div>
                                    <label class="form-label">Product Website / Demo URL</label>
                                    <input type="text" name="link_url" id="prod_link_url" class="form-control" required value="https://github.com/gangasaagar">
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
                                    <label class="form-label">Tech Stack Tags (comma-separated)</label>
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
                                    <td><img src="../<?= htmlspecialchars($prod['image']) ?>" class="img-thumb" alt="" onerror="this.src='../images/projectcard1.jpg'"></td>
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
                                            <button class="btn btn-secondary btn-sm" onclick='editProduct(<?= json_encode($prod) ?>)' title="Edit"><i class="bx bx-edit"></i></button>
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
            <!-- COMPONENT TAB 3: ACADEMIC PROJECTS -->
            <!-- ───────────────────────────────────────────────────────────── -->
            <section id="tab-academic" class="tab-panel">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="panel-header-info">
                            <h2>Key Academic Projects</h2>
                            <p>Undergraduate coursework projects and foundational software implementations</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-academic')">
                            <i class="bx bx-plus"></i> Add Academic Project
                        </button>
                    </div>

                    <!-- Collapsible Add/Edit Form -->
                    <div id="form-add-academic" class="form-drawer" style="display:none;">
                        <form method="POST" action="">
                            <input type="hidden" name="acad_id" id="acad_id" value="0">
                            <div class="form-grid">
                                <div>
                                    <label class="form-label">Project Title</label>
                                    <input type="text" name="title" id="acad_title" class="form-control" required placeholder="e.g. Barcode -based Student Attendance System">
                                </div>
                                <div>
                                    <label class="form-label">Skills (comma-separated)</label>
                                    <input type="text" name="skills" id="acad_skills" class="form-control" required placeholder="C#, .NET, MySQL, Desktop App">
                                </div>
                                <div>
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="display_order" id="acad_order" class="form-control" value="1">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Description &amp; Coursework Context</label>
                                    <textarea name="description" id="acad_desc" class="form-control" required placeholder="A desktop-based attendance management system..."></textarea>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="save_academic" class="btn btn-primary btn-sm">Save Academic Project</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="resetAndCloseForm('form-add-academic', 'acad_id')">Cancel</button>
                            </div>
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Project Title</th>
                                    <th>Description &amp; Context</th>
                                    <th>Technologies</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($academic_list as $ap): ?>
                                <tr>
                                    <td style="width:26%;"><strong><?= htmlspecialchars($ap['title']) ?></strong></td>
                                    <td style="font-size:0.84rem; color:var(--text-muted); line-height:1.45;"><?= htmlspecialchars($ap['description']) ?></td>
                                    <td>
                                        <?php foreach ($ap['skills_array'] as $as): ?>
                                            <span class="pill-badge" style="color:#b45309; background:rgba(217,119,6,0.08);"><?= htmlspecialchars($as) ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                    <td><span style="font-weight:600; font-size:0.84rem;"><?= intval($ap['display_order']) ?></span></td>
                                    <td>
                                        <div class="actions-cell">
                                            <button class="btn btn-secondary btn-sm" onclick='editAcademic(<?= json_encode($ap) ?>)' title="Edit"><i class="bx bx-edit"></i></button>
                                            <a href="?del_acad=<?= $ap['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this project?')" title="Delete"><i class="bx bx-trash"></i></a>
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
                        <form method="POST" action="">
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
                                    <label class="form-label">Featured Image Path</label>
                                    <input type="text" name="image" id="blog_image" class="form-control" required value="images/projectcard1.jpg" placeholder="images/projectcard1.jpg">
                                </div>
                                <div>
                                    <label class="form-label">Read Time</label>
                                    <input type="text" name="read_time" id="blog_read" class="form-control" required value="5 min read">
                                </div>
                                <div>
                                    <label class="form-label">Date String</label>
                                    <input type="text" name="date" id="blog_date" class="form-control" required value="<?= date('M Y') ?>">
                                </div>
                                <div>
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="display_order" id="blog_order" class="form-control" value="1">
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Short Excerpt (shows on card)</label>
                                    <textarea name="excerpt" id="blog_excerpt" class="form-control" required placeholder="A brief summary for the card..."></textarea>
                                </div>
                                <div class="form-col-full">
                                    <label class="form-label">Full Article Content</label>
                                    <textarea name="content" id="blog_content" class="form-control" style="min-height:120px;" placeholder="Full markdown or text..."></textarea>
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
                                    <td><img src="../<?= htmlspecialchars($bp['image'] ?? 'images/projectcard1.jpg') ?>" class="img-thumb" alt="" onerror="this.src='../images/projectcard1.jpg'"></td>
                                    <td><span class="pill-badge"><?= htmlspecialchars($bp['category']) ?></span></td>
                                    <td>
                                        <strong><?= htmlspecialchars($bp['title']) ?></strong>
                                        <div style="font-size:0.75rem; color:#94a3b8; margin-top:2px;">slug: <?= htmlspecialchars($bp['slug'] ?? '') ?></div>
                                    </td>
                                    <td style="font-size:0.82rem; color:var(--text-muted);"><?= htmlspecialchars($bp['read_time']) ?> &bull; <?= htmlspecialchars($bp['date']) ?></td>
                                    <td>
                                        <div class="actions-cell">
                                            <button class="btn btn-secondary btn-sm" onclick='editBlog(<?= json_encode($bp) ?>)' title="Edit"><i class="bx bx-edit"></i></button>
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

            // Close mobile sidebar if open
            const sidebar = document.getElementById('adminSidebar');
            if (sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        }

        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
        }

        function toggleForm(formId) {
            const form = document.getElementById(formId);
            if (!form) return;
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function resetAndCloseForm(formId, idField) {
            const formContainer = document.getElementById(formId);
            if (!formContainer) return;
            const form = formContainer.querySelector('form');
            if (form) form.reset();
            const idInput = document.getElementById(idField);
            if (idInput) idInput.value = '0';
            formContainer.style.display = 'none';
        }

        function editFeatured(item) {
            switchTab('tab-featured', 'Featured Projects');
            const drawer = document.getElementById('form-add-featured');
            drawer.style.display = 'block';
            document.getElementById('feat_id').value = item.id;
            document.getElementById('feat_title').value = item.title;
            document.getElementById('feat_image').value = item.image;
            document.getElementById('feat_skills').value = item.skills;
            document.getElementById('feat_live_url').value = item.live_url;
            document.getElementById('feat_order').value = item.display_order;
            document.getElementById('feat_desc').value = item.description;
            drawer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function editProduct(item) {
            switchTab('tab-products', 'Strataflow Products');
            const drawer = document.getElementById('form-add-product');
            drawer.style.display = 'block';
            document.getElementById('prod_id').value = item.id;
            document.getElementById('prod_title').value = item.title;
            document.getElementById('prod_category').value = item.category;
            document.getElementById('prod_status').value = item.status_text;
            document.getElementById('prod_image').value = item.image;
            document.getElementById('prod_link_url').value = item.link_url;
            document.getElementById('prod_link_text').value = item.link_text || 'View Details';
            document.getElementById('prod_order').value = item.display_order;
            document.getElementById('prod_pills').value = item.tech_pills || '';
            document.getElementById('prod_desc').value = item.description;
            document.getElementById('prod_features').value = item.features || '';
            drawer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function editAcademic(item) {
            switchTab('tab-academic', 'Academic Projects');
            const drawer = document.getElementById('form-add-academic');
            drawer.style.display = 'block';
            document.getElementById('acad_id').value = item.id;
            document.getElementById('acad_title').value = item.title;
            document.getElementById('acad_skills').value = item.skills;
            document.getElementById('acad_order').value = item.display_order;
            document.getElementById('acad_desc').value = item.description;
            drawer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function editBlog(item) {
            switchTab('tab-blog', 'Insights & Articles');
            const drawer = document.getElementById('form-add-blog');
            drawer.style.display = 'block';
            document.getElementById('blog_id').value = item.id;
            document.getElementById('blog_title').value = item.title;
            document.getElementById('blog_cat').value = item.category;
            document.getElementById('blog_image').value = item.image || 'images/projectcard1.jpg';
            document.getElementById('blog_read').value = item.read_time;
            document.getElementById('blog_date').value = item.date;
            document.getElementById('blog_order').value = item.display_order;
            document.getElementById('blog_excerpt').value = item.excerpt;
            document.getElementById('blog_content').value = item.content;
            drawer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // On Page Load: Restore tab from hash if present
        window.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash.replace('#', '');
            if (hash) {
                const map = {
                    'tab-featured': 'Featured Projects',
                    'tab-products': 'Strataflow Products',
                    'tab-academic': 'Academic Projects',
                    'tab-blog': 'Insights & Articles',
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

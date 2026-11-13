<?php
session_start();
require_once __DIR__ . '/../backend/db.php';
require_once __DIR__ . '/../backend/functions.php';

// Authentication Config (Loaded from .env / config/app.php with fallback)
require_once __DIR__ . '/../config/env.php';
$app_config = file_exists(__DIR__ . '/../config/app.php') ? require __DIR__ . '/../config/app.php' : [];
define('ADMIN_USER', $app_config['admin']['user'] ?? env('ADMIN_USERNAME', 'admin'));
define('ADMIN_PASS', $app_config['admin']['pass'] ?? env('ADMIN_PASSWORD', 'admin123'));

// Handle Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['admin_logged_in']);
    session_destroy();
    header('Location: index.php');
    exit;
}

// Handle Login Form
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === ADMIN_USER && $password === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background: #f8fafc; color: #0f172a; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1.5rem; }
        .login-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 2.5rem; width: 100%; max-width: 420px; box-shadow: 0 10px 30px rgba(15,23,42,0.06); }
        .login-logo { font-family: 'Poppins', sans-serif; font-size: 1.4rem; font-weight: 700; color: #2563eb; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .login-title { font-size: 1.1rem; color: #475569; margin-bottom: 1.8rem; }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 0.4rem; }
        input[type="text"], input[type="password"] { width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.95rem; outline: none; transition: all 0.2s; }
        input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.15); }
        .btn-submit { width: 100%; padding: 0.8rem; background: linear-gradient(135deg, #2563eb, #7c3aed); color: #fff; border: none; border-radius: 10px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: all 0.2s; margin-top: 0.5rem; }
        .btn-submit:hover { filter: brightness(1.08); transform: translateY(-1px); }
        .alert-error { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.65rem 1rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.2rem; }
        .back-link { display: block; text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: #64748b; text-decoration: none; }
        .back-link:hover { color: #2563eb; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo"><i class="bx bx-shield-quarter"></i> Portfolio Admin</div>
        <p class="login-title">Sign in to manage projects &amp; blog</p>
        <?php if ($login_error): ?>
            <div class="alert-error"><?= htmlspecialchars($login_error) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="admin" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" name="login_submit" class="btn-submit">Sign In</button>
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

// Action: Export Static HTML
if (isset($_GET['action']) && $_GET['action'] === 'export_static') {
    ob_start();
    require __DIR__ . '/../index.php';
    $html = ob_get_clean();
    file_put_contents(__DIR__ . '/../index.html', $html);
    $message = 'Successfully generated and synchronized root index.html from dynamic database!';
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
    $read_time = trim($_POST['read_time'] ?? '5 min read');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $date = trim($_POST['date'] ?? date('M Y'));
    $order = intval($_POST['display_order'] ?? 0);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $blog_id = intval($_POST['blog_id'] ?? 0);

    if ($blog_id > 0) {
        $stmt = $pdo->prepare("UPDATE blog_insights SET title=?, slug=?, category=?, read_time=?, excerpt=?, content=?, date=?, display_order=? WHERE id=?");
        $stmt->execute([$title, $slug, $category, $read_time, $excerpt, $content, $date, $order, $blog_id]);
        $message = "Blog Insight '{$title}' updated.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO blog_insights (title, slug, category, read_time, excerpt, content, date, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $category, $read_time, $excerpt, $content, $date, $order]);
        $message = "New Insight '{$title}' published.";
    }
}

// Fetch all current data
$featured_list = get_featured_projects();
$academic_list = get_academic_projects();
$blog_list = $pdo->query("SELECT * FROM blog_insights ORDER BY display_order ASC, id DESC")->fetchAll();
$inquiries_list = $pdo->query("SELECT * FROM contact_inquiries ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Ganga Sagar Joshi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --font-head: 'Poppins', sans-serif;
            --font-body: 'Inter', sans-serif;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: var(--font-body); }
        body { background: var(--bg); color: var(--text-main); min-height: 100vh; padding-bottom: 4rem; }
        .topbar { background: #ffffff; border-bottom: 1px solid var(--border); padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .brand { font-family: var(--font-head); font-weight: 700; font-size: 1.25rem; color: var(--primary); display: flex; align-items: center; gap: 0.5rem; text-decoration: none; }
        .top-actions { display: flex; align-items: center; gap: 0.75rem; }
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.86rem; font-weight: 600; text-decoration: none; cursor: pointer; transition: all 0.2s; border: none; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-secondary { background: #fff; border: 1px solid var(--border); color: var(--text-main); }
        .btn-secondary:hover { border-color: var(--primary); color: var(--primary); }
        .btn-export { background: #10b981; color: #fff; }
        .btn-export:hover { background: #059669; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.78rem; border-radius: 6px; }

        .container { max-width: 1240px; margin: 2rem auto; padding: 0 1.5rem; }
        .alert-bar { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 0.85rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; font-weight: 500; font-size: 0.92rem; }

        /* Tabs Nav */
        .tabs-header { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; flex-wrap: wrap; }
        .tab-btn { background: none; border: none; font-size: 0.92rem; font-weight: 600; color: var(--text-muted); padding: 0.6rem 1.1rem; border-radius: 8px; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.4rem; }
        .tab-btn.active { color: var(--primary); background: rgba(37,99,235,0.08); }
        .tab-btn .badge-count { font-size: 0.75rem; background: rgba(15,23,42,0.08); padding: 0.15rem 0.5rem; border-radius: 9999px; }
        .tab-btn.active .badge-count { background: var(--primary); color: #fff; }

        /* Panels */
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        .content-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 18px; padding: 1.75rem; box-shadow: 0 2px 8px rgba(15,23,42,0.03); margin-bottom: 2rem; }
        .panel-heading-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
        .panel-title { font-family: var(--font-head); font-size: 1.25rem; font-weight: 700; color: var(--text-main); }

        /* Tables */
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #f8fafc; color: var(--text-muted); font-size: 0.78rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; padding: 0.75rem 1rem; border-bottom: 1px solid var(--border); }
        td { padding: 0.95rem 1rem; border-bottom: 1px solid var(--border); font-size: 0.88rem; vertical-align: middle; }
        tr:hover td { background: #fbfcfe; }
        .img-thumb { width: 56px; height: 36px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border); }
        .pill-badge { font-size: 0.72rem; font-weight: 600; padding: 0.18rem 0.55rem; border-radius: 9999px; background: rgba(37,99,235,0.08); color: var(--primary); display: inline-block; margin: 0.1rem; }
        .actions-cell { display: flex; gap: 0.4rem; }

        /* Forms */
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.2rem; }
        .form-col-full { grid-column: span 2; }
        .form-label { display: block; font-size: 0.84rem; font-weight: 600; color: #334155; margin-bottom: 0.35rem; }
        .form-control { width: 100%; padding: 0.65rem 0.95rem; border: 1px solid var(--border); border-radius: 8px; font-size: 0.92rem; outline: none; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.12); }
        textarea.form-control { resize: vertical; min-height: 80px; }
    </style>
</head>
<body>

    <!-- Topbar -->
    <header class="topbar">
        <a href="index.php" class="brand">
            <i class="bx bx-slider-alt"></i>
            <span>Ganga Sagar &bull; Portfolio Admin</span>
        </a>
        <div class="top-actions">
            <a href="?action=export_static" class="btn btn-export" title="Render database into static index.html for GitHub Pages">
                <i class="bx bx-sync"></i>
                <span>Export Static index.html</span>
            </a>
            <a href="../index.php" target="_blank" class="btn btn-secondary">
                <i class="bx bx-link-external"></i>
                <span>View Live Site</span>
            </a>
            <a href="?action=logout" class="btn btn-danger btn-sm">
                <i class="bx bx-log-out"></i>
                <span>Logout</span>
            </a>
        </div>
    </header>

    <main class="container">

        <?php if ($message): ?>
            <div class="alert-bar">
                <span><i class="bx bx-check-circle"></i> <?= htmlspecialchars($message) ?></span>
                <span style="cursor:pointer;" onclick="this.parentElement.style.display='none';">&times;</span>
            </div>
        <?php endif; ?>

        <!-- Tabs Navigation -->
        <nav class="tabs-header">
            <button class="tab-btn active" onclick="showTab('tab-featured')">
                <i class="bx bx-folder"></i>
                <span>Featured Projects</span>
                <span class="badge-count"><?= count($featured_list) ?></span>
            </button>
            <button class="tab-btn" onclick="showTab('tab-academic')">
                <i class="bx bx-git-repo-forked"></i>
                <span>Academic Projects</span>
                <span class="badge-count"><?= count($academic_list) ?></span>
            </button>
            <button class="tab-btn" onclick="showTab('tab-blog')">
                <i class="bx bx-news"></i>
                <span>Insights &amp; Blog</span>
                <span class="badge-count"><?= count($blog_list) ?></span>
            </button>
            <button class="tab-btn" onclick="showTab('tab-inquiries')">
                <i class="bx bx-envelope"></i>
                <span>Contact Inquiries</span>
                <span class="badge-count"><?= count($inquiries_list) ?></span>
            </button>
        </nav>

        <!-- ───────────────────────────────────────────────────────────── -->
        <!-- TAB 1: FEATURED PROJECTS -->
        <!-- ───────────────────────────────────────────────────────────── -->
        <section id="tab-featured" class="tab-panel active">
            <div class="content-card">
                <div class="panel-heading-row">
                    <h2 class="panel-title">Featured Projects</h2>
                    <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-featured')">
                        <i class="bx bx-plus"></i> Add New Project
                    </button>
                </div>

                <!-- Add/Edit Featured Form -->
                <div id="form-add-featured" style="display:none; background:#f8fafc; border:1px solid var(--border); border-radius:12px; padding:1.25rem; margin-bottom:1.5rem;">
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
                        <div style="margin-top:1rem; display:flex; gap:0.5rem;">
                            <button type="submit" name="save_featured" class="btn btn-primary btn-sm">Save Project</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-add-featured')">Cancel</button>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Preview</th>
                                <th>Title</th>
                                <th>Skills</th>
                                <th>Live Link</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($featured_list as $fp): ?>
                            <tr>
                                <td><img src="../<?= htmlspecialchars($fp['image']) ?>" class="img-thumb" alt=""></td>
                                <td><strong><?= htmlspecialchars($fp['title']) ?></strong></td>
                                <td>
                                    <?php foreach ($fp['skills_array'] as $s): ?>
                                        <span class="pill-badge"><?= htmlspecialchars($s) ?></span>
                                    <?php endforeach; ?>
                                </td>
                                <td><a href="<?= htmlspecialchars($fp['live_url']) ?>" target="_blank" style="color:var(--primary); font-size:0.84rem;">Link ↗</a></td>
                                <td><?= intval($fp['display_order']) ?></td>
                                <td class="actions-cell">
                                    <button class="btn btn-secondary btn-sm" onclick='editFeatured(<?= json_encode($fp) ?>)'><i class="bx bx-edit"></i></button>
                                    <a href="?del_feat=<?= $fp['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this project?')"><i class="bx bx-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ───────────────────────────────────────────────────────────── -->
        <!-- TAB 2: ACADEMIC PROJECTS -->
        <!-- ───────────────────────────────────────────────────────────── -->
        <section id="tab-academic" class="tab-panel">
            <div class="content-card">
                <div class="panel-heading-row">
                    <h2 class="panel-title">Key Academic Projects</h2>
                    <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-academic')">
                        <i class="bx bx-plus"></i> Add Academic Project
                    </button>
                </div>

                <!-- Add/Edit Form -->
                <div id="form-add-academic" style="display:none; background:#f8fafc; border:1px solid var(--border); border-radius:12px; padding:1.25rem; margin-bottom:1.5rem;">
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
                        <div style="margin-top:1rem; display:flex; gap:0.5rem;">
                            <button type="submit" name="save_academic" class="btn btn-primary btn-sm">Save Academic Project</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-add-academic')">Cancel</button>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Skills</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($academic_list as $ap): ?>
                            <tr>
                                <td style="width:28%;"><strong><?= htmlspecialchars($ap['title']) ?></strong></td>
                                <td style="font-size:0.84rem; color:var(--text-muted);"><?= htmlspecialchars($ap['description']) ?></td>
                                <td>
                                    <?php foreach ($ap['skills_array'] as $as): ?>
                                        <span class="pill-badge" style="color:#b45309; background:rgba(217,119,6,0.08);"><?= htmlspecialchars($as) ?></span>
                                    <?php endforeach; ?>
                                </td>
                                <td><?= intval($ap['display_order']) ?></td>
                                <td class="actions-cell">
                                    <button class="btn btn-secondary btn-sm" onclick='editAcademic(<?= json_encode($ap) ?>)'><i class="bx bx-edit"></i></button>
                                    <a href="?del_acad=<?= $ap['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this project?')"><i class="bx bx-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ───────────────────────────────────────────────────────────── -->
        <!-- TAB 3: BLOG & INSIGHTS -->
        <!-- ───────────────────────────────────────────────────────────── -->
        <section id="tab-blog" class="tab-panel">
            <div class="content-card">
                <div class="panel-heading-row">
                    <h2 class="panel-title">Engineering Insights &amp; Articles</h2>
                    <button class="btn btn-primary btn-sm" onclick="toggleForm('form-add-blog')">
                        <i class="bx bx-plus"></i> Write New Insight
                    </button>
                </div>

                <!-- Add/Edit Form -->
                <div id="form-add-blog" style="display:none; background:#f8fafc; border:1px solid var(--border); border-radius:12px; padding:1.25rem; margin-bottom:1.5rem;">
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
                        <div style="margin-top:1rem; display:flex; gap:0.5rem;">
                            <button type="submit" name="save_blog" class="btn btn-primary btn-sm">Publish Insight</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-add-blog')">Cancel</button>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Title</th>
                                <th>Date / Read Time</th>
                                <th>Excerpt</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($blog_list as $bp): ?>
                            <tr>
                                <td><span class="pill-badge"><?= htmlspecialchars($bp['category']) ?></span></td>
                                <td><strong><?= htmlspecialchars($bp['title']) ?></strong></td>
                                <td style="font-size:0.82rem; color:var(--text-muted);"><?= htmlspecialchars($bp['read_time']) ?> &bull; <?= htmlspecialchars($bp['date']) ?></td>
                                <td style="font-size:0.84rem; color:var(--text-muted);"><?= htmlspecialchars($bp['excerpt']) ?></td>
                                <td class="actions-cell">
                                    <button class="btn btn-secondary btn-sm" onclick='editBlog(<?= json_encode($bp) ?>)'><i class="bx bx-edit"></i></button>
                                    <a href="?del_blog=<?= $bp['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this post?')"><i class="bx bx-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ───────────────────────────────────────────────────────────── -->
        <!-- TAB 4: CONTACT INQUIRIES -->
        <!-- ───────────────────────────────────────────────────────────── -->
        <section id="tab-inquiries" class="tab-panel">
            <div class="content-card">
                <div class="panel-heading-row">
                    <h2 class="panel-title">Contact Form Inquiries</h2>
                </div>
                <?php if (empty($inquiries_list)): ?>
                    <p style="color:var(--text-muted); font-size:0.92rem;">No messages submitted yet. Messages from the portfolio Contact form will be securely captured here.</p>
                <?php else: ?>
                    <div style="overflow-x:auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sender</th>
                                    <th>Email / Phone</th>
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
                                        <a href="mailto:<?= htmlspecialchars($inq['email']) ?>" style="color:var(--primary);"><?= htmlspecialchars($inq['email']) ?></a>
                                        <?php if (!empty($inq['phone'])): ?>
                                            <br><small style="color:var(--text-muted);"><?= htmlspecialchars($inq['phone']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($inq['subject']) ?></td>
                                    <td style="font-size:0.86rem;"><?= nl2br(htmlspecialchars($inq['message'])) ?></td>
                                    <td style="font-size:0.8rem; color:var(--text-muted);"><?= htmlspecialchars($inq['created_at']) ?></td>
                                    <td>
                                        <a href="?del_msg=<?= $inq['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remove message?')"><i class="bx bx-trash"></i></a>
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

    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));

            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        function toggleForm(formId) {
            const form = document.getElementById(formId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function editFeatured(item) {
            document.getElementById('form-add-featured').style.display = 'block';
            document.getElementById('feat_id').value = item.id;
            document.getElementById('feat_title').value = item.title;
            document.getElementById('feat_image').value = item.image;
            document.getElementById('feat_skills').value = item.skills;
            document.getElementById('feat_live_url').value = item.live_url;
            document.getElementById('feat_order').value = item.display_order;
            document.getElementById('feat_desc').value = item.description;
            window.scrollTo({ top: 180, behavior: 'smooth' });
        }

        function editAcademic(item) {
            document.getElementById('form-add-academic').style.display = 'block';
            document.getElementById('acad_id').value = item.id;
            document.getElementById('acad_title').value = item.title;
            document.getElementById('acad_skills').value = item.skills;
            document.getElementById('acad_order').value = item.display_order;
            document.getElementById('acad_desc').value = item.description;
            window.scrollTo({ top: 180, behavior: 'smooth' });
        }

        function editBlog(item) {
            document.getElementById('form-add-blog').style.display = 'block';
            document.getElementById('blog_id').value = item.id;
            document.getElementById('blog_title').value = item.title;
            document.getElementById('blog_cat').value = item.category;
            document.getElementById('blog_read').value = item.read_time;
            document.getElementById('blog_date').value = item.date;
            document.getElementById('blog_order').value = item.display_order;
            document.getElementById('blog_excerpt').value = item.excerpt;
            document.getElementById('blog_content').value = item.content;
            window.scrollTo({ top: 180, behavior: 'smooth' });
        }
    </script>
</body>
</html>


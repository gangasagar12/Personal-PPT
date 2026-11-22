<?php
/**
 * Shared SEO, GEO, Social Graph & Structured Data Meta Partial
 */
use App\Core\SEO;

$baseUrl = 'https://gangasagar.com.np';
try {
    if (class_exists(SEO::class)) {
        $baseUrl = SEO::baseUrl();
    }
} catch (\Throwable $t) {}

$canonicalPath = $seo_path ?? parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$canonicalUrl = $seo_canonical ?? $baseUrl . ($canonicalPath === '/' ? '' : $canonicalPath);
$title = $seo_title ?? 'Ganga Sagar Joshi — Software Developer & Django Backend Engineer';
$desc = $seo_desc ?? "Portfolio of Ganga Sagar Joshi, Software Developer & Co-Founder at Strataflow Technologies specializing in Python, Django, scalable REST APIs, relational databases, and AI systems in Kathmandu, Nepal.";
$imageRel = !empty($seo_image) ? '/' . ltrim($seo_image, '/') : '/images/logo-square.png';
$imageUrl = str_starts_with($imageRel, 'http') ? $imageRel : $baseUrl . $imageRel;
$type = $seo_type ?? 'website';
$noindex = !empty($seo_noindex);
?>

  <!-- Canonical URL -->
  <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>" />

  <!-- Indexing & Robots Directives -->
<?php if ($noindex): ?>
  <meta name="robots" content="noindex, nofollow, noarchive" />
<?php else: ?>
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
  <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
<?php endif; ?>

  <!-- Standard & Author SEO Meta -->
  <meta name="author" content="Ganga Sagar Joshi" />
  <meta name="creator" content="Ganga Sagar Joshi" />
  <meta name="publisher" content="Strataflow Technologies Pvt. Ltd." />
  <meta name="description" content="<?= htmlspecialchars($desc) ?>" />
  <meta name="keywords" content="Ganga Sagar Joshi, Software Developer Nepal, Django Backend Engineer, Python Developer Kathmandu, Strataflow Technologies, SaaS Product Builder, REST APIs, PostgreSQL" />
  <meta name="theme-color" content="#0b1329" />

  <!-- GEO & Local SEO (Kathmandu, Bagmati, Nepal) -->
  <meta name="geo.region" content="NP-BA" />
  <meta name="geo.placename" content="Kathmandu" />
  <meta name="geo.position" content="27.7172;85.3240" />
  <meta name="ICBM" content="27.7172, 85.3240" />

  <!-- Open Graph / Facebook / LinkedIn -->
  <meta property="og:type" content="<?= htmlspecialchars($type) ?>" />
  <meta property="og:site_name" content="Ganga Sagar Joshi" />
  <meta property="og:title" content="<?= htmlspecialchars($title) ?>" />
  <meta property="og:description" content="<?= htmlspecialchars($desc) ?>" />
  <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>" />
  <meta property="og:image" content="<?= htmlspecialchars($imageUrl) ?>" />
  <meta property="og:image:alt" content="<?= htmlspecialchars($title) ?>" />
  <meta property="og:locale" content="en_US" />

  <!-- Twitter Cards -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= htmlspecialchars($title) ?>" />
  <meta name="twitter:description" content="<?= htmlspecialchars($desc) ?>" />
  <meta name="twitter:image" content="<?= htmlspecialchars($imageUrl) ?>" />
  <meta name="twitter:image:alt" content="<?= htmlspecialchars($title) ?>" />

  <!-- Favicon & Touch Icons (Google Multiple-of-48px Compliant) -->
  <link rel="icon" type="image/x-icon" href="/favicon.ico" />
  <link rel="icon" type="image/png" sizes="48x48" href="/images/favicon-48x48.png" />
  <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon-96x96.png" />
  <link rel="icon" type="image/png" sizes="192x192" href="/images/favicon-192x192.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png" />

  <!-- Performance: Resource Hints & DNS Prefetching -->
  <link rel="dns-prefetch" href="https://fonts.googleapis.com" />
  <link rel="dns-prefetch" href="https://fonts.gstatic.com" />
  <link rel="dns-prefetch" href="https://unpkg.com" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

  <!-- Structured Data: Person, ProfilePage & WebSite Schema (JSON-LD) -->
<?php
  $schemas = [
      SEO::personSchema(),
      SEO::profilePageSchema(),
      SEO::websiteSchema()
  ];
  if (!empty($seo_custom_schema) && is_array($seo_custom_schema)) {
      $schemas[] = $seo_custom_schema;
  }
?>
  <script type="application/ld+json">
  <?= json_encode(['@context' => 'https://schema.org', '@graph' => $schemas], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
  </script>

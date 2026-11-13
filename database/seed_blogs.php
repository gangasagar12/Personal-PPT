<?php
require_once __DIR__ . '/../backend/db.php';

$blogs = [
    [
        'id' => 1,
        'title' => 'Architecting Scalable Relational Database Schemas for High-Traffic Applications',
        'slug' => 'architecting-scalable-relational-database-schemas',
        'category' => 'Database Architecture',
        'image' => 'images/projectcard1.jpg',
        'read_time' => '5 min read',
        'excerpt' => 'How indexing strategies, foreign key constraints, and 3NF normalization prevent latency spikes in production database backends.',
        'content' => <<<EOT
<p>In modern backend engineering, database schema architecture dictates throughput and response latency far more than server-side application logic. When traffic spikes 100x during production launches, an unindexed foreign key or an unconstrained join condition will immediately saturate database worker pools, causing cascade timeouts throughout your microservices.</p>

<h3>1. The Critical Role of Relational Normalization (3NF)</h3>
<p>Third Normal Form (3NF) guarantees that every non-key column depends strictly on the primary key, the whole key, and nothing but the key. Adhering to strict relational integrity prevents data duplication and protects transactional consistency (ACID):</p>
<ul>
  <li><strong>First Normal Form (1NF):</strong> Enforce atomic scalar values; avoid storing delimited CSV strings or array JSONs when records need individual indexed query capabilities.</li>
  <li><strong>Second Normal Form (2NF):</strong> Remove partial dependencies on composite keys so tables have single, well-defined identities.</li>
  <li><strong>Third Normal Form (3NF):</strong> Eliminate transitive dependencies where non-key attributes determine other non-key attributes.</li>
</ul>

<h3>2. High-Performance Indexing Strategies</h3>
<p>Every index carries a write penalty because insert and update transactions must update the B-tree structure. However, read queries without indexes degrade to full table sequential scans (O(N)):</p>
<ul>
  <li><strong>Foreign Key Indexing:</strong> Most relational databases (PostgreSQL, MySQL InnoDB) do not automatically create indexes on foreign keys referenced in child tables. Always add explicit composite indexes for parent-child relationship keys.</li>
  <li><strong>Covering Indexes:</strong> Include frequently selected columns in composite indexes so queries can be satisfied entirely from the index memory pages without touching disk heap blocks.</li>
  <li><strong>Partial & Filtered Indexes:</strong> Index only active records (e.g., <code>WHERE is_active = 1</code>) to keep index memory footprints small and cache-friendly.</li>
</ul>

<h3>3. Controlled Denormalization & Read Replicas</h3>
<p>Only denormalize when read traffic cannot be satisfied by read replicas and caching layers (Redis/Memcached). When denormalizing aggregates, enforce updates through database triggers or atomic background jobs to prevent silent data desynchronization.</p>

<h3>Key Takeaway</h3>
<p>Architect your relational models strictly normalized first, establish benchmark queries under simulated loads with <code>EXPLAIN ANALYZE</code>, and introduce targeted indexes before attempting premature caching or distributed NoSQL shifts.</p>
EOT
,
        'date' => 'Nov 2026',
        'display_order' => 1
    ],
    [
        'id' => 2,
        'title' => 'Designing Bulletproof RESTful APIs with Django REST Framework and JWT',
        'slug' => 'designing-bulletproof-restful-apis-drf-jwt',
        'category' => 'Backend Engineering',
        'image' => 'images/projectcard2.jpg',
        'read_time' => '6 min read',
        'excerpt' => 'A pragmatic guide to architecting decoupled, stateless authentication microservices with token refreshing and role-based permissions.',
        'content' => <<<EOT
<p>Stateless API design allows web frontends, mobile applications, and third-party integrations to communicate seamlessly with backend systems without server-side session affinity. Implementing JSON Web Token (JWT) authentication properly requires addressing token lifecycles, revocation, and robust permission guards.</p>

<h3>1. Token Pair Strategy (Access + Refresh Tokens)</h3>
<p>A common vulnerability in naive JWT setups is issuing tokens with long expiration windows (e.g., 30 days). If intercepted, an attacker holds persistent access. A battle-tested approach uses short-lived access tokens combined with sliding refresh tokens:</p>
<ul>
  <li><strong>Access Tokens (10 - 15 Minutes):</strong> Packed with minimal user metadata (user_id, role, tenant_id) and signed with RSA256 or HS256. Validated statelessly on every API request.</li>
  <li><strong>Refresh Tokens (7 Days):</strong> Stored securely in <code>HttpOnly, Secure, SameSite=Strict</code> cookies or encrypted client storage. Handled only by a dedicated token refresh endpoint.</li>
  <li><strong>Blacklisting & Revocation:</strong> Store revoked refresh token signatures in an in-memory Redis cluster with TTL matching the expiration window to allow immediate session termination upon logout.</li>
</ul>

<h3>2. Idiomatic Serialization & Field Validation</h3>
<p>Django REST Framework serializers act as the firewall between untrusted HTTP requests and your internal database models. Never rely on frontend validation alone:</p>
<ul>
  <li>Always define explicit fields in <code>Meta.fields</code> rather than using <code>'__all__'</code> to prevent accidental mass assignment vulnerabilities.</li>
  <li>Implement field-level <code>validate_fieldname()</code> methods to sanitize inputs, enforce regular expression patterns, and verify business constraints before persistence.</li>
</ul>

<h3>3. Defense in Depth: Rate Limiting & Error Normalization</h3>
<p>Expose uniform JSON error formats across all HTTP 4xx and 5xx responses with standard machine-readable codes. Implement token bucket rate limiting (DRF ScopedRateThrottle or Redis-backed throttles) to insulate endpoints against brute-force attacks and resource exhaustion.</p>
EOT
,
        'date' => 'Oct 2026',
        'display_order' => 2
    ],
    [
        'id' => 3,
        'title' => 'Optimizing Django QuerySets & Eliminating the Dreaded N+1 Problem',
        'slug' => 'optimizing-django-querysets-n-plus-1',
        'category' => 'Python & Django',
        'image' => 'images/projectcard3.webp',
        'read_time' => '4 min read',
        'excerpt' => 'Mastering select_related and prefetch_related to drastically reduce SQL roundtrips and improve application throughput.',
        'content' => <<<EOT
<p>Django's Object-Relational Mapper (ORM) provides powerful developer ergonomics, but its lazy evaluation model can quietly trigger the infamous N+1 query antipattern. A view rendering 50 items can inadvertently execute 51 or even 151 individual SQL statements, choking the database connection pool.</p>

<h3>1. What Causes the N+1 Antipattern?</h3>
<p>When you loop over a QuerySet and access a foreign key or related relation that was not eagerly loaded, Django fires a separate SQL query for each single record in the iteration:</p>
<pre><code># Antipattern: Fires 1 query for clinics + 50 queries for doctor details!
clinics = Clinic.objects.all()
for c in clinics:
    print(c.head_doctor.name)</code></pre>

<h3>2. The Antidotes: select_related vs. prefetch_related</h3>
<p>Understanding which technique to use based on the relational cardinalities is crucial for performance:</p>
<ul>
  <li><strong><code>select_related</code> (Single-valued relationships):</strong> Uses an SQL <code>INNER JOIN</code> or <code>LEFT OUTER JOIN</code> to fetch the parent and related records in a single query. Ideal for <code>ForeignKey</code> and <code>OneToOneField</code>.</li>
  <li><strong><code>prefetch_related</code> (Multi-valued relationships):</strong> Executes a second targeted query using <code>WHERE id IN (...)</code> and joins the records in Python memory. Ideal for <code>ManyToManyField</code> and reverse foreign key relations.</li>
</ul>

<h3>3. Advanced Prefetching with Custom Filtered QuerySets</h3>
<p>Use the <code>Prefetch</code> object when you need to order, filter, or slice the prefetched related records:</p>
<pre><code>from django.db.models import Prefetch

# Fetch doctors and only their active appointments scheduled for today
today_appointments = Appointment.objects.filter(status='scheduled', date=today)
doctors = Doctor.objects.prefetch_related(
    Prefetch('appointments', queryset=today_appointments, to_attr='todays_schedule')
)</code></pre>

<h3>Result</h3>
<p>By replacing naive iteration with targeted eager loading, API response times commonly plummet from 800ms down to under 45ms, while reducing database CPU consumption by over 90%.</p>
EOT
,
        'date' => 'Sep 2026',
        'display_order' => 3
    ]
];

foreach ($blogs as $b) {
    $stmt = $pdo->prepare("UPDATE blog_insights SET 
        title = ?, slug = ?, category = ?, image = ?, read_time = ?, excerpt = ?, content = ?, date = ?, display_order = ?
        WHERE id = ?");
    $stmt->execute([
        $b['title'],
        $b['slug'],
        $b['category'],
        $b['image'],
        $b['read_time'],
        $b['excerpt'],
        $b['content'],
        $b['date'],
        $b['display_order'],
        $b['id']
    ]);
}

echo "Successfully updated " . count($blogs) . " blog articles with rich content and images.\n";


<?php
$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'appuser';
$pass = getenv('DB_PASSWORD') ?: 'apppassword';
$dbName = getenv('DB_NAME') ?: 'homeworkdb';
$appVersion = getenv('APP_VERSION') ?: 'v1';
$hostname = gethostname();

$connected = false;
$count = null;
$errorMsg = null;

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbName};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
        ]
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS visits (
            id INT AUTO_INCREMENT PRIMARY KEY,
            visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB"
    );

    // Each page load records one visit. The row remains in the named volume.
    $pdo->exec("INSERT INTO visits () VALUES ()");
    $count = (int) $pdo->query("SELECT COUNT(*) FROM visits")->fetchColumn();
    $connected = true;
} catch (Throwable $e) {
    // Show a concise diagnostic on the homework page.
    $errorMsg = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Docker Compose Homework</title>
<style>
:root { color-scheme: light; --navy:#0f172a; --blue:#2563eb; --muted:#64748b; }
* { box-sizing:border-box; }
body { margin:0; min-height:100vh; display:grid; place-items:center; padding:24px; text-align:center;
       font-family:Arial,Helvetica,sans-serif; background:linear-gradient(135deg,#0f172a,#1d4ed8); }
.card { width:min(100%,540px); background:#fff; border-radius:18px; padding:36px;
        box-shadow:0 20px 55px #0004; }
.badge { display:inline-block; border-radius:999px; background:#dbeafe; color:#1d4ed8;
         padding:7px 12px; font-weight:bold; font-size:12px; text-align:center; margin-bottom:12px; }
h1 { color:var(--navy); margin:18px 0 8px; font-size:28px; }
.subtitle { color:var(--muted); margin-bottom:24px; }
.status { font-weight:bold; margin:20px 0; text-align:center; font-size:16px; }
.ok { color:#15803d; } .err { color:#b91c1c; }
.counter { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;
           padding:22px; text-align:center; margin:20px 0; }
.number { font-size:48px; font-weight:bold; color:var(--blue); }
.label { color:var(--muted); margin-top:5px; }
.meta { color:var(--muted); font:12px monospace; overflow-wrap:anywhere; text-align:center; margin-top:12px; }
.error { color:#991b1b; background:#fef2f2; border:1px solid #fecaca; padding:12px;
         border-radius:8px; overflow-wrap:anywhere; font-size:13px; }
p { color:var(--muted); }
</style>
</head>
<body>
<main class="card">
  <span class="badge">APP VERSION <?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?></span>
  <h1>Docker Compose Homework</h1>
  <div class="subtitle">PHP · MySQL · Custom Network · Named Volume</div>
  <?php if ($connected): ?>
    <div class="status ok">● Connected to MySQL successfully</div>
    <div class="counter">
      <div class="number"><?= htmlspecialchars((string)$count, ENT_QUOTES, 'UTF-8') ?></div>
      <div class="label">total visits recorded in the database</div>
    </div>
    <div class="meta">Application container: <?= htmlspecialchars($hostname, ENT_QUOTES, 'UTF-8') ?></div>
    <p>Reload this page to add a visit. Then restart the Compose project to test persistence.</p>
  <?php else: ?>
    <div class="status err">● Database connection failed</div>
    <div class="error"><?= htmlspecialchars($errorMsg ?? 'Unknown error', ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
</main>
</body>
</html>

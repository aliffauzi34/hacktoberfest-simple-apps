<?php
// 🔐 PHP Password Generator (Bootstrap)
// Options -> build a character pool -> generate with random_int()

$error = '';
$result = '';
$len = (int)($_POST['length'] ?? 12);
$useLower = isset($_POST['lower']);
$useUpper = isset($_POST['upper']);
$useNums  = isset($_POST['numbers']);
$useSyms  = isset($_POST['symbols']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($len < 4 || $len > 64) {
    $error = 'Length must be between 4 and 64.';
  } else {
    $pool = '';
    if ($useLower) $pool .= 'abcdefghijklmnopqrstuvwxyz';
    if ($useUpper) $pool .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($useNums)  $pool .= '0123456789';
    if ($useSyms)  $pool .= '!@#$%^&*()-_=+[]{};:,.?/';

    if ($pool === '') {
      $error = 'Select at least one character set.';
    } else {
      $pwd = '';
      $poolLen = strlen($pool);

      // ensure at least one char from each selected set (simple coverage)
      $sets = [];
      if ($useLower) $sets[] = 'abcdefghijklmnopqrstuvwxyz';
      if ($useUpper) $sets[] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      if ($useNums)  $sets[] = '0123456789';
      if ($useSyms)  $sets[] = '!@#$%^&*()-_=+[]{};:,.?/';
      foreach ($sets as $s) { $pwd .= $s[random_int(0, strlen($s)-1)]; }

      // fill the rest
      for ($i = strlen($pwd); $i < $len; $i++) {
        $pwd .= $pool[random_int(0, $poolLen - 1)];
      }

      // simple shuffle
      $pwd = str_shuffle($pwd);
      $result = $pwd;
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>PHP Password Generator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#f7f7fb;min-height:100vh;display:flex;align-items:center;justify-content:center}
    .card{border-radius:1rem;box-shadow:0 10px 30px rgba(0,0,0,.08);max-width:720px;width:100%}
    code.small{font-size:.9rem}
  </style>
</head>
<body>
  <div class="container p-3 p-md-4">
    <div class="card p-4 p-md-5 mx-auto">
      <h1 class="h4 mb-3">🔐 PHP Password Generator</h1>
      <p class="text-muted mb-4">Generate a strong password using PHP <code class="small">random_int()</code>. No DB, no JS required (copy with your mouse/keyboard).</p>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" class="row g-3 align-items-end">
        <div class="col-12 col-md-3">
          <label class="form-label">Length</label>
          <input type="number" min="4" max="64" name="length" value="<?= htmlspecialchars((string)($len ?: 12)) ?>" class="form-control" required>
        </div>

        <div class="col-12 col-md-9">
          <div class="row g-2">
            <div class="col-6 col-md-3 form-check">
              <input class="form-check-input" type="checkbox" id="lower" name="lower" <?= $useLower?'checked':'' ?> >
              <label class="form-check-label" for="lower">a–z</label>
            </div>
            <div class="col-6 col-md-3 form-check">
              <input class="form-check-input" type="checkbox" id="upper" name="upper" <?= $useUpper?'checked':'' ?> >
              <label class="form-check-label" for="upper">A–Z</label>
            </div>
            <div class="col-6 col-md-3 form-check">
              <input class="form-check-input" type="checkbox" id="numbers" name="numbers" <?= $useNums?'checked':'' ?> >
              <label class="form-check-label" for="numbers">0–9</label>
            </div>
            <div class="col-6 col-md-3 form-check">
              <input class="form-check-input" type="checkbox" id="symbols" name="symbols" <?= $useSyms?'checked':'' ?> >
              <label class="form-check-label" for="symbols">Symbols</label>
            </div>
          </div>
        </div>

        <div class="col-12 d-grid d-md-block">
          <button class="btn btn-dark px-4" type="submit">Generate</button>
        </div>
      </form>

      <?php if ($result): ?>
        <hr class="my-4">
        <label class="form-label">Result</label>
        <input class="form-control form-control-lg" value="<?= htmlspecialchars($result) ?>" readonly>
        <div class="form-text mt-2">Copy the password above and store it safely.</div>
      <?php endif; ?>

      <hr class="my-4">
      <small class="text-muted">Tip: increase length (16–24) for stronger passwords. This is a demo generator with static character sets.</small>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

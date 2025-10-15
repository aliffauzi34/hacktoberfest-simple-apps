<?php
// ✨ PHP Slug Generator (Bootstrap) — single-file app
// Features: lowercase option, custom delimiter, ascii cleanup, length limit, optional stopword removal

function slugify($text, $delimiter = '-', $lowercase = true, $limit = 80, $rmStop = false) {
  $orig = $text;

  // 1) Normalize unicode -> ASCII basic (strip accents where possible)
  $trans = [
    'Š'=>'S','š'=>'s','Ð'=>'Dj','Ž'=>'Z','ž'=>'z','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A',
    'Æ'=>'AE','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N',
    'Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y',
    'Þ'=>'B','ß'=>'ss','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'ae','ç'=>'c','è'=>'e',
    'é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o',
    'ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ý'=>'y','þ'=>'b','ÿ'=>'y'
  ];
  $text = strtr($text, $trans);

  // 2) Replace non-alnum with delimiter
  $text = preg_replace('~[^A-Za-z0-9]+~', $delimiter, $text);

  // 3) Trim duplicate delimiters
  $text = preg_replace('~' . preg_quote($delimiter, '~') . '+~', $delimiter, $text);
  $text = trim($text, $delimiter);

  // 4) Lowercase if requested
  if ($lowercase) $text = strtolower($text);

  // 5) Remove common stopwords (en/id) if requested
  if ($rmStop) {
    $stops = [
      // english
      'the','a','an','and','or','of','to','in','on','for','with','by','from','at','is','are',
      // bahasa indonesia
      'yang','dan','atau','di','ke','dari','untuk','dengan','pada','ini','itu'
    ];
    $parts = array_filter(explode($delimiter, $text), function($w) use ($stops) {
      return $w !== '' && !in_array($w, $stops, true);
    });
    $text = implode($delimiter, $parts);
  }

  // 6) Limit length
  if ($limit > 0 && strlen($text) > $limit) {
    $text = substr($text, 0, $limit);
    $text = rtrim($text, $delimiter);
  }

  return [$orig, $text];
}

$result = '';
$input  = '';
$delimiter = '-';
$limit = 80;
$lower = true;
$rmStop = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $input    = trim($_POST['input'] ?? '');
  $delimiter= $_POST['delimiter'] !== '' ? substr($_POST['delimiter'],0,1) : '-';
  $limit    = (int)($_POST['limit'] ?? 80);
  $lower    = isset($_POST['lower']);
  $rmStop   = isset($_POST['rmstop']);

  if ($limit < 10) $limit = 10;
  if ($limit > 120) $limit = 120;

  [, $result] = slugify($input, $delimiter, $lower, $limit, $rmStop);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>PHP Slug Generator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#f7f7fb;min-height:100vh;display:flex;align-items:center;justify-content:center}
    .card{border-radius:1rem;box-shadow:0 10px 30px rgba(0,0,0,.08);max-width:860px;width:100%}
  </style>
</head>
<body>
  <div class="container p-3 p-md-4">
    <div class="card p-4 p-md-5 mx-auto">
      <h1 class="h4 mb-3">🔗 PHP Slug Generator</h1>
      <p class="text-muted mb-4">Convert any title into a clean URL slug. Options: lowercase, custom delimiter, stopword removal, length limit.</p>

      <form method="post" class="row g-3">
        <div class="col-12">
          <label class="form-label">Title / Text</label>
          <textarea name="input" class="form-control" rows="3" required><?= htmlspecialchars($input) ?></textarea>
        </div>

        <div class="col-6 col-md-3">
          <label class="form-label">Delimiter</label>
          <input name="delimiter" class="form-control" maxlength="1" value="<?= htmlspecialchars($delimiter) ?>">
          <div class="form-text">Default: hyphen (-)</div>
        </div>

        <div class="col-6 col-md-3">
          <label class="form-label">Max Length</label>
          <input type="number" name="limit" min="10" max="120" class="form-control" value="<?= (int)$limit ?>">
        </div>

        <div class="col-12 col-md-6 d-flex align-items-end">
          <div class="form-check me-4">
            <input class="form-check-input" type="checkbox" id="lower" name="lower" <?= $lower ? 'checked':'' ?>>
            <label class="form-check-label" for="lower">Lowercase</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="rmstop" name="rmstop" <?= $rmStop ? 'checked':'' ?>>
            <label class="form-check-label" for="rmstop">Remove stopwords (en/id)</label>
          </div>
        </div>

        <div class="col-12 d-grid d-md-block">
          <button class="btn btn-dark px-4">Generate</button>
        </div>
      </form>

      <?php if ($result !== ''): ?>
        <hr class="my-4">
        <div class="mb-2"><strong>Result:</strong></div>
        <input class="form-control form-control-lg" value="<?= htmlspecialchars($result) ?>" readonly>

        <div class="mt-3">
          <label class="form-label">Example URL</label>
          <input class="form-control" value="https://example.com/blog/<?= htmlspecialchars($result) ?>" readonly>
        </div>
      <?php endif; ?>

      <hr class="my-4">
      <small class="text-muted">Tip: try titles with accents or Bahasa Indonesia stopwords to see normalization and removal in action.</small>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

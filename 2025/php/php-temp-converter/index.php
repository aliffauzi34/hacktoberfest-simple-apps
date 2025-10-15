<?php
// 🌡️ PHP Temperature Converter (Bootstrap)
$result = null;
$type = $_POST['type'] ?? 'CtoF';
$value = $_POST['value'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_numeric($value)) {
    $value = floatval($value);
    if ($type === 'CtoF') {
        $result = ($value * 9 / 5) + 32;
    } else {
        $result = ($value - 32) * 5 / 9;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>PHP Temperature Converter</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="col-md-6 mx-auto">
    <div class="card shadow-sm p-4">
      <h3 class="mb-3 text-center">🌡️ PHP Temperature Converter</h3>
      <form method="post" class="row g-3">
        <div class="col-12">
          <label class="form-label">Enter Value</label>
          <input type="number" step="0.1" name="value" class="form-control" value="<?= htmlspecialchars($value) ?>" required>
        </div>
        <div class="col-12">
          <label class="form-label">Convert Type</label>
          <select name="type" class="form-select">
            <option value="CtoF" <?= $type==='CtoF'?'selected':'' ?>>Celsius → Fahrenheit</option>
            <option value="FtoC" <?= $type==='FtoC'?'selected':'' ?>>Fahrenheit → Celsius</option>
          </select>
        </div>
        <div class="col-12 d-grid">
          <button class="btn btn-primary">Convert</button>
        </div>
      </form>

      <?php if ($result !== null): ?>
        <div class="alert alert-info text-center mt-4">
          <strong>Result:</strong> <?= round($result, 2) ?>°
          <?= $type === 'CtoF' ? 'F' : 'C' ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>

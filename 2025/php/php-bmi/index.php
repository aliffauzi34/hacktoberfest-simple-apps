<?php
// PHP BMI Calculator (Bootstrap version)
$bmi = null;
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $weight = floatval($_POST['weight'] ?? 0);
    $height = floatval($_POST['height'] ?? 0) / 100; // convert cm to meter

    if ($weight > 0 && $height > 0) {
        $bmi = round($weight / ($height * $height), 2);

        if ($bmi < 18.5) $status = 'Underweight';
        elseif ($bmi < 24.9) $status = 'Normal';
        elseif ($bmi < 29.9) $status = 'Overweight';
        else $status = 'Obese';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="col-md-6 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-center mb-3">🏋️‍♀️ PHP BMI Calculator</h3>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" name="weight" step="0.1" min="1" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Height (cm)</label>
                            <input type="number" name="height" step="0.1" min="1" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Calculate</button>
                    </form>

                    <?php if ($bmi): ?>
                        <div class="alert alert-info mt-4 text-center">
                            <strong>Your BMI:</strong> <?= $bmi ?><br>
                            <span class="fw-bold"><?= $status ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php date_default_timezone_set("Asia/Jakarta"); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>PHP Digital Clock</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#121212;color:#0f0;display:flex;justify-content:center;align-items:center;height:100vh;font-family:monospace;font-size:3rem}
.clock{border:2px solid #0f0;padding:20px;border-radius:10px;box-shadow:0 0 20px #0f0 inset}
</style>
<script>
function updateClock(){
  const d = new Date();
  document.getElementById("clock").textContent = d.toLocaleTimeString();
}
setInterval(updateClock, 1000);
</script>
</head>
<body onload="updateClock()">
<div class="clock" id="clock"><?= date("H:i:s") ?></div>
</body>
</html>
<?php
$reason = $_GET['reason'] ?? 'wrong';
$win = $_GET['win'] ?? 'false';

session_start();
session_destroy();

$message = ($win === 'true') 
  ? "🎉 Congratulations, Millionaire!"
  : (($reason === 'timeout') 
      ? "⏱ Time’s up! Game over." 
      : "❌ Wrong Answer. Game Over!");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Game Result</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- 🔊 Sound for result -->
  <audio autoplay>
    <source src="assets/sounds/<?= $win === 'true' ? 'correct' : 'wrong' ?>.mp3" type="audio/mpeg">
  </audio>

  <!-- 🎉 Display message -->
  <div class="welcome-container">
    <h1><?= $message ?></h1>
    <a href="index.php" class="start-button">Play Again</a>
  </div>

</body>
</html>
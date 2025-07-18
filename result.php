<?php




$reason = $_GET['reason'] ?? 'wrong';
$win = $_GET['win'] ?? 'false';

session_start();


require_once 'leaderboard.php';

// Determine player name & score.
$player = $_SESSION["username"] ?? 'Guest???';
$q_index = $_SESSION['q_index'] ?? 0;

// If they won all 15 questions:
if (isset($_GET['win']) && $_GET['win'] === 'true') {
    $score = pow(2, 14) * 100; // $1,638,400 for question 15
} else {
    // Last correct = index-1
    $last = max(0, $q_index - 1);
    $score = pow(2, $last) * 100;
}

// Record into leaderboard
record_score($player, $score);



session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Game Result</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      background: radial-gradient(circle, #000428, #004e92);
      color: white;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      flex-direction: column;
      text-align: center;
      overflow: hidden;
    }

    h1 {
      font-size: 2.5em;
      margin-bottom: 20px;
    }

    .start-button {
      display: inline-block;
      text-align: center;
      padding: 12px 24px;
      background-color: #00c3ff;
      color: #000;
      border-radius: 10px;
      text-decoration: none;
      font-size: 1.1em;
      font-weight: bold;
      box-shadow: 0 0 10px #00c3ff;
      transition: 0.3s ease-in-out;
    }

    .start-button:hover {
      background-color: #00e6ff;
      transform: scale(1.05);
    }

    /* 🎉 Confetti styles */
    .confetti-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      overflow: hidden;
      z-index: 999;
    }

    .confetti {
      position: absolute;
      top: -20px;
      font-size: 24px;
      animation: fall 3s linear infinite;
      left: calc(100% * var(--x));
      animation-delay: calc(var(--x) * 2s);
    }

    @keyframes fall {
      to {
        transform: translateY(100vh) rotate(720deg);
      }
    }
  </style>
</head>
<body class="<?= $reason === 'timeout' ? 'fade-out' : '' ?>">

   <!-- 🔊 Sound for results -->
  <audio controls>
    <source src="assets/sounds/<?= $win === 'true' ? 'win.mp3' : ($reason === 'timeout' ? 'lose.mp3' : 'lose.mp3') ?>" type="audio/mpeg">
  </audio>

  <!-- 🎉 Confetti if win -->
  <?php if ($win === 'true'): ?>
    <div class="confetti-container">
      <?php for ($i = 0; $i < 50; $i++): ?>
        <div class="confetti" style="--x: <?= rand(0, 100) / 100 ?>;">🎉</div>
      <?php endfor; ?>
    </div>
  <?php endif; ?>

  <!-- 🧠 Message -->
  <div class="welcome-container">
    <h1>
      <?php
        if ($win === 'true') {
          echo "🎉 Congratulations, Millionaire!";
        } elseif ($reason === 'timeout') {
          echo "⏱ Time’s up! Game over.";
        } else {
          echo "❌ Wrong Answer. Game Over!";
        }
      ?>
    </h1>

    <a href="login.php" class="start-button">Play Again</a>
  </div>

</body>
</html>
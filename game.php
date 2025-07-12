<?php
session_start();
include('data/questions.php');
include('lifelines.php');

// Initialize session values
if (!isset($_SESSION['q_index'])) {
    $_SESSION['q_index'] = 0;
    $_SESSION['start_time'] = time();
    $_SESSION['used_5050'] = false;
}

$currentIndex = $_SESSION['q_index'];
$current = $questions[$currentIndex];
$timeLeft = 30 - (time() - $_SESSION['start_time']);

if ($timeLeft <= 0) {
    header('Location: result.php?win=false');
    exit();
}

// Handle lifeline activation
if (isset($_POST['use_5050'])) {
    $_SESSION['used_5050'] = true;
}

// Handle answer submission
if (isset($_POST['answer'])) {
    $selected = $_POST['answer'];
    if ($selected == $current['answer']) {
        $_SESSION['q_index']++;
        $_SESSION['start_time'] = time(); // reset timer
        $_SESSION['used_5050'] = false;   // reset lifeline for next question
        if ($_SESSION['q_index'] >= count($questions)) {
            header('Location: result.php?win=true');
            exit();
        }
        header('Location: game.php');
        exit();
    } else {
        header('Location: result.php?win=false');
        exit();
    }
}

// Determine which options to show
$showIndexes = range(0, 3);
if ($_SESSION['used_5050']) {
    $showIndexes = get5050Options($current['choices'], $current['answer']);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Millionaire Game</title>
  <link rel="stylesheet" href="css/style.css">
  <meta http-equiv="refresh" content="<?= $timeLeft ?>;url=result.php?win=false&reason=timeout">
</head>
<body>
  <audio autoplay loop>
    <source src="assets/sounds/background.mp3" type="audio/mpeg">
  </audio>

  <div style="text-align: center; margin-bottom: 30px;">
    <h1 style="font-family: 'Trebuchet MS', Arial, sans-serif; font-size: 2.5em; color:white;">
      Who Wants to Be a Millionaire?
    </h1>
    <img src="assets/logo.png" alt="Millionaire Logo" style="max-width: 220px; width: 100%; height: auto;">
  </div>

  <div class="game-wrapper">
    <div class="money-ladder">
      <?php for ($i = 14; $i >= 0; $i--): ?>
        <div class="ladder-item <?= $_SESSION['q_index'] == $i ? 'active' : '' ?>">
          <?= ($i + 1) . ' $' . number_format(pow(2, $i) * 100) ?>
        </div>
      <?php endfor; ?>
    </div>

    <div>
      <div class="lifeline-bar" style="display: flex; gap: 10px;">
        <form method="post" style="margin: 0;">
          <?php if (!$_SESSION['used_5050']): ?>
            <button name="use_5050" title="Use 50:50">
              <img src="assets/icons/50-50.png" alt="50:50">
            </button>
          <?php endif; ?>
        </form>
        <form method="post" style="margin: 0;">
          <?php if (!$_SESSION['used_audience']): ?>
            <button name="use_audience" title="Use audience">
              <img src="assets/icons/audience.png" alt="audience">
            </button>
          <?php endif; ?>
        </form>
        <form method="post" style="margin: 0;">
          <?php if (!$_SESSION['use_phone_a_friend']): ?>
            <button name="use_phone_a_friend" title="Use phone a friend">
              <img src="assets/icons/phone_a_friend.png" alt="phone_a_friend">
            </button>
          <?php endif; ?>
        </form>
      </div>

      <form method="post" class="question-box">
        <h2><?= $current['question'] ?></h2>
        <p>⏱ <?= $timeLeft ?> seconds remaining</p>
        <div class="answers-grid">
          <div class="answer-row">
            <?php if (in_array(0, $showIndexes)): ?>
              <button name="answer" value="0">A: <?= $current['choices'][0] ?></button>
            <?php endif; ?>
            <?php if (in_array(1, $showIndexes)): ?>
              <button name="answer" value="1">B: <?= $current['choices'][1] ?></button>
            <?php endif; ?>
          </div>
          <div class="answer-row">
            <?php if (in_array(2, $showIndexes)): ?>
              <button name="answer" value="2">C: <?= $current['choices'][2] ?></button>
            <?php endif; ?>
            <?php if (in_array(3, $showIndexes)): ?>
              <button name="answer" value="3">D: <?= $current['choices'][3] ?></button>
            <?php endif; ?>
          </div>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
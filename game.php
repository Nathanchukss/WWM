<?php
session_start();
include('data/questions.php');
include('lifelines.php');

// Initialize game session
if (!isset($_SESSION['q_index'])) {
    $_SESSION['q_index'] = 0;
    $_SESSION['start_time'] = time();
    $_SESSION['used_5050'] = false;
    $_SESSION['used_audience'] = false;
    $_SESSION['used_phone_a_friend'] = false;
    $_SESSION['fifty_options'] = null;
    $_SESSION['phone_result'] = null;
    $_SESSION['audience_result'] = null;
}

$currentIndex = $_SESSION['q_index'];
$current = $questions[$currentIndex];
$timeLeft = 30 - (time() - $_SESSION['start_time']);

if ($timeLeft <= 0) {
    header('Location: result.php?win=false&reason=timeout');
    exit();
}

// 50:50 activation (only once, for one question)
if (isset($_POST['use_5050']) && !$_SESSION['used_5050']) {
    $_SESSION['used_5050'] = true;
    $_SESSION['fifty_options'] = [
        'q' => $_SESSION['q_index'],
        'indexes' => get5050Options($current['choices'], $current['answer'])
    ];
}

// Ask the Audience lifeline
if (isset($_POST['use_audience']) && !$_SESSION['used_audience']) {
    $_SESSION['used_audience'] = true;

    $correct = $current['answer'];
    $votes = [0, 0, 0, 0];
    $votes[$correct] = rand(40, 70);
    $remaining = 100 - $votes[$correct];

    $wrong_indexes = array_diff([0, 1, 2, 3], [$correct]);
    shuffle($wrong_indexes);

    foreach ($wrong_indexes as $i => $index) {
        if ($i === 2) {
            $votes[$index] = $remaining;
        } else {
            $share = rand(0, $remaining);
            $votes[$index] = $share;
            $remaining -= $share;
        }
    }

    $_SESSION['audience_result'] = $votes;
}

// Phone a Friend lifeline
if (isset($_POST['use_phone_a_friend']) && !$_SESSION['used_phone_a_friend']) {
    $_SESSION['used_phone_a_friend'] = true;

    $_SESSION['phone_result'] = [
        'guess' => rand(0, 3),
        'confidence' => rand(60, 100)
    ];
}

// Handle answer submission
if (isset($_POST['answer'])) {
    $selected = $_POST['answer'];
    if ($selected == $current['answer']) {
        $_SESSION['q_index']++;
        $_SESSION['start_time'] = time();

        // Reset visual state (but keep lifeline usage locked)
        $_SESSION['phone_result'] = null;
        $_SESSION['audience_result'] = null;
        $_SESSION['fifty_options'] = null;

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

// Determine which answer options to show
if (isset($_SESSION['fifty_options']) && $_SESSION['fifty_options']['q'] === $_SESSION['q_index']) {
    $showIndexes = $_SESSION['fifty_options']['indexes'];
} else {
    $showIndexes = range(0, 3);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Millionaire Game</title>
  <link rel="stylesheet" href="css/style.css">
  <meta http-equiv="refresh" content="<?= $timeLeft ?>;url=result.php?win=false&reason=timeout">
  <style>
    .question-box {
      opacity: 0;
      animation: fadeIn 0.8s ease-in-out forwards;
      animation-delay: 0.2s;
      transform: scale(0.97);
    }

    @keyframes fadeIn {
      to {
        opacity: 1;
        transform: scale(1);
      }
    }
  </style>
</head>
<body>
  <audio autoplay loop>
    <source src="assets/sounds/background.mp3" type="audio/mpeg">
  </audio>

  <div style="text-align: center; margin-bottom: 30px;">
    <h1 style="font-family: 'Trebuchet MS', Arial, sans-serif; font-size: 2.5em; color:white;">
      Who Wants to Be a Millionaire?
    </h1>
    <img src="assets/icons/logo1.png" alt="Millionaire Logo" style="max-width: 220px; width: 100%; height: auto;">
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
        <?php if (!$_SESSION['used_5050']): ?>
          <form method="post" style="margin: 0;">
            <button name="use_5050" title="Use 50:50">
              <img src="assets/icons/50-50.png" alt="50:50">
            </button>
          </form>
        <?php endif; ?>
        <?php if (!$_SESSION['used_audience']): ?>
          <form method="post" style="margin: 0;">
            <button name="use_audience" title="Ask the Audience">
              <img src="assets/icons/audience.png" alt="Audience">
            </button>
          </form>
        <?php endif; ?>
        <?php if (!$_SESSION['used_phone_a_friend']): ?>
          <form method="post" style="margin: 0;">
            <button name="use_phone_a_friend" title="Phone a Friend">
              <img src="assets/icons/phone_a_friend.png" alt="Phone a Friend">
            </button>
          </form>
        <?php endif; ?>
      </div>

      <form method="post" class="question-box">
        <h2><?= $current['question'] ?></h2>
        <p>⏱ <?= $timeLeft ?> seconds remaining</p>

        <?php if ($_SESSION['phone_result']): ?>
          <div style="background:#003366;padding:1em;border-radius:10px;margin:1em auto;color:#00e6ff;">
            📞 Your friend thinks the answer is
            <strong><?= chr(65 + $_SESSION['phone_result']['guess']) ?>: <?= $current['choices'][$_SESSION['phone_result']['guess']] ?></strong>
            with <strong><?= $_SESSION['phone_result']['confidence'] ?>%</strong> confidence.
          </div>
        <?php endif; ?>

        <?php if ($_SESSION['audience_result']): ?>
          <div style="background:#002244;padding:1em;margin:1em auto;border-radius:10px;color:white;width:80%;max-width:400px;text-align:left;">
            <h3>👥 Audience Poll:</h3>
            <?php foreach ($_SESSION['audience_result'] as $i => $percent): ?>
              <div style="margin:10px 0; display:flex; align-items:center;">
                <span style="width:30px; font-weight:bold;"><?= chr(65 + $i) ?>:</span>
                <div style="background:#00e6ff; color:black; padding-left:10px; height:25px; line-height:25px; border-radius:5px; margin-left:10px; font-weight:bold; width:<?= $percent ?>%;">
                  <?= $percent ?>%
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

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

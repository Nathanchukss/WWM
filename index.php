<?php 
session_start();  

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['username'])) {
    //
    $name = $_POST['username'];

    // store in session
    $_SESSION['username'] = $name;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Who Wants to Be a Millionaire</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="welcome-container">
    <h1>“Who Wants to Be a Millionaire?” </h1>
    <p style="text-align: center;">
      The game is open to anyone willing to put their knowledge to the test in a series of questions and answers.<br><br>
      Just keep your wits about you, have fun, and get rich!<br><br>
      If you’re in the mood to make a quick million dollars for yourself, this is your chance.<br><br>
      The game consists of 15 questions, with increasing difficulty and prize money as you progress—from $100 to $1,000 and beyond.<br><br>
      Answer each question correctly to grow your riches exponentially until you reach the final question.<br><br>
      Answer it right, and you’ll win a million dollars! Your intellect and knowledge are the keys to success.<br><br>
      Think you’ve got what it takes? Go ahead and play—put your random facts to good use and turn them into a million dollars.
    </p>
    <p></p>
    <h1 style="font-family: 'Trebuchet MS', Arial, sans-serif; font-size: 2.5em; color:white;">
      Welcome <?php print $_SESSION['username'] ?>
    </h1>
    <a href="game.php" class="start-button" 
    style="display: inline-block; text-align: center; 
    padding: 10px 20px; background-color: #007bff; 
    color: #fff; border-radius: 5px; text-decoration: none; 
    font-size: 18px; font-weight: bold;">Start Game</a>
  </div>
</body>
</html>
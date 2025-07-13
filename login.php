<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Who Wants to Be a Millionaire</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: radial-gradient(circle at center, #000428, #004e92);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      background-color: rgba(0, 0, 0, 0.6);
      padding: 2em 3em;
      border-radius: 20px;
      box-shadow: 0 0 20px rgba(0, 255, 255, 0.4);
      text-align: center;
      width: 90%;
      max-width: 400px;
    }

    .login-container img {
      width: 100px;
      margin-bottom: 20px;
    }

    .login-container h1 {
      font-size: 1.8em;
      margin-bottom: 20px;
      color: #f0c420;
    }

    .login-container input[type="text"],
    .login-container input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0 20px;
      border: none;
      border-radius: 10px;
      background: #001f3f;
      color: white;
      font-size: 1em;
      box-shadow: 0 0 5px rgba(0, 255, 255, 0.5);
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
  </style>
</head>
<body>

  <div class="login-container">
    <img src="assets/icons/logo1.png" alt="WWM Logo">
    <h1>Login to Play</h1>

    <form action="index.php" method="post">
      <input type="text" name="visitor_name" placeholder="Your Name" required>
      <input type="password" name="password" placeholder="Password" required>
      <br>
      <button type="submit" class="start-button">Start Game</button>
    </form>
  </div>

</body>
</html>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Generate a random arithmetic problem if not already set
if (!isset($_SESSION['captcha_question'])) {
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    $operator = rand(0, 1) ? '+' : '-';
    $_SESSION['captcha_question'] = "$num1 $operator $num2";
    $_SESSION['captcha_answer'] = $operator === '+' ? $num1 + $num2 : $num1 - $num2;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_answer = intval($_POST['captcha_answer']);
    if ($user_answer !== $_SESSION['captcha_answer']) {
        $error_message = "Incorrect answer. Please try again.";
    } else {
        unset($_SESSION['captcha_question']);
        unset($_SESSION['captcha_answer']);
        header("Location: registration_complete.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification - Job Force</title>
    <link rel="stylesheet" href="../assets/css/kp.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f2ef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h2 {
            margin-top: 0;
            color: #333;
            font-size: 1.8em;
            font-weight: bold;
        }

        p {
            color: #666;
            font-size: 1em;
            margin: 20px 0;
        }

        .btn {
            background-color: #0073b1;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            width: 100%;
            margin: 20px 0 10px;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #005582;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verification</h2>
        <p>Please solve the following problem to verify you are human:</p>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <p><?= $_SESSION['captcha_question'] ?></p>
            <input type="text" name="captcha_answer" placeholder="Your answer" required>
            <button type="submit" class="btn">Verify</button>
        </form>
    </div>
</body>
</html>

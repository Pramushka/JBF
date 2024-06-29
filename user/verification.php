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

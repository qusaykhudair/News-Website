<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'news_website';

$message = '';
$success = false;

try {
    $conn = new mysqli($host, $user, $password, $dbname);
    $conn->set_charset("utf8mb4");

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
        $email = trim($_POST['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "❌ Invalid email address.";
        } else {
            $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $message = "✅ Thank you for subscribing!";
            $success = true;
            $stmt->close();
        }
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        $message = "⚠️ You are already subscribed with this email.";
    } else {
        $message = "❌ Subscription failed. Please try again.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Subscribe to Newsletter</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #222;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .container {
            max-width: 500px;
            margin: 100px auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        a {
            text-decoration: none;
            color: white;
        }

        h2 {
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        input[type="email"] {
            width: 95%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 1rem;
            outline: none;
        }

        .dark-mode input[type="email"] {
            background-color: #2b2b2b;
            color: #fff;
            border: 1px solid #444;
        }

        button {
            width: 100%;
            background-color: #2563eb;
            color: white;
            font-size: 16px;
            padding: 12px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
            margin: 5px;
        }

        button:hover {
            background-color: #1d4ed8;
        }

        .message {
            margin-top: 1rem;
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: 500;
        }

        .message.success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .message.error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }
    </style>
</head>

<body class="<?php echo isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark' ? 'dark-mode' : ''; ?>">

    <div class="container">
        <h2>Subscribe to Our Newsletter</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter your email..." required />
            <button type="submit">Subscribe</button>

        </form>
        <button type="submit"><a href="index.php">Back To Home Page</a></button>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>
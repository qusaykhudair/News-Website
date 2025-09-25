<?php
require_once 'config/database.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            $conn = getDBConnection();

            // Check if username or email exists
            $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insert_query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("sss", $username, $email, $hashed_password);

                if ($stmt->execute()) {
                    $success_message = 'Registration successful. You can now login.';
                } else {
                    $error_message = 'Registration failed. Please try again.';
                }
            } else {
                $error_message = 'Username or email already exists.';
            }
            $conn->close();
        } else {
            $error_message = 'Passwords do not match.';
        }
    } else {
        $error_message = 'Please fill in all fields.';
    }
}

$page_title = "Register";
require_once 'includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-form">
                <h2>Create Account</h2>

                <?php if ($error_message): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <?php if ($success_message): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">Register</button>
                </form>

                <p class="auth-link">
                    Already have an account? <a href="login.php">Login here</a>
                </p>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
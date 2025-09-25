<?php
$page_title = "Contact Us";
include 'includes/header.php';

$name = $email = $subject = $message = "";
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  function cleanInput($data)
  {
    return htmlspecialchars(trim($data));
  }

  $name = cleanInput($_POST["name"]);
  $email = cleanInput($_POST["email"]);
  $subject = cleanInput($_POST["subject"]);
  $message = cleanInput($_POST["message"]);


  if (empty($name) || !preg_match("/^[a-zA-Z\s]{3,50}$/", $name)) {
    $errors[] = "Please enter a valid name (letters and spaces only, min 3 characters).";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
  }

  if (strlen($subject) < 5) {
    $errors[] = "Subject must be at least 5 characters.";
  }

  if (strlen($message) < 10) {
    $errors[] = "Message must be at least 10 characters.";
  }

  if (empty($errors)) {

    $success = "Your message has been sent successfully!";
  }
}
?>
<html>

<head>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <section class="contact-form" style="max-width: 800px; margin: auto; padding: 2rem;">
    <?php if (!empty($errors)): ?>
      <div class="alert alert-error">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="" method="post" novalidate>
      <label for="name">Full Name:</label>
      <input type="text" id="name" name="name" value="<?php echo $name; ?>" pattern="[A-Za-z\s]{3,50}"
        title="Only letters and spaces (3-50 characters)" required>

      <label for="email">Email Address:</label>
      <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>

      <label for="subject">Subject:</label>
      <input type="text" id="subject" name="subject" value="<?php echo $subject; ?>" minlength="5" required>

      <label for="message">Message:</label>
      <textarea id="message" name="message" rows="6" minlength="10" required><?php echo $message; ?></textarea>

      <button class="btn btn-primary" type="submit">Send Message</button>
    </form>
  </section>
</body>

</html>


<?php include 'includes/footer.php'; ?>
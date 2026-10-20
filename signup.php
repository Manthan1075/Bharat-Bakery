<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Create Account';
$errors = [];
$name = '';
$email = '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!empty($_SESSION['user_id'])) {
    header('Location: my-orders.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $errors['general'] = 'Your session expired. Please refresh the page and try again.';
    }
    if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
        $errors['name'] = 'Enter your name (2–100 characters).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) {
        $errors['email'] = 'Enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (!$errors) {
        try {
            $pdo = db();
            $check = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
            $check->execute([$email]);

            if ($check->fetchColumn()) {
                $errors['email'] = 'An account with this email already exists. Please log in.';
            } else {
                $insert = $pdo->prepare(
                    'INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)'
                );
                $insert->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);

                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $pdo->lastInsertId();
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                header('Location: my-orders.php');
                exit;
            }
        } catch (Throwable $ex) {
            error_log('Bharat Bakery signup error: ' . $ex->getMessage());
            $errors['general'] = 'We could not create your account right now. Please try again.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<header class="page-banner">
  <div class="container">
    <h1>Create Account</h1>
    <p>Join Bharat Bakery to keep track of your orders.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width: 560px">
    <div class="form-card">
      <h2 class="h3 mb-1">Create your account</h2>
      <p class="text-muted mb-4">Enter your details below to get started.</p>

      <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger"><?= e($errors['general']) ?></div>
      <?php endif; ?>

      <form method="post" action="signup.php" autocomplete="on">
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

        <div class="mb-3">
          <label for="name" class="form-label">Full name</label>
          <input class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                 id="name" name="name" type="text" maxlength="100"
                 value="<?= e($name) ?>" autocomplete="name" required>
          <?php if (isset($errors['name'])): ?>
            <div class="invalid-feedback"><?= e($errors['name']) ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                 id="email" name="email" type="email" maxlength="190"
                 value="<?= e($email) ?>" autocomplete="email" required>
          <?php if (isset($errors['email'])): ?>
            <div class="invalid-feedback"><?= e($errors['email']) ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                 id="password" name="password" type="password" minlength="8"
                 autocomplete="new-password" required>
          <?php if (isset($errors['password'])): ?>
            <div class="invalid-feedback"><?= e($errors['password']) ?></div>
          <?php endif; ?>
          <div class="form-text">Use at least 8 characters.</div>
        </div>

        <div class="mb-4">
          <label for="confirm_password" class="form-label">Confirm password</label>
          <input class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                 id="confirm_password" name="confirm_password" type="password"
                 minlength="8" autocomplete="new-password" required>
          <?php if (isset($errors['confirm_password'])): ?>
            <div class="invalid-feedback"><?= e($errors['confirm_password']) ?></div>
          <?php endif; ?>
        </div>

        <button class="btn btn-gold btn-lg w-100" type="submit">Create Account</button>
      </form>

      <p class="text-center mt-4 mb-0">
        Already have an account? <a href="login.php">Log in</a>
      </p>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

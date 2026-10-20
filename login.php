<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Login';
$errors = [];
$email = '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!empty($_SESSION['user_id'])) {
    header('Location: my-orders.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $errors['general'] = 'Your session expired. Please refresh the page and try again.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $errors['general'] = 'Enter a valid email address and password.';
    } else {
        try {
            $stmt = db()->prepare(
                'SELECT id, name, email, password_hash FROM users WHERE email = ? LIMIT 1'
            );
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($password, $user['password_hash'])) {
                $errors['general'] = 'Email or password is incorrect.';
            } else {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                header('Location: my-orders.php');
                exit;
            }
        } catch (Throwable $ex) {
            error_log('Bharat Bakery login error: ' . $ex->getMessage());
            $errors['general'] = 'We could not log you in right now. Please try again.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<header class="page-banner">
  <div class="container">
    <h1>Welcome Back</h1>
    <p>Log in to access your Bharat Bakery account.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width: 520px">
    <div class="form-card">
      <h2 class="h3 mb-1">Login to your account</h2>
      <p class="text-muted mb-4">Enter your email and password.</p>

      <?php if (!empty($errors['general'])): ?>
        <div class="alert alert-danger"><?= e($errors['general']) ?></div>
      <?php endif; ?>

      <form method="post" action="login.php" autocomplete="on">
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input class="form-control" id="email" name="email" type="email"
                 value="<?= e($email) ?>" autocomplete="email" required>
        </div>

        <div class="mb-4">
          <label for="password" class="form-label">Password</label>
          <input class="form-control" id="password" name="password" type="password"
                 autocomplete="current-password" required>
        </div>

        <button class="btn btn-gold btn-lg w-100" type="submit">Login</button>
      </form>

      <p class="text-center mt-4 mb-0">
        Don't have an account? <a href="signup.php">Create one</a>
      </p>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

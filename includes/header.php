
<?php
require_once __DIR__ . '/functions.php';

$current = basename($_SERVER['SCRIPT_NAME']);
$pageTitle = $pageTitle ?? 'Bharat Bakery';
$flash = get_flash();

$isLoggedIn = !empty($_SESSION['user_id']);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$nav = [
    'index.php'   => 'Home',
    'about.php'   => 'About Us',
    'products.php'=> 'Our Products',
    'contact.php' => 'Contact Us',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= e($pageTitle) ?> | Bharat Bakery</title>

    <meta name="description"
          content="Bharat Bakery - freshly baked cakes, pastries, bread, cookies and snacks.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Nunito+Sans:wght@400;600;700&display=swap"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <?php
    $logoFile = basename((string) setting('logo_file'));

    if (
        $logoFile !== '' &&
        is_file(__DIR__ . '/../assets/images/' . $logoFile)
    ):
    ?>
        <link rel="icon"
              href="assets/images/<?= e($logoFile) ?>">
    <?php endif; ?>

    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top bakery-nav">
    <div class="container">

        <a class="navbar-brand brand" href="index.php">
            <?= brand_logo() ?>
        </a>

        <button
            class="navbar-toggler border-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNav"
            aria-controls="mainNav"
            aria-expanded="false"
            aria-label="Toggle menu">

            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">

            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">

                <?php foreach ($nav as $file => $label): ?>
                    <li class="nav-item">
                        <a
                            class="nav-link <?= $current === $file ? 'active' : '' ?>"
                            href="<?= e($file) ?>">

                            <?= e($label) ?>
                        </a>
                    </li>
                <?php endforeach; ?>

                <?php if ($isLoggedIn): ?>
                    <li class="nav-item">
                        <a
                            class="nav-link <?= $current === 'my-orders.php' ? 'active' : '' ?>"
                            href="my-orders.php">
                            My Orders
                        </a>
                    </li>
                <?php endif; ?>

            </ul>

            <div class="d-flex flex-wrap align-items-center gap-2">

                <!-- Shopping Cart -->
                <a href="cart.php"
                   class="btn btn-gold position-relative">

                    🛒 Cart

                    <span class="cart-badge">
                        <?= cart_count() ?>
                    </span>
                </a>

                <?php if ($isLoggedIn): ?>

                    <!-- Customer Account -->
                    <span class="small fw-bold">
                        Hi, <?= e($_SESSION['user_name'] ?? 'Customer') ?>
                    </span>

                    <!-- Secure Logout -->
                    <form method="post"
                          action="logout.php"
                          class="m-0">

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= e($_SESSION['csrf_token']) ?>">

                        <button type="submit"
                                class="btn btn-outline-secondary">
                            Logout
                        </button>
                    </form>

                <?php else: ?>

                    <!-- Login -->
                    <a href="login.php"
                       class="btn btn-outline-secondary">
                        Login
                    </a>

                    <!-- Signup -->
                    <a href="signup.php"
                       class="btn btn-brown">
                        Sign Up
                    </a>

                <?php endif; ?>

            </div>
        </div>
    </div>
</nav>

<?php if ($flash): ?>
    <div class="container mt-3">
        <div
            class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show auto-dismiss"
            role="alert">

            <?= e($flash['message']) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>
    </div>
<?php endif; ?>

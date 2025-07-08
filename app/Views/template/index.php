<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $appName; ?></title>
    <meta name="author" content="<?= $appAuthor; ?>">
    <!-- Bootstrap 5 CSS -->
    <link href="<?= base_url(); ?>/assets/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/lib/bootstrap-icon/font/bootstrap-icons.min.css">
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/custom.css">
</head>

<body>

    <?php if (@$renderMenu): ?>
        <?= $this->include('template/admin') ?>
    <?php else: ?>
        <?= $this->renderSection('content') ?>
    <?php endif ?>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="<?= base_url(); ?>/assets/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
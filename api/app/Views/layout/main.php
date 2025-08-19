<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <?= view('layout/style') ?>
</head>

<body>
    <?= view('layout/header') ?>
    <div class="main-content">
        <?= $content ?>
        <?= view('layout/headmaster-sign') ?>
    </div>
</body>

</html>
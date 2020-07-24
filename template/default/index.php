<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $this->title ?></title>
    <link href="<?= asset('css/bootstrap.min.css') ?>" type="text/css" rel="stylesheet"/>
    <?php foreach($this->css as $css): ?>
    <link href="<?= $css ?>" type="text/css" rel="stylesheet"/>
    <?php endforeach; ?>
</head>
<body>
<?= $content; ?>
<script src="<?= asset('js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/bootstrap.min.js') ?>"></script>
<?php foreach($this->js as $js): ?>
<script src="<?= $js ?>"></script>
<?php endforeach; ?>
</body>
</html>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        echo implode(PHP_EOL, $stylesheets);
        echo implode(PHP_EOL, $scripts);
    ?>
    <title><?php echo htmlspecialchars($title); ?></title>
</head>
<body>
    <?php foreach ($components as $component) {
        echo $component->show();
    } ?>
</body>
</html>
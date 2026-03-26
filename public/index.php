<?php

$storagePath = __DIR__ . '/storage';
$storageExists = is_dir($storagePath);

if ($storageExists) {
    $files = scandir($storagePath);
    $files = array_filter($files, function ($file) use ($storagePath) {
        return !is_dir($storagePath . "/$file") && $file !== '.' && $file !== '..';
    });

    $isStorageEmpty = count($files) === 0;
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>files.yuri.rocks</title>
</head>
<body>
<?php if (!$storageExists): ?>
    <p>Storage directory does not exist.</p>
<?php elseif (!$isStorageEmpty): ?>
    <ul>
        <?php
        foreach ($files as $file) {
            echo "
                <li>
                    <a target='_blank' href=\"storage/$file\">$file</a>
                </li>
            ";
        }
        ?>
    </ul>
<?php else: ?>
    <p>The storage directory is empty.</p>
<?php endif; ?>
</body>
</html>
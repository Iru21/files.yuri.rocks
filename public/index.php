<?php

if (!is_dir(__DIR__ . '/storage')) {
    mkdir(__DIR__ . '/storage');
}
$files = scandir(__DIR__ . '/storage');
$files = array_filter($files, function ($file) {
    return !is_dir(__DIR__ . "/storage/$file") && $file !== '.' && $file !== '..';
});

$isStorageEmpty = count($files) === 0;

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<!--    <ul>-->
<!--        --><?php
//            foreach ($files as $file) {
//                echo "
//                    <li>
//                        <a target='_blank' href=\"storage/$file\">$file</a>
//                    </li>";
//            }
//            if ($isStorageEmpty) {
//                echo '<li>No files found</li>';
//            }
//        ?>
<!--    </ul>-->
</body>
</html>
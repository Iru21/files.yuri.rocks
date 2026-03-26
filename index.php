<?php

$storagePath = __DIR__ . '/storage';
$storageExists = is_dir($storagePath);

$isStorageEmpty = false;
$files = [];

if ($storageExists) {
    $files = scandir($storagePath);
    $files = array_filter($files, function ($file) use ($storagePath) {
        return !is_dir($storagePath . "/$file") && $file !== '.' && $file !== '..';
    });
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $files = array_filter($files, function ($file) use ($search) {
            return str_contains($file, $search);
        });
    }
    $files = array_map(function ($file) use ($storagePath) {
        return [
            'name' => $file,
            'created_at' => filectime($storagePath . "/$file"),
            'type' => (function () use ($storagePath, $file) {
                $mimeType = mime_content_type($storagePath . "/$file");
                if (str_starts_with($mimeType, 'image/')) {
                    return 'image';
                } elseif (str_starts_with($mimeType, 'video/')) {
                    return 'video';
                } elseif (str_starts_with($mimeType, 'audio/')) {
                    return 'audio';
                } elseif (str_ends_with($file, '.zip') || str_ends_with($file, '.rar') || str_ends_with($file, '.7z') || str_ends_with($file, '.tar') || str_ends_with($file, '.gz')) {
                    return 'archive';
                } else {
                    return 'other';
                }
            })(),
        ];
    }, $files);
    usort($files, function ($a, $b) {
        return $b['created_at'] - $a['created_at'];
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

    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div id="chito-and-yuuri">
        <img src="images/chito-and-yuuri.png" alt="chito and yuuri">
    </div>
    <div id="wrapper">
        <a id="back" href="https://yuri.rocks">
            <img src="images/35j-back.gif" alt="go back" />
        </a>
        <header>
            <h1>Files</h1>
            <?php if ($storageExists && !$isStorageEmpty): ?>
                <form>
                    <label>
                        Search
                        <input type="text" name="search" placeholder="..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
                    </label>
                    <button type="submit">Submit</button>
                    <?php if (isset($_GET['search'])): ?>
                        <a href="?">Clear</a>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </header>

        <?php if (!$storageExists): ?>
            <p>Storage directory does not exist.</p>
        <?php elseif ($isStorageEmpty): ?>
            <?php if (isset($_GET['search'])): ?>
                <p>No files were found.</p>
            <?php else: ?>
                <p>The storage directory is empty.</p>
            <?php endif; ?>
        <?php else: ?>
            <div id="files">
                <?php foreach ($files as $file): ?>
                    <a class="file" href="storage/<?php echo urlencode($file['name']); ?>" target="_blank" title="<?php echo htmlspecialchars($file['name']); ?>">
                        <?php if (time() - $file['created_at'] < 24 * 60 * 60): ?>
                            <img class="new" src="images/eb06-icon-new.gif" alt="new" />
                        <?php endif; ?>
                        <?php if ($file['type'] == 'image'): ?>
                            <img class="icon" src="images/ja15-icon-camera.gif" alt="image" />
                        <?php elseif ($file['type'] == 'video'): ?>
                            <img class="icon" src="images/e03-icon-television.gif" alt="video" />
                        <?php elseif ($file['type'] == 'audio'): ?>
                            <img class="icon" src="images/bd08-icon-piano.gif" alt="audio" />
                        <?php elseif ($file['type'] == 'archive'): ?>
                            <img class="icon" src="images/ff12-icon-present.gif" alt="zip" />
                        <?php else: ?>
                            <img class="icon" src="images/dc04-icon-notepad.gif" alt="file" />
                        <?php endif; ?>
                        <span class="filename"><?php echo htmlspecialchars($file['name']); ?></span>
                        <span class="date"><?php echo date('Y-m-d H:i:s', $file['created_at']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <footer>
            <a href="https://github.com/Iru21/files.yuri.rocks">Source code</a>
            <a href="https://foollovers.com/">Icons</a>
            <span>
                Current year for some reason: <?php echo date('Y'); ?>
            </span>
        </footer>
    </div>
</body>
</html>
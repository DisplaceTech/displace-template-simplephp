<?php
/**
 * Simple Directory Listing
 *
 * A clean, modern directory listing script for serving files.
 * Replace this with your own PHP application.
 */

// Configuration
$title = 'File Repository';
$showHidden = false;
$excludeFiles = ['index.php', '.htaccess', '.git', '.gitignore'];

// Get current directory
$dir = __DIR__;

// Get file list
$files = [];
if ($handle = opendir($dir)) {
    while (false !== ($entry = readdir($handle))) {
        // Skip special directories and excluded files
        if ($entry === '.' || $entry === '..') continue;
        if (!$showHidden && $entry[0] === '.') continue;
        if (in_array($entry, $excludeFiles)) continue;

        $path = $dir . '/' . $entry;
        $files[] = [
            'name' => $entry,
            'size' => is_file($path) ? filesize($path) : null,
            'modified' => filemtime($path),
            'is_dir' => is_dir($path)
        ];
    }
    closedir($handle);
}

// Sort: directories first, then by name
usort($files, function($a, $b) {
    if ($a['is_dir'] !== $b['is_dir']) {
        return $b['is_dir'] - $a['is_dir'];
    }
    return strcasecmp($a['name'], $b['name']);
});

// Format file size
function formatSize($bytes) {
    if ($bytes === null) return '-';
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 1) . ' ' . $units[$i];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
            background: #f5f5f5;
        }
        h1 {
            margin-bottom: 1.5rem;
            color: #1a1a1a;
            font-weight: 600;
        }
        .file-list {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .file-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #eee;
            text-decoration: none;
            color: inherit;
            transition: background 0.15s;
        }
        .file-item:hover { background: #f9f9f9; }
        .file-item:last-child { border-bottom: none; }
        .file-icon {
            width: 24px;
            margin-right: 0.75rem;
            text-align: center;
            color: #666;
        }
        .file-name {
            flex: 1;
            font-weight: 500;
            color: #0066cc;
        }
        .file-item:hover .file-name { text-decoration: underline; }
        .file-meta {
            font-size: 0.875rem;
            color: #888;
            text-align: right;
        }
        .file-size { min-width: 80px; }
        .file-date { min-width: 140px; margin-left: 1rem; }
        .empty {
            padding: 2rem;
            text-align: center;
            color: #888;
        }
        @media (max-width: 600px) {
            .file-date { display: none; }
        }
    </style>
</head>
<body>
    <h1><?= htmlspecialchars($title) ?></h1>

    <div class="file-list">
        <?php if (empty($files)): ?>
            <div class="empty">No files found</div>
        <?php else: ?>
            <?php foreach ($files as $file): ?>
                <a href="<?= htmlspecialchars($file['name']) ?><?= $file['is_dir'] ? '/' : '' ?>" class="file-item">
                    <span class="file-icon"><?= $file['is_dir'] ? '📁' : '📄' ?></span>
                    <span class="file-name"><?= htmlspecialchars($file['name']) ?></span>
                    <span class="file-meta file-size"><?= formatSize($file['size']) ?></span>
                    <span class="file-meta file-date"><?= date('Y-m-d H:i', $file['modified']) ?></span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
/**
 * Storage proxy for shared hosting where php symlink() and exec() are disabled.
 * Apache rewrites /storage/* to this file when no real symlink exists.
 * If a real symlink is present, Apache serves the file directly (this file
 * is never called in that case).
 */

// Sanitise the requested path — prevent directory traversal
$raw  = $_GET['file'] ?? '';
$safe = ltrim(str_replace(['..', "\0"], '', $raw), '/\\');

if ($safe === '') {
    http_response_code(400);
    exit('Bad request');
}

$base = dirname(__DIR__) . '/storage/app/public/';
$full = realpath($base . $safe);

// Confirm the resolved path is still inside storage/app/public/
if ($full === false || strpos($full, realpath($base)) !== 0 || is_dir($full)) {
    http_response_code(404);
    exit('Not found');
}

// Serve the file with appropriate headers
$mime = mime_content_type($full) ?: 'application/octet-stream';
$size = filesize($full);
$mtime = filemtime($full);

header('Content-Type: '  . $mime);
header('Content-Length: ' . $size);
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
header('Cache-Control: public, max-age=86400');
header('X-Storage-Proxy: 1');

readfile($full);
exit;

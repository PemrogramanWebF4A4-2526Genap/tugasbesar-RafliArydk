<?php
// --- FILE UPLOAD HELPERS ---

// Handles uploading image file and validation
function upload_image_file($file, $uploadDir, $allowedMimes = ['image/jpeg', 'image/png'], $maxBytes = 2097152) {
    if (!isset($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    if (($file['size'] ?? 0) <= 0 || $file['size'] > $maxBytes) {
        return null;
    }

    $tmpName = $file['tmp_name'] ?? '';
    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        return null;
    }

    $mime = mime_content_type($tmpName);
    if (!in_array($mime, $allowedMimes, true)) {
        return null;
    }

    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    ];
    $ext = $extensions[$mime] ?? null;
    if ($ext === null) {
        return null;
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $fileName = uniqid('', true) . '.' . $ext;
    return move_uploaded_file($tmpName, rtrim($uploadDir, '/\\') . DIRECTORY_SEPARATOR . $fileName)
        ? $fileName
        : null;
}

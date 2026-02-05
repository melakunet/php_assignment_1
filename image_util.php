<?php

define('IMAGE_UPLOAD_DIR', 'images/');
define('ALLOWED_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024);

function process_image($file) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'filename' => '', 'error' => 'No file uploaded'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'filename' => '', 'error' => 'Upload error occurred'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'filename' => '', 'error' => 'File is too large (max 5MB)'];
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, ALLOWED_TYPES)) {
        return ['success' => false, 'filename' => '', 'error' => 'Invalid file type. Only JPG, PNG, and GIF allowed'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = uniqid('worker_') . '_' . time() . '.' . $extension;
    
    if (!file_exists(IMAGE_UPLOAD_DIR)) {
        mkdir(IMAGE_UPLOAD_DIR, 0755, true);
    }
    
    $destination = IMAGE_UPLOAD_DIR . $new_filename;
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $new_filename, 'error' => ''];
    } else {
        return ['success' => false, 'filename' => '', 'error' => 'Failed to save file'];
    }
}

function delete_image($filename) {
    if (empty($filename)) {
        return true;
    }
    
    $filepath = IMAGE_UPLOAD_DIR . $filename;
    
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    
    return true;
}

function get_image_path($filename) {
    if (empty($filename)) {
        return IMAGE_UPLOAD_DIR . 'placeholder.png';
    }
    
    $filepath = IMAGE_UPLOAD_DIR . $filename;
    
    if (file_exists($filepath)) {
        return $filepath;
    }
    
    return IMAGE_UPLOAD_DIR . 'placeholder.png';
}

function image_exists($filename) {
    if (empty($filename)) {
        return false;
    }
    
    return file_exists(IMAGE_UPLOAD_DIR . $filename);
}

?>

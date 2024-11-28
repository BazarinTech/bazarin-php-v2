<?php 
class FileHelper {
    public static function upload($file, $destination) {
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $destination;
        }
        throw new \Exception('File upload failed.');
    }
}
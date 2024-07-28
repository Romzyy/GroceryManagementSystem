<?php
class FileHandler {
    public function uploadFile($file, $upload_dir) {
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_new_name = uniqid('', true) . '.' . $file_ext;
        $file_destination = $upload_dir . $file_new_name;

        if (move_uploaded_file($file_tmp, $file_destination)) {
            return $file_destination;
        } else {
            return false;
        }
    }
}
?>

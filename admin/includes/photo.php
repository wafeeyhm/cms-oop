<?php

class Photo extends Db_object {

    protected static $db_table = "photos";
    protected static $db_table_fields = ['photo_id', 'title', 'description', 'filename', 'type', 'size'];
    public $photo_id;
    public $title;
    public $description;
    public $filename;
    public $type;
    public $size;

    public $tmp_path;
    public $upload_directory = "images";
    public $errors = [];
    public $upload_errors_array = [
        UPLOAD_ERR_OK => "No error, the file uploaded successfully.",
        UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
        UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
        UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded.",
        UPLOAD_ERR_NO_FILE => "No file was uploaded.",
        UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder.",
        UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
        UPLOAD_ERR_EXTENSION => "File upload stopped by a PHP extension."
    ];

    /**
     * Set file properties
     */
    public function set_files($file) {
        if (!$file || empty($file) || !is_array($file)) {
            $this->errors[] = "No file uploaded.";
            return false;
        } elseif ($file['error'] != 0) {
            $this->errors[] = $this->upload_errors_array[$file['error']];
            return false;
        } else {
            $this->filename = basename($file['name']);
            $this->tmp_path = $file['tmp_name'];
            $this->type = $file['type'];
            $this->size = $file['size'];
        }
    }

    /**
     * Save the photo
     */
    public function save() {
        if ($this->photo_id) {
            return $this->update();
        } else {
            if (!empty($this->errors)) {
                return false;
            }

            if (empty($this->filename) || empty($this->tmp_path)) {
                $this->errors[] = "The file was not available.";
                return false;
            }

            $target_path = SITE_ROOT . DS . 'admin' . DS . $this->upload_directory . DS . $this->filename;

            // Check if the file already exists
            if (file_exists($target_path)) {
                $this->errors[] = "The file {$this->filename} already exists.";
                return false;
            }

            // Move the file
            if (move_uploaded_file($this->tmp_path, $target_path)) {
                if ($this->create()) {
                    unset($this->tmp_path); // clear temp path after upload
                    return true;
                }
            } else {
                $this->errors[] = "The file directory does not have the required permissions.";
                return false;
            }
        }
    }

    /**
     * Delete the photo file from the directory
     */
    public function delete_photo() {
        if ($this->delete()) {
            $target_path = SITE_ROOT . DS . 'admin' . DS . $this->upload_directory . DS . $this->filename;
            return unlink($target_path) ? true : false;
        } else {
            return false;
        }
    }

    /**
     * Display the path of the photo
     */
    public function picture_path() {
        return $this->upload_directory . DS . $this->filename;
    }

    /**
     * Return a short description or caption of the photo
     */
    public function get_short_description($length = 30) {
        if (strlen($this->description) > $length) {
            return substr($this->description, 0, $length) . "...";
        } else {
            return $this->description;
        }
    }
}

?>
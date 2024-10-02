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
    public $errors = array();
    public $upload_errors_array = array(
        UPLOAD_ERR_OK => "No error, the file uploaded with success.",
        UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
        UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
        UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded.",
        UPLOAD_ERR_NO_FILE => "No file was uploaded.",
        UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder.",
        UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
        UPLOAD_ERR_EXTENSION => "File upload stopped by a PHP extension."
    );

    // Setting files for upload
    public function set_files($file) {
        if (empty($file) || !$file || !is_array($file)) {
            $this->errors[] = "There was no file uploaded here.";
            return false;
        } elseif ($file['error'] != 0) {
            $this->errors[] = $this->upload_errors_array[$file['error']];
            return false;
        } else {
            $this->filename = basename($file['name']);
            $this->tmp_path = $file['tmp_name'];
            $this->type = $file['type'];
            $this->size = $file['size'];
            return true;
        }
    }

    // Saving file and database entry
    public function save() {
        if ($this->photo_id) {
            // Update existing record
            return $this->update();
        } else {
            // Debug: Check for errors before continuing
            if (!empty($this->errors)) {
                echo "Errors found before saving: " . print_r($this->errors, true) . "<br>"; // Debug output
                return false;
            }

            if (empty($this->filename) || empty($this->tmp_path)) {
                $this->errors[] = "The file was not available.";
                return false;
            }

            // Set the target path for the file
            $target_path = SITE_ROOT . DS . 'admin' . DS . $this->upload_directory . DS . $this->filename;

            // Debug: Output the target path for verification
            echo "Target path: {$target_path}<br>";

            // Check if the file already exists
            if (file_exists($target_path)) {
                $this->errors[] = "The file {$this->filename} already exists.";
                return false;
            }

            // Attempt to move the uploaded file
            if (move_uploaded_file($this->tmp_path, $target_path)) {
                echo "File successfully moved to {$target_path}<br>"; // Debug output

                if ($this->create()) {
                    // If successfully created in the database
                    unset($this->tmp_path);
                    return true;
                } else {
                    // Debug: Database save issue
                    echo "Failed to save in database.<br>";
                }
            } else {
                $this->errors[] = "The file upload failed (unable to move file).";
                return false;
            }
        }
    }
}


?>
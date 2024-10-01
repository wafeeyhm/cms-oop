<?php

class Photo extends Db_object{

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

    public function set_files($file){

        if (empty($file) || !$file || !is_Array($file)) {
            # code...
            $this->errors[] = "There was no file uplaoded here";
            return false;
        }
        else if($file['error'] != 0){

            $this->errors[] = $this->upload_errors_array[$file['error']];
            return false;

        }
        else{

            $this->filename = basename($file['name']);
            $this->tmp_path = $file['tmp_name'];
            $this->type = $file['type'];
            $this->size = $file['size'];

        }

    }

    public function save(){

        if ($this->photo_id) {
            # code...
            $this->update();
        }
        else{

            if (!empty($this->errors)) {
                # code...
                return false;

            }
            
            if (empty($this->filename) || empty($this->tmp_path)) {
                # code...
                $this->errors[] = "the file was not available";
                return false;

            }

            $target_path = SITE_ROOT . DS . 'admin' . DS . $this->upload_directory . DS . $this->filename;

            $this->create();
        }

    }

}

?>
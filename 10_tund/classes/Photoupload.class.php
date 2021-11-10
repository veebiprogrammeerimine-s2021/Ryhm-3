<?php
    class Photoupload {
        private $photo_to_upload;
        private $file_type;//esialgu saadame, hiljem teeb klass selle väärtuse ise kindlaks
        private $my_temp_image;
        private $my_new_temp_image;
        
        function __construct($photo, $file_type){
            $this->photo_to_upload = $photo;
            $this->file_type = $file_type;
            $this->my_temp_image = $this->create_image_from_file($this->photo_to_upload["tmp_name"], $this->file_type);
        }
        
        private function create_image_from_file($photo, $file_type){
            //teen graafikaobjekti, image objekti
            if($file_type == "jpg"){
                $my_temp_image = imagecreatefromjpeg($photo);
            }
            if($file_type == "png"){
                $my_temp_image = imagecreatefrompng($photo);
            }
            if($file_type == "gif"){
                $my_temp_image = imagecreatefromgif($photo);
            }
            return $my_temp_image;
        }
        
    }//class lõppeb
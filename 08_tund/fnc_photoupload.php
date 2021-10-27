<?php

    function save_image($image, $file_type, $target){
        $notice = null;
        
        if($file_type == "jpg"){
            if(imagejpeg($image, $target, 90)){
                $notice = "Foto salvestamine õnnestus!";
            } else {
                $notice = "Foto salvestamine ei õnnestunud!";
            }
        }
        
        if($file_type == "png"){
            if(imagepng($image, $target, 6)){
                $notice = "Foto salvestamine õnnestus!";
            } else {
                $notice = "Foto salvestamine ei õnnestunud!";
            }
        }
        
        if($file_type == "gif"){
            if(imagegif($image, $target)){
                $notice = "Foto salvestamine õnnestus!";
            } else {
                $notice = "Foto salvestamine ei õnnestunud!";
            }
        }
        
        return $notice;
    }
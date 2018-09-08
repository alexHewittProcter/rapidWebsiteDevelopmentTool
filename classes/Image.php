<?php
    /**
     * Image is a subclass of element.
     * Able to be used with any website class
     */
    class Image extends Element 
    {
        /**
         * Holds the value of the database that the image information is stored in
         * 
         * @var String
         */
        public $databaseValue;

        /**
         * Holds the value of the database column that holds the string value
         * 
         * @var String
         */
        public $databaseColumnValue;

        /**
         * Holds the value if the images are multiple
         * 
         * @var Boolean
         */
        public $imageMultiple;

        /**
         * Holds the value of the primary id of the database table
         * 
         * @var String
         */
        public $primaryId;

        /**
         * Holds the value of the foreign key that references another table
         * 
         * @var String
         */
        public $foreignId;

        /**
         * Sets the information for the image database
         * 
         * @param String $database The database table and foldername
         * 
         * @param String $column The value of the file name column in the database
         * 
         * @param Boolean $multiple The value if the amount of images are multiple
         * 
         * @param String $primaryId The value of the primary id of the database
         * 
         * @param String $foreignId The value of the foreign key that references the another table in the database
         * 
         * @return Null
         */
        public function setImageDatabase($database,$column,$multiple,$primaryId,$foreignId) {
            $this->databaseValue = $database;
            $this->databaseColumnValue = $column;
            $this->imageMultiple = $multiple;
            $this->primaryId = $primaryId;
            $this->foreignId = $foreignId;
            /*if($this->imageMultiple) {
                $this->setExternalQueryMethod(uploadImages);
            } else {
                $this->setExternalQueryMethod(uploadImage);
            }*/
        }

        /**
         * Uploading image method
         * 
         * @param DBC $dbc
         * 
         * @param Image $imageElement
         * 
         * @param Array[Element] $inputs
         * 
         * @return Null
         */
        public function uploadImage($dbc,$imageElement,$inputs) {
            if(isset($inputs[$imageElement->getName()])) {
                if(!empty($_FILES[$imageElement->getName()])) {
                    $targetDir = $this->databaseValue;
                    $uploadOk = 1;
                    $targetFile = $targetDir . basename($_FILES[$imageElement->getName()]);
                    $fileExtension = pathinfo($targetFile,PATHINFO_EXTENSION);
                    if($fileExtension != "jpg" && $fileExtension != "png" && $fileExtension != "jpeg" && $fileExtension != "gif" ) {
                        $uploadOk = 0;
                        echo "IMAGE IS NOT FILE";
                    }
                    //Move file
                    if($uploadOk == 1) {
                        $query = "INSERT INTO " . $this->databaseValue . "(" . $this->foreignId . ") values (" . $inputs[$this->foreignId] . ")";
                        echo $query;
                        $result = mysqli_query($dbc,$query) or die("Failed to query database".$query);
                        $primaryIdValue = mysqli_insert_id($dbc);
                        $photoName = $primaryIdValue . "." . $fileExtension;
                        $targetFile = $targetDir . $photoName;
                        if(move_uploaded_file($_FILES[$imageElement->getName()]['tmp_name'],$targetFile)) {
                            $query = "UPDATE ".$this->databaseValue." SET ".$this->databaseColumnValue."='$photoName' WHERE ".$this->foreignId."='" .$inputs[$this->foreignId]. "' AND ".$this->primaryId."='$primaryIdValue'";
                            echo $query;
                            $result = mysqli_query($dbc,$query) or die("Failed to query database");
                        } else {
                            if(is_uploaded_file($_FILES['usrPhoto']['tmp_name'])) {
                                $smallErrors['Photo'] = 'Unfortunately your photo did not upload.';
                            }
                        }
                    }
                }
            }
        }
        /**
         * Uploading multiple images method
         * 
         * @param DBC $dbc
         * 
         * @param Image $imageElement
         * 
         * @param Array[Element] $inputs
         * 
         * @return Null
         */
        public function uploadImages($dbc,$imageElement,$inputs) {
            echo "Works";
            if(isset($inputs[$imageElement->getName()])) {
                if(!empty($_FILES[$imageElement->getName()])) {
                    $targetDir = $this->databaseValue;
                    $uploadOk = 1;
                    $targetFile = $targetDir . basename($_FILES[$imageElement->getName()]);
                    $fileExtension = pathinfo($targetFile,PATHINFO_EXTENSION);
                    if($fileExtension != "jpg" && $fileExtension != "png" && $fileExtension != "jpeg" && $fileExtension != "gif" ) {
                        $uploadOk = 0;
                    }
                    //Move file
                    if($uploadOk == 1) {
                        $query = "INSERT INTO " . $this->databaseValue . "(" . $this->foreignId . ") values (" . $inputs[$this->foreignId] . ")";
                        $result = mysqli_query($dbc,$query) or die("Failed to query database".$query);
                        $primaryIdValue = mysqli_insert_id($dbc);
                        $photoName = $primaryIdValue . "." . $fileExtension;
                        $targetFile = $targetDir . $photoName;
                        if(move_uploaded_file($_FILES[$imageElement->getName()]['tmp_name'],$targetFile)) {
                            $query = "UPDATE ".$this->databaseValue." SET ".$this->databaseColumnValue."='$photoName' WHERE ".$this->foreignId."='" .$inputs[$this->foreignId]. "' AND ".$this->primaryId."='$primaryIdValue'";
                            $result = mysqli_query($dbc,$query) or die("Failed to query database");
                        } else {
                            if(is_uploaded_file($_FILES['usrPhoto']['tmp_name'])) {
                                $smallErrors['Photo'] = 'Unfortunately your photo did not upload.';
                            }
                        }
                    }
                }
            }
        }
    }
?>
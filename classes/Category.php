<?php
    /**
     * Category class is a subclass of the webgroup
     * 
     * It is specific to the listingWebsite class usage
     */
    class Category extends WebGroup {

        /**
         * The name of the table the category corresponds to
         * 
         * @var String
         */
        private $tableName;

        /**
         * Holds an array of all the elements in the category
         * 
         * @var Array[Element]
         */
        private $elements = array();

        /**
         * Holds an array of all the sub categories to the category
         * 
         * @var Array[Category]
         */
        private $subCatergoriesArray = array();

        /**
         * Holds an array of listing types for the category
         * 
         * @var Array[ListingType]
         */
        private $listingTypes = array();

        /**
         * Holds a boolean value that determines if subcategories are used
         * 
         * @var Boolean
         */
        public $subCategories = false;

        /**
         * Constructor
         * 
         * Sets the type,name and tablename
         * 
         * @param String $name of the category
         * 
         * @param Int $type of the category
         * 
         * @param String $tableName of the category
         * 
         * @return null
         */
        public function __CONSTRUCT($name,$type,$tableName) {
            $this->type = $type;
            $this->name = $name;
            $this->tableName = $tableName;
        }

        /**
         * Gets all the elements for this category
         * 
         * @return Array[Element]
         */
        public function getElements() {
            return $this->elements;
        }

        /**
         * Adds element to category
         * 
         * @param Element $element to be added
         *
         * @return Null
         */
        public function addElement($element) {
            $this->elements[] = $element;
        }

        /**
         * Adds a listing type object to the listingTypes array
         * 
         * @param Listing $listingType
         * 
         * @return null
         */
        public function addListingType($listingType) {
            $this->listingTypes[] = $listingType;
        }

        /**
         * Gets the array of listing types
         * 
         * @return Array[ListingType]
         */
        public function getListingTypes() {
            return $this->listingTypes;
        }

        /**
         * Gets the table name
         * 
         * @return String
         */
        public function getTableName() {
            return $this->tableName;
        }
    }
?>
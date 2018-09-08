<?php
    /**
     * The ListingType class represents a type for a listing
     * 
     * Used exclusively with the ListingWebsite class
     */
    class ListingType extends WebGroup {

        /**
         * Holds elements for the ListingType
         * 
         * @var Array[Element]
         */
        private $elements = array();

        /**
         * Constructor
         * 
         * Sets the type and name, also adds the listing type element to the elements list
         * 
         * @param Int $type the type of the listing type
         * 
         * @param String $name the name of the listing type
         * 
         * @return null
         */
        public function __CONSTRUCT($type,$name) {
            $this->type = $type;
            $this->name = $name;
            $typeElement = new Element("input","text","listingType","listingType");
            $typeElement->setValue($this->type);
            $this->addRegisterElement($typeElement);
        }

        /**
         * Adds an element to the elements array
         * 
         * @param Element $element to be added
         * 
         * @return null
         */
        public function addElement($element) {
            $this->elements[] = $element;
        }

        /**
         * Gets all the elements from the element array
         * 
         * @return Array[Element]
         */
        public function getElements() {
            return $this->elements;
        }
    }
?>
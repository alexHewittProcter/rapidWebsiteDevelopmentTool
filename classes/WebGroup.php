<?php

    /**
     * WebGroup Class
     * 
     * This is used as an interface class with properties such as type and name to reduce duplicate code
     */
    class WebGroup extends Functions
    {
        /**
         * Holds the value of type for the webgroup
         * 
         * @var Int
         */
        protected $type;

        /**
         * Holds the value of the name for the webgroup
         * 
         * @var String
         */
        protected $name;

        /**
         * Holds an array of elements that represents elements that the webgroup needs when registering
         * 
         * @var Array[Element]
         */
        protected $registerElements = array();

        /**
         * Returns the $type value
         * 
         * @return Int $type value
         */
        public function getType() {
            return $this->type;
        }

        /**
         * Returns the value of the $name
         * 
         * @return String $name value
         */
        public function getName() {
            return $this->name;
        }

        //Register variables

        /**
         * Adds an element to the registerElements array
         * 
         * @param Element $element the element to be added
         * 
         * @return null
         */
        public function addRegisterElement($element) {
            $this->registerElements[] = $element;
        }

        /**
         * Returns the register elements
         * 
         * @return Array[Element]
         */
        public function getRegisterElements() {
            return $this->registerElements;
        }
    }
?>
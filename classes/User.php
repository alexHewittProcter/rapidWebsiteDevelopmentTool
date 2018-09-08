<?php
/**
 * The user elements is built to show a certain type of user
 * 
 * Can be used with the superclass Website and any subclasses
 */
class User extends WebGroup
{
    /**
     * Holds the elements corresponding to the name of the user
     * 
     * @var Array[Element]
     */
    private $nameFormElements = array();

    /**
     * Holds the elements corresponding to the contact of the user
     * 
     * @var Array[Element]
     */
    private $contactFormElements = array();

    /**
     * Holds elements that are used to update the users information
     * 
     * @var Array[Element]
     */
    private $updateElements = array();

    /**
     * Holds image elements for the user
     * 
     * @var Array[Image]
     */
    private $images = array();

    /**
     * Holds the links and link names for after signup
     * 
     * @var Array[String]
     */
    private $passSignUpLinks = array();

    /**
     * Constructor
     * 
     * Sets teh type, name and adds a register element
     * 
     * @param Int $type
     * 
     * @param String $name
     * 
     * @return Null
     */
    public function __construct($type,$name) {
        $this->type = $type;
        $typeElement = new Element("input","text","type","userType");
        $typeElement->setValue($dbc,$this->type);
        $this->addRegisterElement($typeElement);
        $this->name = $name;
    }
    
    /**
     * Adds a element to the name element array
     * 
     * @param Element $element
     * 
     * @return Null
     */
    public function addNameElement($element) {
        array_push($this->nameFormElements,$element);
    }

    /**
     * Returns all the name form elements
     * 
     * @return Array[Element] $nameFormElements
     */
    public function getNameElements() {
        return $this->nameFormElements;
    }

    /**
     * Adds an element to the contact form elements
     * 
     * @param Element $element
     * 
     * @return Null
     */
    public function addContactElement($element) {
        array_push($this->contactFormElements,$element);
    }

    /**
     * Gets all the name and contact form elements
     * 
     * @return Array[Element]
     */
    public function getFormElements() {
        $formArray = array();
        foreach($this->nameFormElements as $value) {
            array_push($formArray,$value);
        }
        foreach($this->contactFormElements as $value) {
            array_push($formArray,$value);
        }
        return $formArray;
    }

    //Update variables
    /**
     * Adds an element to the update elements array
     * 
     * @param Element $element
     * 
     * @return Null
     */
    public function addUpdateElement($element) {
        $this->updateElements[] = $element;
    }

    //Images
    /**
     * Adds an image to the images array
     * 
     * @param Image $image
     * 
     * @return Null
     */
    public function addUserImage($image) {
        $this->images[] = $image;
    }

    /**
     * Returns all the image array elements
     * 
     * @return Array[Image]
     */
    public function getUserImages() {
        return $this->images;
    }

    /**
     * Adds a pass signup link to the array
     * 
     * @param String $url
     * 
     * @param String $name
     * 
     * @return Null
     */
    public function addPassSignUp($url,$name) {
        $this->passSignUpLinks[] = ["url"=>$url,"name"=>$name];
    }

    /**
     * Returns the passsignup array
     * 
     * @return Array[String]
     */
    public function getPassSignUp() {
        return $this->passSignUpLinks;
    }
}
?>
<?php
class ListingWebsite extends Website
{
    //Standard variables
    /**
     * Holds the value if listing on the website charge fees
     * 
     * @var Boolean
     */
    private $listingFeeStructure = false;

    /**
     * Holds the value if the website is a listing website
     * 
     * @var Boolean
     */
    private $listingWebsite = true;
    //Arrays

    /**
     * Holds the values of all the listing categories
     * 
     * @var Array[Category]
     */
    private $listingCategories = array();

    /**
     * Holds the values of all the default sub categories of listings categories
     * 
     * @var Array[Category]
     */
    private $defaultSubCategories = array();

    /**
     * Holds the values of all the listing types
     * 
     * @var Array[ListingType]
     */
    private $listingTypes = array();

    /**
     * Holds the elements that are default to any category
     * 
     * @var Array[Element]
     */
    private $defaultCategoryElements = array();

    /**
     * Holds the elements that are for the register of categories
     * 
     * @var Array[Element]
     */
    private $categoryRegisterElements = array();

    /**
     * Holds elements that are default file elements
     * 
     * @var Array[Element]
     */
    private $defaultFileElements = array();

    /**
     * Adds a listing type to the listing type array
     * 
     * @param ListingType $listingType
     * 
     * @return Null
     */
    public function addListingType($listingType) {
        $this->listingTypes[] = $listingType;
    }

    /**
     * Adds a category to the listing category
     * 
     * @param Category $category
     * 
     * @return Null
     */
    public function addCategory($category) {
        $this->listingCategories[] = $category;
    }

    /**
     * Gets elements for a category
     * 
     * @param Category $category
     * 
     * @return Array[Element]
     */
    public function getCategoryFormElements($category) {
        //TYPE
        //DEFAULT ELEMENTS
        //Category ELEMENTS
        
    }

    /**
     * Prints a custom page
     * 
     * @param 'New Listing'
     * 
     * @return Null
     */
    public function printCustomListingPage($page) {
        $this->setCurrentPageTitle($page);
        $this->createConnection();
        $this->init();
        //Pre header
        switch($page) {
            case "New listing" : 
                    if(isset($_GET['type'])) {
                        $category = $this->getByType($this->listingCategories,$_GET['type']);
                    }
                    if(isset($_POST['submit'])) {
                        $categoryElements = $category->getElements();
                        $categoryElements = array_merge($categoryElements,$category->getRegisterElements());
                        $formElements = $this->defaultCategoryElements;
                        $formElements = array_merge($formElements,$this->categoryRegisterElements);
                        if(isset($_GET['listingType'])) {
                            $listingCategory = $this->getByType($this->listingTypes,$_GET['listingType']);
                            $formElements = array_merge($formElements,$listingCategory->getElements());
                            $formElements = array_merge($formElements,$listingCategory->getRegisterElements());
                        }
                        $connectedFormElements = $this->connectFormVariables($categoryElements,$_POST);
                        //var_dump($connectedFormElements);
                        $connectedListingElements = $this->connectFormVariables($formElements,$_POST);
                        //var_dump($connectedListingElements);
                        $query = $this->createQuery("listings","insert",$connectedListingElements);
                        echo $query;
                        $query2 = $this->createQuery($category->getTableName(),"insert",$connectedFormElements);

                    }
                break;
        }
        //Post header
        $this->printHeader();
        echo "<div class='container'>";
        switch($page) {
            case "New listing" : 
                if(isset($_GET['type'])) {
                    if((count($category->getListingTypes()) > 1) && !isset($_GET['listingType'])) {
                        $listingTypes = [];
                        foreach($category->getListingTypes() as $categoryListingTypes) {
                            $listingTypes[] = $this->getByType($this->listingTypes,$categoryListingTypes);
                        }
                        $this->printPageHeader("New listing - " . $category->getName());
                        $this->printMenu($listingTypes,"getType","listingType","getName");
                    } else {
                        $this->printPageHeader("New listing - " . $category->getName());
                        $elements = $category->getElements();
                        $hidden = $category->getRegisterElements();
                        $elements = array_merge($elements,$this->defaultCategoryElements);
                        $hidden = array_merge($hidden,$this->categoryRegisterElements);
                        if(isset($_GET['listingType'])) {
                            $listingCategory = $this->getByType($this->listingTypes,$_GET['listingType']);
                            $elements = array_merge($elements,$listingCategory->getElements());
                            $hidden = array_merge($hidden,$listingCategory->getRegisterElements());
                        }
                        //Set values for $hidden array
                        foreach($hidden as $value) {
                            if(isset($_GET[$value->getName()])) {
                                $value->setValue($this->dbc,$_GET[$value->getName()]);
                            }
                        }
                        $this->printForm($elements,$_POST,"POST",$hidden);
                    }
                } else {
                    $this->printPageHeader("New listing");
                    $this->printMenu($this->listingCategories,"getType","type","getName");
                }
                break;
        }
        echo "</div>";
        $this->printFooter();
    }

    /**
     * Adds a default category element
     * 
     * @param Element $element
     * 
     * @return Null
     */
    public function addDefaultCategoryElement($element) {
        $this->defaultCategoryElements[] = $element;
    }

    /**
     * Gets all default category elements
     * 
     * @return Null
     */
    public function getDefaultCategoryElements() {
        return $this->defaultCategoryElements;
    }

    /**
     * Adds an element to the category register
     * 
     * @param Element $element
     * 
     * @return Null
     */
    public function addCategoryRegisterElement ($element) {
        $this->categoryRegisterElements[] = $element;
    }

    /**
     * Gets the category register elements
     * 
     * @return Array[Element]
     */
    public function getCategoryRegisterElements() {
        return $this->categoryRegisterElements;
    }
}
?>
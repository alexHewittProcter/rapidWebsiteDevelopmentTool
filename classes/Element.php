<?php
class Element extends Functions
{
    /**
     * Holds the value of the element tag used
     * 
     * @var String
     */
    private $tag;

    /**
     * Holds the value of the input type
     * For some tags there can be an input type, this attribute holds that value
     * 
     * @var String
     */
    private $inputType;

    /**
     * Holds the value of the name of the element
     * All form elements have a name to use in a corresponding script
     * 
     * @var String
     */
    private $name;

    /**
     * Holds the value that corresponds to the element in a database
     * This will be a column name in the database
     * 
     * @var String
     */
    private $databaseInput;

    /**
     * Holds the value of a label that will describe the element when printed
     * 
     * @var String
     */
    private $label;

    /**
     * Holds the value of the id associated with this element
     * 
     * @var String
     */
    private $id;

    /**
     * Holds the value of the set of classes associated with this element
     * 
     * @var String
     */
    private $class;

    /**
     * Holds the value determining if the element is printable or not
     * Printable elements are ones that can be show visually
     * 
     * @var Boolean
     */
    private $printable = true;

    /**
     * Holds the value determining if there is a custom action on the value
     * 
     * @var Boolean
     */
    private $customAction = false;

    /**
     * Holds the value of the function if there is a custom action
     * 
     * @var Function
     */
    private $customActionMethod;

    /**
     * Holds the value determining if there is a action on the value to validate it
     * 
     * @var Boolean
     */
    private $validationAction = false;

    /**
     * Holds the value to validate the value of the element
     * 
     * @var Function
     */
    private $validationActionMethod;

    /**
     * Holds the value determining if externalquerymethod is used
     * 
     * @var Boolean
     */
    private $externalQuery = false;
    /**
     * Holds the external query method
     * 
     * @var Function
     */
    private $externalQueryMethod;

    /**
     * Holds the value of the element
     * 
     * @var String|Int
     */
    private $value;

    /**
     * Holds a value of the non printable action
     * 
     * @var 'randomString'
     */
    private $nonPrintableAction;

    /**
     * Holds whether there is a non printable action
     * 
     * @var Boolean
     */
    private $nonPrintableAct = false;

    /**
     * Holds an error for the element, normally used in forms
     * 
     * @var String
     */
    private $error;

    /**
     * Holds the function for a custom input method, used with a customActionMethod
     * 
     * @var Function
     */
    private $customInputMethod;

    /**
     * Holds subelements for this element, sub elements can be used in form elements such as options
     * 
     * @var Array[String => String]
     */
    private $subElements = array();

    /**
     * Holds the table that contains subelements
     * 
     * This can be used instead of entering strings, in this case, values would be obtained from the database
     * 
     * @var String
     */
    private $subElementTable;

    /**
     * Holds the key column name for the subElements, this key value would be the value entered
     * 
     * @var String
     */
    private $subElementTableKey;

    /**
     * Holds the value column for the subelements, this value would represent the key
     */
    private $subElementTableValue;

    /**
     * Value to determine if subelements are to be used
     * 
     * @var Boolean
     */
    private $subElementsMethod = false;

    /**
     * Holds the value of the position of an element, this can be used to order elements in a form
     * 
     * @var Int
     */
    private $position;

    /**
     * Constructor
     * 
     * Sets the tag, inputtype, name, databaseinput, label, id and class of the element
     * 
     * @param String $tag of the element
     * 
     * @param String $inputType of the element
     * 
     * @param String $name of the element
     * 
     * @param String $databaseInput of the element
     * 
     * @param String $label of the element
     * 
     * @param String $id of the element
     * 
     * @param String $class of the element
     * 
     * @return Null 
     */
    public function __construct($tag,$inputType,$name,$databaseInput,$label,$id,$class) {
        $this->tag = $tag;
        $this->inputType = $inputType;
        $this->name = $name;
        $this->databaseInput = $databaseInput;
        $this->label = $label;
        $this->id = $id;
        $this->class = $class;
    }

    /**
     * Prints the element
     * 
     * This class prints a element based on its tag
     * 
     * @param DBC $dbc the database connection of the website object
     * 
     * @return Null
     */
    public function printElement($dbc) {
        switch($this->tag) {
            case 'input' :
                echo '<div class="form-group">';
                echo '<label>' . $this->label . ' : </label>';
                switch($this->inputType) {
                    case 'password' :
                        echo '<input type="' . $this->inputType . '" class="form-control" name="password[]">';
                        echo "<label> Re-enter Password : </label>";
                        echo '<input type="' . $this->inputType . '" class="form-control" name="password[]">';
                        break;
                    default : echo '<input type="' . $this->inputType . '" class="form-control ' . $this->class .  ' " name="' . $this->name . '" value="' . $this->value . '">';
                }
                if(!empty($this->error)) {
                    echo "<div class='alert alert-danger' role='alert'>" . $this->error . "</div>";
                }
                echo '</div>';
                break;
            case 'textarea' :
                echo '<div class="form-group">';
                echo '<label>' . $this->label . ' : </label>';
                echo '<textarea type="' . $this->inputType . '" class="form-control ' . $this->class .  ' " name="' . $this->name . '" value="' . $this->value . '"></textarea>';
                echo '</div>';
                break;
            case 'checkbox' :
                    echo "<div class='form-check'>";
                    echo "<input type='checkbox' class='form-check-input' id='" . $this->id ."'>";
                    echo "<label class='form-check-label' for='" . $this->id . "'>" . $this->label . "</label>";
                    echo "</div>";
                break;
            case 'radio' :
                    echo '<div class="form-group">';
                    echo '<label>' . $this->label . ' : </label><br/>';
                    
                    if(count($this->subElements) == 0) {
                        $query = "Select * FROM " . $this->subElementTable;
                        $result = mysqli_query($dbc,$query) or die("Failed to query dataase");
                        while($row = mysqli_fetch_array($result)) {
                            $value = $row[$this->subElementTableValue];
                            echo "<label class='radio-inline'>";
                            echo "<input type='radio' name='" . $this->name . "' value='$value'>$value";
                            echo "</label>";
                        }
                    } else {
                        foreach($this->subElements as $key => $value) {
                            echo "<label class='radio-inline'>";
                            echo "<input type='radio' name='" . $this->name . "' value='$value'>$value";
                            echo "</label>";
                        }
                    }
            echo "</div>";
                break;
            case 'select' :  
                    echo '<div class="form-group">';
                    echo '<label>' . $this->label . ' : </label>';
                    echo "<select class='form-control' name='" . $this->name . "'>";
                    if(count($this->subElements) == 0) {
                        $query = "Select * FROM " . $this->subElementTable;
                        $result = mysqli_query($dbc,$query) or die("Failed to query dataase");
                        while($row = mysqli_fetch_array($result)) {
                            $value = $row[$this->subElementTableValue];
                            echo "<option value='$value'>$value</option>";
                        }
                    } else {
                        foreach($this->subElements as $key => $value) {
                            echo "<option value='$value'>$value</option>";
                        }
                    }
                    echo "</select>";
                    echo "</div>";
                break;
            case 'file' :
                echo "<label>" . $this->label . " : </label>";
                echo "<input type='file' name='" . $this->name . "' class='form-control'>";
                break;
        }
    }

    /**
     * Print the element but hidden to the page
     * 
     * This is because its part of the form but not editable to the user
     * 
     * @return Null 
     */
    public function printHidden() {
        echo "<input type='text' name='" . $this->name . "' class='hidden' value='" . $this->value . "'>";
    }

    /**
     * Print element as a non printable
     * 
     * @return Null
     */
    public function printNonPrintable() {
        echo '<input type="' . $this->inputType . '" class="hidden" name="nonPrintables[]" value="' . $this->name . '">';
    }

    /**
     * Returns the name of the element
     * 
     * @return String $name the name of the element
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Returns the database table name 
     * 
     * @return String $databaseInput the table name in the database
     */
    public function getDatabaseInput() {
        return $this->databaseInput;
    }

    /**
     * Sets the elements printable attribute
     * 
     * @param Boolean $printable the boolean value
     * 
     * @return Null 
     */
    public function setPrintable($boolean) {
        $this->printable = $boolean;
    }

    /**
     * Returns the printable values
     * 
     * @return Boolean
     */
    public function getPrintable() {
        return $this->printable;
    }

    /**
     * Sets the nonprintableaction for the Element
     * 
     * @param Function $action the function to use
     * 
     * @return Null
     */
    public function setNonPrintableAction($action) {
        $this->nonPrintableAction = $action;
        $this->nonPrintableAct = true;
    }

    /**
     * Returns the value of the boolean $nonPrintableAct
     * 
     * @return Boolean $nonPrintableAct the value determining if there is a nonprintable action
     */
    public function getNonPrintableAct() {
        return $this->nonPrintableAct;
    }

    /**
     * Returns the resultant value of an non printable action being called
     * 
     * @return String|Int
     */
    public function getNonPrintableAction() {
        switch($this->nonPrintableAction) {
            case "randomString" :
                return $this->randomString();
                break; 
        }
    }

    /**
     * Returns if there are subelements or not for this element
     * 
     * @return Boolean if there are subelements or not
     */
    public function subElements() {
        if(count($this->subElements) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Adds a sub element to the subelement array
     * 
     * @param String $key the value of subelement
     * 
     * @param String $value the name of the subelement
     * 
     * @return Null
     */
    public function addSubElement($key,$value) {
        $this->subElements[$key] = $value;
        $this->subElementsMethod = true;
    }

    /**
     * Sets a subelementtables values
     * 
     * @param String $table the database table
     * 
     * @param String $key the index key for the table
     * 
     * @param String $value the column with the value in the table
     * 
     * @return Null
     */
    public function setSubElementTable($table,$key,$value) {
        $this->subElementTable = $table;
        $this->subElementTableKey = $key;
        $this->subElementTableValue = $value;
        $this->subElementsMethod = true;
    }

    /**
     * Returns the subelements array
     * 
     * @return Array[String]
     */
    public function getSubElements() {
        return $this->subElementsMethod;
    }

    /**
     * Returns the key for a value entered
     * 
     * This works with both the array and database version of subelements
     * 
     * @param DBC $dbc database connection
     * 
     * @param String $value 
     * 
     * @return String
     */
    public function subElementMethod($dbc,$value) {
        if(count($this->subElements) == 0) {
            $query = "Select * FROM " . $this->subElementTable . " WHERE " . $this->subElementTableValue . "='$value'";
            $result = mysqli_query($dbc,$query) or die("Failed to query dataase");
            $row = mysqli_fetch_array($result);
            return $row[$this->subElementTableKey];
        } else {
            foreach($this->subElements as $key => $elementValue) {
                if($elementValue == $value) {
                    return $key;
                }
            }
        }
    }

    /**
     * Sets the value of the element
     * 
     * @param DBC $dbc database connection
     * 
     * @param String $value name value of subelement
     * 
     * @return Null
     * 
     */
    public function setValue($dbc,$value) {
        if($this->subElementsMethod == true) {
            if(count($this->subElements) == 0) {
                $query = "Select * FROM " . $this->subElementTable . " WHERE " . $this->subElementTableKey . "='$value'";
                $result = mysqli_query($dbc,$query) or die("Failed to query dataase");
                $row = mysqli_fetch_array($result);
                $this->value = $row[$this->subElementTableValue];
            } else {
                $this->value = $this->subElements[$value];
            }
        } else {
            $this->value = $value;
        }
        
    }

    /**
     * Returns the value of the element
     * 
     * @return String|Int
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Sets the custom action for the element
     * 
     * @param Function $method
     * 
     * @return Null
     */
    public function setCustomAction($method) {
        $this->customAction = true;
        $this->customActionMethod = $method;
    }

    /**
     * Returns the boolean action determining if there is a custom action
     * 
     * @return Boolean
     */
    public function getCustomAction() {
        return $this->customAction;
    }

    /**
     * Returns the custom action for the element
     * 
     * @return Function
     */
    public function getCustomActionMethod() {
        return $this->customActionMethod;
    }

    /**
     * Set the custom input method for retreiving a value
     * 
     * @param Function 
     */
    public function setCustomInputMethod($method) {
        $this->customInputMethod = $method;
    }

    /**
     * Returns the custominputmethod
     * 
     * @return Function
     */
    public function getCustomInputMethod() {
        return $this->customInputMethod;
    }

    /**
     * Sets a validation action method
     * 
     * @param Function $method for validation
     * 
     * @return Null
     */
    public function setValidationAction($method) {
        $this->validationAction = true;
        $this->validationActionMethod = $method;
    }

    /**
     * Returns the validation action boolean
     * 
     * @return Boolean
     */
    public function getValidationAction() {
        return $this->validationAction;
    }

    /**
     * Returns the validation action method
     * 
     * @return Function
     */
    public function getValidationActionMethod() {
        return $this->validationActionMethod;
    }

    /**
     * Adds an error message
     * 
     * @param String $error
     * 
     * @return Null
     */
    public function addError($error) {
        $this->error = $error;
    }

    /**
     * Returns the externalQuery boolean
     * 
     * @return Boolean
     */
    public function getExternalQuery() {
        return $this->externalQuery;
    }

    /**
     * Returns the externalQueryMethod
     * 
     * @return Function
     */
    public function getExternalQueryMethod() {
        return $this->externalQueryMethod;
    }

    /**
     * Sets the external query method
     * 
     * @param Function $method
     * 
     * @return Null
     */
    public function setExternalQueryMethod($method) {
        $this->externalQuery = true;
        $this->externalQueryMethod = $method;
    }

    /**
     * Sets the position of the element
     * 
     * @param Int
     * 
     * @return Null
     */
    public function setPosition($position) {
        $this->position = $position;
    }

    /**
     * Returns the position of the element
     * 
     * @return Int
     */
    public function getPosition() {
        return $this->position;
    }
}
?>
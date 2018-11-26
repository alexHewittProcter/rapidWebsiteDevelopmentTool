<?php
/**
 * LoggedInDropDown is a class that stores menu items for the drop down menu when users have logged in
 */
class LoggedInDropDown {
    /**
     * Holds the title of the menu item
     * 
     * @var int
     */
    public $title;

    /**
     * Holds the link string of the menu item
     * 
     * @var int
     */
    public $link;

    /**
     * Holds an array of the possible conditions for the menu item
     * 
     * @var Array[int]
     */
    private $conditionArray;

    /**
     * Constructor for the class
     * 
     * @param string $title holds the title of the menu item
     * 
     * @param string $link holds the string link of the menu item
     * 
     * @param Array[int]|int $conditionArray holds the conditions to be met for the menu item
     */
    public function __construct($title,$link,$conditionArray) {
        $this->title = $title;
        $this->link = $link;
        if(!is_array($conditionArray)) {
            $this->conditionArray = [$conditionArray];
        } else if(!empty($conditionArray)) {
            $this->conditionArray = $conditionArray;
        }
    }

    /**
     * 
     */
    public function conditionMet($condition) {
        if(count($this->conditionArray) == 0) {
            return true;
        }
        if($condition == "" || $condition == null) {
            return false;
        } else {
            foreach ($this->conditionArray as $key => $value) {
                if($value == $condition) {
                    return true;
                }
            }
            return false;
        }
    }
}

?>

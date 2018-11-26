<?php
/**
 * Functions is the ultimate super class
 * 
 * It holds several universal functions
 */
class Functions
{
    /**
     * Create query method, creates a query from parameters
     * 
     * @param DBC $database
     * 
     * @param 'insert' $queryType
     * 
     * @param Array[String] $variables
     * 
     * @return String $query
     */
    public static function createQuery($database,$queryType,$variables) {
        
        switch($queryType) {
            case "insert" :
                $query = "INSERT INTO " . $database;
                $columnNames = [];
                $inputValues = [];
                foreach($variables as $key => $value) {
                    $columnNames[] = $key;
                    $inputValues[] = "'" . $value . "'";
                }
                $query .= "(" . implode(",",$columnNames) . ") VALUES (" . implode(",",$inputValues) . ")";
                return $query;
                break;
        }
    }

    /**
     * Sorts elements into ascending order of position
     * 
     * @param Array[Element] $elements
     * 
     * @return Array[Element] $returnArray sorted list
     */
    public function sortByPosition($elements) {
        $returnArray = [];
        for($i =100; $i >= 0; $i--) {
            foreach($elements as $element) {
                if($element->getPosition() == $i) {
                    $returnArray[] = $element;
                }
            }
        }
        return $returnArray;
    }

    /**
     * Generates a random string between 15 and 255 characters
     * 
     * @return String
     */
    public function randomstring() {
        $charArray = str_split("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,./;#()[]{}!$%^&*");
          $tempPass = ""; 
          for($i=0;$i<rand(15,255);$i++){
              $tempPass .= $charArray[rand(0,count($charArray) - 1)];
          }
          return $tempPass;
    }


    /**
     * Generates a random string between 15 and 255 characters that are non escapable characters
     */
    public function randomAlphaString() {
        $charArray = str_split("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
            $tempPass = ""; 
            for($i=0;$i<rand(15,255);$i++){
                $tempPass .= $charArray[rand(0,count($charArray) - 1)];
            }
            return $tempPass;
    }

    /**
     * Validates a password based on regex and other factors
     * 
     * @param Array[String] $passArray
     * 
     * @return Bool|String Boolean if valid, otherwise a string message
     */
    public static function validatePassword($passArray) {
        $pregMatch = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(.{8,})/';
        if($passArray[0] == $passArray[1]) {
            if($passArray[0] != "") {
                if(preg_match($pregMatch,$passArray[0])) {
                    return true;
                } else {
                    return "Your password is not valid";
                }
            } else {
                return "Please enter a password";
            }
        } else {
            return "Your passwords do not match";
        }
    }

    /**
     * Hashses a password
     * 
     * @param String $passArray
     * 
     * @return String hash
     */
    public static function insertPassword($passArray) {
        $hash = password_hash($passArray[0],PASSWORD_DEFAULT);
        return $hash;
    }


    /**
     * Converts money from one currency to another, completely reliant on a currency table being created
     * 
     * @param DBC $db Database connection
     * 
     * @param Int $amountto Amount to convert
     * 
     * @param String $currencyfrom Current currency
     * 
     * @param String $currencyto Currency to convert to
     * 
     * @return Int Newly converted amount
     */
    public static function converterfunction($db,$amountto,$currencyfrom,$currencyto) {
        echo "FUNCTION INVOKED";
        if($currencyfrom == "GBP") {
            $query10 = "SELECT * FROM currency WHERE currencycode='$currencyto'";
            $result1 = mysqli_query($db, $query10) or die("Failed to query currency");
            $row = mysqli_fetch_array($result1);
            $currencyrate = $row['currencyrate'];
            $convertedcurrency = $currencyrate * $amountto;
        } else if($currencyto == "GBP") {
            $query10 = "SELECT * FROM currency WHERE currencycode='$currencyfrom'";
            $result1 = mysqli_query($db, $query10) or die("Failed to query currency");
            $row = mysqli_fetch_array($result1);
            $currencyrate = $row['currencyrate'];
            $convertedcurrency = $amountto / $currencyrate;

        }
        return $convertedcurrency;
  }
}
?>
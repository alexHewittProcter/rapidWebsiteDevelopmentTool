<?php
/**
 * Website class
 * 
 * 
 */
class Website extends Functions
{
    //Standard variables
    /**
     * Holds the value of the websites url
     * 
     * @var String
     */
    private $url;
    
    /**
     * Holds the value of the website name
     * 
     * @var String
     */
    private $websiteName;

    /**
     * hold the value of the release date
     * 
     * @var String|null
     */
    private $liveReleaseDate;

    /**
     * Holds the value of whether the website charges fees with a membership structure
     * 
     * @var Boolean
     */
    private $membershipFeeStructure = false;

    /**
     * Holds the value of whether the user is logged in or not
     * 
     * @var Boolean
     */
    private $loggedIn = false;
    
    /**
     * Holds the value of the user id if the user is logged in
     * 
     * @var Int
     */
    private $loggedInUserId;

    /**
     * Holds the value of the current page title
     * 
     * @var String
     */
    private $currentPageTitle;

    /**
     * Holds the value of the current url
     * 
     * @var String
     */
    private $currentURL;

    /**
     * Holds the url that the signup page is redirected to
     * 
     * @var String
     */
    private $passSignUpURL;

    /**
     * Holds the object for the current user
     * 
     * @var /Library/User
     */
    public $currentUserObj;

    /**
     * Holds the data from the database about a user
     * 
     * @var Array
     */
    public $userData;

    /**
     * Holds the database connection for the website
     * 
     * @var DatabaseConnection
     */
    public $dbc;
    //Arrays

    /**
     * Holds database arrays with information needed for a connection
     * 
     * @var Array[["dbHost","dbPassword","dbUser","dbName"]]
     */
    private $database = array();

    /**
     * Holds User objects for the different user types
     * 
     * @var Array[User]
     */
    private $users = array();

    /** 
     * Holds the Elements that are default to all users
     * 
     * @var Array[Element]
    */
    private $defaultUserElements = array();

    /**
     * Holds the Image Elements that are default to all users
     * 
     * @var Array[Image]
     */
    private $defaultUserImages = array();

    /**
     * Holds the Elements that the user has to enter to login to the website
     * 
     * @var Array[Element]
     */
    private $loginElements = array();

    /**
     * Holds the values of the different links for the nav bar
     * 
     * @var Array[string Name => String Url]
     */
    private $navLinks = array();

    /**
     * Holds the values of the links for the footer of the website
     * 
     * @var Array[string Name => String Url]
     */
    private $footerLinks = array();

    /**
     * Holds the Elements that are not printed when a user signs up
     * 
     * @var Array[Element]
     */
    private $nonPrintables = array();

    /**
     * A global array used in any form usage to determine errors before querying the database
     */
    private $formErrors = array();

    /**
     * Constructor
     * 
     * Sets the basic variables of the website
     * 
     * @param String|null $websiteName name of the website
     * 
     * @param String|null $url url of the website
     * 
     * @param String|null $liveReleaseDate the string of the date of release of the website
     * 
     * @return null
     */
    public  function __construct($websiteName,$url,$liveReleaseDate) {
        $this->websiteName = $websiteName;
        $this->url = $url;
        $this->liveReleaseDate = $liveReleaseDate;
        
    }

    /**
     * Initial method before any other interaction
     * 
     * Checks if the user is logged in or not
     * 
     * @return null
     */
    public function init() {
        $this->userLoggedIn();
    }

    /**
     * Gets the name of the website
     * 
     * @return null|String
     */
    public function getWebsiteName() {
        return $this->websiteName;
    }
    
    /**
     * Adds a navigation link to the $navLiks array
     * 
     * @param String the title or name associated with the page
     * 
     * @param String the url of the page
     */
    public function addNavLink($key,$value) {
        $this->navLinks[$key] = $value;
    }

    /**
     * Gets the array of navigation links
     * 
     * @return Array
     */
    public function getNavLinks() {
        return $this->navLinks;
    }

    /**
     * Prints the header for a page, including all the scripts and stylesheets
     * 
     * @param Array|null $styleArray an array that holds links to different stylesheets
     * 
     * @param Array|null $scriptArray an array that holds links to different scripts
     * 
     * @return null
     */
    public function printHeader($styleArray,$scriptArray) {
        ?>
        <html>
        <head>
            <title>
                <?php
                    echo $this->currentPageTitle . " - " . $this->getWebsiteName();
                ?>
            </title>
            <?php
                echo '<meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>';
            ?>
            <?php
                
                echo '<link rel="Stylesheet" href="templatefiles/template.css">';
                echo '<script src="templatefiles/template.js"></script>';
                foreach($styleArray as $style) {
                    echo "<link rel='Stylesheet' href='$style'>";
                }
                echo $script;
            ?>
        </head>
        <body>
            <!-- Fixed navbar -->
        <nav class="navbar navbar-default navbar-fixed-top">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#"><?php echo $this->getWebsiteName() ?></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                  <?php
                    foreach($this->getNavLinks() as $key => $value) {
                        $string = "";
                        if($redirectPageName == $value) {
                            $string .= "<li class='active'>";
                        } else {
                            $string .= "<li>";
                        }
                        $string .= "<a href='$value'>$key</a></li>";
                        echo $string;
                    }
                  ?>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                  <?php
                    
                    if($this->loggedIn) {
                        $names = $this->currentUserObj->getNameElements();
                        echo '<li class="dropdown">';
                        echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'  . $this->userData['userFirstName'] . '<span class="caret"></span></a>';
                        echo '<ul class="dropdown-menu">';
                        switch($this->currentUserObj->getType()) {
                            case 0 :
                                echo '<li><a href="savedlistings.php">Saved Listings</a></li>';
                                break;
                            case 1 :
                                echo '<li><a href="admin.php">Admin</a></li>';
                                break;
                            case 2 :
                                echo '<li><a href="admin.php">Admin</a></li>';
                                break;
                            case 3 :
                                echo '<li><a href="admin.php">Site Admin</a></li>';
                                break;
                        }
    
                        echo '<li role="separator" class="divider"></li>';
                        echo '<li><a href="settings.php">Settings</a></li>';
                        echo '<li><a href="logout.php">Logout</a></li>';
                        echo '</ul>';
                        echo '</li>';
                        
                    } else {
                        echo "<li><a href='login.php'>Sign in</a></li>";
                    }
                  ?>
              </ul>
            </div><!--/.nav-collapse -->
          </div>
        </nav>
        <?php

    }

    /**
     * Adds a footer link to the footer link array
     * 
     * @param String|null $key the name or title associated with the page
     * 
     * @param String|null $value the url of the page
     * 
     * @return null
     */
    public function addFooterLink($key,$value) {
        $this->footerLinks[$key] = $value;
    }

    /**
     * Gets all the footer links
     * 
     * @return Array
     */
    public function getFooterLinks() {
        return $this->footerLinks;
    }
    /**
     * Prints the footer with all its links
     * 
     * @return null
     */
    public function printFooter() {
        ?>
        <footer class="footer text-center">
            <div class="container">
                <p>
                <?php
                    foreach($this->getFooterLinks() as $key => $value) {
                        echo "<a href='$value'>$key </a>";
                    }
                ?>
            </p>
        </div>
        </footer>
        </body>
        </html>
        <?php
    }

    /**
     * Adds a user class
     * 
     * @param User $user the class of the user
     * 
     * @return null
     */
    public function addUser($user) {
        $this->users[] = $user;
    }

    /**
     * Adds a user element to the default user elements array
     * 
     * @param Element $userElement the element being added
     */
    public function addUserElement($userElement) {
        array_push($this->defaultUserElements,$userElement);
    }

    /**
     * Prints all the user elements that are default
     * 
     * @return null
     */
    public function printUserDefaults() {
        foreach($this->defaultUserElements as $element => $value) {
            $value->printElement();
        }
    }

    /**
     * Adds a element that is used in a user logging in
     * 
     * @param Element $element the element being added
     * 
     * @return null
     */
    public function addLoginElement($element) {
        array_push($this->loginElements,$element);
    }

    /**
     * Prints the login elements
     * 
     * @return null
     */
    public function printLoginElements() {
        foreach($this->loginElements as $element => $value) {
            $value->printElement();
        }
    }

    /**
     * Adds a non printable element to the non printable array
     * 
     * @param Element|null $nonPrintable the element being added as a non printable
     */
    public function addNonPrintable($nonPrintable) {
        array_push($this->nonPrintables,$nonPrintable);
    }

    /**
     * Prints all the non printable elements
     * 
     * @return null
     */
    public function printNonPrintables() {
        foreach($this->nonPrintables as $element => $value) {
            $value->printNonPrintable();
        }
    }

    /**
     * Gets all the users
     * 
     * @return Array[User]
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Gets a webgroup class element based on its type
     * 
     * @return WebGroup
     */
    public function getByType($array,$type) {
        foreach($array as $key => $value) {
            if($value->getType() == $type) {
                return $value;
            }
        }
    }

    /**
     * Gets all the user form elements for a user based on its user types as we
     * 
     * @param User $formType the user element for the form
     * 
     * @return Array[Element]
     */
    public function getUserFormElements($formType) {
        $newArray = array();
        
        foreach($this->loginElements as $value) {
            array_push($newArray,$value);
        }
        //print_r($newArray);
        if($formType != null) {
            foreach($formType->getFormElements() as $value) {
                array_push($newArray,$value);
            }
        }
        //print_r($newArray);
        foreach($this->defaultUserElements as $value) {
            array_push($newArray,$value);
        }
        //print_r($newArray);
        foreach($this->nonPrintables as $value) {
            array_push($newArray,$value);
        }
        //print_r($newArray);
        return $newArray;
    }

    /**
     * Gets a specific user element
     * 
     * @param User $formType the user element
     * 
     * @param String $name the name of the element
     * 
     * @return Element|Null
     */
    public function getUserElement($formType, $name) {
        $newArray = $this->getFormElements($formType);
        foreach($newArray as $key => $value) {
            if($name == $value->getName()) {
                return $value;
            }
        }
        return null;
    }

    /**
     * Adds a image to the default user images array
     * 
     * @param Image $image The image element being added
     * 
     * @return Null
     */
    public function addDefaultImage($image) {
        $this->userDefaultImages[] = $image;
    }

    /**
     * Sets the current page title
     * 
     * @param String $title the title of the current page
     * 
     * @return Null
     */
    public function setCurrentPageTitle($title) {
        $this->currentPageTitle = $title;
    }

    /**
     * Prints a custom page that is default to the website
     * 
     * @param String $pageName the name of the page
     * Allowed values :
     * - "Sign up"
     * - "Sign up upload image"
     * - "Pass sign up"
     * 
     * @return Null
     */
    public function printCustomPage($pageName) {
        $this->setCurrentPageTitle($pageName);
        $this->createConnection();
        $this->init();
        //Before header
        switch($pageName) {
            case "Sign up" :
                if(isset($_GET['type'])) {
                    $user = $this->getByType($this->users,$_GET['type']);
                } else if(count($this->users) < 2) {
                    $user = $this->getByType($this->users,0);
                }
                if(isset($_POST['submit'])) {
                    $formElements = $this->getUserFormElements($user);
                    $connectFormElements = $this->connectFormVariables($formElements,$_POST);
                    $query = $this->createQuery("users","insert",$connectFormElements);
                    if(count($this->formErrors) == 0) {
                        $link = $this->getConnection();
                        $result = mysqli_query($link,$query) or die("Failed to query databse".$query);
                        $this->logInUser($_POST);
                        if(count(array_merge($this->userDefaultImages,$user->getUserImages())) > 0) {
                            header("Location:signUpUploadImage.php");
                        } else if($this->membershipFeeStructure == true) {
                            header("Location:MembershipFee.php?from=signUp");
                        } else {
                            header("Location:".$this->passSignUpURL);
                        }
                    } else {
                        
                    }
                }
                
                break;
            case "Sign up upload image" : 
                $query = "SELECT * FROM users WHERE userId='" . $this->userId . "'";
                if(empty($this->dbc)) {
                    $this->createConnection();
                }
                $result = mysqli_query($this->dbc,$query) or die("Failed to query database");
                $row = mysqli_fetch_array($result);
                $user = $this->getByType($this->users,$row['userType']);
                if(isset($_POST['submit'])) {
                     foreach(array_merge($this->userDefaultImages,$user->getUserImages()) as $image) {
                         $method = $image->getExternalQueryMethod();
                         if(empty($this->dbc)) {
                             $this->createConnection();
                         }
                         $method($this->dbc,$image,array_merge($_POST,array("userId"=>$_SESSION['userId'])));
                        if($this->membershipFeeStructure == true) {
                            header("Location:MembershipFee.php?from=signUp");
                        } else {
                            header("Location:".$this->passSignUpURL);
                        }
                     }
                }
                break;
        }

        //After header
        switch($pageName) {
            case "Sign up" :
                $this->setCurrentURL('signUp.php');
                $this->printHeader();
                echo "<div class='container'>";
                if(count($this->users) < 2) {
                    $this->printPageHeader('Sign up');
                    $array = $this->getUserFormElements($user);
                    if($user != null) {
                        $hidden = $user->getRegisterElements();
                    } else {
                        $hidden = [];
                    }
                    $this->printForm($array,$_POST,"POST",$hidden);
                } else if(isset($_GET['type'])) {
                    $this->printPageHeader($user->getName() . " sign up");
                    $array = $this->getUserFormElements($user);
                    $hidden = $user->getRegisterElements();
                    $this->printForm($array,$_POST,"POST",$hidden);
                } else {
                    $this->printPageHeader("Sign up");
                    $this->printMenu($this->users,"getType","type","getName");
                }
                
                echo "</div>";
                $this->printFooter();
                break;
            case "Sign up upload image" :
                $this->setCurrentURL('signUpUploadImage.php');
                $this->printHeader();
                echo "<div class='container'>";
                $this->printPageHeader($user->getName() . " image upload");
                $this->printForm($this->userDefaultImages,$_POST,"POST",null,true,"passSignUp.php");
                echo "</div>";
                $this->printFooter();
                break;
            case "Pass sign up" :
                $this->setCurrentUrl('passSignUp.php');
                $this->printHeader();
                echo "<div class='container text-center'>";
                $userName = $this->currentUserObj->getNameElements()[0];
                echo "<h2>Welcome " . $this->userData[$userName->getDatabaseInput()] . "</h2>";
                echo "<div class='row'>";
                $count = 12/count($this->currentUserObj->getPassSignUp());
                if($count % 2 != 0) {
                    $count++;
                }
                foreach($this->currentUserObj->getPassSignUp() as $value) {
                    $this->printMenuButton($value['url'],$value['name'],["col-lg-".$count,"col-xs-12"]);
                }
                echo "</div>";
                echo "</div>";
                $this->printFooter();
                break;
        }
    }

    /**
     * Sets the current URL 
     * 
     * @param String $url the url of the current page
     * 
     * @return Null
     */
    public function setCurrentURL($url) {
        $this->currentURL = $url;
    }

    /**
     * Gets the current URL
     * 
     * @return String
     */
    public function getCurrentURL() {
        return $this->currentURL;
    }
    
    /**
     * Prints a page header, a specific componenet in Bootstrap
     * 
     * @param String $content the content to be put inside the header
     * 
     * @return Null
     */
    public function printPageHeader($content) {
        echo "<div class='page-header'>";
        echo "<h2>";
        echo $content;
        echo "</h2>";
        echo "</div>";
    }

    /**
     * Prints all the form elements
     * 
     * @param Array[Element] $formElements The forms elements
     * 
     * @param Array[] $formVariable The variables that can be connected to the elements
     * 
     * @param String $formMethod the method of the form
     * 
     * @param Array[Element] $hiddenElements the elements that are hidden but need to be printed
     * 
     * @param Boolean $skippable whether this part of the form is skippable or not
     * 
     * @param String $skipUrl the url that is used if the form can be skipped
     *
     * @return Null
     */
    public function printForm($formElements,$formVariable,$formMethod,$hiddenElements,$skippable,$skipUrl) {
        $formElements = $this->sortByPosition($formElements);
        echo "<form method='$formMethod' enctype='multipart/form-data'>";
        foreach($formElements as $element => $value) {
            
            if($value->getPrintable() == false) {
            } else {
                if(!empty($this->formErrors[$value->getName])) {
                    $value->addError($this->formErrors[$value->getName]);
                }
                $value->printElement($this->dbc);
            }
            
        }
        
        foreach($hiddenElements as $element => $value) {
            $value->printHidden();
        }
        if($skippable) {
            echo "<div class='row'>";
            echo "<div class='col-lg-6'><button type='submit' name='submit' class='btn btn-large btn-block btn-default'>Submit</button></div>";
            ?><div class='col-lg-6'><button type='button' class='btn btn-large btn-block btn-default' onclick="window.location.href='<?php echo $this->url . $skipUrl ?>'">Skip</button></div><?php;
            echo "</div>";
        } else {
            echo "<button type='submit' name='submit' class='btn btn-large btn-block btn-default'>Submit</button>";
        }
        echo "</form>";
    }

    /**
     * Prints a menu system that can be navigated through
     * 
     * @param Array[Object] $input the array holding the different menu options
     * 
     * @param String $linkMethod the method used to get the value associated with each input
     * 
     * @param String $linkName the value used to in the url
     * 
     * @param String $nameMethod the method used to get the name associated with each element
     * 
     * @return Null
     */
    public function printMenu($input,$linkMethod,$linkName,$nameMethod) {
        echo "<div class='row'>";
        $getVariables = $this->getUrlVariables();
        foreach($input as $value) {
            $getVariables[$linkName] = $linkName . "=" . $value->$linkMethod();
            $this->printMenuButton($this->currentURL . "?" . implode("&",$getVariables),$value->$nameMethod(),["col-xs-12","col-lg-4"]);
        }
        echo "</div>";
    }

    /**
     * Prints a menu button
     * 
     * @param String $url the link the button has
     * 
     * @param String $name the name string associated with the button
     * 
     * @param Array[String] $classes the strings that are used as classes
     * 
     * @return Null
     */
    public function printMenuButton($url,$name,$classes) {
        echo "<div class='".implode(" ",$classes)."' style='padding:20px'>";
        echo "<a class='btn btn-default btn-large btn-block' href='" . $url .  "'>";
        echo $name;
        echo "</a>";
        echo "</div>";
    }

    /**
     * Connects a set of elements table column names to the input values
     * 
     * @param Array[Element] $elements the elements that are in the form
     * 
     * @param Array[] $formInputs the inputs of data, using the name value of an Element as reference
     * 
     * @return Array[] $variables the resulting key and value pairs that connect values with corresponding columns
     */
    public function connectFormVariables($elements,$formInputs) {
        $variables = [];
        foreach($elements as $value) {
            $validation = true;
            if($value->getValidationAction()) {
                $validationMethod = $value->getValidationActionMethod();
                if($validationMethod($formInputs[$value->getName()]) != "true") {
                    $validation = false;
                    $this->formErrors[$value->getName()] = $validationMethod($formInputs[$value->getName()]);
                }
            }
            if($validation) {
                if($value->getExternalQuery()) {
                    if(count($this->formErrors) == 0) {
                        $method = $value->getExternalQueryMethod();
                        if(empty($this->dbc)) {
                            $this->createConnection();
                        }
                        $variables[$value->getDatabaseInput()] = $method($this->dbc,$value,$formInputs);
                    }
                } else if($value->getCustomAction()) {
                    $method = $value->getCustomActionMethod();
                    $variables[$value->getDatabaseInput()] = $method($this->dbc,$formInputs[$value->getName()]);
                } else if($value->getSubElements()) {
                    $variables[$value->getDatabaseInput()] = $value->subElementMethod($this->dbc,$formInputs[$value->getName()]);
                } else {
                    if($value->getNonPrintableAct()) {
                        $variables[$value->getDatabaseInput()] = $value->getNonPrintableAction();
                    } else {
                        $variables[$value->getDatabaseInput()] = $formInputs[$value->getName()];
                    }
                }
            }
        }
        return $variables;
    }

    /**
     * Returns all the get variables
     * 
     * @return Array[] $variables the key value pairs of the get variables 
     */
    public function getUrlVariables() {
        $variables = [];
        foreach($_GET as $key => $value) {
            $variables[$key] = $key . "=" . $value;
        }
        return $variables;
    }

    /**
     * Adds a database to the website
     * 
     * @param Array[] $databaseArray the array with the details to create a database connection
     * 
     * @return null
     */
    public function addDatabase($databaseArray) {
        $this->database = $databaseArray;
    }

    /**
     * Creates a database connection using the details from a database array
     * 
     * @return null
     */
    public function createConnection() {
        $this->dbc = mysqli_connect($this->database['dbHost'],$this->database['dbUser'],$this->database['dbPassword'],$this->database['dbName']);
    }

    /**
     * Returns a database connection
     * 
     * @return DBC $dbc the database connection
     */
    public function getConnection() {
        return $this->dbc;
    }

    /**
     * Closes the database connection
     * 
     * @return null
     */
    public function closeConnection() {
        mysqli_close($this->dbc);
        $this->dbc;
    }

    /**
     * Sets a url to be forwarded to at the end of the signup page
     * 
     * @param String $url the string of the url
     */
    public function setPassSignUpURL($url) {
        $this->passSignUpURL = $url;
    }

    /**
     * Attempts to log in a user with login data
     * 
     * @param Array[] $loginData Holds the values of the loginelements form name and their values
     * 
     * @return null
     */
    public function logInUser($loginData) {
        $loginArray = [];
        foreach($this->loginElements as $value) {
            $validation = true;
            if($value->getValidationAction()) {
                $validationMethod = $value->getValidationActionMethod();
                if($validationMethod($loginData[$value->getName()]) != "true") {
                    $validation = false;
                    $this->formErrors[$value->getName()] = $validationMethod($loginData[$value->getName()]);
                }
            }
            if($value->getCustomAction()) {
                $method = $value->getCustomInputMethod();
                $loginArray[$value->getDatabaseInput()] = $method($loginData[$value->getName()]);
            } else {
                if($value->getPrintable()) {
                    $loginArray[$value->getDatabaseInput()]= $loginData[$value->getName()];
                } else {
                    $loginArray[$value->getDatabaseInput()] = $value->getNonPrintableAction();
                }
            }
        }
        $query = "SELECT * FROM users WHERE userEmail='" . $loginArray['userEmail'] . "'";
        if(empty($this->dbc)) {
            $this->createConnection();
        }
        $result = mysqli_query($this->dbc,$query) or die("Failed to query database");
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            if(password_verify($loginArray['userPass'],$row['userPass'])) {
                //Logged in
                $this->loggedIn = true;
                $_SESSION['userId'] = $row['userId'];
                $_SESSION['userString'] = $row['userString'];
                $_SESSION['userEmail'] = $row['userEmail'];
                setcookie('userEmail',$row['userEmail'],365 * 24 * 60 * 60 * 60);
                setcookie('userString',$row['userString'],365 * 24 * 60 * 60 * 60);
                $this->currentUserId = $row['userId'];
            }
        }
    }

    /**
     * Checks if a user is logged in
     * 
     * using the loggedIn variable
     * 
     * @return null
     */
    public function userLoggedIn() {
        $email = $_SESSION['userEmail'];
        $string = $_SESSION['userString'];
        $query = "SELECT * FROM users WHERE userEmail='$email' AND userString='$string'";
        if(empty($this->dbc)) {
            $this->createConnection();
        }
        $result = mysqli_query($this->dbc,$query) or die("Failed to query database");
        if(mysqli_num_rows($result) == 1) {
            $this->loggedIn = true;
            $row = mysqli_fetch_array($result);
            $this->userData = $row;
            $this->currentUserObj = $this->getByType($this->users,$row['userAccountType']);
            $_SESSION['userId'] = $row['userId'];
        } else {
            $this->loggedIn = false;
        }
    }
}
?>
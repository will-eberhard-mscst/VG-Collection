<?php
//open DB connection
include("dbconnect.php");
include("check_login.php");
//error message
$message = "";

//this will enter the data
if(isset($_POST["enter"])){
    $stmt = $conn->prepare("INSERT INTO accessories (accessoryName, originalPlatform, connectivity, type, quantity, comments, username) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $accessoryName, $originalPlatform, $connectivity, $type, $quantity, $comments, $username);
    //set empty strings to null
    foreach ( $_POST as $key => $value) {
        if(trim($value) == ""){
            $_POST["$key"] = null;
        }
    }
    
    //enter all data
    $accessoryName = $_POST["accessoryName"];
    $originalPlatform = $_POST["originalPlatform"];
    $connectivity = $_POST["connectivity"];
    $type = $_POST["type"];
    $quantity = $_POST["quantity"];
    $comments = $_POST["comments"];
    $username = $user;
    //checks if the SQL failed
    if($stmt->execute()){
        $message = "<div class='success'>New records created successfully</div>";
    }
    else{
        $message = "<div class='alert'>Error: Failed to enter record. Try Again</div>";
    }
}

?>

<!doctype html>
<html lang="en">
 <?php include("head.html"); ?>
    <body class="entry">
        <h1>Video Game Collection</h1>
        <?php include("header.html"); ?>
        <h2>Add Accessories</h2>
        <div class="buttonA"> <!--Make look like a button -->
            <a href=accessories.php>Go Back</a>
        </div>
        <span><?php echo $message; ?></span>
        
        <!--
       	accessoryID int not null auto_increment,
        accessoryName varchar(300) not null,
        originalPlatform varchar(100),
        connectivity varchar(200),
        type enum ("Controller", "Add-On", "Memory Storage", "Cable", "NFC Figure", "Other") (200),
        quantity int not null,
        comments varchar(300),
        username varchar(50) not null,
        -->
        <!-- This creates on autocomplete field for Platforms -->
        <?php include("autocomplete_platforms.php") ?>
        
        <form method="post">
            <table>
                <caption>Required fields*</caption>
                <tr>
                    <th>Accessory Name*</th>
                    <th>Original Platform</th>
                    <th>Connectivity</th>
                    <th>Type</th>
                    <th>Quantity*</th>
                    <th>Comments</th>
                </tr>
                    <tr>
                        <td>
                            <input type="text" name="accessoryName" maxlength="300" required>
                        </td>
                        <td>
                            <input type="text" name="originalPlatform" maxlength="100" id="platform">
                        </td>
                        <td>
                            <input type="text" name="connectivity" maxlength="200">
                        </td>
                        <td>
                            <select name="type" onchange="addFieldType(this.value)">
                                <option value="Controller">Controller</option>
                                <option value="Add-On">Add-On</option>
                                <option value="Memory Storage">Memory Storage</option>
                                <option value="Cable">Cable</option>
                                <option value="NFC Figure">NFC Figure</option>
                                <option value="Other">Other</option>
                            </select>
                            <div id="Other"></div>
                        </td>
                        <td>
                            <input type="text" name="quantity" maxlength="4" size="1" value="1" required>
                        </td>
                        <td>
                            <input type="text" name="comments" maxlength="300">
                        </td>
                    </tr>
                       
            </table>
             <input type="submit" name="enter" value="Enter">
        </form>
        <?php include("scripts.php"); ?>
        <?php $conn->close(); ?>
    </body>
</html>
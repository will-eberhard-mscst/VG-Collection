<?php
//open DB connection
include("dbconnect.php");
include("check_login.php");
//error message
$message = "";

//set variables to null
$id = "";
$accessoryName = "";
$originalPlatform = "";
$connectivity = "";
$type = "";
$quantity = "";
$comments = "";

//when they first land on the edit page
if(isset($_POST["id1"])){
    $id = $_POST["id1"];
    $stmt = $conn->prepare("SELECT * FROM accessories WHERE accessoryID = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $accessoryName = $row["accessoryName"];
            $originalPlatform = $row["originalPlatform"];
            $connectivity = $row["connectivity"];
            $type = $row["type"];
            $quantity = $row["quantity"];
            $comments = $row["comments"];
        }
    }
    else{
        $message = "<div class='alert'>Error: No data found. Go back to the <a href='accessory.php'>previous page</a></div>";
    }
}

//part 2. Update
if(isset($_POST["update"])){
    $id = $_POST["id2"];
    $stmt = $conn->prepare("UPDATE accessories SET accessoryName=?, originalPlatform=?, connectivity=?, type=?, quantity=?, comments=? WHERE accessoryID = $id");
    $stmt->bind_param("ssssss", $accessoryName, $originalPlatform, $connectivity, $type, $quantity, $comments);
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
    
    if($stmt->execute()){
        $message = "<div class='success'>Record updated successfully</div>";
    }
    else{
        $message = "<div class='alert'>Error: Failed to update record. Try Again</div>";
    }
}

//part 2. Delete record
if(isset($_POST["delete"])){
    $id = $_POST["id3"];
    $stmt = $conn->prepare("DELETE FROM accessories WHERE accessoryID = ?");
    $stmt->bind_param("s", $id);
    if($stmt->execute()){
        $_SESSION["message"] = "<div class='success'>Record has been deleted</div>";
        header("location:accessories.php");
    }
    else{
        $message = "<div class='alert'>Error: Failed to Delte record. Try again.</div>";
    }
}

if(trim($id) == ""){ //if you access the page without clicking Edit
    $message = "<div class='alert'>Error: No data found. Go back to the <a href='accessories.php'>previous page</a></div>";
    header("location:accessories.php");
}

?>
<!doctype html>
<html lang="en">
 <?php include("head.html"); ?>
    <body class="entry">
        <h1>Video Game Collection</h1>
        <?php include("header.html"); ?>
        <h2>Edit Entry</h2>
        <span><?php echo $message; ?></span>
        <div class="buttonA"> <!--Make look like a button -->
            <a href=accessories.php>Go Back</a>
        </div>
        
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
                            <input type="hidden" name="id2" value="<?php echo $id; ?>">
                            <input value="<?php echo $accessoryName; ?>" type="text" name="accessoryName" maxlength="300" required>
                        </td>
                        <td>
                            <input value="<?php echo $originalPlatform; ?>" type="text" name="originalPlatform" maxlength="100" id="platform">
                        </td>
                        <td>
                            <input value="<?php echo $connectivity; ?>" type="text" name="connectivity" maxlength="200">
                        </td>
                        <td>
                            <select name="type" onchange="addFieldType(this.value)">
                                <?php
                                $co = "";
                                $ao = "";
                                $ms = "";
                                $cb = "";
                                $nf = "";
                                $ot = "";
                                $other = false;
                                if($type == "Controller"){
                                    $co = "selected";
                                }
                                elseif($type == "Add-On"){
                                    $ao = "selected";
                                }
                                elseif($type == "Memory Storage"){
                                    $ms = "selected";
                                }
                                elseif($type == "Cable"){
                                    $cb = "selected";
                                }
                                elseif($type == "NFC Figure"){
                                    $nf = "selected";
                                }
                                else{
                                    $ot = "selected";
                                    $other = true;
                                }
                                echo "<option $co value='Controller'>Controller</option>
                                <option $ao value='Add-On'>Add-On</option>
                                <option $ms value='Memory Storage'>Memory Storage</option>
                                <option $cb value='Cable'>Cable</option>
                                <option $nf value='NFC Figure'>NFC Figure</option>
                                <option $ot value='Other'>Other</option>";
                                ?>
                            </select>
                            <?php
                            if($other){
                                echo "<div id='Other' >
                                        <label>Please Specify:</label>
                                        <input value='$type' type='text' name='type' maxlength='200'>
                                     </div>";
                            }
                            else{
                              echo "<div id='Other'></div>";  
                            }
                            ?>
                        </td>
                        <td>
                            <input value="<?php echo $quantity; ?>" type="text" name="quantity" maxlength="4" size="1" value="1" required>
                        </td>
                        <td>
                            <input value="<?php echo $comments; ?>" type="text" name="comments" maxlength="300">
                        </td>
                    </tr>
                       
            </table>
             <input type="submit" name="update" value="Update">
        </form>
        <form method="post">
            <input type="hidden" name="id3" value="<?php echo $id; ?>">
            <input value="Delete" type="submit" name="delete">
        </form>
        <?php include("scripts.php"); ?>
        <?php $conn->close(); ?>
    </body>
</html>
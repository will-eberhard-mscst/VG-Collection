<?php
//open DB connection
include("dbconnect.php");
include("check_login.php");
//error message
$message = "";

//set variables to null
$id = "";
$shortName = "";
$fullName = "";
$model = "";
$developer = "";
$releaseDate = "";
$region = "";
$quantity = "";
$comments = "";

//when they first land on the edit page
if(isset($_POST["id1"])){
    $id = $_POST["id1"];
    $stmt = $conn->prepare("SELECT * FROM platforms WHERE platformID = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $shortName = $row["shortName"];
            $fullName = $row["fullName"];
            $model = $row["model"];
            $developer = $row["developer"];
            $releaseDate = $row["releaseDate"];
            $region = $row["region"];
            $quantity = $row["quantity"];
            $comments = $row["comments"];
        }
    }
    else{
        $message = "<div class='alert'>Error: No data found. Go back to the <a href='platforms.php'>previous page</a></div>";
    }
}

//part 2. Update
if(isset($_POST["update"])){
    $id = $_POST["id2"];
    $stmt = $conn->prepare("UPDATE platforms SET shortName=?, fullName=?, model=?, developer=?, releaseDate=?, region=?, quantity=?, comments=? WHERE platformID = $id");
    $stmt->bind_param("ssssssss", $shortName, $fullName, $model, $developer, $releaseDate, $region, $quantity, $comments);
    //set empty strings to null
    foreach ( $_POST as $key => $value) {
        if(trim($value) == ""){
            $_POST["$key"] = null;
        }
    }
    
    //enter all data
    $shortName = $_POST["shortName"];
    $fullName = $_POST["fullName"];
    $model = $_POST["model"];
    $developer = $_POST["developer"];
    $releaseDate = $_POST["releaseDate"];
    $region = $_POST["region"];
    $quantity = $_POST["quantity"];
    $comments = $_POST["comments"];
    
    if($stmt->execute()){
        $message = "<div class='success'>Record updated successfully</div>";
    }
    else{
        $message = "<div class='alert'>Failed to update record. Make sure the date is in the right format.</div>";
    }
}

//part 2. Delete record
if(isset($_POST["delete"])){
    $id = $_POST["id3"];
    $stmt = $conn->prepare("DELETE FROM platforms WHERE platformID = ?");
    $stmt->bind_param("s", $id);
    if($stmt->execute()){
        $_SESSION["message"] = "<div class='success'>Record has been deleted</div>";
        header("location:platforms.php");
    }
    else{
        $message = "<div class='alert'>Error: Failed to Delte record. Try again.</div>";
    }
}

if(trim($id) == ""){ //if you access the page without clicking Edit
    $message = "<div class='alert'>Error: No data found. Go back to the <a href='platforms.php'>previous page</a></div>";
    header("location:platforms.php");
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
            <a href=platforms.php>Go Back</a>
        </div>
        
        <!--
        platformID int not null auto_increment,
        shortName varchar(100) not null,
        fullName varchar(300) not null,
        model varchar(100) not null,
        developer varchar(200),
        releaseDate date,
        region varchar(100),
        quantity int not null,
        comments varchar (300),
        username varchar(50) not null,
        -->
        
        <form method="post">
            <table>
                <caption>Required fields*</caption>
                <tr>
                    <th>Short Name*</th>
                    <th>Full Name*</th>
                    <th>Model*</th>
                    <th>Developer</th>
                    <th>Release Date (YYY-MM-DD)</th>
                    <th>Region</th>
                    <th>Quantity*</th>
                    <th>Comments</th>
                </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="id2" value="<?php echo $id; ?>">
                            <input value="<?php echo $shortName; ?>" type="text" name="shortName" maxlength="100" required>
                        </td>
                        <td>
                            <input value="<?php echo $fullName; ?>" type="text" name="fullName" maxlength="300" required>
                        </td>
                        <td>
                            <input type="text" name="model" maxlength="100" value="<?php echo $model; ?>">
                        </td>
                        <td>
                            <input value="<?php echo $developer; ?>" type="text" name="developer" maxlength="200">
                        </td>
                        <td>
                            <input value="<?php echo $releaseDate; ?>" type="text" name="releaseDate" maxlength="10" placeholder="YYYY-MM-DD">
                        </td>
                        <td>
                            <select name="region" id="region" onchange="addFieldRegion(this.value)">
                                <?php
                                $na = "";
                                $eu = "";
                                $jp = "";
                                $sk = "";
                                $ot = "";
                                $other = false;
                                if($region == "North America"){
                                    $na = "selected";
                                }
                                elseif($region == "Europe/Oceania"){
                                    $eu = "selected";
                                }
                                elseif($region == "Japan"){
                                    $jp = "selected";
                                }
                                elseif($region == "South Korea"){
                                    $sk = "selected";
                                }
                                else{
                                    $ot = "selected";
                                    $other = true;
                                }
                                echo "<option $na value='North America'>North America</option>
                                <option $eu value='Europe/Oceania'>Europe/Oceania</option>
                                <option $jp value='Japan'>Japan</option>
                                <option $sk value='South Korea'>South Korea</option>
                                <option $ot value='Other'>Other</option>";
                                ?>
                            </select>
                            <?php
                            if($other){
                                echo "<div id='Other' >
                                        <label>Please Specify:</label>
                                        <input value='$region' type='text' name='region' maxlength='100'>
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
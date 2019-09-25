<?php
//open DB connection
include("dbconnect.php");
include("check_login.php");
//error message
$message = "";

//this will enter the data
if(isset($_POST["enter"])){
    $stmt = $conn->prepare("INSERT INTO platforms (shortName, fullName, model, developer, releaseDate, region, quantity, comments, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $shortName, $fullName, $model, $developer, $releaseDate, $region, $quantity, $comments, $username);
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
    $username = $user;
    //checks if the SQL failed
    if($stmt->execute()){
        $message = "<div class='success'>New records created successfully</div>";
    }
    else{
        $message = "<div class='alert'>Failed to enter record. Make sure the date is in the right format.</div>";
    }
}

?>

<!doctype html>
<html lang="en">
 <?php include("head.html"); ?>
    <body class="entry">
        <h1>Video Game Collection</h1>
        <?php include("header.html"); ?>
        <h2>Add Platforms</h2>
        <div class="buttonA"> <!--Make look like a button -->
            <a href=platforms.php>Go Back</a>
        </div>
        <span><?php echo $message; ?></span>
        
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
                            <input type="text" name="shortName" maxlength="100" required placeholder="Ex. N64, PS2, etc">
                        </td>
                        <td>
                            <input type="text" name="fullName" maxlength="300" size="30" required placeholder="Ex. Nintendo 64, PlayStation 2, etc">
                        </td>
                        <td>
                            <input type="text" name="model" maxlength="100" value="Original">
                        </td>
                        <td>
                            <input type="text" name="developer" maxlength="200">
                        </td>
                        <td>
                            <input type="text" name="releaseDate" maxlength="10" placeholder="YYYY-MM-DD">
                        </td>
                        <td>
                            <select name="region" id="region" onchange="addField(this.value)">
                                <option value="North America">North America</option>
                                <option value="Europe/Oceania">Europe/Oceania</option>
                                <option value="Japan">Japan</option>
                                <option value="South Korea">South Korea</option>
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
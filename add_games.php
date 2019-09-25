<?php
//open DB connection
include("dbconnect.php");
include("check_login.php");
//error message
$message = "";

//this will enter the data
if(isset($_POST["enter"])){
    $stmt = $conn->prepare("INSERT INTO games (title, platform, medium, developers, publishers, releaseDate, region, quantity, comments, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $title, $platform, $medium, $developers, $publishers, $releaseDate, $region, $quantity, $comments, $username);
    //set empty strings to null
    foreach ( $_POST as $key => $value) {
        if(trim($value) == ""){
            $_POST["$key"] = null;
        }
    }
    
    //enter all data
    $title = $_POST["title"];
    $platform = $_POST["platform"];
    $medium = $_POST["medium"];
    $developers = $_POST["developers"];
    $publishers = $_POST["publishers"];
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
        <h2>Add Games</h2>
        <div class="buttonA"> <!--Make look like a button -->
            <a href=games.php>Go Back</a>
        </div>
        <span><?php echo $message; ?></span>
        
        <!--
        gameID int not null auto_increment,
        title varchar(150) not null,
        platform varchar(100) not null,
        medium enum ("Physical", "Digital") not null,
        developers varchar(200),
        publishers varchar(200),
        releaseDate date,
        region varchar(100),
        quantity int not null,
        comments varchar(300),
        username varchar(50) not null,
        primary key (gameID)
        -->
        <!-- This creates on autocomplete field for Platforms -->
        <?php include("autocomplete_platforms.php") ?>
        
        <form method="post">
            <table>
                <caption>Required fields*</caption>
                <tr>
                    <th>Title*</th>
                    <th>Platform*</th>
                    <th>Medium*</th>
                    <th>Developers</th>
                    <th>Publishers</th>
                    <th>Release Date (YYY-MM-DD)</th>
                    <th>Region</th>
                    <th>Quantity*</th>
                    <th>Comments</th>
                </tr>
                    <tr>
                        <td>
                            <input type="text" name="title" maxlength="150" required>
                        </td>
                        <td>
                            <input type="text" name="platform" maxlength="100" id="platform" required>
                        </td>
                        <td>
                            <select required name="medium">
                                <option value="Physical">Physical</option>
                                <option value="Digital">Digital</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="developers" maxlength="200">
                        </td>
                        <td>
                            <input type="text" name="publishers" maxlength="200">
                        </td>
                        <td>
                            <input type="text" name="releaseDate" maxlength="10" placeholder="YYYY-MM-DD">
                        </td>
                        <td>
                            <select name="region" id="region" onchange="addFieldRegion(this.value)">
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
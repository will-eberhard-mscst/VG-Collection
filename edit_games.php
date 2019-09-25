<?php
//open DB connection
include("dbconnect.php");
include("check_login.php");
//error message
$message = "";

//set variables to null
$id = "";
$title = "";
$platform = "";
$medium = "";
$developers = "";
$publishers = "";
$releaseDate = "";
$region = "";
$quantity = "";
$comments = "";

//when they first land on the edit page
if(isset($_POST["id1"])){
    $id = $_POST["id1"];
    $stmt = $conn->prepare("SELECT * FROM games WHERE gameID = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $title = $row["title"];
            $platform = $row["platform"];
            $medium = $row["medium"];
            $developers = $row["developers"];
            $publishers = $row["publishers"];
            $releaseDate = $row["releaseDate"];
            $region = $row["region"];
            $quantity = $row["quantity"];
            $comments = $row["comments"];
        }
    }
    else{
        $message = "<div class='alert'>Error: No data found. Go back to the <a href='games.php'>previous page</a></div>";
    }
}

//part 2. Update
if(isset($_POST["update"])){
    $id = $_POST["id2"];
    $stmt = $conn->prepare("UPDATE games SET title=?, platform=?, medium=?, developers=?, publishers=?, releaseDate=?, region=?, quantity=?, comments=? WHERE gameID = $id");
    $stmt->bind_param("sssssssss", $title, $platform, $medium, $developers, $publishers, $releaseDate, $region, $quantity, $comments);
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
    $stmt = $conn->prepare("DELETE FROM games WHERE gameID = ?");
    $stmt->bind_param("s", $id);
    if($stmt->execute()){
        $_SESSION["message"] = "<div class='success'>Record has been deleted</div>";
        header("location:games.php");
    }
    else{
        $message = "<div class='alert'>Error: Failed to Delte record. Try again.</div>";
    }
}

if(trim($id) == ""){ //if you access the page without clicking Edit
    $message = "<div class='alert'>Error: No data found. Go back to the <a href='games.php'>previous page</a></div>";
    header("location:games.php");
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
            <a href=games.php>Go Back</a>
        </div>
        
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
                            <input type="hidden" name="id2" value="<?php echo $id; ?>">
                            <input value="<?php echo $title; ?>" type="text" name="title" maxlength="150" required>
                        </td>
                        <td>
                            <input value="<?php echo $platform; ?>" type="text" name="platform" maxlength="100" id="platform" required>
                        </td>
                        <td>
                            <select required name="medium">
                                <?php
                                if($medium == "Digital"){
                                    echo '<option value="Physical">Physical</option>
                                          <option value="Digital" selected>Digital</option>';
                                }
                                else{
                                     echo '<option value="Physical" selected>Physical</option>
                                           <option value="Digital">Digital</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input value="<?php echo $developers; ?>" type="text" name="developers" maxlength="200">
                        </td>
                        <td>
                            <input value="<?php echo $publishers; ?>" type="text" name="publishers" maxlength="200">
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
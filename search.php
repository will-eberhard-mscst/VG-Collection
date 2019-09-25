<?php
//open DB connection
include("dbconnect.php");

//error message
$message = "";
//search sql
if(isset($_POST["search"])){
    $searched = $_POST["search"];
    $sql = "SELECT * FROM games WHERE title like '%$searched%' OR platform like '%$searched%'
            OR developers like '%$searched%' OR publishers like '%$searched%'";
    
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        echo "Title\tPlatform\tMedium\tDevelopers\tPublishers\tRelease Date\tRegion\tQuantity\tComments\tUsername<br>";
        while($row = $result->fetch_assoc()) {
             echo $row["title"] . $row["platform"] . $row["medium"] . $row["developers"]
                . $row["publishers"] . $row["region"] . $row["releaseDate"] . $row["quantity"]
                . $row["comments"] . $row["username"] . "<br>";
        }
    } else {
        echo "No data found";
    }
}

$conn->close();
?>

<!doctype html>
<html lang="en">
 <head>
        <title>Search</title>
        <meta charset="utf-8">
    </head>
    <body>
        <span><?php echo $message; ?></span>
        <form method="post">
            <label>Search games:</label>
            <input type="text" name="search">
            <input type="submit" name="enter">
        </form>
    </body>
</html>
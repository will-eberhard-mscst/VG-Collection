<?php
//open DB connection
include("dbconnect.php");
include("check_login_users.php");
//error message
$message = "";

if(isset($_SESSION["message"])){
    $message = $_SESSION["message"];
    $_SESSION["message"] = "";
}

//confirm that a user was searched
if(isset($_GET["user"])){
    $username = $_GET["user"];
    $sql = "SELECT dateCreated FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $dateCreated = $row["dateCreated"];
        }
    }
    else{
        $dateCreated = "No date found";
    }
}
else{
    header("location:users.php");
}

?>

<!doctype html>
<html lang="en">
<?php include("head.html"); ?>
<body>
    <h1>Video Game Collection</h1>
    <h2><?php echo "$username's Page"; ?></h2>
    <?php include("header_user.php"); ?>
    <span><?php echo $message; ?></span>
    <ul>
        <li>Username: <?php echo $username; ?></li>
        <li>Account Created: <?php echo $dateCreated; ?></li>
    </ul>
    <?php include("scripts.php"); ?>
</body>
</html>
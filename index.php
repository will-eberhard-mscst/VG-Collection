 <?php
include("dbconnect.php");
$message = "";
//user data
$dateCreated = "";
//logged in, show games
if(isset($_COOKIE["user"]))
{
    $user = $_COOKIE["user"];
    $sql = "SELECT dateCreated FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
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
    //add log out button
    echo "<nav id='top'>
            <a href='users.php'>Users</a>
            <a href='index.php'>My Page</a>
            <a href='logout.php'>Logout</a>
        </nav>";
}
else{//if not logged in, goto login page
    header("location:login.php");
}

$conn->close();
?>

<!doctype html>
<html lang="en">
<?php include("head.html"); ?>
<body>
    <h1>Video Game Collection</h1>
    <h2><?php echo "$user's Page"; ?></h2>
    <?php include("header.html"); ?>
    <span><?php echo $message; ?></span>
    <ul>
        <li>Username: <?php echo $user; ?></li>
        <li>Account Created: <?php echo $dateCreated; ?></li>
    </ul>
    <?php include("scripts.php"); ?>
</body>
</html>
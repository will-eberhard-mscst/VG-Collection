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

?>

<!doctype html>
<html>
<?php include("head.html"); ?>
    <body>
        <h1>Video Game Collection</h1>
        <h2>User Search</h2>
        <p>Click on a user to view their profile</p>
        <form method="post" class="search">
            <input type="text" name="search" placeholder="Search...">
            <input type="submit" name="enter" value="Search">
        </form>
        <span><?php echo $message; ?></span>
        <?php
        //search users
        if(isset($_POST["search"])){
            $searched = "%{$_POST['search']}%";
            $sql = "SELECT username FROM users WHERE username LIKE ? ORDER BY username";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $searched);
        }
        else{
            $sql = "SELECT username FROM users ORDER BY username";
            $stmt = $conn->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // output data of each row
            echo "<table id='usersTable'>";
            echo "<tr>
                    <th>User</th>
                 </tr>";
            while($row = $result->fetch_assoc()) {
                $username = $row["username"];
                 echo "<tr>". 
                         "<td>" . "<a href='user_page.php?user=$username'>$username</a>" . "</td>" .
                     "</tr>";
            }
            echo "</table>";
        }
        else{
            echo "<p>No data found</p>";
        }
        ?>
        <?php include("scripts.php"); ?>
    </body>
</html>
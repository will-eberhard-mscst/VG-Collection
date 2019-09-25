<?php
//open DB connection
include("dbconnect.php");
//if signed in, goto index.php
if(isset($_COOKIE["user"])){
    header("location:index.php");
}

//error message
$message = "";
//check is there is cookie
if(isset($_POST["login"])){
    if(isset($_POST["username"]) && isset($_POST["password"])){
        $enteredUser = $_POST["username"];
        $enteredPassword = $_POST["password"];
        //search for user
        $stmt = $conn->prepare("SELECT username, userPassword FROM users WHERE username = ?");
        $stmt->bind_param('s', $enteredUser);
        $stmt->execute();
        $result = $stmt->get_result();
        $user;
        if ($result->num_rows > 0) {
            // output data of each row
            $row = $result->fetch_assoc();
            $message = "<div class='alert'>Found User: " . $row["username"] . "</div>";
            $user = $row["username"];
            //check password
            if(password_verify($enteredPassword, $row["userPassword"])){
                $message = "<div>Password is correct!</div>";
                //set cookie for 30 days
                setcookie("user", $user, time() + (86400 * 30)); // 86400 = 1 day
                //goto index.php
                header("location:index.php");
            }
            else{
                $message = "<div class='alert'>This password is incorrect</div>";
            }

        } else {
            $message = "<div class='alert'>User doesn't exist</div>";
        }
    }
    else{ //if both nothing is entered
        $message = "<div class='alert'>Both Fields are required</div>";
    }
}
$conn->close();
?>

<!doctype html>
<html lang="en">
<?php include("head.html"); ?>
    <body class="login">
        <h1>Video Game Collection</h1>
        <h2>Login</h2>
        <span><?php echo $message; ?></span>
        <form method="post">
            <label>Username:</label>
            <!-- name = "username" is a variable name-->
            <input type="text" name="username">
            <label>Password:</label>
            <input type="password" name="password">
            <input type="submit" name="login">
        </form>
        <div>
            <a href="create_account.php">Create an Account</a>
        </div>
        <div>
            <a href="users.php">Continue without signing in</a>
        </div>
        <?php include("scripts.php"); ?>
    </body>
</html>
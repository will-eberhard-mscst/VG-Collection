<?php
//open DB connection
include("dbconnect.php");
//will need to verify that the username is valid
$message = "";
//used to see if username is open
$nameFree = false;
$passwordConfirm = false;
$username = "";
$password = "";
if(isset($_POST["createAccount"])){
    if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["repassword"])){
        //check is username and passwords meet certain length
        if(strlen($_POST["username"]) < 3){
            $message = "<div class='alert'>Username must use 3 or more characters.</div>";
        }
        else if(strlen($_POST["password"]) < 8 || strlen($_POST["repassword"]) < 8){
            $message = "<div class='alert'>Password must use 8 or more characters.</div>";
        }
        else if($_POST["username"] == $_POST["password"]){
            $message = "<div class='alert'>Your password cannot be the same as your username.</div>";
        }
        else{
            //check if username is taken
            $enteredUsername = $_POST["username"];
            $stmt = $conn->prepare("SELECT username, userPassword FROM users WHERE username = ?");
            $stmt->bind_param('s', $enteredUsername);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $message = "<div class='alert'>The username " . $row["username"] . " is already taken. Enter a different username.</div>";
                $user = $row["username"];
            }
            else{//if username is not taken
                $nameFree = true;
                $username = $_POST["username"];
            }

             //check if password equals repassword
            if($_POST["password"] == $_POST["repassword"]){
                $passwordConfirm = true;
                $password = $_POST["password"];
            }
            else{
                $message = "<div class='alert'>Your password does not match the re-entered password.</div>";
            }
            //if all is correct
            if($nameFree && $passwordConfirm){
                //encrypt password
                $hash = password_hash($password, PASSWORD_DEFAULT);
                //create user
                $type = "user";
                $today = date("Y-m-d");
                $stmt = $conn->prepare("INSERT INTO users (username, userPassword, userType, dateCreated) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $hash, $type, $today);
                if ($stmt->execute()) {
                    $message = "<div class='alert'>User created!</div>";
                    //create cookie
                    setcookie("user", $username, time() + (86400 * 30)); // 86400 = 1 day
                    //goto index.php
                    header("location:index.php");
                } else {
                    $message = "<div class='alert'>Error with SQL</div>";
                }
            }
        }
    }
    else{//if one field id empty
        $message = "<div class='alert'>All Fields are required</div>";
    }
}

$conn->close();
?>

<!doctype html>
<html lang="en">
    <?php include("head.html"); ?>
    <body class="login">
        <h1>Video Game Collection</h1>
        <h2>Create an Account</h2>
        <div>Create a username and password</div>
        <span><?php echo $message; ?></span>
        <form method="post">
            <label>Username:</label>
            <input type="text" name="username">
            <label>Password:</label>
            <input type="password" name="password">
            <label>Re-enter Password:</label>
            <input type="password" name="repassword">
            <input type="submit" name="createAccount">
        </form>
        <div>Password must have 8 or more characters</div>
        <div>Have an account already? Log in instead</div>
        <div><a href="login.php">Log In</a></div>
        <?php include("scripts.php"); ?>
    </body>
</html>
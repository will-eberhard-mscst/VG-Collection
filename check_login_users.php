<?php
//Checks if the user is logged in
if(isset($_COOKIE["user"])){
    //add log out button
    $user = $_COOKIE["user"];
    echo 
        "<nav id='top'>
            <a href='users.php'>Users</a>
            <a href='index.php'>My Page</a>
            <a href='logout.php'>Logout</a>
        </nav>";
}
else{
    echo 
        "<nav id='top'>
            <a href='users.php'>Users</a>
            <a href='login.php'>Login</a>
        </nav>";
}
?>
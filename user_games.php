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
    <h2><?php echo "$username's Games"; ?></h2>
    <?php include("header_user.php"); ?>
    <form method="post">
        <input type="text" name="search">
        <select name="criteria">
            <option value="title">Title</option>
            <option value="platform">Platform</option>
            <option value="medium">Medium</option>
            <option value="developers">Developers</option>
            <option value="publishers">Publishers</option>
            <option value="releaseDate">Release Date</option>
            <option value="region">Region</option>
            <option value="quantity">Quantity</option>
            <option value="comments">Comments</option>
        </select>
        <span title="Show all entries">
            <input type="radio" name="view" value="all" checked>
            <label for="all" >All</label>
        </span>
        <span title="Show entries where Quantity is greater than 0">
            <input type="radio" name="view" value="owned">
            <label for="owend">Owned</label>
        </span>
        <input type="submit" name="enter" value="Search">
    </form>
    <span><?php echo $message; ?></span>
    <?php
    //search games
    if(isset($_POST["search"])){
        $searched = "%{$_POST['search']}%";
        if($_POST["criteria"] == "title"){
            $criteria = "title";
        }
        elseif($_POST["criteria"] == "platform"){
            $criteria = "platform";
        }
        elseif($_POST["criteria"] == "medium"){
            $criteria = "medium";
        }
        elseif($_POST["criteria"] == "developers"){
            $criteria = "developers";
        }
        elseif($_POST["criteria"] == "publishers"){
            $criteria = "publishers";
        }
        elseif($_POST["criteria"] == "releaseDate"){
            $criteria = "releaseDate";
        }
        elseif($_POST["criteria"] == "region"){
            $criteria = "region";
        }
        elseif($_POST["criteria"] == "quantity"){
            $criteria = "quantity";
        }
        elseif($_POST["criteria"] == "comments"){
            $criteria = "comments";
        }

        if($_POST["view"] == "all"){
            $stmt = $conn->prepare("SELECT * FROM games WHERE $criteria LIKE ? AND username = ? ORDER BY title");
            $stmt->bind_param("ss", $searched, $username);
        }
        elseif($_POST["view"] == "owned"){
            $sql = "SELECT * FROM games WHERE $criteria LIKE ? AND username = ? AND quantity > 0 ORDER BY title";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $searched, $username);
        }
    }
    else{
        $sql = "SELECT * FROM games WHERE username = ? ORDER BY title";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // output data of each row
        echo "<table>";
        echo "<tr>
                <th>Title</th>
                <th>Platform</th>
                <th>Medium</th>
                <th>Developers</th>
                <th>Publishers</th>
                <th>Release Date</th>
                <th>Region</th>
                <th>Quantity</th>
                <th>Comments</th>
             </tr>";
        while($row = $result->fetch_assoc()) {
             echo "<tr>". 
                     "<td>" . $row["title"] . "</td>" .
                     "<td>" . $row["platform"] . "</td>" .
                     "<td>" . $row["medium"] . "</td>" .
                     "<td>" . $row["developers"] ."</td>" .
                     "<td>" . $row["publishers"] . "</td>" .
                     "<td>" . $row["releaseDate"] . "</td>" .
                     "<td>" . $row["region"] . "</td>" .
                     "<td>" . $row["quantity"] . "</td>" .
                     "<td>" . $row["comments"] . "</td>" .
                 "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No data found</p>";
    }
    ?>
    <?php include("scripts.php"); ?>
</body>
</html>
<?php
//open DB connection
include("dbconnect.php");
include("check_login.php");
//error message
$message = "";

if(isset($_SESSION["message"])){
    $message = $_SESSION["message"];
    $_SESSION["message"] = "";
}
?>

<!doctype html>
<html lang="en">
 <?php include("head.html"); ?>
    <body>
        <h1>Video Game Collection</h1>
        <h2><?php echo "$user's Platforms"; ?></h2>
        <?php include("header.html"); ?>
        <form method="post" class="search">
            <input type="text" name="search" placeholder="Search...">
            <select name="criteria">
                <option value="name">Name</option>
                <option value="model">Model</option>
                <option value="developer">Developer</option>
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
        <div class="buttonA"> <!--Make look like a button -->
            <a href=add_platforms.php>Add Platforms</a>
        </div>
        <?php
        $totalQty = null;
        //search games
        if(isset($_POST["search"])){
            $searched = "%{$_POST['search']}%";
            if($_POST["criteria"] == "name"){
                $criteria = "shortName like '%$searched%' OR fullName ";
            }
            elseif($_POST["criteria"] == "model"){
                $criteria = "model";
            }
            elseif($_POST["criteria"] == "developer"){
                $criteria = "developer";
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
                $sum = "SELECT sum(quantity) FROM platforms WHERE ($criteria like ?) AND username = ? ORDER BY shortName";
                $stmt = $conn->prepare($sum);
                $stmt->bind_param("ss", $searched, $user);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $totalQty = $row["sum(quantity)"];
                    }
                }
                //normal SQL
                $stmt = $conn->prepare("SELECT * FROM platforms WHERE ($criteria like ?) AND username = ? ORDER BY shortName");
                $stmt->bind_param("ss", $searched, $user);
            }
            elseif($_POST["view"] == "owned"){
                $stmt = $conn->prepare("SELECT * FROM platforms WHERE ($criteria like ?) AND username = ? AND quantity > 0 ORDER BY shortName");
                $stmt->bind_param("ss", $searched, $user);
            }
        }
        else{
            $sum = "SELECT sum(quantity) FROM platforms WHERE username = ? ORDER BY shortName";
            $stmt = $conn->prepare($sum);
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $totalQty = $row["sum(quantity)"];
                }
            }
            //normal SQL
            $sql = "SELECT * FROM platforms WHERE username = ? ORDER BY shortName";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $user);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // output data of each row
            echo "<div>Total results: $result->num_rows</div>";
            echo "<div>Total Quantity: $totalQty";
            echo "<table>";
            echo "<tr>
                    <th>Short Name</th>
                    <th>Full Name</th>
                    <th>Model</th>
                    <th>Developer</th>
                    <th>Release Date</th>
                    <th>Region</th>
                    <th>Quantity</th>
                    <th>Comments</th>
                 </tr>";
            while($row = $result->fetch_assoc()) {
                $ID = $row["platformID"];
                 echo "<tr>". 
                         "<td>" . $row["shortName"] . "</td>" .
                         "<td>" . $row["fullName"] . "</td>" .
                         "<td>" . $row["model"] . "</td>" .
                         "<td>" . $row["developer"] ."</td>" .
                         "<td>" . $row["releaseDate"] . "</td>" .
                         "<td>" . $row["region"] . "</td>" .
                         "<td>" . $row["quantity"] . "</td>" .
                         "<td>" . $row["comments"] . "</td>" .
                         "<td>" . 
                            "<form method='post' action='edit_platforms.php'>
                                <input type='hidden' name='id1' value='$ID'>
                                <input type='submit' name='edit' value='Edit'>
                            </form>" . 
                        "</td>" .
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
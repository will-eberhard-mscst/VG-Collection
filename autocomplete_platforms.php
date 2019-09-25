<?php
//get all platform names for JS
$sql = "SELECT DISTINCT shortName FROM platforms WHERE username = '$user'";
$result = $conn->query($sql);
$platforms; //array
$sendArray = "";
if ($result->num_rows > 0) {
    $i = 0;
    while($row = $result->fetch_assoc()) {
        $platforms[$i] = $row["shortName"];
        $i++;
    }
}
?>
<script type="text/javascript">
    //send array to JS
    var platformTags = <?php echo json_encode($platforms); ?>;
    $( function() {
        $( "#platform" ).autocomplete({
          source: platformTags
        });
      } );
</script>
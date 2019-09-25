//add input if region = other
var other = document.getElementById("Other");
function addFieldRegion(value){
    if(value == "Other"){
       other.innerHTML = "<label>Please Specify:</label><input type='text' name='region' maxlength='100'>";
    }
    else{
        other.innerHTML = "";
    }
}
function addFieldType(value){
    if(value == "Other"){
       other.innerHTML = "<label>Please Specify:</label><input type='text' name='type' maxlength='200'>";
    }
    else{
        other.innerHTML = "";
    }
}

//autocomplete field
//get array from PHP
//var plat = document.getElementsByClassName("getPlatforms");
//var platformTags = [];
//for(var i = 0; i < plat.length; i++){
//    platformTags[i] = plat[i].textContent;
//}
////autocomplete function
// $( function() {
//    $( "#platform" ).autocomplete({
//      source: platformTags
//    });
//  } );

//       <!-- used to send array to JS 
//        <div>
//            <?php
//            foreach($platforms as $name){
//             echo "<div class='getPlatforms hidden'>$name</div>";
//           }
//            ?>
//        </div>
//        -->
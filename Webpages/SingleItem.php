<!DOCTYPE html>
<html>
<head>
    <title>FaceList</title>
    <link rel="stylesheet" type="text/css" href="../Assets/CSS/SingleItem.css">
    <?php
    
    $db = new SQLite3("db/main.db");
    
    $ID = $_GET['ID'];
    
    $result = $db->query('SELECT Title, Body, Date, Price, Location, Call, Text, Phone, Cat, BroadCat, UID FROM Post WHERE ID=' . $ID);
    $res = $result->fetchArray();
    
    $title = $res['Title'];
    $body = $res['Body'];
    $date = $res['Date'];
    $price = $res['Price'];
    $cat = $res['Cat'];
    $broadcat = $res['BroadCat'];
    $UID = $res['UID'];
    
    $result = $db->query('SELECT Path FROM Pictures WHERE PID=' . $ID);
    $pictures = array();
    $i = 0;
    while ($res = $result->fetchArray()) {
        $pictures[$i] = $res['Path'];
        $i++;
    }
    
    
    $main = '<div id="topOptions">';
    //$main .= '<button id="replyButton" onclick="reply(this)">reply</button>';
    $main .= '<button id="replyButton">reply</button>';
    $main .= '<div id="picTitleBox">';
    $main .= '<h2 id="picTitle">$'.$price. ': ' . $title . '</h2>';
    $main .= '</div>';
    $main .= '<div id="posted">Posted:<br>';
    $main .= '<span id="postDate">' . date("m/d/y", $date) . '</span>';
    $main .= '</div></div>';
    $main .= '<div id="picFrame">';
    $main .= '<img src="' . $pictures[0] . '" id="mainPic">';
    $main .= '</div>';
    $main .= '<div id="picSelector">';
    $i = 0;
    foreach ($pictures as $pic) {
        if ($pic == $pictures[0])
            $main .= '<div class="picSelectionBox"><img onmouseover="displayPicture(this)"  class="picSelection" onload="firstDisplay(this)" src="' . $pic . '"></div>';
        else
            $main .= '<div class="picSelectionBox"><img onmouseover="displayPicture(this)" class="picSelection" src="' . $pic . '"></div>';
        $i++;
    }
    $main .= '</div>';
    $main .= '<div id="itemInfo">';
    $main .= '<p>' . $body . '</p>';
    $main .= '</div>';
            
    
    function formatCat($str) {
        $j = 0;
        //capitalize words
        while ($j < strlen($str)) {
            if ($j === 0) {
                if ($str[0] != '_') { 
                    $str = substr($str, 0, $j) . strtoupper($str[$j]) . substr($str, $j+1);
                }
            } else if ($str[$j - 1] === '_' || $str[$j - 1] === '+') {
                $str = substr($str, 0, $j) . strtoupper($str[$j]) . substr($str, $j+1);
            }
            $j++;
        }
        return str_replace("_", " ", $str);
    }
    
    ?>
    
    <script>
        
        var currSelectionDiv;
        
        function firstDisplay(x) {
            document.getElementById("mainPic").src = x.src;
            x.parentNode.style.borderColor = "orange";
            currSelectionDiv = x.parentNode;
        }
        var currSelection = document.getElementById("picSelection0");
        
        function displayPicture(x) {
            document.getElementById("mainPic").src = x.src;
            currSelectionDiv.style.borderColor = "#EEE";    
            x.parentNode.style.borderColor = "orange";
            currSelectionDiv = x.parentNode;
        }
        
        function reply(x) {
            var menu = document.getElementById("replyMenu");
            if (menu.style.visibility === "visible") {
                menu.style.visibility = "hidden";
            } else {
                menu.style.visibility = "visible";
            }
            
        }   
        
    </script>
</head>

<body>
    <div id="header">
        <div id="linkBox">
            <a href="FaceList.html" >FaceList</a> >
            <?php 
                echo '<a href="ItemList.php?broadcat=' . $broadcat . '&index=0" id="broadCategory">' . formatCat($broadcat) . '</a> > '
                       . '<a href="ItemList.php?cat=' . $cat . '&index=0" id="specificCategory">' . formatCat($cat) . '</a>';
            ?>
        </div>
        <div id="postButton">
            <a href="Submission.html">post</a>
        </div>
    </div>
    <div id="main">
        <?php
            echo $main;
        ?>
        <div id="replyMenu">
            
        </div>
    </div>
</body>
</html>

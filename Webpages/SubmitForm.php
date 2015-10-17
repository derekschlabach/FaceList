<?php

$title = htmlspecialchars($_POST["postingTitle"]);
$price = htmlspecialchars($_POST["price"]);
$location = htmlspecialchars($_POST["location"]);
$body = htmlspecialchars($_POST["postingBody"]);
$username = htmlspecialchars($_POST["username"]);
$password = htmlspecialchars($_POST["password"]);


if (array_key_exists("call", $_POST)) {
    $call = 1;
} else {
    $call = 0;
}
if (array_key_exists("text", $_POST)) {
    $text = 1;
} else {
    $text = 0;
}
$phone = htmlspecialchars($_POST["phone1"]) . htmlspecialchars($_POST["phone2"]) . htmlspecialchars($_POST["phone3"]);
if (($broadcat = htmlspecialchars($_POST["broad"])) == "forSale") {
    $cat = htmlspecialchars($_POST["forSaleList"]);
    $broadcat = "for_sale";
} else if (($broadcat = htmlspecialchars($_POST["broad"])) == "community") {
    $cat = htmlspecialchars($_POST["communityList"]);
}



$db = new SQLite3("db/main.db");

//check Username

$query = $db->query('SELECT ID, Password FROM User WHERE Username = "' . $username . '"');
if ($res = $query->fetchArray()) {
    if ($res['Password'] == $password) {
        $UID = $res['ID'];
    
        $insert = 'INSERT INTO Post (Title, Body, Date, Price, Location, Call, Text, Phone, UID, Cat, BroadCat) ' .
                            'VALUES ("' . $title . '", "' . $body . '", ' . time() . ', ' . $price . ', "' .
                                          $location . '", ' . $call . ', ' . $text . ', ' . $phone . ', ' .
                                          $UID . ', "' . $cat . '", "' . $broadcat . '")';
        
        //echo $insert;
        
        if ($db->exec($insert)) {
            $ID = $db->lastInsertRowID();
            foreach ($_FILES['pictures']['tmp_name'] as $i => $tmpname) {
                move_uploaded_file($tmpname, '../PhotoDest/' . $ID . $i);
                if ($tmpname) {
                    $db->exec('INSERT INTO Pictures (PID, Path) VALUES (' . $ID . ', "../PhotoDest/' . $ID . $i . '")');
                }
            }
        }
        
        echo '<META http-equiv="refresh" content="0;URL=FaceList.html">';
        
    } else {
        echo '<META http-equiv="refresh" content="5;URL=Submission.html">';
        echo 'Incorrect Password';
    }
} else {
    echo '<META http-equiv="refresh" content="5;URL=Submission.html">';
    echo 'Incorrect Username';
}

?>

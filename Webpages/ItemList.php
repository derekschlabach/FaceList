<!DOCTYPE html>
<html>
<head>
    <title>FaceList</title>
    <link rel="stylesheet" type="text/css" href="../Assets/CSS/ItemList.css">
    
    <?php
    
    $db = new SQLite3("db/main.db");
    
    // Construct a query based on passed paramater(s)
    if (isset($_GET["broadcat"])) {
        $broadcat = $_GET["broadcat"];
        $query = 'SELECT * FROM Post WHERE BroadCat="' . $broadcat . '"';
        $url = 'ItemList.php?broadcat=' . $broadcat;
    } elseif (isset($_GET["cat"])) {
        $cat = $_GET["cat"];
        if ($cat === 'photo video') $cat = 'photo+video';
        if ($cat === 'toys games') $cat = 'toys+games';
        if ($cat === 'lost found') $cat = 'lost+found';
        $broadcat = $db->querySingle('SELECT BroadCat FROM Cat WHERE Cat="' . $cat .'"');
        $query = 'SELECT * FROM Post WHERE Cat="' . $cat . '"';
        $url = 'ItemList.php?cat=' . $cat;
    } if (isset($_GET["search"])) {
        $search = $_GET["search"];         //SQL LIKE
        
        $query .= ' AND (Title = "%' . $search . '%" OR' .
                        'Body = "%' . $search . '%" OR' .
                        'Location = "%' . $search . '%")';
    }
    $index = intval($_GET["index"]);
    $itemsPerPage = 50;
    $firstItem = $index * $itemsPerPage;
    $lastItem = $firstItem + $itemsPerPage;
    $i = 0;
    $allItems = array();
    
    
    $results = $db->query($query);
    $print = '';
    while ($res = $results->fetchArray()) {
        
        if (!isset($res['ID'])) continue;
        
        $ID = $res['ID'];
        $price = $res['Price'];
        $date = $res['Date'];
        $caption = $res['Title'];
        $location = $res['Location'];
        $category = $res['Cat'];
        
        
        $picPath = $db->querySingle('SELECT Path FROM Pictures WHERE PID=' . $ID);
        
        $allItems[$i] = formatDisplay($ID, $price, $date, $caption, $location, $category, $picPath);
        $i++;
        
    }
    
    $totalItems = count($allItems);
    $lastItem = ($lastItem <= $totalItems ? $lastItem : $totalItems);
    $prevUrl = ($index > 0) ? $url . '&index=' . ($index - 1) : '';
    $nextUrl = ($lastItem < $totalItems) ? $url . '&index=' . ($index + 1) : '';
    
    $i = 0;
    $itemToPrint = $firstItem;
    while ($i < $itemsPerPage && $itemToPrint < $totalItems) {
        $print .= $allItems[$itemToPrint];
        $i++;
        $itemToPrint++;
    }
    
    function formatDisplay($ID, $price, $date, $caption, $location, $category, $picPath) {
        $return = '<div class="itemFrame">';
        $return .= '<div class="price">';
        $return .= '<span id="bigPrice">$' . htmlspecialchars($price) . '</span>';
        $return .= '</div>';
        $return .= '<div class="picture">';
        $return .= '<a href="SingleItem.php?ID=' . htmlspecialchars($ID) . '">';
        $return .= '<img src="' . htmlspecialchars($picPath) . '">';
        $return .= '</a>';
        $return .= '</div>';
        $return .= '<div class="caption">';
        $return .= '<span class="smallPrice">$' . htmlspecialchars($price) . '</span>';
        $return .= '<span class="date"> ' . htmlspecialchars($date) . '</span>';
        $return .= '<a href="SingleItem.php?ID=' . htmlspecialchars($ID) . '" id="picTitle"> ' . htmlspecialchars($caption) . '</a>';
        $return .= ' (<span id="location">' . htmlspecialchars($location) . '</span>) ';
        $return .= '<a href="ItemList.php?cat=' . htmlspecialchars($category) . '&index=0" id="category">' . htmlspecialchars($category) . '</a>';
        $return .= '</div>';
        $return .= '</div>';
        
        return $return;
    }
    
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
</head>

<body>
    <div id="header">
        <div id="linkBox">
            <a href="FaceList.html" >FaceList</a> >
            
            <?php
                if (isset($cat)) {
                    echo '<a href="ItemList.php?broadcat=' . $broadcat . '&index=0" id="broadCategory">' . formatCat($broadcat) . '</a> > '
                       . '<a href="ItemList.php?cat=' . $cat . '&index=0" id="specificCategory">' . formatCat($cat) . '</a>';
                } else {
                    echo '<a href="ItemList.php?broadcat=' . $broadcat . '&index=0" id="broadCategory">' . formatCat($broadcat) . '</a>';
                }
            ?>
        </div>
        <div id="pageCounter">
            <div id="pageLeft"><?php
                if ($prevUrl === '')
                    echo '< prev';
                else
                    echo '<a href="' . $prevUrl . '">< prev</a>';
            ?></div>
            <div id="pageCenter"><?php echo (($lastItem != 0) ? $firstItem + 1 : 0) . ' - ' . $lastItem . ' of ' . $totalItems; ?></div>
            <div id="pageRight"><?php
                if ($nextUrl === '')
                    echo 'next >';
                else
                    echo '<a href="' . $nextUrl . '">next ></a>';
            ?></div>
        </div>
        <div id="postButton">
            <a href="Submission.html">post</a>
        </div>
    </div>
    
    <?php
    
    echo $print;

    ?>

</body>



</html>

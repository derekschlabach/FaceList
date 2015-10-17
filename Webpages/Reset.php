<!DOCTYPE html>
<html>
    <head>
        <META http-equiv="refresh" content="0;URL=FaceList.html">
    </head>
    <body>
        <?php
        
        $db = new SQLite3("db/main.db");
        
        $db->exec("DROP TABLE Post");
        $db->exec("DROP TABLE USER");
        $db->exec("DROP TABLE Pictures");
        
        $db->exec("CREATE TABLE IF NOT EXISTS Post(ID INTEGER PRIMARY KEY, 
                                                    Title VARCHAR(255), 
                                                    Body VARCHAR(255),
                                                    Date INTEGER,
                                                    Price INTEGER,
                                                    Location VARCHAR(255),
                                                    Call INTEGER,
                                                    Text INTEGER,
                                                    Phone INTEGER,
                                                    UID INTEGER,
                                                    Cat VARCHAR(255),
                                                    BroadCat VARCHAR(255))");

        $db->exec("CREATE TABLE IF NOT EXISTS User(ID INTEGER PRIMARY KEY,
                                                        Username VARCHAR(255),
                                                        Password VARCHAR(255))");

        $db->exec("CREATE TABLE IF NOT EXISTS Pictures(ID INTEGER PRIMARY KEY,
                                                        Path VARCHAR(255),
                                                        PID INTEGER)");
        
        $db->exec("CREATE TABLE IF NOT EXISTS Cat(ID INTEGER PRIMARY KEY,
                                                    Cat VARCHAR(255),
                                                    BroadCat VARCHAR(255))");
        
        
        
        
        $community = ["activities", "artists", "events", "general", "housing", "jobs", "lost+found", "musicians", "rideshare", "sports"];
        
        $forSale = ["bikes", "books", "electronics", "free", "furniture", "general", "household", "jewelry", "musical_instruments", "photo+video", "sporting", "tickets", "toys+games", "video_games", "wanted"];
        
        
        
        if (($query = $db->querySingle('SELECT COUNT(*) FROM User')) == 0) {
            $db->exec('INSERT INTO User (Username, Password) VALUES ("Derek", "Derek")');
        }
        
        if (($query = $db->querySingle('SELECT COUNT(*) FROM Cat')) == 0) {
            foreach ($community as $i => $val) {
                insertCat($db, $i + 1, $val, 'community');
            }
        
            foreach ($forSale as $j => $val) {
                insertCat($db, $j + $i + 2, $val, 'for_sale');
            }
        }
        
        function insertCat($db, $index, $cat, $broadcat) {
            $db->exec('INSERT INTO Cat (ID, Cat, BroadCat) VALUES (' . $index . ', "' . $cat . '", "' . $broadcat . '")');
        }
        ?>

    </body>
</html>

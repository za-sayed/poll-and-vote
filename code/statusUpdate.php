<?php
try {
    require("connection.php");
    // text due date , varchar status
    $qToUpdate = $db->query("select * from questions where duedate != '' and status ='active' ");
    date_default_timezone_set("Asia/Bahrain");
    while ($r = $qToUpdate->fetch(PDO::FETCH_NUM)) {
        if (strtotime($r[4]) < time())
            $db->exec("update questions set status='inactive' where qid = $r[0]");
    }
    $db = null;
} catch (PDOException $ex) {
    die("Error:" . $ex->getMessage());
}

?>
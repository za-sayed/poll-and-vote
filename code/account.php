<?php
try {
    require("authorization.php");
    require("statusUpdate.php");
    require("connection.php");
    $uid = $_SESSION['uid'];
    $ud = $db->query("select * from user where uid=$uid");
    $ru = $ud->fetch();
    if (isset($_POST['stop']))
        $db->exec("update questions set status ='inactive' where qid = " . $_POST['qid']);
    else if (isset($_POST['signOut'])) {
        unset($_SESSION['uid']);
        header("Location:login.php");
    } else if (isset($_POST['delete'])) {
        $db->beginTransaction();
        $qid = $_POST['qid'];
        $db->exec("delete from votes where qid=$qid");
        $db->exec("delete from choices where qid=$qid");
        $db->exec("delete from questions where qid=$qid");
        $db->commit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Signika:wght@300;400;500;600&display=swap" rel="stylesheet">
        <!-- website icon -->
        <link rel="icon" href="favicon.ico">
        <!-- css file styling -->
        <link rel="stylesheet" href="AHstyle.css">
        <!-- icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        <title>Profile</title>
    </head>

    <body>
    <header>
        <div>
            <img src="favicon.ico" alt="page logo" style="height:20px;">
            Poll and Vote
        </div>
        <div class="icons">
            <a href="home.php"><span class="material-symbols-outlined">home</span></a>
            <a href="createPoll.php"><span class="material-symbols-outlined">add_circle</span></a>
            <a href="account.php"><span class="material-symbols-outlined">person</span></a>
        </div>

    </header>
        <!-- user information -->
        <nav>
            <p>Email</p>
                    <p>
                        <?php
                        echo $ru[2];
                        ?>
                    </p>
                    <p>User Name</p>
                    <p>
                        <?php
                        echo $ru[1];
                        ?>
                    </p>
                    <form method="post">
                        <button type="submit" name="signOut" id="signOut">
                            <span class="material-symbols-outlined">logout</span>Sign Out
                        </button>
                    </form>
        </nav>
        <h3 class="cpt">Created Polls</h3>
        <main>
            
            <?php
            $uqs = $db->query("select * from questions where uid=$uid");
            while ($ruq = $uqs->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='poll'> <form method='post'><input type='hidden' name='qid' value='" . $ruq['qid'] . "'>";
                if ($ruq['status'] == "active")
                    echo "<div id='status'> <span class='material-symbols-outlined' style='color:green'>check_circle</span><p style='color:green'>Active</p></div>";
                else
                    echo "<div id='status'> <span class='material-symbols-outlined'style='color:red'>block</span><p style='color:red'>Inactive</p></div>";
                echo "<br/><p>" . $ruq['question'] . "</p>";
                $qv = $db->query("select * from votes where qid=$ruq[qid]");
                # total question votes
                $tqv = $qv->rowCount();

                $qch = $db->query("select * from choices where qid=$ruq[qid]");
                while ($ch = $qch->fetch(PDO::FETCH_ASSOC)) {
                    $chv = $db->query("select * from votes where qid=$ruq[qid] and chid=$ch[chid]");
                    # total choice votes
                    $nv = $chv->rowCount();
                    if ($tqv == 0)
                        $percentage = 0;
                    else
                        $percentage = ($nv / $tqv) * 100;
                    echo "<label for='chid'>" . $ch['choice'] . "</label><span id='percentage'>$percentage%($nv votes)</span>
                    <br/> <progress id='chid' max='" . $tqv . "' value='" . $nv . "'></progress><br/>";
                }
                echo "<p class='total'>Total votes : " . $tqv . "</p>";
                if ($ruq['dueDate'] == null) {
                    if ($ruq['status'] == "active")
                        echo "<input type='submit' name='stop' value='Click to Stop' onclick='return confirm(\"You are stoping the poll\")' />";
                    else
                        echo "<p class='dm'>Has been stopped manually</p>";
                } 
                else {
                    echo "<p class='dm'>End date : " . $ruq['dueDate']."</p>";
                }
                echo "<button type='submit' id='delete' name='delete' onclick='return confirm(\"You are deleting the poll\")'>Delete the poll</button>";
                echo "</form></div>";
            }
            ?>
        </main>

    </body>

    </html>
    <?php
    $db = null;
} catch (PDOException $ex) {
    die("Error:" . $ex->getMessage());
}
?>
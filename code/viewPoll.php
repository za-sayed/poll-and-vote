<?php
# to prevent session hijacking and sniffing
ini_set("session.cookie_httponly", 1);
ini_set("session.cookie_secure", 1);
session_start();
if(isset($_SESSION['uid']))
    $user = $_SESSION['uid'];
else
    $user = null;
require('statusUpdate.php');
            try {
                # open connection
                require('connection.php');

                # check if the user voted
                function voted($userID, $questionID){
                    $rows = $GLOBALS['db']->prepare("SELECT * FROM votes WHERE uid = ? AND qid = ?;");
                    $rows->execute(array($userID, $questionID));
                    if($vote = $rows->fetch()) 
                        return $vote['chid'];
                    else
                        return null;
                }
                # validating inputs
                function test_input($data){
                    $data = trim($data); // remove unnecessary spaces
                    $data = stripslashes($data); // remove \ 
                    $data = htmlspecialchars($data); // change characters
                    return $data;
                }

                # insert votes into the database 
                if(isset($_POST['vote']) && isset($_POST['chid']) && isset($_POST['uid'])){
                    $c = test_input($_POST['chid']);
                    $u = test_input($_POST['uid']);
                    $q = test_input($_POST['qid']);
                    $vote = voted($user, $_POST['qid']);
                    if($vote == null){
                        $vote = $db->prepare("INSERT INTO votes VALUES (?,?,?);");
                        $vote->execute(array($u, $q, $c));
                    }
                }

                # check for applied filters
                if(isset($_GET['filter']) && $_GET['filter'] != 'ALL') {
                    $category = test_input($_GET['filter']);
                    $sql = "SELECT * FROM questions WHERE category = ? ORDER BY qid DESC;";
                    $questions = $db->prepare($sql);
                    $questions->execute(array($category));
                }
                elseif(isset($_GET['status'])){
                    $status = test_input($_GET['status']);
                    $sql = "SELECT * FROM questions WHERE status = ? ORDER BY qid DESC;";
                    $questions = $db->prepare($sql);
                    $questions->execute(array($status));
                }
                else {
                    $sql = "SELECT * FROM questions ORDER BY qid DESC;";
                    $questions = $db->prepare($sql);
                    $questions->execute();
                }

                # search query
                if(isset($_GET['text'])){
                    $text = test_input($_GET['text']);
                    $sql = "SELECT * FROM questions WHERE question LIKE ? ORDER BY qid DESC;";
                    $questions = $db->prepare($sql);
                    $questions->bindValue(1, "%$text%");
                    $questions->execute();
                }

                # retrieve choices from the database #
                $choices = $db->prepare ("SELECT * FROM choices WHERE qid = ?");
                # loop through the questions
                foreach($questions as $question) {
                    $choices->execute(array($question['qid']));
                    if($user!=0)    $vote = voted($user, $question['qid']);
                    # in all cases the question and end date are appearing
                    echo "
                        <div class='poll' id='$question[qid]'> 
                            <p>
                            $question[question] <br>";
                    if($question['dueDate'] != null)
                        echo"<span id='date'> end date: $question[dueDate] </span> <br>";
                    echo "</p>";
                        
                    # click results button or questions status is inactove or the user already voted 
                    if(isset($_POST[$question['qid']]) || $question['status'] == "inactive" || isset($vote)){
                        # count total votes on a question
                        $totalVotes = $db->prepare("SELECT COUNT(qid) FROM votes WHERE qid = ?;");
                        $totalVotes->execute(array($question['qid']));
                        if($total = $totalVotes->fetch()) $tv = $total[0];
                        # count total choice votes of a question
                        $choiceVotes = $db->prepare("SELECT COUNT(chid) FROM votes WHERE chid = ?;");
                    }
                    # a form for active, not voted questions
                    else {
                        $url = "home.php?" . htmlspecialchars($_SERVER['QUERY_STRING']) . "#" . $question['qid'];
                        echo "
                            <form class='pollForm' method='post' onsubmit='return validateUser()' action='$url'>
                        ";
                    }
                    # looping through the choices of every question
                    foreach($choices as $choice){
                        if((isset($_POST[$question['qid']]) || $question['status'] == "inactive" || isset($vote))){
                            $choiceVotes->execute(array($choice['chid']));
                            if($votes = $choiceVotes->fetch()) 
                                $vs = $votes[0];
                            if($tv == 0)
                                $percentage = 0;
                            else
                                $percentage = ($vs/$tv)*100;
                            echo "
                                <label for='chid'>$choice[choice]</label> <span id='percentage'>$percentage%($vs votes)</span> <br>
                                <progress id='chid' value=$vs max=$tv></progress>
                            ";
                            # checking the choice with the vote 
                            if(isset($vote) && ($vote == $choice['chid'])){
                                echo "
                                &check;
                                ";
                            }
                            echo "<br>";
                        }
                        else {
                            echo "
                                    <input type='radio' value='$choice[chid]' name='chid' id='$choice[chid]'> 
                                    <label for='$choice[chid]' class='radioLabels'>$choice[choice]</label> <br>
                                ";
                        }
                    }
                    # show a back button after clicking the results button
                    if(isset($_POST[$question['qid']])){
                        echo "
                            <form method='post'>
                                <input type='submit' value='back' name='back'>
                            </form>
                            ";
                    }
                    elseif($question['status'] != "inactive" && !isset($vote))
                        echo "
                            <br>
                            <input type='hidden' value='$question[qid]' name='qid' id='qid'>
                            <input type='hidden' value='$user' name='uid' id='uid'>
                            <input type='submit' value='vote' name='vote'>
                            <input type='submit' value='results' name='$question[qid]' >
                            </form>
                            ";
                    else
                        echo "<span class='total'>total votes = $tv</span><br>";
                    echo "</div>";
                }

                $db = null;
            }
            catch (PDOException $e) {
                die($e->getMessage());
            }
?>
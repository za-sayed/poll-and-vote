<?php
    require('authorization.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Poll</title>
    <!-- css file -->
    <link href="CPstyle.css" rel="stylesheet"/>
    <!-- website icon -->
    <link rel="icon" href="favicon.ico">
    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Signika:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
</head>
<body style="font-family: 'Signika', sans-serif;">
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
<?php
    function test_input($data){
        $data = trim($data); // remove unnecessary spaces
        $data = stripslashes($data); // remove \ 
        $data = htmlspecialchars($data); // change characters
        return $data;
    }
    if(isset($_POST["sub"])){
        $que = test_input($_POST["question"]);
        $cat = test_input($_POST["category"]);
        $opt = $_POST["option"];
        $rad = test_input($_POST['radio']);
        if ($rad == 'stop') {$due = '';}
        else{
            # formatting the date before saving it to the database
            $due = date("Y-m-d H:i:s", strtotime($_POST["datetime"]));
        } 
        try{
            require('connection.php');
            $db->beginTransaction();

            $stmt = $db->prepare("INSERT INTO questions (uid, question, category, duedate, status) VALUES (:uid, :question, :category, :duedate, 'active')");
            $stmt->bindParam(':uid', $_SESSION['uid']);
            $stmt->bindParam(':question', $que);   
            $stmt->bindParam(':category', $cat);
            $stmt->bindParam(':duedate', $due);
            $stmt->execute();
            $insertId = $db->lastInsertId();
            $stm = $db->prepare("INSERT INTO choices (qid, choice) VALUES (:qid, :choice)");
            $stm->bindParam(':qid', $insertId);
            $stm->bindParam(':choice', $option);
            foreach($opt as $option){
                $option = test_input($option);
                $stm->execute();
            }
            $db->commit(); 
            $db = null;  
            header('location:account.php');
        }
        catch (PDOException $ex){
            $db->rollBack();
            echo $ex->getMessage();
        }
    }
?>
    <h1 class="cph">Create a Poll</h1>
    <h4 class="cph">Complete the below fields to create your poll</h4>
    <div class='cpcontainer'>
        <form name="myForm" method='post' onsubmit='return validateForm()'>
                <label class='cplabel'>Category</label><br/>
                <select name="category" class='cpbox'>
                    <option selected >GENERAL</option>
                    <option>SPORTS</option>
                    <option>ART</option>
                    <option>TRAVEL</option>
                    <option>FOOD</option>
                    <option>MOVIES</option>
                    <option>EDUCATION</option>
                    <option>BOOKS</option>
                    <option>VIDEO GAMES</option>
                    <option>TECHNOLOGY</option>
                </select><br/>
                <label class='cplabel'>Question</label><br/>
                <input type='text' name='question' placeholder='Enter Your Question' class='cpbox question' onkeyup="hide('que')"><br/>
                <div class="cpwrong" id="que" style="display: none;">* You must Enter The Question</div>
                <label class='cplabel'>Answer Options</label><br/>
                <div id='option'>
                    <div class="Op">
                        <div class="numop">1</div>
                        <input type='text' name='option[]' placeholder='Option 1' class='opt' onkeyup="hide('opt')"><br/>
                    </div>
                    <div class="Op">
                        <div class="numop">2</div>
                        <input type='text' name='option[]' placeholder='Option 2' class='opt' onkeyup="hide('opt')"><br/>
                    </div>
                    
                </div>
                <div class="cpwrong" id="opt" style="display: none;">* You Must Fill All the Options</div>
                <div class="btnd">
                    <input type='button' name='add' value='Add Option' class='cpbox btn' onclick='AddOption()'/>
                    <input type='button' name='delete' value='Delete Last Option' class='cpbox btn' onclick='DeleteLast()'/><br/><br/>
                </div>

                <label class="cplabel">How would you like to end this Poll?</label><br/>
                <label><input type='radio' name='radio' onclick="disnone()" value='stop' checked> STOP Button</label><br/>
                <label><input type='radio' name='radio' onclick="display()" value='time'> Scheduled End Date</label><br/>
                <div class="cpwrong" id="rad"></div>
                <div id="cptime" style="display:none;">
                    <input type="datetime-local" class='cpbox' value="<?php echo date('Y-m-d\TH:i', strtotime('+1 day')); ?>" name="datetime" class="datetime" id="date1" onclick="hide('time2')"><br/>
                    <div class="cpwrong lcp" id="time2" style="display: none;">* Invalid Time</div>
                </div>
                <button type='submit' name="sub" style="font-family: 'Signika', sans-serif;">CREATE POLL</button>
        </form>
    </div>
    <footer></footer>
        
    <script>
        let x = 2;
        let stop = true;
        function AddOption(){
            if(x == 10){
                alert("The maximum number of options is 10");
            }
            else{
                x++;
                let option = document.querySelector('#option');
                let newDiv = document.createElement('div');
                newDiv.classList.add('Op');

                let numOption = document.createElement('div');
                numOption.classList.add('numop');
                numOption.textContent = x;
                newDiv.appendChild(numOption);

                let inpOption = document.createElement('input');
                inpOption.type = 'text';
                inpOption.name = 'option[]';
                inpOption.placeholder = 'Option ' + x;
                inpOption.classList.add('opt');
                inpOption.onkeyup = function() { hide('opt'); };

                newDiv.appendChild(inpOption);
                newDiv.appendChild(document.createElement('br'));
                option.appendChild(newDiv); 
           }
        }
        function DeleteLast(){
            let option = document.querySelector('#option');
            let lastOption = option.lastChild;
            if (x>2){
                option.removeChild(lastOption);
                x--;
            }
            else{
                alert("You Need To Have at Least 2 Options");
            }
        }  
        function display(){
            stop=false;
            document.getElementById("cptime").style.display="block";
            
        } 
        function disnone(){
            stop=true;
            document.getElementById("cptime").style.display="none";
           
        }  

        function validateForm() {
            let pos = document.forms["myForm"];
            if(pos["question"].value.trim() == ""){
                document.getElementById("que").style.display= "block";
                return false;
            }
            
            for(let i=1; i<x; i++){
                if(pos["option[]"][i].value.trim() == ""){
                    document.getElementById("opt").style.display= "block";
                    return false;
                }
            }

            // the date and time should not be in the past
            if(!stop){
                let input = pos["datetime"].value;
                let dateEntered = new Date(input);
                let now = new Date();
                if (now > dateEntered) {
                    document.getElementById("time2").style.display = "block";
                    return false;
                }
            }

            return true;
        }

        // for hiding the error message if the user write in the input field
        function hide(id){
            document.getElementById(id).style.display= "none";
        }
        
    </script>
</body>
</html>

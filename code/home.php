<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
    <!-- css file -->
    <link rel="stylesheet" href="AHstyle.css"> 
    <!-- website icon -->
    <link rel="icon" href="favicon.ico">
    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Signika:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /> 
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />  
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
            <a href="#" onclick="document.querySelector('.search').style.display='block';"><span class="material-symbols-outlined">search</span></a>
        </div>

    </header>
    <div class="search" style="display:none;">
        <hr>
        <p>Search Questions:</p> <input type="text" onkeyup="lookup(this.value)"><br>
        <p>Search by Category or Status:</p>
        <div class="categories">
            <a href="home.php?filter=ALL">All</a>
            <a href="home.php?filter=SPORTS">SPORTS</a>
            <a href="home.php?filter=ART">ART</a>
            <a href="home.php?filter=TRAVEL">TRAVEL</a>
            <a href="home.php?filter=FOOD">FOOD</a>
            <a href="home.php?filter=MOVIES">MOVIES</a>
            <a href="home.php?filter=EDUCATION">EDUCATION</a>
            <a href="home.php?filter=BOOKS">BOOKS</a>
            <a href="home.php?filter=VIDEO GAMES">VIDEO GAMES</a>
            <a href="home.php?filter=TECHNOLOGY">TECHNOLOGY</a>
            <a href="home.php?filter=GENERAL">GENERAL</a>
            <a href="home.php?status=active">ACTIVE</a>
            <a href="home.php?status=inactive">INACTIVE</a>
        </div>
        <hr>
    </div>
    <!-- error message for unauthorized users -->
    <div id="message" style="display: none; position:fixed; z-index:1; top: 40px; right:0; width: 100%; height: 30px; font-size: 14px;color: white;text-align:center;background-color: #ff6464; padding: 10px 0px 0px 0px; font-weight: 500;">
            <a href="login.php" style="color:white">login</a> or <a href="register.php" style="color:white">register</a> to view results and vote <span class = "closebtn" onclick='cancel()' style="margin-right: 20px; font-weight: bold; float: right; font-size: 20px; line-height: 18px; cursor: pointer;">&times;</span>
    </div>
    <main>
        <?php
            require('viewPoll.php');
        ?>
    </main>
    <script>
        // ajax search function
        function lookup(text){
            const xhttp = new XMLHttpRequest();
            xhttp.onload = polls;
            xhttp.open("GET", "viewPoll.php?text="+text);
            xhttp.send();
        }
        function polls(){
            document.querySelector('main').innerHTML = this.responseText;
        }
        // form submission 
        function validateUser() {
        let user = document.getElementById('uid').value;
        if (user == '') {
            document.getElementById('message').style.display = 'block';
            return false;
        }

        else {
            return true; 
        }
        }

        // remove error message
        function cancel(){
            document.getElementById('message').style.display = 'none';
        }
    </script>
</body>
</html>
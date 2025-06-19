<?php
    # to prevent session hijacking and sniffing
    ini_set("session.cookie_httponly", 1);
    ini_set("session.cookie_secure", 1);
    session_start();

    if (isset($_POST['email'])) 
    {
        try
        {
                require('connection.php');

                $email = $_POST['email'];
                $password = $_POST['password'];
                
                $email = trim($email);
                $email = stripslashes($email);
                $email = htmlspecialchars($email);

                $password = trim($password);
                $password = stripslashes($password);
                $password = htmlspecialchars($password);
        
                $stmt = $db -> prepare("SELECT * FROM user WHERE email = :email");
                $stmt -> bindParam(":email", $email);
                $stmt -> execute();
                $result = $stmt -> fetch();

                // if the email exist in the database and the password is correct
                if ($result !== false && password_verify($password, $result[3])) 
                {
                    $uid = $result[0];
                    $_SESSION['uid'] = $uid;
                    header("Location:Home.php");
                    exit();
                } 
                else
                {
                    $_SESSION['login_error'] =  '* Invalid Email Or Password'; 
                    header("Location: login.php");
                    exit();  
                }
                $db = null;
        }
        catch(PDOException $ex) 
            { 
                die ($ex->getMessage());
            }    
    } 
?>    
<!DOCTYPE html>
<html>
    <head>
        <title> Login </title>
        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
        <!-- css styling file -->
        <link rel = "stylesheet" href = "login.css">
        <!-- website icon -->
        <link rel="icon" href="favicon.ico">
        <!-- icons -->
        <script src = "https://kit.fontawesome.com/7f38d76ae3.js" crossorigin = "anonymous"></script> 
        <!-- font -->
        <link rel = "preconnect" href = "https://fonts.googleapis.com">
        <link rel = "preconnect" href = "https://fonts.gstatic.com" crossorigin>
        <link href = "https://fonts.googleapis.com/css2?family=Signika:wght@400;500;600;700&display=swap" rel ="stylesheet">
    </head>

    <body>
        <div class = "container">
            <form id = "login" onsubmit = "return validateForm()" method = "POST">
                  
                <h1>Login</h1>

                <?php if (isset($_SESSION['registration_success'])): ?>
                    <div class = "alert-S">
                        <span class = "closebtn">&times;</span> 
                        <?php echo "Registration successful!"?>
                    </div>
                    <?php unset($_SESSION['registration_success'])?>
                <?php endif;?> 

                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class = "alert-G">
                        <span class = "closebtn">&times;</span> 
                        <?php echo $_SESSION['login_error']; ?>
                    </div>
                <?php endif;?>   

                <div class = "input-box">
                    <i class = "fa-solid fa-envelope fa-xs"></i>
                    <input type = "text" id = "email" name = "email" placeholder = "Email">
                </div>
                
                <div class = "input-box">
                    <i class = "fa-solid fa-lock fa-xs"></i>
                    <input type = "password" id = "password" name = "password" placeholder = "Password">
                    <span class = "eye-icon">
                        <i class = "fa-regular fa-eye" id = "opened" onclick = "toggle()"></i>
                    </span>
                    <span class = "eye-icon">
                        <i class = "fa-regular fa-eye-slash" id = "closed"  style = "display: none;" onclick = "toggle()"></i>
                    </span>
                </div>

                <button type = "submit" name = "register" id = "btn">Login</button><br>

                <div class = "Register-link">            
                    <p>
                        You don't have an account? <a href = "register.php" id = "reg"> Register </a>
                    </p>
                </div> 

                <div class = "guest-link">        
                    <p> 
                        <a href="Home.php"> Browse as a guest </a>
                    </p>
                </div>

            </form>
            
        </div>
        <script>
            // when the eye is clicked the input type will turn to text instead of password
            function toggle() 
            {
                var password = document.getElementById('password');
                if (password.type === 'password') 
                {
                    password.type = 'text';
                    document.getElementById('opened').style.display = 'none';
                    document.getElementById('closed').style.display = 'inline';
                } 
                else 
                {
                    password.type = 'password';
                    document.getElementById('opened').style.display = 'inline';
                    document.getElementById('closed').style.display = 'none';
                }
            }

            function validateForm() 
            {
                var email = document.getElementById('email').value.trim();
                var password = document.getElementById('password').value.trim();
                if (email === '' || password === '') 
                {
                    alert('Please fill in all fields');
                    return false;
                }
                return true;
            }

            var close = document.getElementsByClassName("closebtn");
            var i;
            for (i = 0; i < close.length; i++) 
            {
                close[i].onclick = function(){
                    var div = this.parentElement;
                    div.style.opacity = "0";
                    setTimeout(function(){ div.style.display = "none"; }, 400);
                }
            }

        </script> 

        <?php
        unset($_SESSION['login_error']);
        ?>  

    </body>
</html>

<?php
    session_start();

    if (isset($_POST['name']))
    {    
        try
        {
            require('connection.php');

            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $cpassword = $_POST['confirm-password'];

            $name = trim($name);
            $name = stripslashes($name);
            $name = htmlspecialchars($name);

            $email = trim($email);
            $email = stripslashes($email);
            $email = htmlspecialchars($email);

            $password = trim($password);
            $password = stripslashes($password);
            $password = htmlspecialchars($password);
            
            $cpassword = trim($cpassword);
            $cpassword = stripslashes($cpassword);
            $cpassword = htmlspecialchars($cpassword);

            $error = array();

            $name_exp = "/^([a-zA-z]{3,10}\s?)+$/";
            if (empty($name)) 
            {
                $error['name'] = '* Name Is Required';
            }
            else if (!preg_match($name_exp,$name))
            {
                $error['name'] = '* Invalid Name';
            }

            $email_exp = "/^\w+([\.\w-])*@([a-zA-z\.])+\.([a-zA-Z]{2,4})$/";
            if (empty($email))
            {
                $error['email'] = '* Email Is Required'; 
            }
            else if (!preg_match($email_exp,$email))
            {
                $error['email'] = '* Invalid Email'; 
            }

            $password_exp = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[$#@%*\-_\.])[\w$#@%*\-\.]{8,}$/";
            if (empty($password))
            {
                $error['password'] = '* Password is Required';
            }
            else if (!preg_match($password_exp,$password))
            {
                if (strlen($password) < 8) {
                    $error['password'][] = "* Must Be At Least 8 Characters";
                }
                if (!preg_match("/[a-z]/", $password)) {
                    $error['password'][] = "* Must Contain At Least 1 Lowercase Letter";
                }
                if (!preg_match("/[A-Z]/", $password)) {
                    $error['password'][] = "* Must Contain At Least 1 Uppercase Letter";
                }
                if (!preg_match("/\d/", $password)) {
                    $error['password'][] = "* Must Contain At Least 1 Number";
                }
                if (!preg_match("/[\W_]/", $password)) {
                    $error['password'][] = "* Must Contain At Least 1 Special Character";
                }
            }

            if (empty($cpassword)) 
            {
                $error['cpassword'] = '* Confirm Password Is Required';
            }
            else if ($password != $cpassword)
            {
                $error['cpassword'] = '* Input Don\'t Match Password';
            }

            if (count($error) === 0) 
            {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $rs = $db -> prepare("INSERT INTO user VALUES (null, :name, :email, :password)");
                $rs -> bindParam(":name", $name);
                $rs -> bindParam(":email", $email);
                $rs -> bindParam(":password", $password);
                $rs -> execute();
                $_SESSION['registration_success'] = true;
                header("Location: login.php");
                exit();
            } 
            else 
            {
                $_SESSION['registration_errors'] = $error;
                $error = $_SESSION['registration_errors'];
                if (isset($error['name']) && isset($error['email']))
                {
                    unset($_SESSION['registration_data']);
                }
                else if (isset($error['name'])) 
                {
                    $_SESSION['registration_data'] = [
                        'email' => $email,
                    ];
                } 
                else if (isset($error['email']))
                {
                    $_SESSION['registration_data'] = [
                        'name' => $name,
                    ];
                }
                else
                {
                $_SESSION['registration_data'] = [
                    'name' => $name,
                    'email' => $email,
                ];
                }
    
                header("Location: register.php");
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
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <title> Register </title>
    <!-- css styling file -->
    <link rel = "stylesheet" href = "register.css">
    <!-- website tab icon -->
    <link rel="icon" href="favicon.ico">
    <!-- icons -->
    <script src = "https://kit.fontawesome.com/7f38d76ae3.js" crossorigin = "anonymous"></script> 
    <!-- fonts -->
    <link rel = "preconnect" href = "https://fonts.googleapis.com">
    <link rel = "preconnect" href = "https://fonts.gstatic.com" crossorigin>
    <link href = "https://fonts.googleapis.com/css2?family=Signika:wght@400;500;600;700&display=swap" rel = "stylesheet">
</head>

<body>
    <div class = "container">   
        <form id = "registration" onsubmit = "return validateForm()" method = "POST">
            <h1> Registration </h1>
            
                <!-- user name -->
                <div class = "input-box">
                    <input type = "text" id = "name" name = "name" placeholder = "User name">
                    <i class="fa-solid fa-user fa-xs"></i>
                </div>  
                <?php if (isset($_SESSION['registration_errors']['name'])): ?>
                        <div class = "alert-N">
                            <span class = "closebtn">&times;</span>      
                            <?php echo $_SESSION['registration_errors']['name']; ?>
                        </div>    
                <?php endif; ?>  
                
                <!-- email -->
                <div class = "input-box">
                    <i class = "fa-solid fa-envelope fa-xs"></i>
                    <input type = "text" id = "email" name = "email" placeholder = "Email">  
                </div> 
                <?php if (isset($_SESSION['registration_errors']['email'])): ?>
                        <div class = "alert-E">
                            <span class = "closebtn">&times;</span> 
                            <?php echo $_SESSION['registration_errors']['email']; ?>
                        </div>
                <?php endif; ?> 

                <!-- password -->
                <div class = "input-box">
                    <i class = "fa-solid fa-lock fa-xs"></i>
                    <input type = "password" id = "password" name = "password" placeholder = "Password">
                    <i class = "fa-regular fa-eye" id = "opened" onclick = "toggle('password', 'opened', 'closed')"></i>
                    <i class = "fa-regular fa-eye-slash" id = "closed"  style = "display: none;" onclick = "toggle('password', 'opened', 'closed')"></i>
                </div>

                <?php if (isset($_SESSION['registration_errors']['password'])): ?>
                    <?php foreach ($_SESSION['registration_errors']['password'] as $i => $p_error):?>
                        <div class = "alert-P<?php echo ($i === count($_SESSION['registration_errors']['password']) - 1) ? ' last' : ''; ?>">
                            <span class = "closebtn">&times;</span> 
                            <?php echo $p_error; ?>
                        </div>
                    <?php endforeach;?>    
                <?php endif; ?>
                
                <!-- confirm password -->
                <div class = "input-box">            
                    <i class = "fa-solid fa-lock fa-xs"></i>
                    <input type = "password" id = "confirm-password" name = "confirm-password" placeholder = "Confirm Password">
                    <i class = "fa-regular fa-eye" id = "opened-2"  onclick = "toggle('confirm-password', 'opened-2', 'closed-2')"></i>
                    <i class = "fa-regular fa-eye-slash" id = "closed-2"  style="display: none;" onclick = "toggle('confirm-password', 'opened-2', 'closed-2')"></i>
                </div> 
                <?php if (isset($_SESSION['registration_errors']['cpassword'])): ?>
                    <div class = "alert-C">
                        <span class = "closebtn">&times;</span> 
                        <?php echo $_SESSION['registration_errors']['cpassword']; ?>
                    </div>
                <?php endif; ?>

            <button type = "submit" name = "register" id = "btn">Register</button><br>

            <div class = "login-link">            
                <p>
                already have an account? <a href = "login.php" id = "log"> login </a>
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

    // retype the data if an error occurred, so user would not have to write it again
    <?php if (isset($_SESSION['registration_data'])): ?>

        <?php if (isset($_SESSION['registration_data']['name'])): ?>
            document.getElementById('name').value = '<?php echo $_SESSION['registration_data']['name']; ?>';
        <?php endif; ?>

        <?php if (isset($_SESSION['registration_data']['email'])): ?>
            document.getElementById('email').value = '<?php echo $_SESSION['registration_data']['email']; ?>';
        <?php endif; ?>

    <?php endif; ?>

    function toggle(inputId, openedId, closedId) 
    {
        var passwordInput = document.getElementById(inputId);
        if (passwordInput.type === 'password') 
        {
            passwordInput.type = 'text';
            document.getElementById(openedId).style.display = 'none';
            document.getElementById(closedId).style.display = 'inline';
        } 
        else 
        {
            passwordInput.type = 'password';
            document.getElementById(openedId).style.display = 'inline';
            document.getElementById(closedId).style.display = 'none';
        }
    }

    function validateForm() 
    {
        var name = document.getElementById('name').value.trim();
        var email = document.getElementById('email').value.trim();
        var password = document.getElementById('password').value.trim();
        var confirmPassword = document.getElementById('confirm-password').value.trim();

        if (name === '' || email === '' || password === '' || confirmPassword === '') {
            alert('Please fill in all fields');
            return false;
        }
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function () 
        {
            if (this.responseText.trim() == "taken") 
            {
                alert('Email Is Already Used');
            }
            else
            {
                document.getElementById('registration').submit();
            } 
        };
        xhttp.open("GET", "checkemail.php?email=" + email, true);
        xhttp.send();
        return false;
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
unset($_SESSION['registration_errors']);
unset($_SESSION['registration_data']);
?>

</body>
</html>

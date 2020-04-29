<!DOCTYPE html>

<html lang="ro">
<head>
    <title>Stol(Univeral Storage Tool)</title>
    <meta charset="UTF-8">
    <meta name="description" content="Stol(Universal Storage Tool)">
    <meta name="keywords" content="Stol,Storage_Manager">
    <meta name="author" content="co-authored by Ionita Mihail-Catalin, Georgica Marius">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../page/css/register.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.3.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    <div class="login_form">
        <h1>Stol</h1>

        <!-- <form method="POST" action=""> -->

            <div class="email_input">
                <i class="ri-mail-line"></i>
                <input type="email" id="email_field" name="email" placeholder="Enter Email">
            </div>

            <div class="username_input">
                <i class="ri-user-smile-line"></i>
                <input type="text" id="username_field" name="username" placeholder="Enter Username">
            </div>
           
            <div class="password_input">
                <i class="ri-lock-line"></i>
                <input type="password" id="password_field" name="password" placeholder="Enter Password">
            </div>

            <div class="alert" style="display:none">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <p id="alert_text"></p>
            </div>
            
            <!-- <button class="btn btn-back" id="back_button" type="button" onclick="location.href='login';">Back</button>
            <input class="btn btn-register" id="register_button" type="submit" name="submit_register" value="Register" /> -->
            <button class="btn btn-back" id="back_button" type="button" onclick="location.href='login';">Back</button>
            <button class="btn btn-register" id="register_button" />Register</button>

        <!-- </form> -->

        <script src='../page/js/register.js'></script>
    </div>
      
</body>

</html>
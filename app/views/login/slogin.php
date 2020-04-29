<!DOCTYPE html>

<html lang="ro">
<head>
    <title>Stol(Univeral Storage Tool)</title>
    <meta charset="UTF-8">
    <meta name="description" content="Stol(Universal Storage Tool)">
    <meta name="keywords" content="Stol,Storage_Manager">
    <meta name="author" content="co-authored by Ionita Mihail-Catalin,Georgica Marius">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../page/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.3.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    <div class="login_form">
        <h1>Stol</h1>
        <!-- <form method="POST" action=""> -->
            <div class="username_input">
                <i class="ri-user-smile-line"></i>
                <!--<p>Username</p> -->
                <input type="text" id="username_field" name="username" placeholder="Enter Username">
            </div>
            <div class="password_input">
            	<!--<p>Password</p>-->
                <i class="ri-lock-line"></i>
                <input type="password" id="password_field" name="password" placeholder="Enter Password">
            </div>

            <div class="alert" style="display:none">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <p id="alert_text"></p>
            </div>

            <div>
                <!-- <input name="submit_login" type="submit" value="Login" class="btn btn-login" > -->
                <button class="btn btn-login" id="login_button">Login</button>
	        	<a href="http://localhost/ProiectTW/page/recover">Lost your password?</a><br>
	        	<a href="http://localhost/ProiectTW/page/register">Don't have an account?</a>
        	</div>
           
        <!-- </form> -->

        <script src='../page/js/login.js'></script>
    </div>
      
</body>

</html>
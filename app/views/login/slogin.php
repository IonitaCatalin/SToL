<!DOCTYPE html>

<html lang="ro">
<head>
    <title>Stol(Univeral Storage Tool)</title>
    <meta charset="UTF-8">
    <meta name="description" content="Stol(Universal Storage Tool)">
    <meta name="keywords" content="Stol,Storage_Manager">
    <meta name="author" content="co-authored by Ionita Mihail-Catalin,Georgica Marius">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../public/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.3.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    <div class="login_form">
        <h1>Stol</h1>
        <?php if($error_input!='' || $error_model!=''):?>
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <?php if($error_input!=''):?>
                        <?php echo $error_input; ?>
                <?php endif; ?>
                <?php  if($error_input=='' && $error_model!=''): ?>
                        <?php echo $error_model; ?>
                <?php endif;?>
                </div>
            <?php endif ?>
        <form method="POST" action="">
            <div class="username_input">
                <i class="ri-user-smile-line"></i>
                <!--<p>Username</p> -->
                <input type="text" name="username" placeholder="Enter Username">
            </div>
            <div class="password_input">
            	<!--<p>Password</p>-->
                <i class="ri-lock-line"></i>
                <input type="password" name="password" placeholder="Enter Password">
            </div>
            <div>
               <!-- <input type="submit" name="login" value="Login">-->
                <input name="submit_login" type="submit" value="Login" class="btn btn-login" >
	        	<a href="recover.html">Lost your password?</a><br>
	        	<a href="register.html">Don't have an account?</a>
        	</div>
           
        </form>
    </div>
      
</body>

</html>
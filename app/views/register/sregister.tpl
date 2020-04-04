<!DOCTYPE html>

<html lang="ro">
<head>
    <title>Stol(Univeral Storage Tool)</title>
    <meta charset="UTF-8">
    <meta name="description" content="Stol(Universal Storage Tool)">
    <meta name="keywords" content="Stol,Storage_Manager">
    <meta name="author" content="co-authored by Ionita Mihail-Catalin, Georgica Marius">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../public/css/register.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.3.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
    <div class="login_form">
        <h1>Stol</h1>
        <form method="POST" action="">
            <div class="email_input">
                <i class="ri-mail-line"></i>
                <input type="email" name="email" placeholder="Enter Email">
            </div>

            <div class="username_input">
                <i class="ri-user-smile-line"></i>
                <input type="text" name="username" placeholder="Enter Username">
            </div>
           
            <div class="password_input">
                <i class="ri-lock-line"></i>
                <input type="password" name="password" placeholder="Enter Password">
            </div>
            
            <?php
            if($inputValid == 'incomplet')
                echo '<p style="color: red; font-size: 1.1em;"> Nu ati completat toate campurile</p>';

            if($inputValid == 'succes')
                echo '<p style="color: green; font-size: 1.1em;"> Contul s-a creat cu succes</p>';

            ?>

            <button class="btn btn-back" type="button" onclick="location.href='clogin';">Back</button>
            <input type="hidden" name="actiune" value="register">
            <input class="btn btn-register" type="submit" value="Register" />

        </form>
    </div>
      
</body>

</html>
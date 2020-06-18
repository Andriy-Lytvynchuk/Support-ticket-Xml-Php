<?php
session_start();

// this is our error message. we going to tell user what is wrong if we are not able to log him in
$message = '';
$xml=simplexml_load_file("../xml/tickets.xml");

/*passw test ================
$_POST['password'] = 1;
$psw_hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
if (password_verify(1, $psw_hashed)){
    echo 'true';
}else{echo 'false';}*/
//==============================

// if user hit 'submit' button
if(isset($_POST['username']) && isset($_POST['password'])){
    if (!$xml = @simplexml_load_file("../xml/tickets.xml")) {
        $message = "Unable to read 'tickets.xml' file and fetch users <br>";
    }
    if(empty($message)) {

    // LOGIN logic
    $psw_entered = $_POST['password'];
    foreach ($xml->users->user as $user) {
        if ($user->username == $_POST['username'] && password_verify ($psw_entered, $user->password)) {
            $_SESSION['user-id'] = (int)$user->userid;
        }

//        echo 'username entered '. $_POST['username'] .'<br>';
//        echo 'username stored '. $user->username .'<br>';

        }
    }
    if(empty($_SESSION['user-id'])){
        $message .= "Wrong username or password";
    }
}
// it means that user has logged in successfully and there is no need to show this page
if(!empty($_SESSION['user-id'])) {

    $privilege = $xml->xpath("//user[@userid={$_SESSION['user-id']}]/privilege")[0];
//    echo "privilege = ". $privilege.'<br />';
//      echo '$_SESSION[user-id] = '. $_SESSION['user-id'].'<br />';

    if ($privilege == "admin"){
        header("Location: tickets-adm.php");
    }else{
        header("Location: tickets-us.php");
    }
}

include_once '../layout/top_noside.php';
?>
<form action="" id="login-form" method="post">
    <h3>Login</h3>
    <p>Please contact me on <a href="https://www.linkedin.com/in/andriy-lytvynchuk/">Linked In</a> to get login details</p>

    <?php if ($message){?>
        <div class="alert alert-warning alert-dismissible fade show"><?php echo $message ?></div>
    <?php } ?>

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" name="username" aria-describedby="emailHelp"
               value="<?php if(isset($_POST['submit'])) {echo $_POST['username'];} ?>" placeholder="Username">
<!--        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
    </div>

    <div class="form-group">
        <label for="passwotd">Password</label>
        <input type="password" class="form-control" name="password" placeholder="Password">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php

include_once '../layout/bottom.php';
?>

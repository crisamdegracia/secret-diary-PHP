<?php 


//echo phpinfo();
session_start();
$error      = '';
$success    = '';
$err        = '';

//DEFINE('DB_USERNAME', 'root');
//DEFINE('DB_PASSWORD', 'root');
//DEFINE('DB_HOST', 'localhost');
//DEFINE('DB_DATABASE', 'performance_schema');

if(mysqli_connect_error()){
    die("ERROR  adasdasdasdas");
} 
//$link = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD);

if(array_key_exists('logout' , $_GET)){

    unset($_SESSION['id']);
    unset($_COOKIE['id']);
    $_COOKIE['id'] = '';
    setcookie('id' , '' , time() - 60*60 );

}
//if( (array_key_exists('id' , $_SESSION) AND $_SESSION['id'] ) OR (array_key_exists('id' , $_COOKIE) AND $_COOKIE['id']) ){
else if( ( array_key_exists('id' , $_SESSION) && $_SESSION['id']) OR (array_key_exists('id' , $_COOKIE )) ){

    header('Location: secret.php');
}

if(array_key_exists('submit' , $_POST)){

    $email      = $_POST['email'];
    $password   = $_POST['password'];


    if(!$email){
        $err .= 'Email is required.<br>';
    } /*if no email
    */
    if( !$password ){
        $err .= 'Password is required.';
    }/*no pw*/

    if(!$error){

        if( $_POST['signup'] == '1' ){


            if(!$email){
                $error .= 'Email is required.<br>';
            } /*if no email
    */
            else if( !$password ){
                $error .= 'Password is required.';
            }/*no pw*/

            if( !$error ) {

                $link = mysqli_connect('127.0.0.1','root', '','userdbs');
                echo VAR_DUMP($link);
                $query  = "SELECT * FROM `users` WHERE email = '$email' ";
//                echo VAR_DUMP($query);
                if( $result = mysqli_query($link , $query)){

                    $row       = mysqli_fetch_array($result);
                    $numRows   = mysqli_num_rows($result);

                    if($numRows > 0 ) {
                        $err .= 'Email already taken.<br>';
                    }/*if email exist */

                    else {
                        $success = "logginn in ......";
                        $emailData = mysqli_real_escape_string($link , $email );
                        $passwordData = mysqli_real_escape_string($link , password_hash($password , PASSWORD_DEFAULT) );
                        $query  = "INSERT INTO `users`(`email`, `password`) VALUES('$emailData', '$passwordData') ";

                        if($result = mysqli_query($link , $query)){
                            $_POST['stayLoggin']  = '0';

                            if($_POST['stayLoggin'] == '1'){
                                setcookie('id' ,mysqli_insert_id($link) , time() + 60*60*24);
                            } 

                            $_SESSION['id'] = mysqli_insert_id($link);
                            header("Location: secret.php");  
                        } /*Registering input*/

                        else {
                            $err .= 'There was an error.';
                        }

                    }


                }/*Compiling Query for Select user email*/
                else {
                    $err .= 'Error Shit!';
                }
            }/*no error*/

        }/*if sign in = 1*/


        else if($_POST['signup'] == '0' ){

            if(!$_POST['email']){
                $error .= "Email is required.";
            } else if (!$_POST['password']) {
                $error .= "Password cannot b e empty";
            }

            if(!$error){

                $emailData = mysqli_real_escape_string($link , $email );
                $passwordData = mysqli_real_escape_string($link , password_hash($password , PASSWORD_DEFAULT) );

                $query  = "SELECT * FROM `users` WHERE email = '$emailData' ";

                if($result = mysqli_query($link , $query)){
                    $row = mysqli_fetch_array($result);

                    if(isset($row)){
                        if( $row['email'] == $email && password_verify( $password , $row['password'] )){
                            $_POST['stayLoggin']  = '0';
                            if($_POST['stayLoggin'] == '1'){
                                setcookie('id' , $row['id'] , time() + 60*60*24);
                            }
                            $_SESSION['id'] = $row['id'];
                            header('Location: secret.php');   


                        }
                        else {
                            $err .= "Email or Password is invalid.";
                        }
                    }/*if user exist*/

                    else {
                        $err .="User Doesn't exist.";
                    }
                }/*compiling data*/
            }/*if  no error*/
        }/*if signup - 0*/
        else{
            $err = $error; 
        }/*else for the errors*/
    }/*if no error*/

}/*if submit button was pressed*/



?>



<?php include('resources/templates/partials/header.php') ?>

<div class="container py-5 my-5 text-center">
    <h4 class="display-4">Secret Box</h4>
    <strong class="lead">Store your secrets permanently!</strong>
    <div class="col-md-6 m-auto">
        <?php if($err) { ?>
        <h4 class="alert alert-danger"> <?php echo $err; } ?> </h4>
        <?php if($success) { ?>
        <h4 class="alert alert-success"> <?php echo $success; } ?> </h4>
    </div>
    <form action="" method="post" id="signupForm">
        <h5 class="lead">Sign up for <strong>free!</strong></h5>
        <div class="form-group col-md-6 m-auto">
            <div class="input-group ">
                <div class="input-group-addon my-1 bg-warning">@</div>
                <input type="email" name="email" class="form-control my-1" placeholder="Enter Email"  autofocus> 
            </div>
        </div>

        <div class="form-group input-group col-md-6 m-auto">
            <div class="input-group-addon my-1 pr-3 bg-warning">╪</div>
            <input type="password" name="password" class="form-control my-1" placeholder="Enter Password"> 
        </div>
        <div class="form-group col-md-6 m-auto">
            <div class="checkbox ">
                <label>
                    <input type="checkbox" name="stayLoggin" value=1> <span class="text-white">Stay logged in?</span>
                </label>
            </div>
        </div>
        <input type="hidden" name="signup" value="1">
        <div class="form-group col-md-6 m-auto text-center">
            <input name="submit" type="submit" class="btn btn-primary my-1" value="Sign up!">
        </div>
        <p><a class="toggleForm">Login</a></p>
    </form>


    <form action="" method="post" id="loginForm">
        <h5 class="lead">Login using your username and password!</h5>

        <div class="form-group input-group col-md-6 m-auto">

            <div class="input-group-addon my-1 bg-warning">@</div>
            <input type="email" name="email" class="form-control my-1" placeholder="Enter Email"  autofocus> 
        </div>

        <div class="form-group input-group col-md-6 m-auto" >
            <div class="input-group-addon my-1 pr-3 bg-warning">╪</div>
            <input type="password" name="password" class="form-control my-1" placeholder="Enter Password"> 
        </div>
        <div class="form-group col-md-6 m-auto text-center">
            <div class="checkbox ">
                <label>
                    <input type="checkbox" name="stayLoggin" value=1> <span class="text-white">Stay logged in?</span>
                </label>
            </div>        </div>
        <input type="hidden" name="signup" value="0">
        <div class="form-group col-md-6 m-auto text-center">
            <input name="submit" type="submit" class="btn btn-primary my-1" value="Sign in">
        </div>

        <p><a class="toggleForm">Sign up!</a></p>
    </form>
</div>


<?php include('resources/templates/partials/footer.php') ?>
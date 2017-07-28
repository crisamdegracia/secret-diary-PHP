<?php 

session_start();

if(array_key_exists('id' , $_COOKIE)){
    $_SESSION['id'] = $_COOKIE['id'];
}

if(array_key_exists('id', $_SESSION) ){
    
 echo "<a  href='index.php?logout=1'>Log me out! </a>";

} else {
    header("Location: index.php");
}

?>


<?php include('resources/templates/partials/header.php'); ?>

<div class="container text-center py-4 bg-inverse text-white">
    <h4 class="display-4"> HELLO WORLD!</h4>
    <form action="" method="post">
        <textarea class="form-control" id="secret" name="secret" id="" cols="30" rows="10"></textarea>
        <button class="btn btn-primary my-3">Add Secret</button>
    </form>
</div>

<?php   include('resources/templates/partials/footer.php'); ?>

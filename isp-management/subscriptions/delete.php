<?php

include "../config/db.php";


if(isset($_GET['id'])){

    $id = (int) $_GET['id'];


    $query = "DELETE FROM subscriptions WHERE id=$id";


    mysqli_query($conn,$query);

}


header("Location: list.php");
exit();

?>
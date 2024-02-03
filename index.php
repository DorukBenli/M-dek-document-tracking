<?php
   include("helper.php");
   include("database.php");

    $res = createUser("ASDFSAFSA","Argor");
    echo "{$res}";

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1></h1>
</body>
</html>
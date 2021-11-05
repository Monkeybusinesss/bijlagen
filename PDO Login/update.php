<?php

session_start();

$username = $_SESSION['username'];

include_once("config.php");

?>

<html>
<head>
    <title>Update Details for <?php echo $username; ?></title>
</head>
<body>
    <form method="post" action="process.php">
        <input type="text" name="username" placeholder="username"></input>
        <input type="email" name="email" placeholder="email" ></input>
        <input type="password" name="password" placeholder="password"></input>
        <input type="submit" value="Update" name="update"></input>
    </form>
</body>
</html>
<?php

include_once("config.php");

$con = config::connect();

$results = fetchRecords($con);

function fetchRecords($con) {
    $query = $con->prepare("SELECT * FROM users");

    $query->execute();

    return $query->fetchAll();
}

?>

<html>
<head>
</head>
<body>
    <h1>Login Register Project</h1>
    <a href="login.html">Login</a>
    <a href="register.html">Register</a>

    <br>
    <br>

    <table>
        <tr>
            <th>Email</th>
            <th>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Username</th>
        </tr>

        <?php

        foreach ($results as $user) {
            ?>

            <tr>
                <td><?php echo $user['email']; ?></td>
                <td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo $user['username']; ?></td>
            </tr>

            <?php
        }

        ?>
    </table>
</body>
</html>
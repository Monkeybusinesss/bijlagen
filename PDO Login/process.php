<?php

session_start();

include_once("config.php");

if (isset($_POST['register'])) {
    $con = config::connect();
    $username = sanitizeString($_POST['username']);
    $email = sanitizeString($_POST['email']);
    $password = sanitizePassword($_POST['password']);

    if ($username == "" || $email == "" || $password == "") {
        return;
    }

    if (!checkUserNameExists($con,$username)) {
        echo "Username already exists!";
        return;
    }

    if (!checkEmailExists($con,$email)) {
        echo "Email already exists!";
        return;
    }


    if (insertDetails($con,$username,$email,$password)) {
        $_SESSION['username'] = $username;
        header("Location: profile.php");
    }
}

if (isset($_POST['Login'])) {
    $con = config::connect();
    $username = sanitizeString($_POST['username']);
    $password = sanitizePassword($_POST['password']);

    if ($username == "" || $password == "") {
        return;
    }

    if (checkLogin($con,$username,$password)) {
        $_SESSION['username'] = $username;
        header("Location: profile.php");
    } else {
        echo "The username and password are incorrect!";
    }
}

if (isset($_POST['update'])) {
    $con = config::connect();
    $username = sanitizeString($_POST['username']);
    $email = sanitizeString($_POST['email']);
    $password = sanitizePassword($_POST['password']);

    if ($username == "" || $email == "" || $password == "") {
        return;
    }

    if (!checkUserNameExists($con,$username)) {
        echo "Username already exists!";
        return;
    }

    if (!checkEmailExists($con,$email)) {
        echo "Email already exists!";
        return;
    }

    $currentUserName = $_SESSION['username'];

    $query = $con->prepare("SELECT * FROM users WHERE username=:username");

    $query->bindParam(":username",$currentUserName);

    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);

    $id = $result['id'];

    if (updateDetails($con,$id,$username,$email,$password)) {
        $_SESSION['username'] = $username;
        header("Location: profile.php");
    }
}

function insertDetails($con,$username,$email,$password) {
    $query = $con->prepare("INSERT INTO users (username,email,password) VALUES(:username,:email,:password)");

    $query->bindParam(":username",$username);
    $query->bindParam(":email",$email);
    $query->bindParam(":password",$password);

    return $query->execute();
}

function checkLogin($con,$username,$password) {
    $query = $con->prepare("SELECT * FROM users WHERE username=:username AND password=:password");

    $query->bindParam(":username",$username);
    $query->bindParam(":password",$password);

    $query->execute();

    // ckeck how many rows are returned

    if ($query->rowCount() == 1) {
        return true;
    } else {
        return false;
    }
    
}

function sanitizeString($string) {
    $string = strip_tags($string);

    $string = str_replace(" ","",$string);

    return $string;
}

function sanitizePassword($string) {
    $string = md5($string);

    return $string;
}

function updateDetails($con,$id,$username,$email,$password) {
     $query = $con->prepare("UPDATE users SET username=:username,email=:email,password=:password WHERE id=:id");

     $query->bindParam(":username",$username);
     $query->bindParam(":email",$email);
     $query->bindParam(":password",$password);
     $query->bindParam(":id",$id);

     return $query->execute();
}

function checkUserNameExists($con,$username) {
    $query = $con->prepare("SELECT * FROM users WHERE username=:username");

    $query->bindParam(":username",$username);

    $query->execute();

    // check

    if ($query->rowCount() == 1) {
        return false;
    } else {
        return true;
    }
}

function checkEmailExists($con,$email) {
    $query = $con->prepare("SELECT * FROM users WHERE email=:email");

    $query->bindParam(":email",$email);

    $query->execute();

    // check

    if ($query->rowCount() == 1) {
        return false;
    } else {
        return true;
    }
}

?>
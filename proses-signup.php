<?php

if (empty($_POST["username"])) {
    die("Nama dibutuhkan! <br><br> <a href='login-regist.php'><button>Kembali</button></a>");
    }

if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Email yang benar dibutuhkan! <br><br> <a href='login-regist.php'><button>Kembali</button></a>");
}

if (strlen($_POST["password"]) < 8) {
    die("Password harus terdiri dari 8 karakter! <br><br> <a href='login-regist.php'><button>Kembali</button></a>");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password harus terdiri setidaknya 1 huruf! <br><br> <a href='login-regist.php'><button>Kembali</button></a>");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password harus terdiri setidaknya 1 angka! <br><br> <a href='login-regist.php'><button>Kembali</button></a>");
}


$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO tb_system (username, password_hash, email)
        VALUES (?, ?, ?)";
        
$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss",
                  $_POST["username"],
                  $password_hash,
                  $_POST["email"]);
                  
if ($stmt->execute()) {

    header("Location: login-regist.php");
    exit;
    
} else {
    
    if ($mysqli->errno === 1062) {
        die("email sudah terdaftar");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}
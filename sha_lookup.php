<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="sha_lookup.css" rel="stylesheet">
    <title>SHA Lookup</title>
</head>
<body>
<div class="wrapper">
<?php

$hash = htmlentities($_POST['hash']);
$server = "localhost";
$username = "root";
$password = "";
$shaPass = "";
$pass = "";

print("<pre>");
if ($hash != "") {  
    print("<h1>Searching for $hash...</h1>");
} else {
    print("<h3>SHA value cannot be empty. Please try again.</h3>");
}
print("</pre>");


$mysqli = new mysqli($server, $username, $password);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
//Check connections
if ($mysqli -> connect_error) {
    die("Connection Failed: " . $mysqli -> connect_error);
}
$mysqli -> set_charset("utf8mb4");

if (strlen($hash) == 40 or strlen($hash) == 56 or strlen($hash) == 64) { 
    $sql = $mysqli -> prepare("select password from passwords.shapasswords where passwords.shapasswords.sha = CONCAT(?,'\n')");
    $sql -> bind_param('s', $hash);
    $sql -> execute();
    $result = $sql -> get_result();

    $pass = $result->fetch_all(MYSQLI_ASSOC);
    

    if ($pass != null) {
        $found_password = $pass[0]["password"];
        print("<pre>");
        print("<p>Password found: $found_password</p>");
        print("</pre>");
    } else {
        print("Could not find password with the provided SHA value. Please Try again.");
    }
} else if ($hash == "") {
    echo "";
} else {
    echo "Invalid Hash Length. Please Try again.";
}
?>

<form method="post" action="sha_lookup.php">
    <p>Enter SHA Value:  
    <input name="hash" id="hash" size="68"><br>
    </p>
    <input type="submit" value="Lookup Password" class="lookup_button"/>
    </form>
</div>
</body>
</html>

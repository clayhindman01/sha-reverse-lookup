<?php

$server = "localhost";
$username = "root";
$password = "";

//Local instance variables for the .txt files
$sha1_list = file("sha1_list.txt");
$sha224_list = file("sha224_list.txt");
$sha256_list = file("sha256_list.txt");

//Create connections
$conn = new mysqli($server, $username, $password);
//Check connections
if ($conn -> connect_error) {
    die("Connection Failed: " . $conn -> connect_error);
}

//Drop the schema to have a fresh start.
$deleteDB = "DROP SCHEMA passwords";
if($conn -> query($deleteDB) === TRUE) {
    echo "Deleted database successfully\n";
} else {
    echo "Database Deletion Error: " . $conn -> error . "\n";
}

//Create new schema
$createDB = "CREATE SCHEMA passwords";
if ($conn -> query($createDB) === TRUE) {
    echo "Created database successfully\n";
} else {
    echo "Database Creation Error: " . $conn -> error . "\n";
}

//Create the tables
$createTable = "
CREATE TABLE passwords.shapasswords (
	password varchar(100) NULL,
	sha char(100) NULL
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;";

if($conn -> query($createTable) === TRUE) {
    echo "Tables created successfully\n";
} else {
    echo "Error: " . $conn -> error . "\n";
}

//TODO: Add the data into the database
for ($i = 0; $i < sizeof($sha1_list); $i++) {
	//Explode the results of sha1 and add to the database
	list($password_sha1, $sha1) = explode(":", $sha1_list[$i]);
	str_replace(array('\r', '\n'), "", $sha1);
	$sha1Insert = "INSERT INTO passwords.shapasswords (sha, password) VALUES ('$sha1', '$password_sha1')";
	$conn -> query($sha1Insert);

	//Explode the results of sha224 and add to the database
	list($password_sha224, $sha224) = explode(":", $sha224_list[$i]);
	str_replace(array('\r', '\n'), '', $sha224);
	$sha224Insert = "INSERT INTO passwords.shapasswords (sha, password) VALUES ('$sha224', '$password_sha224')";
	$conn -> query($sha224Insert);

	//Explode the results of sha256 and add to the database
	list($password_sha256, $sha256) = explode(":", $sha256_list[$i]);
	str_replace(array('\r', '\n'), '', $sha256);
	$sha256Insert = "INSERT INTO passwords.shapasswords (sha, password) VALUES ('$sha256', '$password_sha256')";
	$conn -> query($sha256Insert);
}

?>
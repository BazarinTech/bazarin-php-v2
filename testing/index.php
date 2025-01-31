<?php 
include '../Database/Connection.php';
include '../Database/QueryBuilder.php';
$conn = new Connection('localhost', 'bazarin', 'bazarin', 'icoinn');
$conn = $conn->connect();
$query = new QueryBuilder($conn);
$user = $query->select('users', 'name', ['id' => 5]);
$user_name = $user[0]['name'] ?? 'No user found';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?=$user_name?>
</body>
</html>
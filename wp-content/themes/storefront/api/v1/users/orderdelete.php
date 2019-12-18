<?php

// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
header('Content-Type: application/json');
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new User($db);

// set ID property of user to be edited
$user->fid = isset($_GET['fid']) ? $_GET['fid'] : die();
$user->orderitemid = isset($_GET['orderitemid']) ? $_GET['orderitemid'] : die();
$user->orderid = isset($_GET['orderid']) ? $_GET['orderid'] : die();
$user->status = isset($_GET['status']) ? $_GET['status'] : die();

$url = $_SERVER['REQUEST_URI'];
$requesturl = explode('?', $url);
$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$endpoint1 = explode('.', $endpoint);

   $stmt=$user->deleteorder();

   if($stmt->rowCount() > 0) {
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // create array
    //if($user->editprofile()) {
       // $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_arr=array(
        "status" => true,
        "message" => "Item deleted!",
        "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
        "end point" => $endpoint1[0],
        //"id" => $row['id'],
        //"username" => $row['username']
    );
}
else {
    $user_arr=array(
        "status" => false,
        "message" => "Failed to delete the item!",
        "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
        "end point" => $endpoint1[0],
    );
}
// make it json format
print_r(json_encode($user_arr));
?>

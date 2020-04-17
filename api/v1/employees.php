<?php

include("../lib/connection.php");
$db = new dataObj();
$connection = $db->getConnstring();
$request_method=$_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            get_employeesid($id);
        }else{
            get_employees();
        }
        break;
    case 'POST':
        insert_employee();
        break;
    default:
    header("http/1.0 405 Method Not Allowed");
    break;
}
function get_employees(){
    global $connection;
    $query = "SELECT * FROM `tb_employee`";
    $response = array();
    $result = mysqli_query($connection,$query);

    while ($row = mysqli_fetch_array($result)){
        $response[]=$row;
    }
    header('Content-Type:application/json');
    echo json_encode($response);
}

function get_employeesid($id=0){
    global $connection;
    $query = "SELECT * FROM `tb_employee`";
    if ($id !=0) {
        $query.="WHERE id ='$id' LIMIT 1";
    }
    $response = array();
    $result = mysqli_query($connection,$query);

    while ($row = mysqli_fetch_array($result)){
        $response[]=$row;
    //     case 'POST': insert_employee();
    // break;
    }
    header('Content-Type:application/json');
    echo json_encode($response);
}
 function insert_employee(){
    global $connection;
    $data = json_decode(file_get_contents('php://input'),TRUE);
    $name = $data["employee_name"];
    $salary = $data["employee_salary"];
    $ege = $data["employee_ege"];
    echo $query = "INSERT INTO `tb_employee` SET 
    `id` = null ,
    `employee_name` = '$name',
    `employee_salary` = '$salary',
    `employee_ege` = '$ege'";
    if (mysqli_query($connection,$query)) {
        $response = array(
            'status'=>1,
            'status_massage' =>'Employee Added Successfully'
        );
    }else {
        $response = array(
            'status'=>0,
            'status_massage' =>'Employee addition failed'
        );
    }
    header('Content_Type: application/json');
    echo json_encode($response);

 }

?>
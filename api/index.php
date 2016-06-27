<?php


## ATENÇÃO leia o .htaccess desta budega ## 
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');  
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');  
header('Access-Control-Allow-Headers: *');  
header('Access-Control-Max-Age: 86400');

/*
//Resolve porem duplica, resolvi pelo .htaccess.... 
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']) && (
            $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'POST' ||
            $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'DELETE' ||
            $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'PUT' )) {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT'); // http://stackoverflow.com/a/7605119/578667
        header('Access-Control-Max-Age: 86400');
    }
    exit;
}
*/



require 'vendor/autoload.php';
 

// var_dump($_SERVER['REQUEST_METHOD']); //GET, POST, PUT, DELETE

//Tem o script sql.sql....

//instancie o objeto
$app = new \Slim\Slim();

function getConnection() {
	
	
	
	if($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME']=='127.0.0.1'){
	
		$db = new PDO("mysql:host=localhost;dbname=bemean", 'root', '');  
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->exec("set names utf8"); //Garante UTF em versão < 5.3
		return $db;
	} else {
		//PRODUÇÃO MANO!11!
		
		
		$db = new PDO("mysql:host=mysql.inwork.com.br;dbname=inwork02;charset=utf8", 'inwork02', 'w4f3x2');  
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->exec("set names utf8"); //Garante UTF em versão < 5.3
		
		return $db;
	}
		
}

$app->get('/user/', function(){
	$stmt = getConnection()->query("select * from users");
	$data = $stmt->fetchAll(PDO::FETCH_OBJ);
	print json_encode($data);
});

$app->get('/user/:id', function ($id) { 
	$db = getConnection();
	$stmt = $db->prepare("select * from users where id = ?");
	if ($stmt->execute(array($id))) {
		while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
			print json_encode($row);
		}
	}
});

$app->delete('/user/:id', function ($id) { 
	
	$db = getConnection();
	$stmt = $db->prepare("DELETE FROM users WHERE id =  :id");
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);   
	
	if($stmt->execute()){
		print json_encode(array('action' => true, 'tipo' => 'delete'));		
	} else {
		print json_encode(array('action' => false));
	}
});

$app->put('/user/', function () { 
	
	$app = \Slim\Slim::getInstance();
	$request = $app->request();
	$user = json_decode($request->getBody());
	
	$db = getConnection();
	
	$sql = "UPDATE users SET 
			 name = :name  			
			,active = :active
			,email = :email
			,type = :type  
			WHERE id = :id";
	$stmt = $db->prepare($sql);                                  
	$stmt->bindParam(':name', $user->name, PDO::PARAM_STR);       
	$stmt->bindParam(':active', $user->active, PDO::PARAM_STR);       
	$stmt->bindParam(':email', $user->email, PDO::PARAM_STR);       
	$stmt->bindParam(':type', $user->type, PDO::PARAM_STR);       
	$stmt->bindParam(':id', $user->id, PDO::PARAM_INT);   
	$stmt->execute();
	print json_encode(array('action' => true, 'tipo' => 'put'));
});


$app->post('/user/', function () { 
	$app = \Slim\Slim::getInstance();
	$request = $app->request();
	$user = json_decode($request->getBody());
	
	$db = getConnection();
	
	$sql = "INSERT INTO users (name, email) VALUES (:name , :email)";
	
	$stmt = $db->prepare($sql);                                  
	$stmt->bindParam(':name', $user->name, PDO::PARAM_STR);       
	// $stmt->bindParam(':active', $user->active, PDO::PARAM_STR);       
	$stmt->bindParam(':email', $user->email, PDO::PARAM_STR);       
	// $stmt->bindParam(':type', $user->type, PDO::PARAM_STR);       
	// $stmt->bindParam(':id', $user->id, PDO::PARAM_INT);   
	$stmt->execute();
	print json_encode(array('action' => true));
});


$app->get('/', function () { 
	print json_encode(array('action' => false, 'message' => 'Root (/) empty'));
});


//rode a aplicação Slim 
$app->run();
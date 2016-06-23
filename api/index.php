<?php
require 'vendor/autoload.php';
 
header('Content-Type: application/json; charset=utf-8');
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
		
		//Na produção ta certo...
		

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
		print json_encode(array('action' => true));		
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
	print json_encode(array('action' => true));
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
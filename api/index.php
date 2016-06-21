<?php
require 'vendor/autoload.php';
 
header('Content-Type: application/json; charset=utf-8');

//Tem o script sql.sql....

//instancie o objeto
$app = new \Slim\Slim();

function getConnection() {
	$db = new PDO("mysql:host=localhost;dbname=bemean", 'root', '');  
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->exec("set names utf8"); //Garante UTF em versão < 5.3
    return $db;
}


function getUsers(){
	$stmt = getConnection()->query("select * from users");
	$data = $stmt->fetchAll(PDO::FETCH_OBJ);
	print json_encode($data);
}


function getUserById($id){
	$db = getConnection();
	$stmt = $db->prepare("select * from users where id = ?");
	if ($stmt->execute(array($id))) {
		while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
			print json_encode($row);
		}
	}
}

function deleteUserById($id){
	$db = getConnection();
	$stmt = $db->prepare("DELETE FROM users WHERE id =  :id");
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);   
	$stmt->execute();	
}

function updateUserById($id){
	$db = getConnection();
	$stmt = $db->prepare("DELETE FROM users WHERE id =  :id");
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);   
	$stmt->execute();	
}

$app->get('/users/','getUsers');



$app->get('/user/:id', function ($id) { 
	getUserById($id);
});

$app->delete('/user/:id', function ($id) { 
	deleteUserById($id);
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


//defina a rota
$app->get('/', function () { 
	echo 'rota vazia...';
});
//rode a aplicação Slim 
$app->run();
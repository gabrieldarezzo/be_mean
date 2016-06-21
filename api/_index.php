<?php

/*
vm.users = [
	 {name: 'Suissa'	,email:'suissa@yahoo.com.br'	,type: 'teacher',active:true}
	,{name: 'Gabriel'	,email:'gabriel@teste.com.br'	,type: 'student',active:false}
	,{name: 'Darezzo'	,email:'darezzogmail.com'		,type: 'teacher',active:false}	
	,{name: 'Roberto' 	,email:'roberto@test.com'		,type: 'student',active:true}
	,{name: 'Fifi' 		,email:'fifi@globo.com'			,type: 'student',active:true}
];
*/

// header ('Content-type: application/json; charset=UTF-8');

// var_dump();die();


$users = array(
	array(
		 'id'		=> 1
		,'name'		=> 'Suissa'
		,'email'	=> 'suissa@yahoo.com.br'
		,'type'		=> 'teacher'
		,'active'	=> true
	)
	,array(
		'id'		=> 2
		,'name'		=> 'Gabriel'
		,'email'	=> 'gabrieldarezzo@yahoo.com.br'
		,'type'		=> 'student'
		,'active'	=> false
	)
	,array(
		 'id'		=> 3
		,'name'		=> 'Test'
		,'email'	=> 'testador@yahoo.com.br'
		,'type'		=> 'student'
		,'active'	=> true
	)
);



if(isset($_GET) && $_GET){
	$user = $users[$_GET['id']];
	print json_encode($user);
	
	$method = $_SERVER['REQUEST_METHOD'];
	
	
	switch:
		case 'GET':
			### CARA ....bem, melhor usar o slimframework...
		break;
		
		case 'DELETE':
			
		break;
		
		case 'POST':
		
		break;
		
		
	
	endswitch;
	
	
} else {
	print json_encode($users);
}

	

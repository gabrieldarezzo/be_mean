'use strict';


angular.module('inSchool', ['ngRoute', 'Aluno'])
	.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider){
		// $locationProvider.html5Mode(true);
		
		$routeProvider
		.when('/', {
			 templateUrl: 'views/index.html'
		})
		
		.when('/erro/404', {
			 templateUrl: 'erro/404.html'
		})
		
		
		.otherwise({redirectTo: '/'});
	}])
;


angular.module('Aluno', [])
	.config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider){
		$routeProvider
		.when('/aluno', {
			 templateUrl: 'home.html'
			,controller: 'HomeController'
			,controllerAs: 'vm'
		});
		
		$httpProvider.interceptors.push('authInterceptor');
		$httpProvider.interceptors.push('notFoundInterceptor');
		
	}])
	//.service('UserService', UserService)
	.factory('authInterceptor',  authInterceptor)	
	.factory('notFoundInterceptor',  notFoundInterceptor)	
	.controller('HomeController', HomeController)
;

function HomeController($http){
	var vm = this;
	//console.log(vm);
	vm.name = 'WebSchool';
	vm.buscarArquivo = buscarArquivo;
	vm.testaToken = testaToken;
	vm.testaErroAutorizacao = testaErroAutorizacao;
	
	//Força um 404
	function buscarArquivo(){
		$http.get('http://localhost/djasidshahdasdais');
	}
	
	function testaToken(){
		console.log('Setando token no localStorage, "token"->"TOKEN_TEST"');
		localStorage.setItem('token', 'TOKEN_TEST');
		console.log('Setando token no localStorage, "token"->"TOKEN_TEST"');
		$http.get('app.js');
	}
	
	function testaErroAutorizacao(){
		localStorage.removeItem('token');
		$http.get('https://api.fullcontact.com/v2/person.json?email=bart@fullcontact.com');
	}	
}

function notFoundInterceptor($q, $location){
	return {
		responseError: function(rejection){						
			if(rejection.status == 404){
				$location.path('/erro/404');
			}
			return $q.reject(rejection);
		}
	}
}


function authInterceptor($q, $location){	
	return {
		request: function(config){
			//console.log('intercept');
			config.headers = config.headers || {};
			if(localStorage.getItem('token')) {
				config.headers.Authorization = 'Bearer ' + localStorage.getItem('token');
			}
			return config;
		}
		,responseError : function(rejection){
			//console.log('intercept(2)');
			if(rejection.status === 401 || rejection.status === 403){
				$location.path('/erro/nao-autorizado');				
			}			
			return $q.rejectrejection;
		}
	};
}
angular.module('BeMEAN', ['ngAnimate', 'ngRoute', 'User'])
	.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider){
		// $locationProvider.html5Mode(true);
		
		$routeProvider
		.when('/', {
			 templateUrl: 'views/index.html'
		})
		.otherwise({redirectTo: '/'});
	}])
;



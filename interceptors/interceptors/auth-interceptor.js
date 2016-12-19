'user strict';

angular.module('app')
	.factory('authInterceptor', authInterceptor);


authInterceptor.$inject = ['$q', '$location'];

function authInterceptor($q, $location){
	return {
		request: function(config){
			config.headers = config.headers || {};
			if(localStorage.getItem('token')) {
				config.headers.Authorization = 'Bearer ' + localStorage.getItem('token');
			}
			return config;
		}
		,responseError : function(rejection){
			if(rejection.status === 401 || rejection.status === 403){
				$location.path('/erro/nao-autorizado');				
			}			
			return $q.rejectrejection);
		}
	};
}
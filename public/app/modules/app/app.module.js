(function() {
  angular
    .module('intranet', [
      'ngRoute',
      'login',
      'admin',
      'dashboard'
    ]).config(config)
      .run(routeLogin)
      .constant('CONFIG',{
        "interval": 10000
      });

    function config($locationProvider, $routeProvider, $httpProvider){
       $routeProvider.otherwise({redirectTo: '/'})
  	}

    function routeLogin($rootScope, $location, $interval, loginService) {
      $rootScope.$on('$routeChangeStart', function(event, next, current){
        var token = loginService.getToken()
        if(token){
          loginService
              .isAuthenticated(token)
              .then(function(authenticated){
                  if(!authenticated.success) $location.path('/login')
                  else if($location.path() === '/login') $location.path('/admin')
              }).catch(function(error){
                $location.path('/login')
              })
        }else{
          $location.path('/login')
        }
      });
    }
})();

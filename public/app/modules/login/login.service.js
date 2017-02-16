(function() {
    'use strict';

    angular
        .module('login')
        .service('loginService', loginService);

    loginService.$inject = ['$http', '$location', '$interval', '$rootScope'];

    /* @ngInject */
    function loginService($http, $location, $interval, $rootScope) {
      var service = {
          authUser: authUser,
          setToken: setToken,
          isAuthenticated: isAuthenticated,
          getToken: getToken,
          routeUser: routeUser,
          refreshToken: refreshToken,
          interval: undefined,
          cancelInterval: cancelInterval,
          startInterval: startInterval,
          logout: logout
        }

        function startInterval(interval) {
          this.interval = interval
        }

        function authUser(credentials) {
          return $http
                    .post('/api/v2/auth', credentials)
                    .then(function(res){
                       return res
                    }).catch(function(res){
                       return res
                    })
        }

        function setToken( token ) {
          sessionStorage.setItem('_uid', token)
        }

        function getToken(){
          var token = sessionStorage.getItem('_uid')
          if(token == "" || token == null) return false
          return token
        }

        function logout() {
          sessionStorage.removeItem('_uid')
        }
        //TODO - return false if token is not available
        /**
         * [isAuthenticated description]
         * @return {Boolean} [description]
         */
        function isAuthenticated(token) {
            var config =  {'Authorization' : 'Bearer ' + token }
            return $http
                .get('/api/v2/token/ack', { headers: config })
                .then(function(res){
                  return res.data
                }).catch(function(res){
                  return res.data
                })

        }

        function routeUser( role ) {
          switch (role) {
            case 'admin': $location.path('/admin')
              break;
            default: $location.path('/dashboard')
          }
        }
        /**
         * [cancelInterval description]
         * @return {[type]} [description]
         */
        function cancelInterval() {
          $interval.cancel(this.interval)
        }
        /**
         * Refreshes the user's token.
         * @return {[type]} [description]
         */
        function refreshToken() {
          var token = sessionStorage.getItem('_uid')
          if(token){
            var config = {'headers': {
                        'Authorization' : 'Bearer ' + sessionStorage.getItem('_uid')
            }}
            $http.post('/api/v2/auth/token/refresh', {}, config)
                  .then(function(res){
                    if(res.data.status === 400 || res.data.status === 500){
                      $location.path('/login')
                    }else{
                      sessionStorage.setItem('_uid', res.data.token)
                    }
                }).catch(function(res){
                  $location.path('/login')
                })
          }else{
            this.cancelInterval()
            $location.path('/login')
          }
        }
        /**
         * Logs out a user from the application.
         * @return {[type]} [description]
         */
        function logout(){
          this.cancelInterval()
          sessionStorage.clear()
          $location.path('/login')
        }

        return service
    }
})();

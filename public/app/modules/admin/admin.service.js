(function() {
    'use strict';

    angular
        .module('admin')
        .service('AdminService', AdminService);

    AdminService.$inject = ['$http', 'loginService'];

    /* @ngInject */
    function AdminService($http, loginService) {
        var service = {
          getUsers: getUsers,
          searchLDAP:searchLDAP,
          createUser:createUser
        }

        return service

        function createUser( user ) {
          var auth_header = _createAuthHeader()
          console.log(user)
          return $http
                  .post('/api/v2/admin/users', user, { headers: auth_header })
                  .then(function (res) {
                     return res
                  }).catch(function (error) {
                     return error
                  });
        }
        /**
         * Get a list of users
         * @return array       List of users if request was success, empty otherwise
         */
        function getUsers() {
           var auth_header = _createAuthHeader()
            return $http
                      .get('/api/v2/admin/users', { headers: auth_header })
                      .then(function(res){
                        return res.data
                      }).catch(function(res){
                         return res.data
                      })
        }
        /**
         * [searchLDAP description]
         * @return {[type]} [description]
         */
        function searchLDAP(query) {
          var auth_header = _createAuthHeader()
          return $http
                    .get('/api/v2/admin/ldap/search?q=' + query, { headers: auth_header })
                    .then(function(res){
                      return res.data
                    }).catch(function(error){
                      return error
                    })
        }
        /**
         * Sets the Authorization header for HTTP request by retrieving
         * a session stored token.
         * @return object Authorization header object with token
         */
        function _createAuthHeader() {
          var token = loginService.getToken() || '',
              auth_header = {'Authorization': 'Bearer ' + token }
          return auth_header
        }
    }
})();

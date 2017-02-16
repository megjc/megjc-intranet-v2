(function() {
    'use strict';

    angular
        .module('admin', [])
        .config(config);

        function config($routeProvider) {
          $routeProvider.when('/admin',{
            templateUrl: 'public/app/modules/admin/tpl/admin.html',
            controller: 'Admin as vm',
            resolve: {
              users: function(AdminService){
                return AdminService.getUsers()
              }
            }
          }).when('/admin/users/create', {
            templateUrl: 'public/app/modules/admin/tpl/create-user.html',
            controller: 'User as vm'
          }).otherwise({redirectTo: '/admin'});
        }
})();

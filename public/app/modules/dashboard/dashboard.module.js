(function() {
    'use strict';

    angular
        .module('dashboard', [])
        .config(config);

        function config($routeProvider) {
          $routeProvider.when('/dashboard', {
            templateUrl: 'public/app/modules/dashboard/tpl/dashboard.html',
            controller: 'Dashboard',
            controllerAs: 'vm',
            resolve: { /* @ngInject */
                messages: function(DashboardService){
                   return DashboardService.getMessages()
                }
            }
          });
        }
})();

(function() {
    'use strict';

    angular
        .module('dashboard')
        .service('DashboardService', DashboardService);

    DashboardService.$inject = ['$http'];

    /* @ngInject */
    function DashboardService($http) {
        var service = {
          getMessages: getMessages
        }

        return service

        function getMessages() {
          return {"id": 1, "label": "Test"}
        }
    }
})();

(function() {
    'use strict';

    angular
        .module('admin')
        .controller('Admin', Admin);

    Admin.$inject = ['$http', '$location', 'users', 'AdminService'];

    /* @ngInject */

    function Admin($http, $location, users, AdminService) {
        var vm = this
        activate()
        /**
         * Handles execution of controller startup logic.
         */
        function activate() {
          vm.users = users.length > 0 ? users : []
        }
    }
})();

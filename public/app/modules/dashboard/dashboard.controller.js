(function() {
    'use strict';

    angular
        .module('dashboard')
        .controller('Dashboard', Dashboard);

    Dashboard.$inject = ['messages', '$interval', 'loginService', 'CONFIG'];

    /* @ngInject */
    function Dashboard(messages, $interval, loginService, CONFIG) {
        var vm = this
        vm.messages = messages
        vm.logout = logout
        activate();
        /**
         * Activates controller startup logic
         */
        function activate() {
          //console.log($rootScope.user)
          // loginService.startInterval($interval(function () {
          //   loginService.refreshToken()
          // }, CONFIG.interval))
        }
        /**
         * Logs out a user from the application
         * @return {[type]} [description]
         */
        function logout() { loginService.logout() }
    }


})();

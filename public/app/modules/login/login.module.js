(function() {
    'use strict';
    angular
      .module('login', [
        'ngMessages'
      ]).config(config);

      function config($routeProvider) {
        $routeProvider
            .when('/login',{
              templateUrl: 'public/app/modules/login/tpl/login.form.html',
              controller: 'Login',
              controllerAs: 'vm',
              access: {restricted: false, role: 'any'}
            });
      }
})();

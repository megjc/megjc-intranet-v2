(function() {
    'use strict';

    angular
        .module('login')
        .controller('Login', Login);

    Login.$inject = ['$location', '$timeout','loginService']
    /* @ngInject */
    function Login($location, $timeout, loginService) {
        var vm = this;
        vm.message = false
        vm.user = {}
        vm.processForm = processForm

        activate()

        function activate() {

        }

        function processForm(form) {
            loginService
              .authUser(vm.user)
              .then(function(res){
                if(res.status == 200){
                  if(form){
                      vm.user = {}
                      form.$setValidity()
                      form.$setPristine()
                      form.$setUntouched()
                  }
                  //console.log(res.data.token)
                  loginService.setToken(res.data.token)
                  $location.path('/admin')
                  //loginService.routeUser(res.data.role)
                }
              }).catch(function(error){
                console.log('Error: ', error)
              })
        }
    }
})();

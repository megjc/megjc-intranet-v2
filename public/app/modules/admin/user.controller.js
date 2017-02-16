(function() {
    'use strict';

    angular
        .module('admin')
        .controller('User', User);

    User.$inject = ['AdminService'];

    /* @ngInject */
    function User(AdminService) {
        var vm = this;
        vm.search = search
        vm.create = create

        activate()

        function activate() {

        }

        function search() {
          //console.log(vm.query)
          if(vm.query){
              AdminService
                .searchLDAP(vm.query)
                .then(function(res){
                  vm.results = res
                }).catch(function(res){
                  vm.results = []
                })
          }
        }
        /**
         * Creates an application user
         * @param  {[type]} user [description]
         * @return {[type]}      [description]
         */
        function create(user) {
          if(user){
            AdminService
              .createUser( user )
              .then(function (res) {
                console.log(res)
              }).catch(function (error) {
                 //show error message
              })
          }
        }
    }
})();

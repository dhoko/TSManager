'use strict';

var TSManager = angular.module('TSManager', ['ngResource','$strap.directives']);

TSManager.config(function($routeProvider) {
  $routeProvider.
      when('/', {
        controller: 'ListCtrl',
        templateUrl: 'partials/list.html',
        depth: 0
      }).
      when('/project/:id', {
        controller: 'EditCtrl',
        templateUrl: 'partials/details.html',
        depth: 1
      });
});

TSManager.run(function ($rootScope) {
  $rootScope.$on('$routeChangeSuccess', function(e, current, previous) {
    var direction = current && previous && current.depth < previous.depth;

  });
});
'use strict';

TSManager.controller('ListCtrl', function ($scope, Tasks) {
  // this.projects = Project.query();
  // 
	$scope.todos =  tasks;

	$scope.submitForm = function (data) {

		// update Tasks/id => data.id
		console.log(data);
	}
});
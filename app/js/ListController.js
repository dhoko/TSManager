'use strict';

TSManager.controller('ListCtrl', function ($scope, Tasks) {
  // this.projects = Project.query();
  // 
	$scope.todos =  Tasks.query();

	$scope.submitForm = function (data) {

		// update Tasks/id => data.id
		console.log(data);
	}
});
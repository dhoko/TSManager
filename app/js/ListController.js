'use strict';

TSManager.controller('ListCtrl', function ($scope, Tasks) {

	$scope.todos =  Tasks.query();
	$scope.alerts = [];

	$scope.alert = function() {
	}
	$scope.submitForm = function (data) {
		Tasks.update({id: data.id},data);
		$scope.alerts.push({
		    "type": "success",
		    "title": "Update success for " + data.name,
		    "content": "Isn't it wonderful ?"
		});
	}
});
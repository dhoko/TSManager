'use strict';

TSManager.controller('ListCtrl', function ($scope, Tasks) {

	$scope.todos =  Tasks.query();
	$scope.from = '-done';
	$scope.reverse = true;
	$scope.alerts = [];
	$scope.modal = {
	  "content": "Hello Modal",
	  "saved": false
	}

	$scope.create = function (task) {
		Tasks.save(task);
		$scope.todos.push(task);
	}

	$scope.remove = function (task) {

		var position = $scope.todos.indexOf(task);
		$scope.todos.splice(position,1);
		// console.log($scope.todos.indexOf(task) + ' - Position de la tach courant');
		Tasks.remove({id: task.id});
	}

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
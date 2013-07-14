'use strict';

TSManager.factory('Tasks', function($resource) {
  return $resource('/tasks/:id', { id: '@id' });
});
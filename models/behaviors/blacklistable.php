<?php
class BlacklistableBehavior extends ModelBehavior {

/**
 * Before find callback
 *
 * @param object $model Model using this behavior
 * @param array $queryData Data used to execute this query, i.e. conditions, order, etc.
 * @return boolean True if the operation should continue, false if it should abort
 * @access public
 */
	function beforeFind(&$model, $query) {
		if (isset($query['fields'])) return true;
		if (!isset($query['blacklist'])) return true;

		$fields = array_keys($model->schema());
		$query['fields'] = array_diff($fields, (array) $query['blacklist']);

		return $query;
	}
}
?>
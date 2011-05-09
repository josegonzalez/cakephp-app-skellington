<?php
/**
 * Enter description here...
 *
 * @package DummyData plugin
 * @author Ronny Vindenes (rvv)
 * @author Alexander Morland (alkemann)
 * @modifed 9. feb 2009
 */
class DummyFieldsController extends DummyAppController {
	public $name = 'DummyFields';
	
	function admin_index($dummy_table_id) {
		$this->DummyField->DummyTable->bindModel(array('hasMany' => array('DummyField'=>array('order' => 'DummyField.name'))));
		$data = $this->DummyField->DummyTable->findById($dummy_table_id);
		if (empty($data['DummyField'])) {
			$field_count = $this->DummyField->find('count');
			if ($field_count == 0) {
				$this->Session->setFlash(__('No fields. Analyze database', true));
				$this->redirect(array('action' => 'analyze_all', 'admin' => true));
			} else {
				$this->Session->setFlash(__('No fields for this table. Analyze table', true));
				$this->redirect(array(
						'action' => 'analyze', 
						$dummy_table_id, 
						'admin' => true));
			}
		}
		$this->set('types', $this->DummyField->DummyType->options());
		$this->set('editable', ($this->DummyField->useTable !== false));
		$this->set('contents', $this->DummyField->DummyTable->contents($dummy_table_id));
		$this->set('data', $data);
	}
	
	function admin_deactivate($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Field.', true));
			$this->redirect(array(
					'controller' => 'dummy_tables', 
					'action' => 'index', 
					'admin' => true));
		}
		$field = $this->DummyField->find('first', array(
				'recursive' => -1, 
				'conditions' => array('id' => $id), 
				'fields' => array('id', 'active', 'dummy_table_id')));
		if (!$field) {
			$this->Session->setFlash(__('Invalid Field.', true));
			$this->redirect(array(
					'controller' => 'dummy_tables', 
					'action' => 'index', 
					'admin' => true));
		}
		$field['DummyField']['active'] = false;
		if ($this->DummyField->save($field, false)) {
			$this->Session->setFlash(__('Field deactivated', true));
		} else {
			$this->Session->setFlash(__('Field save failed.', true));
		}
		$this->redirect(array(
				'action' => 'index', 
				$field['DummyField']['dummy_table_id'], 
				'admin' => true));
	}
	
	function admin_activate($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Field.', true));
			$this->redirect(array(
					'controller' => 'dummy_tables', 
					'action' => 'index', 
					'admin' => true));
		}
		$field = $this->DummyField->find('first', array(
				'recursive' => -1, 
				'conditions' => array('id' => $id), 
				'fields' => array('id', 'active', 'dummy_table_id')));
		if (!$field) {
			$this->Session->setFlash(__('Invalid Field.', true));
			$this->redirect(array(
					'controller' => 'dummy_tables', 
					'action' => 'index', 
					'admin' => true));
		}
		$field['DummyField']['active'] = true;
		if ($this->DummyField->save($field, false)) {
			$this->Session->setFlash(__('Field activated', true));
		} else {
			$this->Session->setFlash(__('Field save failed.', true));
		}
		$this->redirect(array(
				'action' => 'index', 
				$field['DummyField']['dummy_table_id'], 
				'admin' => true));
	}
	
	function admin_analyze_all() {
		if ($this->DummyField->analyze()) {
			$this->Session->setFlash(__('Fields analyzed.', true));
			$this->redirect(array(
					'controller' => 'dummy_tables', 
					'action' => 'index', 
					'admin' => true));
		}
		$this->render('admin_analyze');
	}
	
	function admin_analyze($dummy_table_id = null) {
		if (!$dummy_table_id) {
			$this->redirect(404);
		}
		if ($this->DummyField->analyze($dummy_table_id)) {
			$this->Session->setFlash(__('Fields analyzed.', true));
			$this->redirect(array('action' => 'index', $dummy_table_id, 'admin' => true));
		}
	}
	
	function admin_change($dummy_table_id) {
		if (!empty($this->data)) {
			if ($this->DummyField->save($this->data, false)) {
				$this->Session->setFlash(__('Field updated.', true));
			} else {
				$this->Session->setFlash(__('Field update failed!', true));
			}
		}
		$this->redirect(array('action' => 'index', $dummy_table_id, 'admin' => true));
	}
	
	function admin_edit($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Field.', true));
			$this->redirect(array(
					'controller' => 'dummy_tables', 
					'action' => 'index', 
					'admin' => true));
		}
		if (!empty($this->data)) {
			if ($this->DummyField->save($this->data)) {
				$this->Session->setFlash(__('Field updated.', true));
				if (isset($this->data['DummyField']['dummy_table_id'])) {
					$dummy_table_id = $this->data['DummyField']['dummy_table_id'];
				} else {
					$this->DummyField->read(array('id', 'dummy_table_id'));
					$dummy_table_id = $this->DummyField->data['DummyField']['dummy_table_id'];
				}
				$this->redirect(array(
						'action' => 'index', 
						$dummy_table_id, 
						'admin' => true));
			} else {
				$this->Session->setFlash(__('Field update failed!', true));
			}
		} else {
			$this->DummyField->id = $id;
			$this->DummyField->recursive = -1;			
			$this->data = $this->DummyField->read();
		}
	}

}

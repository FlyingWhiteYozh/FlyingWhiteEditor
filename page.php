<?php
namespace FWE;

class Page
{
	private $type, $id;
	public function __construct($id)
	{
		if(!strpos($id, ':')) error('Malformed ID');

		list($typeName, $id) = explode(':', $id);

		$this->type = Types::get($typeName);
		if(!$this->type) error('Type "' . $typeName . '" is not allowed');

		$this->id = explode('-', $id);
		if(count($this->type->id) != count($this->id)) error('Malformed ID ' . count($this->type->id) . '/' . count($this->id));
	}

	public function update($vars)
	{
		
		$sqlInsertFields = array();
		$sqlPlaceholders = array();
		$sqlUpdateFields = array();
		$sqlValues = array();
		
		foreach(array_keys($this->type->fields) as $fieldKey) {
			if(!isset($vars[$fieldKey])) error('Field "' . $fieldKey . '" not found');
			$sqlInsertFields[] = $fieldKey;
			$sqlPlaceholders[] = ':' . $fieldKey;
			$sqlUpdateFields[] = $fieldKey . ' = :' . $fieldKey;
			$sqlValues[$fieldKey] = $vars[$fieldKey];
		}
		// $sqlValues[$this->type->id] = $this->id;
		if($this->type->shouldInsert) {
			
			foreach($this->type->id as $n => $fieldKey) {
				$sqlInsertFields[] = $fieldKey;
				$sqlPlaceholders[] = ':' . $fieldKey;
				$sqlUpdateFields[] = $fieldKey . ' = :' . $fieldKey;
				$sqlValues[$fieldKey] = $this->id[$n];
			}
			$sql = 'INSERT INTO ' . $this->type->table . ' (' . implode(', ', $sqlInsertFields) . ') VALUES (' . implode(', ', $sqlPlaceholders) . ') ON DUPLICATE KEY UPDATE ' . implode(', ', $sqlUpdateFields);
		}
		else 
			$sql = 'UPDATE ' . $this->type->table . ' SET ' . implode(', ', $sqlUpdateFields) . $this->getWhereClause($sqlValues);
		$stmt = prepare($sql);
		// var_dump($sql, $sqlValues, $this->type);
		if($stmt->execute($sqlValues)) {
			echo '<div class="alert alert-success">OK! ' . $stmt->rowCount() . ' rows affected.</div>';
			// var_dump($this);
			$this->get();
		} else {
			error(var_export($stmt->errorInfo(), 1));
		}
	}

	public function get()
	{
		$content = '<input type="hidden" name="id" value="' . $this->type->name . ':' . (is_array($this->id) ? implode('-', $this->id) : $this->id) . '">';
		$sqlValues = array();
		$sql = 'SELECT ' . implode(',', array_keys($this->type->fields)) . ' FROM ' . $this->type->table . $this->getWhereClause($sqlValues);
		$stmt = prepare($sql);
		// var_dump($stmt);
		$result = $stmt->execute($sqlValues);
		if(!$result) {
			error(var_export($stmt->errorInfo(), 1));
		}
		$data = $stmt->fetch(\PDO::FETCH_ASSOC);
		if(!$data && !$this->type->shouldInsert) error('Page not found');
		
		foreach($this->type->fields as $fieldKey=>$field) {
			$content .= '<div class="form-group"><label for="' . $fieldKey . '">' . $field->name . '</label> <textarea class="form-control" rows="1" name="data[' . $fieldKey . ']" id="' . $fieldKey . '">' . htmlspecialchars($data[$fieldKey]) . '</textarea></div>';
		}
		// var_dump($this->type);
		echo $content;
	}

	private function getWhereClause(&$values)
	{
		$idFields = $this->type->id;
		$idValues = $this->id;

		$placeholders = array();

		foreach($idFields as $k=>$field) {
			$placeholders[] = $field . ' = :' . $field;
			$values[$field] = $idValues[$k];
		}

		return ' WHERE ' . implode(' && ', $placeholders);
	}

}

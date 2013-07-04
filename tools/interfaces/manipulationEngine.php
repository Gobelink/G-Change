<?php
interface manipulationEngine {
	public function createData($entity, $entityResource);

	public function retrieveData($entityResource);

	public function updateData($entity, $entityResource);

	public function deleteData($deletingEntityId, $entityResource);
}
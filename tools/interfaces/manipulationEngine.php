<?php
// Standard interface for interactig with a REST API
interface manipulationEngine {
	public function createData($entity, $entityResource);

	public function retrieveData($entityResource);

	public function updateData($entity, $entityResource);

	public function deleteData($deletingEntityId, $entityResource);
}
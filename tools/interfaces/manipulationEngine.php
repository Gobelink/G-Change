<?php
// Standard interface for interactig with a REST API
interface manipulationEngine {
	public function createData($entity, $entityResource);

	public function retrieveData($entityResource, $entityId, $displayPreference, $entityFilter);

	public function updateData($entity, $entityResource);

	public function deleteData($deletingEntityId, $entityResource);
}
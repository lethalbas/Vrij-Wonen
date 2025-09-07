<?php

require_once __DIR__ . "/base_service.php";

class PropertiesService extends BaseService {

    public function getByObjectId(int $objectId): array {
        return $this->repository->findByObjectId($objectId);
    }

    public function findByName(string $name): ?array {
        return $this->repository->findByName($name);
    }

    public function searchByName(string $searchTerm): array {
        if (strlen(trim($searchTerm)) < 2) {
            return [];
        }
        
        return $this->repository->searchByName(trim($searchTerm));
    }

    public function getPropertiesWithObjectCount(): array {
        return $this->repository->getPropertiesWithObjectCount();
    }

    public function connectToObject(int $objectId, array $propertyIds): bool {
        // Validate property IDs
        foreach ($propertyIds as $propertyId) {
            if (!is_numeric($propertyId) || $propertyId <= 0) {
                throw new InvalidArgumentException('Invalid property ID: ' . $propertyId);
            }
        }

        return $this->repository->connectToObject($objectId, $propertyIds);
    }

    public function validatePropertyData(array $data): array {
        $errors = [];

        if (empty($data['propertie'])) {
            $errors[] = 'Property name is required';
        } elseif (strlen($data['propertie']) < 2) {
            $errors[] = 'Property name must be at least 2 characters';
        } elseif (strlen($data['propertie']) > 100) {
            $errors[] = 'Property name must be less than 100 characters';
        }

        // Check if property already exists
        if (!empty($data['propertie'])) {
            $existing = $this->repository->findByName($data['propertie']);
            if ($existing && (!isset($data['id']) || $existing['id'] != $data['id'])) {
                $errors[] = 'Property with this name already exists';
            }
        }

        return $errors;
    }

    public function createProperty(array $data): bool {
        $errors = $this->validatePropertyData($data);
        if (!empty($errors)) {
            throw new InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
        }

        $sanitizedData = $this->sanitizeData($data);
        return $this->repository->create($sanitizedData);
    }

    public function updateProperty(int $id, array $data): bool {
        $errors = $this->validatePropertyData($data);
        if (!empty($errors)) {
            throw new InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
        }

        $sanitizedData = $this->sanitizeData($data);
        return $this->repository->update($id, $sanitizedData);
    }
}

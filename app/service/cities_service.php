<?php

require_once __DIR__ . "/base_service.php";

class CitiesService extends BaseService {

    public function getAllUsed(): array {
        return $this->repository->findAllUsed();
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

    public function getCitiesWithObjectCount(): array {
        return $this->repository->getCitiesWithObjectCount();
    }

    public function validateCityData(array $data): array {
        $errors = [];

        if (empty($data['citiename'])) {
            $errors[] = 'City name is required';
        } elseif (strlen($data['citiename']) < 2) {
            $errors[] = 'City name must be at least 2 characters';
        } elseif (strlen($data['citiename']) > 100) {
            $errors[] = 'City name must be less than 100 characters';
        }

        // Check if city already exists
        if (!empty($data['citiename'])) {
            $existing = $this->repository->findByName($data['citiename']);
            if ($existing && (!isset($data['id']) || $existing['id'] != $data['id'])) {
                $errors[] = 'City with this name already exists';
            }
        }

        return $errors;
    }

    public function createCity(array $data): bool {
        $errors = $this->validateCityData($data);
        if (!empty($errors)) {
            throw new InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
        }

        $sanitizedData = $this->sanitizeData($data);
        return $this->repository->create($sanitizedData);
    }

    public function updateCity(int $id, array $data): bool {
        $errors = $this->validateCityData($data);
        if (!empty($errors)) {
            throw new InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
        }

        $sanitizedData = $this->sanitizeData($data);
        return $this->repository->update($id, $sanitizedData);
    }

    public function deleteCity(int $id): bool {
        // Check if city is in use by any objects
        $objectsUsingCity = $this->repository->findBy(['cityid' => $id]);
        if (!empty($objectsUsingCity)) {
            throw new InvalidArgumentException('Cannot delete city: it is currently in use by one or more objects');
        }

        return $this->repository->delete($id);
    }
}

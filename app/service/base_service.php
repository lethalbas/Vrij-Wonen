<?php

require_once __DIR__ . "/service_interface.php";

abstract class BaseService implements ServiceInterface {
    protected $repository;

    public function __construct($repository) {
        $this->repository = $repository;
    }

    public function getAll(): array {
        return $this->repository->findAll();
    }

    public function getById(int $id): ?array {
        return $this->repository->findById($id);
    }

    public function create(array $data): bool {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool {
        return $this->repository->delete($id);
    }

    protected function validateRequired(array $data, array $requiredFields): array {
        $errors = [];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[] = "Field '{$field}' is required";
            }
        }
        
        return $errors;
    }

    protected function sanitizeData(array $data): array {
        return array_map(function($value) {
            if (is_string($value)) {
                return trim(strip_tags($value));
            }
            return $value;
        }, $data);
    }
}

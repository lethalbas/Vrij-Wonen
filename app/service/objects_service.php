<?php

require_once __DIR__ . "/base_service.php";
require_once __DIR__ . "/../util/file_handler_util.php";
require_once __DIR__ . "/../util/logging_util.php";

class ObjectsService extends BaseService {
    private $fileHandler;
    private $logger;

    public function __construct($repository) {
        parent::__construct($repository);
        $this->fileHandler = new file_handler_util();
        $this->logger = new logging_util();
    }

    public function getAllWithCity(): array {
        return $this->repository->findAllWithCity();
    }

    public function getAllFiltered(array $filters): array {
        return $this->repository->findAllFiltered($filters);
    }

    public function getByIdWithCity(int $id): ?array {
        return $this->repository->findByIdWithCity($id);
    }

    public function getByIdWithProperties(int $id): ?array {
        $object = $this->repository->findByIdWithCity($id);
        if (!$object) {
            return null;
        }

        $properties = $this->repository->getPropertiesByObjectId($id);
        
        return [
            'object' => $object,
            'properties' => $properties
        ];
    }

    public function createWithImages(array $data): bool {
        try {
            // Validate required fields
            $requiredFields = ['title', 'price', 'adress', 'cityid'];
            $errors = $this->validateRequired($data['object'], $requiredFields);
            
            if (!empty($errors)) {
                throw new InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
            }

            // Sanitize object data
            $objectData = $this->sanitizeData($data['object']);
            $objectData['sold'] = 0; // Default to not sold

            // Handle image uploads
            $imageFields = $this->handleImageUploads($data['images'] ?? []);
            $objectData = array_merge($objectData, $imageFields);

            // Create object with properties
            $properties = $data['properties'] ?? [];
            $success = $this->repository->createWithProperties($objectData, $properties);

            if (!$success) {
                // Clean up uploaded images on failure
                $this->cleanupImages($imageFields);
                throw new Exception('Failed to create object in database');
            }

            return true;

        } catch (Exception $e) {
            $this->logger->create_custom_log("Object creation failed: " . $e->getMessage());
            return false;
        }
    }

    public function updateWithImages(int $id, array $data): bool {
        try {
            // Get existing object for image cleanup
            $existingObject = $this->repository->findById($id);
            if (!$existingObject) {
                throw new InvalidArgumentException('Object not found');
            }

            // Sanitize object data
            $objectData = $this->sanitizeData($data['object']);

            // Handle image uploads and cleanup
            $imageFields = $this->handleImageUploads($data['images'] ?? [], $existingObject);
            $objectData = array_merge($objectData, $imageFields);

            // Update object with properties
            $properties = $data['properties'] ?? [];
            $success = $this->repository->updateWithProperties($id, $objectData, $properties);

            if (!$success) {
                // Clean up newly uploaded images on failure
                $this->cleanupImages($imageFields);
                throw new Exception('Failed to update object in database');
            }

            return true;

        } catch (Exception $e) {
            $this->logger->create_custom_log("Object update failed: " . $e->getMessage());
            return false;
        }
    }

    public function deleteWithCleanup(int $id): bool {
        try {
            $object = $this->repository->findById($id);
            if (!$object) {
                throw new InvalidArgumentException('Object not found');
            }

            $success = $this->repository->deleteWithRelations($id);

            if ($success) {
                // Clean up image files
                $this->cleanupObjectImages($object);
            }

            return $success;

        } catch (Exception $e) {
            $this->logger->create_custom_log("Object deletion failed: " . $e->getMessage());
            return false;
        }
    }

    private function handleImageUploads(array $images, ?array $existingObject = null): array {
        $imageFields = [];
        $imageKeys = ['mainimage', 'image2', 'image3', 'image4', 'image5'];

        foreach ($images as $key => $image) {
            if ($image && isset($image['tmp_name']) && !empty($image['tmp_name'])) {
                $uploadedName = $this->fileHandler->upload($image);
                if ($uploadedName) {
                    $imageFields[$imageKeys[$key - 1]] = $uploadedName;
                }
            }
        }

        // Preserve existing images if not being replaced
        if ($existingObject) {
            foreach ($imageKeys as $key) {
                if (!isset($imageFields[$key])) {
                    $imageFields[$key] = $existingObject[$key];
                }
            }
        }

        return $imageFields;
    }

    private function cleanupImages(array $imageFields): void {
        foreach ($imageFields as $imagePath) {
            if ($imagePath) {
                $this->fileHandler->delete($imagePath);
            }
        }
    }

    private function cleanupObjectImages(array $object): void {
        $imageFields = ['mainimage', 'image2', 'image3', 'image4', 'image5'];
        
        foreach ($imageFields as $field) {
            if (!empty($object[$field])) {
                try {
                    $this->fileHandler->delete($object[$field]);
                } catch (Exception $e) {
                    $this->logger->create_custom_log("Failed to delete image {$object[$field]}: " . $e->getMessage());
                }
            }
        }
    }

    public function validateFilters(array $filters): array {
        $errors = [];

        if (isset($filters['cityid']) && !is_numeric($filters['cityid']) && !is_array($filters['cityid'])) {
            $errors[] = 'City ID must be numeric or array of numeric values';
        }

        if (isset($filters['properties']) && !is_array($filters['properties'])) {
            $errors[] = 'Properties must be an array';
        }

        if (isset($filters['sold']) && !is_bool($filters['sold'])) {
            $errors[] = 'Sold status must be boolean';
        }

        return $errors;
    }
}

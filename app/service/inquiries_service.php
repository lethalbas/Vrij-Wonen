<?php

require_once __DIR__ . "/base_service.php";

class InquiriesService extends BaseService {

    public function getAllOrderedByHandled(): array {
        return $this->repository->findAllOrderedByHandled();
    }

    public function getByObjectId(int $objectId): array {
        return $this->repository->findByObjectId($objectId);
    }

    public function getByEmail(string $email): array {
        return $this->repository->findByEmail($email);
    }

    public function getUnhandled(): array {
        return $this->repository->findUnhandled();
    }

    public function getHandled(): array {
        return $this->repository->findHandled();
    }

    public function markAsHandled(int $id): bool {
        return $this->repository->markAsHandled($id);
    }

    public function markAsUnhandled(int $id): bool {
        return $this->repository->markAsUnhandled($id);
    }

    public function getInquiriesWithObjectInfo(): array {
        return $this->repository->getInquiriesWithObjectInfo();
    }

    public function getInquiryWithObjectInfo(int $id): ?array {
        return $this->repository->getInquiryWithObjectInfo($id);
    }

    public function getInquiryStats(): array {
        return $this->repository->getInquiryStats();
    }

    public function validateInquiryData(array $data): array {
        $errors = [];

        // Object ID validation
        if (empty($data['objectid']) || !is_numeric($data['objectid'])) {
            $errors[] = 'Valid object ID is required';
        }

        // Full name validation
        if (empty($data['fullname'])) {
            $errors[] = 'Full name is required';
        } elseif (strlen($data['fullname']) < 2) {
            $errors[] = 'Full name must be at least 2 characters';
        } elseif (strlen($data['fullname']) > 100) {
            $errors[] = 'Full name must be less than 100 characters';
        }

        // Email validation
        if (empty($data['replyemail'])) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($data['replyemail'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        // Message validation
        if (empty($data['message'])) {
            $errors[] = 'Message is required';
        } elseif (strlen($data['message']) < 10) {
            $errors[] = 'Message must be at least 10 characters';
        } elseif (strlen($data['message']) > 1000) {
            $errors[] = 'Message must be less than 1000 characters';
        }

        return $errors;
    }

    public function createInquiry(array $data): bool {
        $errors = $this->validateInquiryData($data);
        if (!empty($errors)) {
            throw new InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
        }

        $sanitizedData = [
            'objectid' => (int)$data['objectid'],
            'fullname' => trim(strip_tags($data['fullname'])),
            'replyemail' => trim(strtolower($data['replyemail'])),
            'message' => trim(strip_tags($data['message']))
        ];

        return $this->repository->createInquiry($sanitizedData);
    }

    public function updateInquiry(int $id, array $data): bool {
        $errors = $this->validateInquiryData($data);
        if (!empty($errors)) {
            throw new InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
        }

        $sanitizedData = [
            'objectid' => (int)$data['objectid'],
            'fullname' => trim(strip_tags($data['fullname'])),
            'replyemail' => trim(strtolower($data['replyemail'])),
            'message' => trim(strip_tags($data['message']))
        ];

        return $this->repository->update($id, $sanitizedData);
    }

    public function toggleHandledStatus(int $id): bool {
        $inquiry = $this->repository->findById($id);
        if (!$inquiry) {
            throw new InvalidArgumentException('Inquiry not found');
        }

        if ($inquiry['handled']) {
            return $this->repository->markAsUnhandled($id);
        } else {
            return $this->repository->markAsHandled($id);
        }
    }

    public function getInquiryCounts(): array {
        $stats = $this->repository->getInquiryStats();
        return [
            'total' => (int)$stats['total_inquiries'],
            'unhandled' => (int)$stats['unhandled_inquiries'],
            'handled' => (int)$stats['handled_inquiries'],
            'unique_objects' => (int)$stats['unique_objects']
        ];
    }
}

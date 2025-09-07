<?php

interface RepositoryInterface {
    public function findAll(): array;
    public function findById(int $id): ?array;
    public function findBy(array $criteria): array;
    public function create(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function count(): int;
}

<?php

namespace App\Utils\Repository;

use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template T of Model
 * @implements BaseRepository<T>
 */
abstract class BaseRepository
{
    /**
     * @return T
     */
    abstract protected function getModel(): Model;

    /** @return T */
    public function create(array $attributes): Model
    {
        return $this->getModel()->create($attributes);
    }

    /** @return Collection<T> */
    public function get(?array $conditions = null, array $relations = []): Collection
    {
        return $this->fetchData($conditions, $relations)->get();
    }

    /** @return LengthAwarePaginator<T> */
    public function paginate(?array $conditions = null, array $relations = [], ?int $perPage = null): LengthAwarePaginator
    {
        return $this->fetchData($conditions, $relations)->paginate($perPage);
    }

    /** @return ?T */
    public function findById(int $modelId, array $relations = []): ?Model
    {
        return $this->getModel()->query()->with($relations)->find($modelId);
    }

    /** @return T */
    public function findByIdOrFail(int $modelId, array $relations = []): Model
    {
        return $this->getModel()->query()->with($relations)->findOrFail($modelId);
    }

    /** @return ?T */
    public function first(?array $conditions = null, array $relations = []): ?Model
    {
        return $this->fetchData($conditions, $relations)->first();
    }

    /** @return T */
    public function firstOrFail(?array $conditions = null, array $relations = []): Model
    {
        return $this->fetchData($conditions, $relations)->firstOrFail();
    }

    public function count(?array $conditions = null): int
    {
        return $this->fetchData($conditions, [])->count();
    }

    public function exists(?array $conditions = null): bool
    {
        return $this->fetchData($conditions, [])->exists();
    }

    /** @return T */
    public function update(int $modelId, array $values): Model
    {
        $model = $this->findByIdOrFail($modelId);
        $model->fill($values);
        $model->saveOrFail();

        return $model;
    }

    /** @return T */
    public function firstOrCreate(array $attributes, array $values): Model
    {
        return $this->getModel()->query()->firstOrCreate(
            $attributes,
            $values
        );
    }

    /** @return T */
    public function updateOrCreate(array $attributes, array $values): Model
    {
        return $this->getModel()->query()->updateOrCreate(
            $attributes,
            $values
        );
    }

    public function deleteById(int $modelId): bool
    {
        return $this->findByIdOrFail($modelId)->delete();
    }

    /**
     * @param array<array> $values
     * @return boolean
     */
    public function batchInsert(array $values): bool
    {
        if ($this->getModel()->timestamps) {
            $now = Date::now();
            foreach ($values as &$value) {
                if ($this->getModel()->getCreatedAtColumn()) {
                    $value[$this->getModel()->getCreatedAtColumn()] = $now;
                }
                if ($this->getModel()->getUpdatedAtColumn()) {
                    $value[$this->getModel()->getUpdatedAtColumn()] = $now;
                }
            }
        }

        return $this->getModel()->insert($values);
    }

    public function batchUpdate(array $conditions, array $values): int
    {
        return $this->fetchData($conditions, [])->update($values);
    }

    public function batchDelete(array $conditions): int
    {
        return $this->fetchData($conditions, [])->delete();
    }

    private function fetchData(?array $conditions, array $relations)
    {
        return $this
            ->getModel()
            ->query()
            ->when(
                $conditions,
                fn($query) => $query->where($conditions)
            )
            ->with($relations);
    }
}

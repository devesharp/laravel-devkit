<?php

namespace Devesharp\Support;

use Illuminate\Support\Str;

/**
 * Trait Sortable.
 */
trait SortableTrait
{
    public $query = null;

    public static function bootSortableTrait()
    {
        static::created(function ($model) {
            if ($model->shouldSortWhenCreating) {
                $model->moveToStart();
            }
        });
    }

    public function createSortable()
    {
        $this->buildSortQuery()->create([
            $this->orderColumnNameForeignKey => $this->id,
            $this->orderColumnName => $this->getHighestOrderNumber(),
        ]);

        return $this;
    }

    public function getHighestOrderNumber(): int
    {
        $lastSortable = $this->buildSortQuery()
            ->where($this->orderColumnName, '!=', null)
            ->orderBy($this->orderColumnName, 'desc')
            ->first();

        if (! empty($lastSortable)) {
            return $lastSortable->{$this->getGroup()} + 1;
        } else {
            return 0;
        }
    }

    /**
     * Mandar para o comeco da lista.
     *
     * @return $this
     */
    public function moveToStart()
    {
        $this->moveTo(0);

        return $this;
    }

    /**
     * Mandar para o final da lista.
     *
     * @return $this
     */
    public function moveToEnd()
    {
        $this->moveTo(PHP_INT_MAX);

        return $this;
    }

    /**
     * Remover sortable do grupo.
     *
     * @return $this
     */
    public function removeSortable()
    {
        $this->moveTo(PHP_INT_MAX);

        if (isset($this->orderTableName)) {
            $this->buildSortQuery()
                ->where($this->orderColumnNameForeignKey, $this->id)
                ->delete();
        } else {
            $this->update([
                $this->getGroup() => null,
            ]);
        }

        return $this;
    }

    public function getSort()
    {
        $model = $this->buildSortQuery()
            ->where($this->orderColumnNameForeignKey, $this->id)
            ->first();

        return $model->{$this->orderColumnName} ?? null;
    }

    /**
     * Mover sortable.
     *
     * @param  int   $position
     * @return $this
     */
    public function moveTo(int $position)
    {
        if (isset($this->orderTableName)) {
            return $this->moveToWithTable($position);
        } else {
            return $this->moveToWithColumn($position);
        }
    }

    public function moveToWithTable(int $position)
    {
        $model = $this->buildSortQuery()
            ->where($this->orderColumnNameForeignKey, $this->id)
            ->first();

        if (empty($model))
            return $this;

        /*
         * Não pode ser menor que zero
         */
        if (0 > $position) {
            $position = 0;
        }

        /**
         *  Não pode ser maior que o numero de sortable.
         */
        $highestOrder = $this->getHighestOrderNumber() - 1;

        if ($position > $highestOrder) {
            $position = $highestOrder;
        }

        if ($position === $model->{$this->orderColumnName}) {
            return $this;
        }

        $query = $this->buildSortQuery();

        if (isset($model->{$this->orderColumnName})) {
            if ($model->{$this->orderColumnName} > $position) {
                $query
                    ->where($this->orderColumnName, '>=', $position)
                    ->where(
                        $this->orderColumnName,
                        '<=',
                        $model->{$this->orderColumnName},
                    )
                    ->increment($this->orderColumnName);
            } else {
                $query
                    ->where(
                        $this->orderColumnName,
                        '>',
                        $model->{$this->orderColumnName},
                    )
                    ->where($this->orderColumnName, '<=', $position)
                    ->increment($this->orderColumnName, -1);
            }
        } else {
            $query
                ->where($this->orderColumnName, '>=', $position)
                ->increment($this->orderColumnName);
        }

        $model->refresh();

        // Salva posição
        $model->{$this->orderColumnName} = $position;
        $model->save();

        return $this;
    }

    public function moveToWithColumn(int $position)
    {
        $this->refresh();

        if ($position === $this->{$this->getGroup()}) {
            return $this;
        }

        /*
         * Não pode ser menor que zero
         */
        if (0 > $position) {
            $position = 0;
        }

        /**
         *  Não pode ser maior que o numero de sortable.
         */
        $lastSortable = $this->buildSortQuery()
            ->where($this->getGroup(), '!=', null)
            ->orderBy($this->getGroup(), 'desc')
            ->first();

        if (! empty($lastSortable)) {
            // Caso o ultimo sortable tenha a mesma posição, não pode ser maior que o ultimo sortable
            if ($lastSortable->id == $this->id) {
                if ($position >= $lastSortable->{$this->getGroup()}) {
                    $position = $lastSortable->{$this->getGroup()};
                }

                // Caso já seja a ultima posição, não precisa fazer nada
                if ($position == $this->{$this->getGroup()}) {
                    return $this;
                }
            }else if ($position > $lastSortable->{$this->getGroup()} + 1) {
                $position = $lastSortable->{$this->getGroup()} + 1;
            }
        } else {
            $position = 0;
        }

        $query = $this->buildSortQuery();
        if (isset($this->{$this->getGroup()})) {
            if ($this->{$this->getGroup()} > $position) {
                $query
                    ->where($this->getGroup(), '>=', $position)
                    ->where($this->getGroup(), '<=', $this->{$this->getGroup()})
                    ->increment($this->getGroup());
            } else {
                $query
                    ->where($this->getGroup(), '>=', $this->{$this->getGroup()})
                    ->where($this->getGroup(), '<=', $position)
                    ->increment($this->getGroup(), -1);
            }
        } else {
            $query
                ->where($this->getGroup(), '>=', $position)
                ->increment($this->getGroup());
        }

        $this->refresh();

        // Salva posição
        $this->{$this->getGroup()} = $position;
        $this->save();

        return $this;
    }

    /**
     * Resgatar grupo.
     *
     * @return string
     */
    public function getGroup()
    {
        return $this->orderColumnName ?? 'order_column';
    }

    /**
     * Definir grupo.
     *
     * @param  $name
     * @return $this
     */
    public function group($name)
    {
        $this->orderColumnName = $name;

        return $this;
    }

    /**
     * Cria query que deve ser usada para pesquisar os sortables.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function buildSortQuery()
    {
        if (isset($this->orderTableName)) {
            return (new $this->orderTableName())->newQuery();
        } else {
            return $this->newQuery();
        }
    }

    /**
     * Organizar sortable.
     *
     * @param string                                     $group
     * @param \Illuminate\Database\Eloquent\Builder|null $query
     */
    public function organizeSortable($group = '')
    {
        if (isset($this->orderTableName)) {
            $sortable = 0;
            $this->buildSortQuery()
                ->where($this->orderColumnName, '!=', null)
                ->whereNotNull($this->orderColumnName)
                ->orderBy($this->orderColumnName, 'asc')
                ->chunk(2, function ($items) use (&$sortable) {
                    foreach ($items as $item) {
                        if ($item->{$this->orderColumnName} !== $sortable) {
                            $item->{$this->orderColumnName} = $sortable;
                            $item->update();
                        }
                        ++$sortable;
                    }
                });
        } else {
            $groupName = ! empty($group) ? $group : $this->getGroup();

            $sortable = 0;

            if (! isset($query)) {
                $query = $this->buildSortQuery();
            }

            $query
                ->where($this->getGroup(), '!=', null)
                ->whereNotNull($groupName)
                ->orderBy($groupName, 'asc')
                ->chunk(2, function ($items) use (&$sortable, $groupName) {
                    foreach ($items as $item) {
                        if ($item->{$groupName} !== $sortable) {
                            $item->{$groupName} = $sortable;
                            $item->save();
                        }
                        ++$sortable;
                    }
                });
        }
    }
}

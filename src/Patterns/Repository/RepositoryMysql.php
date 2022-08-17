<?php

namespace Devesharp\Patterns\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RepositoryMysql extends RepositoryInterface
{
    protected $primaryKey = 'id';

    /**
     * @var bool Verifica se tabela está deletada por "enabled"
     */
    protected bool $disableEnabledColumn = false;

    /**
     * @var bool Ao invés de deletar coluna, apenas atualiza enabled = false
     */
    protected $softDelete = true;

    /**
     * @var Model
     */
    protected $model = null;

    /**
     * @var Builder
     */
    protected $modelQuery = null;

    /**
     * @var string
     */
    public $tableName = null;

    public function __construct()
    {
        $this->modelQuery = new $this->model();
        $this->tableName = $this->modelQuery->getTable();
        $this->modelQuery = $this->modelQuery->query();
    }

    public function create($body)
    {
        return $this->model::create($body);
    }

    public function createMany($values)
    {
        return (new $this->model())->newQuery()->insert($values);
    }

    /**
     * Resgatar recurso por ID.
     *
     * @param $id
     * @param bool $enabled
     *
     * @return null
     */
    public function findById($id, $enabled = true)
    {
        $this->clearQuery();

        $model = $this->modelQuery
            ->where((new $this->model())->getTable() . '.' . $this->primaryKey, intval($id))
            ->limit(1)
            ->first();

        if (
            ! empty($model) &&
            $enabled &&
            ! $model->enabled &&
            ! $this->disableEnabledColumn
        ) {
            return null;
        }

        return $model;
    }

    /**
     * Resgatar recurso por ID ou falhar.
     *
     * @param $id
     * @param bool $enabled
     *
     * @throws \Devesharp\Exceptions\Exception
     *
     * @return null
     */
    public function findIdOrFail($id, $enabled = true)
    {
        $model = $this->findById($id, $enabled);

        if (empty($model)) {
            \Devesharp\Exceptions\Exception::NotFound();
        }

        return $model;
    }

    public function findOne($enabled = true)
    {
        if ($enabled && ! $this->disableEnabledColumn) {
            $this->whereBoolean($this->tableName . '.enabled', true);
        }

        $model = $this->modelQuery->limit(1)->first();

        $this->clearQuery();

        if (
            ! empty($model) &&
            $enabled &&
            ! $model->enabled &&
            ! $this->disableEnabledColumn
        ) {
            return null;
        }

        return $model;
    }

    public function findMany($enabled = true)
    {
        if ($enabled && ! $this->disableEnabledColumn) {
            $this->whereBoolean($this->tableName . '.enabled', true);
        }

        $query = $this->modelQuery->get()->all();

        $this->clearQuery();

        return $query;
    }

    public function updateById($id, $body)
    {
        $model = $this->model::find($id);

        return $model->update($body);
    }

    public function update($body)
    {
        $model = $this->modelQuery->update($body);

        $this->clearQuery();

        return $model;
    }

    public function updateOne($body)
    {
        $query = $this->modelQuery->limit(1)->update($body);

        $this->clearQuery();

        return $query;
    }

    public function chunk(int $count, \Closure $callback)
    {
        $this->modelQuery->chunk($count, $callback);
        $this->clearQuery();
    }

    public function delete()
    {
        if ($this->softDelete) {
            $model = $this->update(['enabled' => 0]);
        } else {
            $model = $this->modelQuery->delete();
        }

        $this->clearQuery();

        return $model;
    }

    public function deleteById($id, $auth = null)
    {
        $model = new $this->model();
        $model = $model->where($this->primaryKey, $id);

        if ($this->softDelete) {
            if ($auth) {
                $model->update([
                    'enabled' => 0,
                    'deleted_at' => now(),
                    'deleted_by' => $auth->id,
                ]);
            }

            return $model->update(['enabled' => 0]);
        } else {
            return $model->delete();
        }
    }

    public function clearQuery()
    {
        $this->modelQuery = clone (new $this->model())->query();

        return $this;
    }

    public function whereRaw($column): self
    {
        $this->modelQuery = $this->modelQuery->whereRaw($column);

        return $this;
    }

    public function whereNull($column): self
    {
        $this->modelQuery = $this->modelQuery->whereNull($column);

        return $this;
    }

    public function whereNotNull($column): self
    {
        $this->modelQuery = $this->modelQuery->whereNotNull($column);

        return $this;
    }

    public function whereBoolean($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where($column, $value ? 1 : 0);

        return $this;
    }

    public function whereDate($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where($column, $value);

        return $this;
    }

    public function whereDateGt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where($column, '>', $value);

        return $this;
    }

    public function whereDateGte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where($column, '>=', $value);

        return $this;
    }

    public function whereDateLt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where($column, '<', $value);

        return $this;
    }

    public function whereDateLte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where($column, '<=', $value);

        return $this;
    }

    public function whereEqual($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where($column, $value);

        return $this;
    }

    public function whereInt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where($column, intval($value));

        return $this;
    }

    public function whereIntGt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '>',
            intval($value),
        );

        return $this;
    }

    public function whereIntGte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '>=',
            intval($value),
        );

        return $this;
    }

    public function whereIntLt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '<',
            intval($value),
        );

        return $this;
    }

    public function whereIntLte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '<=',
            intval($value),
        );

        return $this;
    }

    public function whereNotInt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '!=',
            intval($value),
        );

        return $this;
    }

    public function whereNumber($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where($column, $value);

        return $this;
    }

    public function whereNumberGt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '>',
            $value,
        );

        return $this;
    }

    public function whereNumberGte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '>=',
            $value,
        );

        return $this;
    }

    public function whereNumberLt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '<',
            $value,
        );

        return $this;
    }

    public function whereNumberLte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '<=',
            $value,
        );

        return $this;
    }

    public function whereNotNumber($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            '!=',
            $value,
        );

        return $this;
    }

    public function whereSameString($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where($column, $value);

        return $this;
    }

    public function whereLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where($column, 'LIKE', $value);

        return $this;
    }

    public function whereNotLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            'NOT LIKE',
            $value,
        );

        return $this;
    }

    public function whereBeginWithLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            'LIKE',
            $value . '%',
        );

        return $this;
    }

    public function whereEndWithLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            'LIKE',
            '%' . $value,
        );

        return $this;
    }

    public function whereContainsLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->where(
            $column,
            'LIKE',
            '%' . $value . '%',
        );

        return $this;
    }

    public function whereContainsExplodeString($column, $value): self
    {
        $value = '%' . str_replace(' ', '%', $value) . '%';
        $this->modelQuery = $this->modelQuery->where($column, 'LIKE', $value);

        return $this;
    }

    public function whereArrayInt($column, $value): self
    {
        $value = array_map('intVal', $value);
        $this->modelQuery = $this->modelQuery->whereIn($column, $value);

        return $this;
    }

    public function whereArrayNotInt($column, $value): self
    {
        $value = array_map('intVal', $value);
        $this->modelQuery = $this->modelQuery->whereNotIn($column, $value);

        return $this;
    }

    public function whereArrayString($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->whereIn($column, $value);

        return $this;
    }

    public function whereArrayNotString($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->whereNotIn($column, $value);

        return $this;
    }

    public function whereArrayIntJson($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->whereJsonContains($column, $value);

        return $this;
    }

    public function orWhereArrayIntJson($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhereJsonContains($column, $value);

        return $this;
    }

    public function orWhereBoolean($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, $value ? 1 : 0);

        return $this;
    }

    public function orWhereDate($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, $value);

        return $this;
    }

    public function orWhereDateGt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, '>', $value);

        return $this;
    }

    public function orWhereDateGte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, '>=', $value);

        return $this;
    }

    public function orWhereDateLt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, '<', $value);

        return $this;
    }

    public function orWhereDateLte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, '<=', $value);

        return $this;
    }

    public function orWhereInt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, intval($value));

        return $this;
    }

    public function orWhereNotInt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '!=',
            intval($value),
        );

        return $this;
    }

    public function orWhereIntGt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '>',
            intval($value),
        );

        return $this;
    }

    public function orWhereIntGte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '>=',
            intval($value),
        );

        return $this;
    }

    public function orWhereIntLt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '<',
            intval($value),
        );

        return $this;
    }

    public function orWhereIntLte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '<=',
            intval($value),
        );

        return $this;
    }

    public function orWhereNumber($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, $value);

        return $this;
    }

    public function orWhereNotNumber($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '!=',
            $value,
        );

        return $this;
    }

    public function orWhereNumberGt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '>',
            $value,
        );

        return $this;
    }

    public function orWhereNumberGte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '>=',
            $value,
        );

        return $this;
    }

    public function orWhereNumberLt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '<',
            $value,
        );

        return $this;
    }

    public function orWhereNumberLte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            '<=',
            $value,
        );

        return $this;
    }

    public function orWhereSameString($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, $value);

        return $this;
    }

    public function orWhereLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, 'LIKE', $value);

        return $this;
    }

    public function orWhereNotLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            'NOT LIKE',
            $value,
        );

        return $this;
    }

    public function orWhereBeginWithLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            'LIKE',
            $value . '%',
        );

        return $this;
    }

    public function orWhereEndWithLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            'LIKE',
            '%' . $value,
        );

        return $this;
    }

    public function orWhereContainsLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere(
            $column,
            'LIKE',
            '%' . $value . '%',
        );

        return $this;
    }

    public function orWhereContainsExplodeString($column, $value): self
    {
        $value = '%' . str_replace(' ', '%', $value) . '%';
        $this->modelQuery = $this->modelQuery->orWhere($column, 'LIKE', $value);

        return $this;
    }

    public function orWhereArrayInt($column, $value): self
    {
        if (! is_array($value)) {
            $this->modelQuery = $this->modelQuery->orWhereRaw(
                'FIND_IN_SET(?,' . $column . ')',
                [$value],
            );
        } else {
            $value = array_map('intVal', $value);
            $this->modelQuery = $this->modelQuery->orWhereIn($column, $value);
        }

        return $this;
    }

    public function orWhereArrayNotInt($column, $value): self
    {
        $value = array_map('intVal', $value);
        $this->modelQuery = $this->modelQuery->orWhereNotIn($column, $value);

        return $this;
    }

    public function orWhereArrayString($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhereIn($column, $value);

        return $this;
    }

    public function orWhereArrayNotString($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhereNotIn($column, $value);

        return $this;
    }

    public function orWhereNull($column): self
    {
        $this->modelQuery = $this->modelQuery->orWhereNull($column);

        return $this;
    }

    public function orWhereNotNull($column): self
    {
        $this->modelQuery = $this->modelQuery->orWhereNotNull($column);

        return $this;
    }

    public function orWhereEqual($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orWhere($column, $value);

        return $this;
    }

    public function orWhere($callback): self
    {
        $that = $this;
        $this->modelQuery = $this->modelQuery->orWhere(function ($model) use (
            $callback,
            $that
        ) {
            $this->modelQuery = $model;
            $callback($that);
        });

        return $this;
    }

    public function andWhere($callback): self
    {
        $that = $this;
        $this->modelQuery = $this->modelQuery->where(function ($model) use (
            $callback,
            $that
        ) {
            $this->modelQuery = $model;
            $callback($that);
        });

        return $this;
    }

    public function havingNull($column): self
    {
        $this->modelQuery = $this->modelQuery->havingRaw($column . ' IS NULL');

        return $this;
    }

    public function havingNotNull($column): self
    {
        $this->modelQuery = $this->modelQuery->havingRaw($column . ' IS NOT NULL');

        return $this;
    }

    public function havingBoolean($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having($column, $value ? 1 : 0);

        return $this;
    }

    public function havingDate($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having($column, $value);

        return $this;
    }

    public function havingDateGt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having($column, '>', $value);

        return $this;
    }

    public function havingDateGte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having($column, '>=', $value);

        return $this;
    }

    public function havingDateLt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having($column, '<', $value);

        return $this;
    }

    public function havingDateLte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having($column, '<=', $value);

        return $this;
    }

    public function havingEqual($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having($column, $value);

        return $this;
    }

    public function havingInt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having($column, intval($value));

        return $this;
    }

    public function havingNotInt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '!=',
            intval($value),
        );

        return $this;
    }

    public function havingIntLte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '<=',
            intval($value),
        );

        return $this;
    }

    public function havingIntGt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '>',
            intval($value),
        );

        return $this;
    }

    public function havingIntGte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '>=',
            intval($value),
        );

        return $this;
    }

    public function havingIntLt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '<',
            intval($value),
        );

        return $this;
    }

    public function havingNumber($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having($column, $value);

        return $this;
    }

    public function havingNotNumber($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '!=',
            $value,
        );

        return $this;
    }

    public function havingNumberLte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '<=',
            $value,
        );

        return $this;
    }

    public function havingNumberGt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '>',
            $value,
        );

        return $this;
    }

    public function havingNumberGte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '>=',
            $value,
        );

        return $this;
    }

    public function havingNumberLt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            '<',
            $value,
        );

        return $this;
    }

    public function havingSameString($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having($column, $value);

        return $this;
    }

    public function havingLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having($column, 'LIKE', $value);

        return $this;
    }

    public function havingNotLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            'NOT LIKE',
            $value,
        );

        return $this;
    }

    public function havingBeginWithLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            'LIKE',
            $value . '%',
        );

        return $this;
    }

    public function havingEndWithLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            'LIKE',
            '%' . $value,
        );

        return $this;
    }

    public function havingContainsLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having(
            $column,
            'LIKE',
            '%' . $value . '%',
        );

        return $this;
    }

    public function havingContainsExplodeString($column, $value): self
    {
        $value = '%' . str_replace(' ', '%', $value) . '%';

        $this->modelQuery = $this->modelQuery->having($column, 'LIKE', $value);

        return $this;
    }

    public function havingArrayInt($column, $value): self
    {
        $value = array_map('intVal', $value);
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '"' . $column . '" in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->havingRaw($query, $value);

        return $this;
    }

    public function havingArrayNotInt($column, $value): self
    {
        $value = array_map('intVal', $value);
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '"' . $column . '" not in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->havingRaw($query, $value);

        return $this;
    }

    public function havingArrayString($column, $value): self
    {
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '"' . $column . '" in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->havingRaw($query, $value);

        return $this;
    }

    public function havingArrayNotString($column, $value): self
    {
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '"' . $column . '" not in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->havingRaw($query, $value);

        return $this;
    }

    public function orHavingBoolean($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having($column, $value ? 1 : 0);

        return $this;
    }

    public function orHavingDate($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving($column, $value);

        return $this;
    }

    public function orHavingDateGt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving($column, '>', $value);

        return $this;
    }

    public function orHavingDateGte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving($column, '>=', $value);

        return $this;
    }

    public function orHavingDateLt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving($column, '<', $value);

        return $this;
    }

    public function orHavingDateLte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving($column, '<=', $value);

        return $this;
    }

    public function orHavingInt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            intval($value),
        );

        return $this;
    }

    public function orHavingNotInt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '!=',
            intval($value),
        );

        return $this;
    }

    public function orHavingIntGt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '>',
            intval($value),
        );

        return $this;
    }

    public function orHavingIntGte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '>=',
            intval($value),
        );

        return $this;
    }

    public function orHavingIntLt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '<',
            intval($value),
        );

        return $this;
    }

    public function orHavingIntLte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '<=',
            intval($value),
        );

        return $this;
    }

    public function orHavingNumber($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving($column, $value);

        return $this;
    }

    public function orHavingNotNumber($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '!=',
            $value,
        );

        return $this;
    }

    public function orHavingNumberGt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '>',
            $value,
        );

        return $this;
    }

    public function orHavingNumberGte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '>=',
            $value,
        );

        return $this;
    }

    public function orHavingNumberLt($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '<',
            $value,
        );

        return $this;
    }

    public function orHavingNumberLte($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            '<=',
            $value,
        );

        return $this;
    }

    public function orHavingSameString($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving($column, $value);

        return $this;
    }

    public function orHavingLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            'LIKE',
            $value,
        );

        return $this;
    }

    public function orHavingNotLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            'NOT LIKE',
            $value,
        );

        return $this;
    }

    public function orHavingBeginWithLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            'LIKE',
            $value . '%',
        );

        return $this;
    }

    public function orHavingEndWithLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            'LIKE',
            '%' . $value,
        );

        return $this;
    }

    public function orHavingContainsLike($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            'LIKE',
            '%' . $value . '%',
        );

        return $this;
    }

    public function orHavingContainsExplodeString($column, $value): self
    {
        $value = '%' . str_replace(' ', '%', $value) . '%';
        $this->modelQuery = $this->modelQuery->orHaving(
            $column,
            'LIKE',
            $value,
        );

        return $this;
    }

    public function orHavingArrayInt($column, $value): self
    {
        $value = array_map('intVal', $value);
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '"' . $column . '" in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->orHavingRaw($query, $value);

        return $this;
    }

    public function orHavingArrayNotInt($column, $value): self
    {
        $value = array_map('intVal', $value);
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '"' . $column . '" not in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->orHavingRaw($query, $value);

        return $this;
    }

    public function orHavingArrayString($column, $value): self
    {
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '"' . $column . '" in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->orHavingRaw($query, $value);

        return $this;
    }

    public function orHavingArrayNotString($column, $value): self
    {
        $bind = range(0, count($value) - 1);
        $bind = array_map(function () {
            return '?';
        }, $bind);

        $query = '"' . $column . '" not in (' . implode(', ', $bind) . ')';
        $this->modelQuery = $this->modelQuery->orHavingRaw($query, $value);

        return $this;
    }

    public function orHavingNull($column): self
    {
        $query = '"' . $column . '" IS NULL';

        $this->modelQuery = $this->modelQuery->orHavingRaw($query);

        return $this;
    }

    public function orHavingNotNull($column): self
    {
        $query = '"' . $column . '" IS NOT NULL';

        $this->modelQuery = $this->modelQuery->orHavingRaw($query);

        return $this;
    }

    public function orHavingEqual($column, $value): self
    {
        $this->modelQuery = $this->modelQuery->having($column, $value);

        return $this;
    }


    public function orderBy($column, $order): self
    {
        if ('asc' === $order) {
            $this->modelQuery = $this->modelQuery->orderBy($column, $order);
        } elseif ('desc' === $order) {
            $this->modelQuery = $this->modelQuery->orderBy($column, $order);
        }

        return $this;
    }

    public function orderByRaw($column): self
    {
        $this->modelQuery = $this->modelQuery->orderByRaw($column);

        return $this;
    }

    public function limit($limit = null): self
    {
        if (! empty($limit)) {
            $this->modelQuery = $this->modelQuery->limit($limit);
        }

        return $this;
    }

    public function offset($offset = null): self
    {
        $this->modelQuery = $this->modelQuery->offset($offset);

        return $this;
    }

    public function count($enabled = true): int
    {
        if ($enabled && ! $this->disableEnabledColumn) {
            $this->whereBoolean($this->tableName . '.enabled', true);
        }

        return $this->modelQuery->count();
    }

    public function cursor($enabled = true)
    {
        if ($enabled && ! $this->disableEnabledColumn) {
            $this->whereBoolean($this->tableName . '.enabled', true);
        }

        return $this->modelQuery->cursor();
    }

    public function increment(string $column, $amount = 1, $enabled = true)
    {
        if ($enabled && ! $this->disableEnabledColumn) {
            $this->whereBoolean($this->tableName . '.enabled', true);
        }

        $increment = $this->modelQuery->increment($column, $amount);

        $this->clearQuery();

        return $increment;
    }

    public function getBuilder()
    {
        return $this->modelQuery;
    }

    public function __clone()
    {
        $this->modelQuery = clone $this->modelQuery;
    }
}

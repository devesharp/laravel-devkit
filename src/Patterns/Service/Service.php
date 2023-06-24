<?php

namespace Devesharp\Patterns\Service;

use Devesharp\Exceptions\Exception;
use Devesharp\Patterns\Repository\RepositoryInterface;
use Devesharp\Patterns\Transformer\Transformer;
use Devesharp\Support\Collection;
use Devesharp\Support\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Service
{
    /**
     * Sorts permitidas.
     */
    public array $sort = [];

    /**
     * @var string Sort padrão
     */
    public string $sort_default = '';

    /**
     * @var array Filtros permitidos
     */
    public array $filters = [];

    /**
     * @var int limit de resultados
     */
    public int $limitMax = 20;

    /**
     * @var int limit padrão
     */
    public int $limitDefault = 20;

    // Variável interna para resgatar apenas dados
    private bool $__getOnlyResults__ = false;

    // Variável interna para resgatar apenas quantidade
    private bool $__getOnlyCount__ = false;

    /**
     * @param  array $data valor é passado por referência, pois algumas operações podem alterar o valor
     * @param  null                $auth
     * @return RepositoryInterface
     */
    protected function makeSearch(&$data, $auth = null)
    {
        if ($data instanceof Collection) {
            return $this->filterSearch(
                $data->toArray(),
                $this->getQuerySearch($data->toArray()),
            );
        } else {
            return $this->filterSearch($data, $this->getQuerySearch($data));
        }
    }

    /**
     * @param  RepositoryInterface $repository
     * @param  mixed               $transformer
     * @param  mixed               $context
     * @param  mixed|null          $auth
     * @return array|int
     */
    protected function transformerSearch(
        $repository,
        $transformer,
        $context,
        $requester = null
    ) {
        return [
            'count' => $this->getSearchCount($repository),
            'results' => $this->getSearchResults($repository, $transformer, $context, $requester),
        ];
    }

    /**
     * Get count of search
     *
     * @param $repository
     * @return mixed
     */
    function getSearchCount($repository): int
    {
        return (clone $repository)
            ->limit(PHP_INT_MAX)
            ->offset(0)
            ->count();
    }

    /**
     * Get results of search
     *
     * @param $repository
     * @return mixed
     */
    function getSearchResults(
        $repository,
        $transformer,
        $context,
        $auth = null
    ): array
    {
        return Transformer::collection(
            (clone $repository)->findMany(),
            $transformer,
            $context,
            $auth,
        );
    }

    /**
     * @param $body
     * @param RepositoryInterface $repository
     *
     * @return RepositoryInterface
     */
    public function filterSearch($body, $repository)
    {
        $limit = $this->limitDefault;

        /*
         * Limit
         */
        if (isset($body['query']['limit'])) {
            $limit = intval($body['query']['limit']);
            if ($limit > $this->limitMax) {
                $limit = $this->limitMax;
            }
        }

        $repository->limit($limit);

        /*
         * Paginacão
         */
        if (isset($body['query']['page'])) {
            $page = intval($body['query']['page']);
            $page = $page < 1 ? 1 : $page;
            --$page;

            $repository->offset($page * $limit);
        }else if (isset($body['query']['offset'])) {
            $offset = intval($body['query']['offset']);

            $repository->offset($offset);
        }

        /*
         * Sort
         */
        $query['sort'] = $body['query']['sort'] ?? $this->sort_default;

        if (! empty($query['sort'])) {
            foreach (explode(',', $query['sort']) as $key => $value) {
                //DESC
                if ('-' == $value[0]) {
                    if (in_array(substr($value, 1), $this->sort)) {
                        $repository->orderBy(substr($value, 1), 'desc');
                    } else {
                        foreach ($this->sort as $sort_key => $sort_value) {
                            if (
                                is_array($sort_value) &&
                                $sort_key == substr($value, 1)
                            ) {
                                $repository->orderBy(
                                    $sort_value['column'],
                                    'desc',
                                );
                            }
                        }
                    }
                } else {
                    if (in_array($value, $this->sort)) {
                        $repository->orderBy($value, 'asc');
                    } else {
                        foreach ($this->sort as $sort_key => $sort_value) {
                            if (
                                (is_array($sort_value) &&
                                    $sort_key == $value) ||
                                (is_string($sort_value) &&
                                    $sort_value == $value)
                            ) {
                                $repository->orderBy(
                                    $sort_value['column'],
                                    'asc',
                                );
                            }
                        }
                    }
                }
            }
        }

        /*
         * Filtrar
         */
        if (isset($body['filters'])) {
            foreach ($body['filters'] as $key => $value) {
                if (isset($this->filters[$key])) {
                    $filter = $this->filters[$key];
                    $functionName = ucfirst(
                        \Illuminate\Support\Str::camel($filter['filter']),
                    );

                    if (Str::contains($filter['column'], 'Searchable')) {
                        $value = searchable_string($value);
                    }

                    if (Str::contains($filter['column'], 'raw:')) {
                        $filter['column'] = DB::raw(
                            str_replace('raw:', '', $filter['column']),
                        );
                    }

                    switch ($functionName) {
                        case 'DateGtStartDay':
                        case 'DateGteStartDay':
                        case 'DateLtStartDay':
                        case 'DateLteStartDay':
                            $functionName = str_replace('StartDay', '', $functionName);
                            $value = \Carbon\Carbon::make($value)->startOfDay();

                            break;
                        case 'DateGtEndDay':
                        case 'DateGteEndDay':
                        case 'DateLtEndDay':
                        case 'DateLteEndDay':
                            $functionName = str_replace('EndDay', '', $functionName);
                            $value = \Carbon\Carbon::make($value)->endOfDay();

                            break;
                    }

                    if (
                        empty($filter['clause']) ||
                        'where' == $filter['clause']
                    ) {
                        $repository->{'where' . $functionName}(
                            $filter['column'],
                            $value,
                        );
                    } else {
                        $repository->{'having' . $functionName}(
                            $filter['column'],
                            $value,
                        );
                    }
                }
            }
        }

        return $repository;
    }

    /**
     * Retorna repositorio com recursos a serem resolvidos na ações como:
     * Deletar, favoritar, destacar, etc..
     *
     * $target pode receber os seguintes itens:
     *
     * int $id
     * array $ids
     * $filters = []; // Mesmo utilizado em $this->search()
     *
     *
     * @param  $target
     * @param  null                $auth
     * @throws Exception
     * @return RepositoryInterface
     */
    public function makeSelectActions($target, $auth = null)
    {
        if (empty($target)) {
            throw new Exception('Resource not found', Exception::NOT_FOUND_RESOURCE);
        }

        /*
         * Verifica se é ID unico, Array de Ids ou um determinado filtro
         */
        if (!empty($target['id'])) {
            $query = $this->makeSearch($data, $auth);
            if ($query instanceof RepositoryInterface) {
                $query->whereInt($query->tableName . '.id', $target['id']);
            } else {
                $query->whereInt('id', $target['id']);
            }
        } elseif (!empty($target['ids'])) {
            $query = $this->makeSearch($data, $auth);

            // Deve ser array numerica ou string
            if (! Helpers::isArrayNumber($target['ids']) && ! Helpers::isArrayString($target['ids'])) {
                throw new Exception('Dado precisa ser uma array', Exception::DATA_ERROR_GENERAL);
            }

            if ($query instanceof RepositoryInterface) {
                $query->whereArrayInt($query->tableName . '.id', $target['ids']);
            } else {
                $query->whereArrayInt('id', $target['ids']);
            }
        } elseif (is_object($target) || Helpers::isArrayAssoc($target)) {
            if (empty($target['filters']) && empty($target['enable_select_all'])) {
                Exception::Exception(Exception::SEARCH_FILTERS_EMPTY);
            }

            $query = $this->makeSearch($target, $auth);
        } else {
            throw new Exception('Resource not found', Exception::DATA_ERROR_GENERAL);
        }

        $query->limit(0);

        return $query;
    }

    /**
     * @param  array|null $body
     * @return RepositoryInterface
     */
    public function getQuerySearch(array $body = null)
    {
        return (clone $this->repository)->clearQuery();
    }

    public function getOnlyResults() {
        $this->__getOnlyResults__ = true;
        return $this;
    }

    public function getOnlyCount() {
        $this->__getOnlyCount__ = true;
        return $this;
    }

    public function makeSearchPublic(&$data, $requester = null) {
        return $this->makeSearch($data, $requester);
    }
}

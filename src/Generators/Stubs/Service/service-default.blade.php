@php
    echo "<?php".PHP_EOL;
@endphp

namespace {{ $namespaceApp }};

use Devesharp\Patterns\Service\Service;
use Devesharp\Patterns\Service\ServiceFilterEnum;
use Devesharp\Patterns\Transformer\Transformer;
use {{ $dtoNamespace }}\Create{{ $resourceName }}Dto;
use {{ $dtoNamespace }}\Update{{ $resourceName }}Dto;
use {{ $dtoNamespace }}\Search{{ $resourceName }}Dto;
use {{ $dtoNamespace }}\Delete{{ $resourceName }}Dto;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\DB;

class {{ $resourceName }}Service extends Service
{
    /**
     * Sorts permitidas.
     */
    public array $sort = [
@foreach($filtersSort as $filter)
        '{{$filter['name']}}' => [
            'column' => '{{$tableName}}.{{$filter['name']}}',
        ],
@endforeach
    ];

    /**
     * @var string Sort padrão
     */
    public string $sort_default = '-id';

    /**
     * @var int limit de resultados
     */
    public int $limitMax = 40;

    /**
     * @var int limit padrão
     */
    public int $limitDefault = 20;

    /**
     * @var array Filtros rápidos
     */
    public array $filters = [
@foreach($filtersSearchable as $filter)
        '{{$filter['name']}}' => [
            'column' => '{{$tableName}}.{{$filter['name']}}',
            'filter' => {{$filter['filterType']}},
        ],
@endforeach
    ];

    public function __construct(
        protected \{{ $transformerNamespace }}\{{ $resourceName }}Transformer $transformer,
        protected \{{ $repositoryNamespace }}\{{ $resourceName }}Repository $repository,
        protected \{{ $policyNamespace }}\{{ $resourceName }}Policy $policy
    ) {
    }

    /**
     * Create resource
     *
     * @param Create{{ $resourceName }}Dto $data
     * @param null $requester
     * @return mixed
     * @throws \Exception
     */
    public function create(Create{{ $resourceName }}Dto $data, $requester = null, $context = 'model')
    {
        try {

            // Authorization
            $this->policy->create($requester);

            // Iniciar transação
            DB::beginTransaction();

            // Treatment data
            $resourceData = $this->treatment($requester, $data, null, 'create');

            // Create Model
            $model = $this->repository->create($resourceData->toArray());

            DB::commit();

            return $this->get($model->id, $requester, $context);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param int $id
     * @param Update{{ $resourceName }}Dto $originalData
     * @param null $requester
     * @return mixed
     * @throws \Exception
     */
    public function update(
        int $id,
        Update{{ $resourceName }}Dto $data,
        $requester = null,
        $context = 'model'
    ) {
        try {
            $model = $this->repository->findIdOrFail($id);

            // Authorization
            $this->policy->update($requester, $model);

            // Iniciar transação
            DB::beginTransaction();

            // Treatment data
            $resourceData = $this->treatment($requester, $data, $model, 'update');

            // Update Model
            $this->repository->updateById($id, $resourceData->toArray());

            DB::commit();

            return $this->get($id, $requester, $context);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param $requester
     * @param Collection $requestData
     * @param $currentModel
     * @param string $method
     * @return Collection
     */
    public function treatment(
        $requester,
        Collection $requestData,
        $currentModel,
        string $method
    ) {
        if ($method == 'update') {

        } else if ($method == 'create') {
@foreach($userGetData as $userGet)
            $requestData['{{$userGet['fieldName']}}'] = $requester->{{$userGet['userFieldName']}};
@endforeach

            return $requestData;
        }

        return $requestData;
    }

    /**
     * @param int $id
     * @param $receiver
     * @param string $context
     * @return mixed
     * @throws \Devesharp\Exceptions\Exception
     */
    public function get(int $id, $receiver, $context = 'default')
    {
        // Get model
        $model = $this->makeSearch($data, $receiver)
            ->whereInt('id', $id)
            ->findOne();

        if (empty($model)) {
            \Devesharp\Exceptions\Exception::NotFound({{ $modelNamespace }}\{{ $resourceName }}::class);
        }

        if ($context != 'model')
            $this->policy->get($receiver, $model);

        return Transformer::item(
            $model,
            $this->transformer,
            $context,
            $receiver,
        );
    }

    /**
     * @param Search{{ $resourceName }}Dto $originalData
     * @param null $requester
     * @return array
     */
    public function search(Search{{ $resourceName }}Dto $data, $requester = null)
    {
        // Authorization
        $this->policy->search($requester);

        // Make query
        $query = $this->makeSearch($data, $requester);

        return $this->transformerSearch(
            $query,
            $this->transformer,
            'default',
            $requester,
        );
    }

    /**
     * @param $data
     * @param null $requester
     * @return \Devesharp\Pattners\Repository\RepositoryInterface|\{{ $repositoryNamespace }}\{{ $resourceName }}Repository
     */
    protected function makeSearch(&$data, $requester = null)
    {
        /** @var \{{ $repositoryNamespace }}\{{ $resourceName }}Repository $query */
        $query = parent::makeSearch($data, $requester);

//        // Example Query
//        $query->whereInt('id', 1);

        return $query;
    }

    /**
     * @param $id
     * @param $requester
     * @return bool
     * @throws \Devesharp\Exceptions\Exception
     */
    public function delete($id, $requester = null)
    {
        try {
            $model = $this->repository->findIdOrFail($id);

            // Authorization
            $this->policy->delete($requester, $model);

            // Iniciar transação
            DB::beginTransaction();

            $this->repository->updateById($id, ['enabled' => false]);

            DB::commit();

            return [
                'id' => $id,
                'deleted' => true,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param Delete{{ $resourceName }}Dto $data
     * @param $requester
     * @return bool
     * @throws \Devesharp\Exceptions\Exception
     */
    public function deleteMany(Delete{{ $resourceName }}Dto $data, $requester = null)
    {
        try {
            // Authorization
            $this->policy->delete($requester);

            $query = $this->makeSelectActions($data, $requester);

            $count = 0;

            $query->chunk(50, function ($resources) use ($requester) {
                foreach ($resources as $resource) {
                    $this->delete($resource->id, $requester);
                    $count++;
                }
            });

            return [
                'executed' => true,
                'count' => $count,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
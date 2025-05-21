<?php

namespace App\Modules\Example\Services;

use Devesharp\Patterns\Service\Service;
use Devesharp\Patterns\Service\ServiceFilterEnum;
use Devesharp\Patterns\Transformer\Transformer;
use App\Modules\Example\Dtos\CreateExampleDto;
use App\Modules\Example\Dtos\UpdateExampleDto;
use App\Modules\Example\Dtos\SearchExampleDto;
use App\Modules\Example\Dtos\DeleteExampleDto;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExampleService extends Service
{
    /**
     * Sorts permitidas.
     */
    public array $sort = [
        'id' => [
            'column' => 'example.id',
        ],
        'user_id' => [
            'column' => 'example.user_id',
        ],
        'published_at' => [
            'column' => 'example.published_at',
        ],
        'status' => [
            'column' => 'example.status',
        ],
        'created_at' => [
            'column' => 'example.created_at',
        ],
        'updated_at' => [
            'column' => 'example.updated_at',
        ],
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
        'id' => [
            'column' => 'example.id',
            'filter' => ServiceFilterEnum::whereEqual,
        ],
        'platform_id' => [
            'column' => 'example.platform_id',
            'filter' => ServiceFilterEnum::whereEqual,
        ],
        'user_id' => [
            'column' => 'example.user_id',
            'filter' => ServiceFilterEnum::whereEqual,
        ],
        'title' => [
            'column' => 'example.title',
            'filter' => ServiceFilterEnum::whereContainsExplodeString,
        ],
        'body' => [
            'column' => 'example.body',
            'filter' => ServiceFilterEnum::whereContainsExplodeString,
        ],
        'is_featured' => [
            'column' => 'example.is_featured',
            'filter' => ServiceFilterEnum::whereBoolean,
        ],
        'published_at' => [
            'column' => 'example.published_at',
            'filter' => ServiceFilterEnum::whereDate,
        ],
        'published_at_gte' => [
            'column' => 'example.published_at_gte',
            'filter' => ServiceFilterEnum::whereDateGte,
        ],
        'published_at_lte' => [
            'column' => 'example.published_at_lte',
            'filter' => ServiceFilterEnum::whereDateLte,
        ],
        'password' => [
            'column' => 'example.password',
            'filter' => ServiceFilterEnum::whereContainsExplodeString,
        ],
        'post_type' => [
            'column' => 'example.post_type',
            'filter' => ServiceFilterEnum::whereInt,
        ],
        'status' => [
            'column' => 'example.status',
            'filter' => ServiceFilterEnum::whereInt,
        ],
        'created_by' => [
            'column' => 'example.created_by',
            'filter' => ServiceFilterEnum::whereEqual,
        ],
        'created_at' => [
            'column' => 'example.created_at',
            'filter' => ServiceFilterEnum::whereDate,
        ],
        'created_at_gte' => [
            'column' => 'example.created_at_gte',
            'filter' => ServiceFilterEnum::whereDateGte,
        ],
        'created_at_lte' => [
            'column' => 'example.created_at_lte',
            'filter' => ServiceFilterEnum::whereDateLte,
        ],
        'updated_at' => [
            'column' => 'example.updated_at',
            'filter' => ServiceFilterEnum::whereDate,
        ],
        'updated_at_gte' => [
            'column' => 'example.updated_at_gte',
            'filter' => ServiceFilterEnum::whereDateGte,
        ],
        'updated_at_lte' => [
            'column' => 'example.updated_at_lte',
            'filter' => ServiceFilterEnum::whereDateLte,
        ],
    ];

    public function __construct(
        protected \App\Modules\Example\Transformers\ExampleTransformer $transformer,
        protected \App\Modules\Example\Resources\Repositories\ExampleRepository $repository,
        protected \App\Modules\Example\Policies\ExamplePolicy $policy
    ) {
    }

    /**
     * Create resource
     *
     * @param CreateExampleDto $data
     * @param null $requester
     * @return mixed
     * @throws \Exception
     */
    public function create(CreateExampleDto $data, $requester = null, $context = 'model')
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
     * @param UpdateExampleDto $originalData
     * @param null $requester
     * @return mixed
     * @throws \Exception
     */
    public function update(
        int $id,
        UpdateExampleDto $data,
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
            $requestData['platform_id'] = $requester->platform_id;
            $requestData['user_id'] = $requester->id;
            $requestData['created_by'] = $requester->id;

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
            \Devesharp\Exceptions\Exception::NotFound(App\Modules\Example\Resources\Models\Example::class);
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
     * @param SearchExampleDto $originalData
     * @param null $requester
     * @return array
     */
    public function search(SearchExampleDto $data, $requester = null)
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
     * @return \Devesharp\Pattners\Repository\RepositoryInterface|\App\Modules\Example\Resources\Repositories\ExampleRepository
     */
    protected function makeSearch(&$data, $requester = null)
    {
        /** @var \App\Modules\Example\Resources\Repositories\ExampleRepository $query */
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
     * @param DeleteExampleDto $data
     * @param $requester
     * @return bool
     * @throws \Devesharp\Exceptions\Exception
     */
    public function deleteMany(DeleteExampleDto $data, $requester = null)
    {
        try {
            // Authorization
            $this->policy->delete($requester);

            $query = $this->makeSelectActions($data, $requester);

            $count = 0;

            $query->chunk(50, function ($resources) use ($requester, $count) {
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

<?php

namespace App\Modules\ModuleExample\Service;

use Devesharp\Patterns\Service\Service;
use Devesharp\Patterns\Service\ServiceFilterEnum;
use Devesharp\Patterns\Transformer\Transformer;
use App\Modules\ModuleExample\Dto\CreateResourceExampleDto;
use App\Modules\ModuleExample\Dto\UpdateResourceExampleDto;
use App\Modules\ModuleExample\Dto\SearchResourceExampleDto;
use App\Modules\ModuleExample\Dto\DeleteResourceExampleDto;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\DB;

class ResourceExampleService extends Service
{
    /**
     * Sorts permitidas.
     */
    public array $sort = [
        'id' => [
            'column' => 'resource_example.id',
        ],
        'user_id' => [
            'column' => 'resource_example.user_id',
        ],
        'published_at' => [
            'column' => 'resource_example.published_at',
        ],
        'status' => [
            'column' => 'resource_example.status',
        ],
        'created_at' => [
            'column' => 'resource_example.created_at',
        ],
        'updated_at' => [
            'column' => 'resource_example.updated_at',
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
            'column' => 'resource_example.id',
            'filter' => ServiceFilterEnum::whereEqual,
        ],
        'platform_id' => [
            'column' => 'resource_example.platform_id',
            'filter' => ServiceFilterEnum::whereEqual,
        ],
        'user_id' => [
            'column' => 'resource_example.user_id',
            'filter' => ServiceFilterEnum::whereEqual,
        ],
        'title' => [
            'column' => 'resource_example.title',
            'filter' => ServiceFilterEnum::whereContainsExplodeString,
        ],
        'body' => [
            'column' => 'resource_example.body',
            'filter' => ServiceFilterEnum::whereContainsExplodeString,
        ],
        'is_featured' => [
            'column' => 'resource_example.is_featured',
            'filter' => ServiceFilterEnum::whereBoolean,
        ],
        'published_at' => [
            'column' => 'resource_example.published_at',
            'filter' => ServiceFilterEnum::whereDate,
        ],
        'published_at_gte' => [
            'column' => 'resource_example.published_at_gte',
            'filter' => ServiceFilterEnum::whereDateGte,
        ],
        'published_at_lte' => [
            'column' => 'resource_example.published_at_lte',
            'filter' => ServiceFilterEnum::whereDateLte,
        ],
        'password' => [
            'column' => 'resource_example.password',
            'filter' => ServiceFilterEnum::whereContainsExplodeString,
        ],
        'post_type' => [
            'column' => 'resource_example.post_type',
            'filter' => ServiceFilterEnum::whereInt,
        ],
        'status' => [
            'column' => 'resource_example.status',
            'filter' => ServiceFilterEnum::whereInt,
        ],
        'created_by' => [
            'column' => 'resource_example.created_by',
            'filter' => ServiceFilterEnum::whereEqual,
        ],
        'created_at' => [
            'column' => 'resource_example.created_at',
            'filter' => ServiceFilterEnum::whereDate,
        ],
        'created_at_gte' => [
            'column' => 'resource_example.created_at_gte',
            'filter' => ServiceFilterEnum::whereDateGte,
        ],
        'created_at_lte' => [
            'column' => 'resource_example.created_at_lte',
            'filter' => ServiceFilterEnum::whereDateLte,
        ],
        'updated_at' => [
            'column' => 'resource_example.updated_at',
            'filter' => ServiceFilterEnum::whereDate,
        ],
        'updated_at_gte' => [
            'column' => 'resource_example.updated_at_gte',
            'filter' => ServiceFilterEnum::whereDateGte,
        ],
        'updated_at_lte' => [
            'column' => 'resource_example.updated_at_lte',
            'filter' => ServiceFilterEnum::whereDateLte,
        ],
    ];

    public function __construct(
        protected App\Modules\ModuleExample\Transformer\ResourceExampleTransformer $transformer,
        protected App\Modules\ModuleExample\Resources\Repository\ResourceExampleRepository $repository,
        protected App\Modules\ModuleExample\Policy\ResourceExamplePolicy $policy
    ) {
    }

    /**
     * Create resource
     *
     * @param CreateResourceExampleDto $data
     * @param null $requester
     * @return mixed
     * @throws \Exception
     */
    public function create(CreateResourceExampleDto $data, $requester = null, $context = 'model')
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
     * @param UpdateResourceExampleDto $originalData
     * @param null $requester
     * @return mixed
     * @throws \Exception
     */
    public function update(
        int $id,
        UpdateResourceExampleDto $data,
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
    public function get(int $id, $receiver, string $context = 'default')
    {
        // Get model
        $model = $this->makeSearch($data, $receiver)
            ->whereInt('id', $id)
            ->findOne();

        if (empty($model)) {
            \Devesharp\Exceptions\Exception::NotFound(App\Modules\ModuleExample\Resources\Model\ResourceExample::class);
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
     * @param SearchResourceExampleDto $originalData
     * @param null $requester
     * @return array
     */
    public function search(SearchResourceExampleDto $data, $requester = null)
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
     * @return \Devesharp\Pattners\Repository\RepositoryInterface|\App\Modules\ModuleExample\Resources\Repository\ResourceExampleRepository
     */
    protected function makeSearch(&$data, $requester = null)
    {
        /** @var \App\Modules\ModuleExample\Resources\Repository\ResourceExampleRepository $query */
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
     * @param DeleteResourceExampleDto $data
     * @param $requester
     * @return bool
     * @throws \Devesharp\Exceptions\Exception
     */
    public function deleteMany(DeleteResourceExampleDto $data, $requester = null)
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

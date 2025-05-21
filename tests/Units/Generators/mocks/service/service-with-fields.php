<?php

namespace App\Modules\Products\Services;

use \Illuminate\Support\Carbon;
use App\Modules\Platforms\Resources\Models\Platforms;
use App\Modules\Users\Resources\Models\Users;
use App\Modules\Cartegories\Resources\Models\Cartegories;
use Devesharp\Patterns\Service\Service;
use Devesharp\Patterns\Service\ServiceFilterEnum;
use Devesharp\Patterns\Transformer\Transformer;
use App\Modules\Products\Dtos\CreateEletronicsDto;
use App\Modules\Products\Dtos\UpdateEletronicsDto;
use App\Modules\Products\Dtos\SearchEletronicsDto;
use App\Modules\Products\Dtos\DeleteEletronicsDto;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\DB;

class EletronicsService extends Service
{
    /**
     * Sorts permitidas.
     */
    public array $sort = [
        'id' => [
            'column' => 'eletronics.id',
        ],
        'user_id' => [
            'column' => 'eletronics.user_id',
        ],
        'published_at' => [
            'column' => 'eletronics.published_at',
        ],
        'status' => [
            'column' => 'eletronics.status',
        ],
        'created_at' => [
            'column' => 'eletronics.created_at',
        ],
        'updated_at' => [
            'column' => 'eletronics.updated_at',
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
            'column' => 'eletronics.id',
            'filter' => ServiceFilterEnum::whereEqual,
        ],
        'platform_id' => [
            'column' => 'eletronics.platform_id',
            'filter' => ServiceFilterEnum::whereEqual,
        ],
        'user_id' => [
            'column' => 'eletronics.user_id',
            'filter' => ServiceFilterEnum::whereEqual,
        ],
        'category_id' => [
            'column' => 'eletronics.category_id',
            'filter' => ServiceFilterEnum::whereEqual,
        ],
        'title' => [
            'column' => 'eletronics.title',
            'filter' => ServiceFilterEnum::whereContainsExplodeString,
        ],
        'body' => [
            'column' => 'eletronics.body',
            'filter' => ServiceFilterEnum::whereContainsExplodeString,
        ],
        'is_featured' => [
            'column' => 'eletronics.is_featured',
            'filter' => ServiceFilterEnum::whereBoolean,
        ],
        'published_at' => [
            'column' => 'eletronics.published_at',
            'filter' => ServiceFilterEnum::whereDate,
        ],
        'published_at_gte' => [
            'column' => 'eletronics.published_at_gte',
            'filter' => ServiceFilterEnum::whereDateGte,
        ],
        'published_at_lte' => [
            'column' => 'eletronics.published_at_lte',
            'filter' => ServiceFilterEnum::whereDateLte,
        ],
        'password' => [
            'column' => 'eletronics.password',
            'filter' => ServiceFilterEnum::whereContainsExplodeString,
        ],
        'post_type' => [
            'column' => 'eletronics.post_type',
            'filter' => ServiceFilterEnum::whereInt,
        ],
        'status' => [
            'column' => 'eletronics.status',
            'filter' => ServiceFilterEnum::whereInt,
        ],
        'created_by' => [
            'column' => 'eletronics.created_by',
            'filter' => ServiceFilterEnum::whereEqual,
        ],
        'created_at' => [
            'column' => 'eletronics.created_at',
            'filter' => ServiceFilterEnum::whereDate,
        ],
        'created_at_gte' => [
            'column' => 'eletronics.created_at_gte',
            'filter' => ServiceFilterEnum::whereDateGte,
        ],
        'created_at_lte' => [
            'column' => 'eletronics.created_at_lte',
            'filter' => ServiceFilterEnum::whereDateLte,
        ],
        'updated_at' => [
            'column' => 'eletronics.updated_at',
            'filter' => ServiceFilterEnum::whereDate,
        ],
        'updated_at_gte' => [
            'column' => 'eletronics.updated_at_gte',
            'filter' => ServiceFilterEnum::whereDateGte,
        ],
        'updated_at_lte' => [
            'column' => 'eletronics.updated_at_lte',
            'filter' => ServiceFilterEnum::whereDateLte,
        ],
    ];

    public function __construct(
        protected \App\Modules\Products\Transformers\EletronicsTransformer $transformer,
        protected \App\Modules\Products\Resources\Repositories\EletronicsRepository $repository,
        protected \App\Modules\Products\Policies\EletronicsPolicy $policy
    ) {
    }

    /**
     * Create resource
     *
     * @param CreateEletronicsDto $data
     * @param null $requester
     * @return mixed
     * @throws \Exception
     */
    public function create(CreateEletronicsDto $data, $requester = null, $context = 'model')
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
     * @param UpdateEletronicsDto $originalData
     * @param null $requester
     * @return mixed
     * @throws \Exception
     */
    public function update(
        int $id,
        UpdateEletronicsDto $data,
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
            $requestData['platform_id'] = 1;
            $requestData['user_id'] = $requester->id;
            $requestData['published_at'] = \Carbon\Carbon::now();
            $requestData['created_by'] = $requester->id;

            return $requestData;
        }

        return $requestData;
    }

    /**
     * @param int $id
     * @param $requester
     * @param string $context
     * @return mixed
     * @throws \Devesharp\Exceptions\Exception
     */
    public function get(int $id, $requester, $context = 'default')
    {
        // Get model
        $model = $this->makeSearch($data, $requester)
            ->whereInt('id', $id)
            ->findOne();

        if (empty($model)) {
            \Devesharp\Exceptions\Exception::NotFound(App\Modules\Products\Resources\Models\Eletronics::class);
        }

        if ($context != 'model')
            $this->policy->get($requester, $model);

        return Transformer::item(
            $model,
            $this->transformer,
            $context,
            $requester,
        );
    }

    /**
     * @param SearchEletronicsDto $originalData
     * @param null $requester
     * @return array
     */
    public function search(SearchEletronicsDto $data, $requester = null)
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
     * @return \Devesharp\Pattners\Repository\RepositoryInterface|\App\Modules\Products\Resources\Repositories\EletronicsRepository
     */
    protected function makeSearch(&$data, $requester = null)
    {
        /** @var \App\Modules\Products\Resources\Repositories\EletronicsRepository $query */
        $query = parent::makeSearch($data, $requester);

        $query->whereEqual('platform_id', 1);
        $query->whereEqual('user_id', $requester->id);

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
     * @param DeleteEletronicsDto $data
     * @param $requester
     * @return bool
     * @throws \Devesharp\Exceptions\Exception
     */
    public function deleteMany(DeleteEletronicsDto $data, $requester = null)
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

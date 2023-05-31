<?php

namespace App\Modules\Products\Services;

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

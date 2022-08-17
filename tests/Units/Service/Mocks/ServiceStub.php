<?php

namespace Tests\Units\Service\Mocks;

use Devesharp\Patterns\Service\Service;
use Devesharp\Patterns\Service\ServiceFilterEnum;
use Devesharp\Patterns\Transformer\Transformer;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\DB;

class ServiceStub extends Service
{
    protected ValidatorStub $validator;
    protected TransformerStub $transformer;
    protected RepositoryStub $repository;
    protected PolicyStub $policy;

    public array $sort = [
        'id' => [
            'column' => 'id',
        ],
    ];

    public string $sort_default = 'id';

    public array $filters = [
        // Filter default
        'id' => [
            'column' => 'id',
            'filter' => ServiceFilterEnum::whereInt,
        ],
        'name' => [
            'column' => 'name',
            'filter' => ServiceFilterEnum::whereContainsLike,
        ],
        // Filter column raw
        'full_name' => [
            'column' => "raw:(name || ' ' || age)",
            'filter' => ServiceFilterEnum::whereContainsExplodeString,
        ],
    ];

    public function __construct(
        ValidatorStub $validator,
        TransformerStub $transformer,
        RepositoryStub $repository,
        PolicyStub $policy
    ) {
        $this->validator = $validator;
        $this->transformer = $transformer;
        $this->repository = $repository;
        $this->policy = $policy;
    }

    /**
     * Create resource
     *
     * @param array $originalData
     * @param null $requester
     * @return mixed
     * @throws \Exception
     */
    public function create(array $originalData, $requester = null)
    {
        try {
            DB::beginTransaction();

            // Authorization
            $this->policy->create($requester);

            // Data validation
            $data = $this->validator->create($originalData, $requester);

            // Treatment data
            $resourceData = $this->treatment($requester, $data, null, 'create');

            // Create Model
            $model = $this->repository->create($resourceData->toArray());

            DB::commit();

            return $this->get($model->id, $requester);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param int $id
     * @param array $originalData
     * @param null $requester
     * @return mixed
     * @throws \Exception
     */
    public function update(
        int $id,
        array $originalData,
        $requester = null
    ) {
        try {
            DB::beginTransaction();

            $model = $this->repository->findIdOrFail($id);

            // Authorization
            $this->policy->update($requester, $model);

            // Data validation
            $data = $this->validator->update($originalData, $requester);

            // Treatment data
            $resourceData = $this->treatment($requester, $data, $model, 'update');

            // Update Model
            $this->repository->updateById($id, $resourceData->toArray());

            DB::commit();

            return $this->get($id, $requester);
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
            return $requestData;
        } else if ($method == 'create') {
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
    public function get(int $id, $receiver)
    {
        // Get model
        $model = $this->repository->findIdOrFail($id);

        $this->policy->get($receiver, $model);

        return Transformer::item(
            $model,
            $this->transformer,
            'default',
            $receiver,
        );
    }

    /**
     * @param array $originalData
     * @param null $requester
     * @return array
     */
    public function search(array $originalData = [], $requester = null)
    {
        // Authorization
        $this->policy->search($requester);

        // Validate data
        $data = $this->validator->search($originalData, $requester);

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
     * @return \Devesharp\CRUD\RepositoryInterface|RepositoryStub
     */
    protected function makeSearch(&$data, $requester = null)
    {
        /** @var RepositoryStub $query */
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
            DB::beginTransaction();

            $model = $this->repository->findIdOrFail($id);

            // Authorization
            $this->policy->delete($requester, $model);

//            $this->repository->updateById($id, ['enabled' => false]);
            $this->repository->deleteById($id, $requester);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

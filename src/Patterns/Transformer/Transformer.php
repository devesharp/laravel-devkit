<?php

namespace Devesharp\Patterns\Transformer;

use Devesharp\CRUD\Repository\RepositoryInterface;
use Devesharp\Support\Helpers;
use Illuminate\Support\Str;

class Transformer
{
    protected array $loads = [];

    /**
     * @param $model
     * @param null $requester
     * @return mixed
     */
    public function getDefault(
        $model,
        $requester = null
    ) {
        return $model;
    }

    /**
     * @param  array  $models
     * @param  string $context
     * @param  null   $requester
     * @return array
     */
    public function transformMany(
        array $models,
        string $context = 'default',
        $requester = null
    ) {
        $transformed = [];

        /**
         * Fazer o pre carregamento de todos os itens que irão ser chamados
         */

        // Resgatar Ids dos loads
        $loads = [];
        foreach ($models as $model) {
            foreach ($this->loads as $loadName => $load) {
                if (class_exists($model[0]))
                    throw new \Exception('Class ' . $model[0] . ' not found for transformer');

                $foreignKey = $load[1] ?? $loadName . '_id';

                // add id for array
                $loads[$loadName][] = $model->{$foreignKey};
            }
        }

        // Fazer load
        foreach ($this->loads as $loadName => $load) {
            $name = \Illuminate\Support\Str::ucfirst(\Illuminate\Support\Str::camel($loadName));
            // Se classe não estiver definida, faz um load padrão com valores
            if (!method_exists($this, 'load' . $name)) {
                $foreignKey = $load[0];
                $this->loadResource($loadName, app($foreignKey), $loads[$loadName]);
            } else {
                $this->{'load' . $name}($loads[$loadName]);
            }
        }

        /**
         * Realizar transformer
         */
        if (Helpers::isArrayAssoc($models)) {
            foreach ($models as $key => $model) {
                $function = 'get' . ucfirst(Str::camel($context));
                $default = [];
                if ($function != 'getDefault') {
                    $default = $this->getDefault($model, $requester, []);
                }

                $transformed[$key] = $this->{$function}(
                    $model,
                    $requester,
                    $default
                );
            }
        } else {
            foreach ($models as $model) {
                $function = 'get' . ucfirst(Str::camel($context));

                $default = [];
                if ($function != 'getDefault') {
                    $default = $this->getDefault($model, $requester, []);
                }

                $transformed[] = $this->{$function}($model, $requester, $default);
            }
        }

        return $transformed;
    }

    /**
     * Carregar recursos e colocar em cache.
     *
     * @param string              $name
     * @param RepositoryInterface $repository
     * @param array               $items
     * @param string              $column
     */
    public function loadResource(
        string $name,
        RepositoryInterface $repository,
        array $items,
        $column = 'id'
    ) {
        if (! isset($this->{$name})) {
            $this->{$name} = [];
        }

        $idsNotLoad = [];

        foreach ($items as $id) {
            if (! isset($this->{$name}[$id])) {
                $idsNotLoad[] = $id;
            }
        }

        if (empty($idsNotLoad)) {
            return;
        }

        $items = $repository
            ->clearQuery()
            ->whereArrayInt($repository->tableName . '.' . $column, $idsNotLoad)
            ->findMany();

        foreach ($items as $item) {
            $this->{$name}[$item->{$column}] = $item;
        }
    }

    /**
     * Verifica se existe uma variavel com o nome do recurso
     * Se existir verifica pelo ID se o recurso já foi resgatado
     * caso não tenha sido resgatado chama load{resource}, carrega e salva em cache.
     *
     * @param  $funName
     * @param  $arguments
     * @return mixed
     */
    public function __call($funName, $arguments)
    {
        // Verifica função
        if (0 === strpos($funName, 'get')) {
            $name = str_replace('get', '', $funName);

            if (isset($this->{lcfirst($name)}[$arguments[0]])) {
                return $this->{lcfirst($name)}[$arguments[0]];
            } else {
                $this->{lcfirst($name)} = [];
            }

            // Carregar recurso, caso não tenha carregado ainda
            $this->{'load' . $name}([$arguments[0]]);

            return $this->{lcfirst($name)}[$arguments[0]] ?? null;
        }
    }

    /**
     * @param $model
     * @param \Devesharp\CRUD\Transformer $transform
     * @param string      $context
     * @param mixed|null  $requester
     *
     * @return mixed
     */
    public static function item(
        $model,
        Transformer $transform,
        string $context = 'default',
        $requester = null
    ) {
        $function = 'get' . ucfirst(Str::camel($context));

        $default = [];
        if ($function != 'getDefault') {
            $default = $transform->getDefault($model, $requester, []);
        }

        return $transform->{$function}($model, $requester, $default);
    }

    /**
     * @param $models
     * @param Transformer $transform
     * @param string      $context
     * @param mixed|null  $requester
     *
     * @return array
     */
    public static function collection(
        $models,
        Transformer $transform,
        string $context = 'default',
        $requester = null
    ) {
        return $transform->transformMany($models, $context, $requester);
    }
}

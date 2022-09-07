<?php

namespace Devesharp\Patterns\Transformer;

use Devesharp\Patterns\Repository\RepositoryInterface;
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
    public function transformDefault(
        $model,
        $requester = null
    ) {
        return $model->toArray();
    }

    /**
     * @param $model
     * @param null $requester
     * @return mixed
     */
    public function transformModel(
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

        $loads = []; // Resgatar Ids dos loads

        foreach ($models as $model) {
            foreach ($this->loads as $loadName => $load) {
                $repositoryClassString = $model[0];
                $localForeignKey = $load[1] ?? $loadName . '_id';

                if (class_exists($repositoryClassString)){
                    throw new \Exception('Class ' . $repositoryClassString . ' not found for transformer');
                }

                // Adicionar IDs
                if (isset($model->{$localForeignKey}) && !empty($model->{$localForeignKey})){
                    $loads[$loadName][] = $model->{$localForeignKey};
                }
            }
        }


        // Fazer Load dos itens
        foreach ($this->loads as $key => $load) {
            $name = \Illuminate\Support\Str::ucfirst(\Illuminate\Support\Str::camel($key));
            // Se classe não estiver definida, faz um load padrão com valores
            if (!method_exists($this, 'load' . $name)) {
                $repository = $load[0];
                $foreignKey = $load[2] ?? 'id';

                $this->loadResource($key, app($repository), $loads[$key], $foreignKey);
            } else {
                // Se classe estiver definida, faz um load usando sua classe
                $this->{'load' . $name}($loads[$key]);
            }
        }

        /**
         * Realizar transformer
         */
        if (Helpers::isArrayAssoc($models)) {
            foreach ($models as $key => $model) {
                $function = 'transform' . ucfirst(Str::camel($context));
                $default = [];

                // Se não for o contexto default e model, faz um load do transformDefault
                if ($context != 'default' && $context != 'model') {
                    $default = $this->transformDefault($model, $requester, []);
                }

                $transformed[$key] = $this->{$function}(
                    $model,
                    $requester,
                    $default
                );
            }
        } else {
            foreach ($models as $model) {
                $function = 'transform' . ucfirst(Str::camel($context));
                $default = [];

                // Se não for o contexto default e model, faz um load do transformDefault
                if ($context != 'default' && $context != 'model') {
                    $default = $this->transformDefault($model, $requester, []);
                }

                $transformed[] = $this->{$function}($model, $requester, $default);
            }
        }

        return $transformed;
    }

    /**
     * @param $model
     * @param \Devesharp\Patterns\Transformer\Transformer $transform
     * @param string      $context
     * @param mixed|null  $requester
     *
     * @return mixed
     */
    public function transformOne(
        $model,
        string $context = 'default',
        $requester = null
    ) {
        $function = 'transform' . ucfirst(Str::camel($context));

        foreach ($this->loads as $loadName => $load) {
            $repositoryClassString = $model[0];
            $localForeignKey = $load[1] ?? $loadName . '_id';

            if (class_exists($repositoryClassString)){
                throw new \Exception('Class ' . $repositoryClassString . ' not found for transformer');
            }

            if (is_array($localForeignKey)) {
                $localForeignKeys = $localForeignKey;
                if (count($localForeignKeys) !=  count($localForeignKeys)) {
                    // error
                }

                $where = [];
                foreach ($localForeignKeys as $key => $localForeignKey) {
                    $foreignKey = $load[2][$key];
                    $where[] = [
                        'field' => $foreignKey,
                        'value' => $model->{$localForeignKey},
                    ];
                }

                $repository = $load[0];
                $foreignKey = $load[2] ?? 'id';

                $this->loadResource($key, app($repository), $ids, $foreignKey);
            }else {
                // Adicionar IDs
                if (isset($model->{$localForeignKey}) && !empty($model->{$localForeignKey})){
                    $key = $loadName;
                    $ids = [$model->{$localForeignKey}];

                    $name = \Illuminate\Support\Str::ucfirst(\Illuminate\Support\Str::camel($key));
                    // Se classe não estiver definida, faz um load padrão com valores
                    if (!method_exists($this, 'load' . $name)) {
                        $repository = $load[0];
                        $foreignKey = $load[2] ?? 'id';

                        $this->loadResource($key, app($repository), $ids, $foreignKey);
                    } else {
                        // Se classe estiver definida, faz um load usando sua classe
                        $this->{'load' . $name}($ids);
                    }
                }
            }
        }

        $default = [];
        // Se não for o contexto default e model, faz um load do transformDefault
        if ($context != 'default' && $context != 'model') {
            $default = $this->transformDefault($model, $requester, []);
        }

        return $this->{$function}($model, $requester, $default);
    }

    /**
     * Carregar recursos e colocar em cache.
     *
     * @param string              $name         Nome do load
     * @param RepositoryInterface $repository   Repositorio
     * @param array               $items        Ids dos itens
     * @param string              $column       Coluna que será usada para buscar o recurso na tabela relacionada
     */
    public function loadResource(
        string $name,
        RepositoryInterface $repository,
        array $items,
        $column = 'id'
    ) {
        // Se não existir cache, inicia
        if (!isset($this->{'loaded_' .$name})) {
            $this->{'loaded_' .$name} = [];
        }

        $idsNotLoad = [];

        // Só faz load do ID se não existir no cache
        foreach ($items as $id) {
            if (! isset($this->{'loaded_' .$name}[$id])) {
                $idsNotLoad[] = $id;
            }
        }

        // Se não existir nenhum item para carregar, retorna
        if (empty($idsNotLoad)) {
            return;
        }

        // Carregar recursos
        $items = $repository
            ->clearQuery()
            ->whereArrayInt($repository->tableName . '.' . $column, $idsNotLoad)
            ->findMany();

        // Colocar em cache no nome do load
        foreach ($items as $item) {
            $this->{'loaded_' . $name}[$item->{$column}] = $item;
        }
    }

    public function loadResourceMany(
        string $name,
        RepositoryInterface $repository,
        array $items,
        $column = 'id'
    ) {
        // Se não existir cache, inicia
        if (!isset($this->{'loaded_' .$name})) {
            $this->{'loaded_' .$name} = [];
        }

        $idsNotLoad = [];

        // Só faz load do ID se não existir no cache
        foreach ($items as $id) {
            if (! isset($this->{'loaded_' .$name}[$id])) {
                $idsNotLoad[] = $id;
            }
        }

        // Se não existir nenhum item para carregar, retorna
        if (empty($idsNotLoad)) {
            return;
        }

        // Carregar recursos
        $items = $repository
            ->clearQuery()
            ->whereArrayInt($repository->tableName . '.' . $column, $idsNotLoad)
            ->findMany();

        // Colocar em cache no nome do load
        foreach ($items as $item) {
            $this->{'loaded_' . $name}[$item->{$column}] = $item;
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

            if (isset($this->{'loaded_' . lcfirst($name)}[$arguments[0]])) {
                return $this->{'loaded_' . lcfirst($name)}[$arguments[0]];
            } else {
                $this->{'loaded_' . lcfirst($name)} = [];
            }

            // Carregar recurso, caso não tenha carregado ainda
            $this->{'load' . $name}([$arguments[0]]);

            return $this->{'loaded_' . lcfirst($name)}[$arguments[0]] ?? null;
        }
    }

    public static function item(
        $model,
        Transformer $transform,
        string $context = 'default',
        $requester = null
    ) {
        return $transform->transformOne($model, $context, $requester);
    }

    /**
     * Transformar array de models
     *
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
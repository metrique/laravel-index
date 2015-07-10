<?php

namespace Metrique\Index;

use Illuminate\Cache\Repository as Cache;
use Metrique\Index\Abstracts\EloquentRepositoryAbstract;
use Metrique\Index\Contracts\IndexRepositoryInterface;

class IndexRepositoryCache implements IndexRepositoryInterface
{
    public function __construct(IndexRepositoryEloquent $repository, Cache $cache)
    {
        $this->cache = $cache;
        $this->repository = $repository;
    }

    public function all(array $columns = ['*'])
    {
        $signature = $this->buildSignature(
            __FUNCTION__,
            serialize($columns)
        );

        return $this->repository->all($columns);
    }

    public function paginate($perPage = 10, array $columns = ['*'])
    {
        $signature = $this->buildSignature(
            __FUNCTION__,
            serialize([$perPage, $columns])
        );

        return $this->repository->paginate($perPage, $columns);
    }

    public function create(array $data)
    {
        $signature = $this->buildSignature(
            __FUNCTION__,
            serialize($date)
        );

        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        $signature = $this->buildSignature(
            __FUNCTION__,
            serialize([$id, $data])
        );

        return $this->repository->update($id, $data);
    }

    public function find($id, array $columns = ['*'])
    {
        $signature = $this->buildSignature(
            __FUNCTION__,
            serialize([$id, $columns])
        );

        return $this->repository->find($id, $columns);
    }

    public function destroy($id)
    {
        $signature = $this->buildSignature(
            __FUNCTION__,
            $id
        );

        return $this->repository->$destory($id);
    }

    public function setNamespace($namespace = null)
    {
        return $this->repository->setNamespace($namespace);
    }

    public function setOrder($column = 'order', $order = 'desc')
    {
        return $this->repository->setOrder($column, $order);
    }

    public function findTypes(array $types)
    {
        $signature = $this->buildSignature(
            __FUNCTION__,
            serialize($types)
        );

        return $this->repository->findTypes($types);
    }

    public function findAndNestTypes(array $types)
    {        
        $signature = $this->buildSignature(
            __FUNCTION__,
            serialize($types)
        );
        die('hi');

        return $this->repository->findAndNestTypes($types);
    }

    private function buildSignature(array $signature)
    {
        array_unshift($signature, __CLASS__);

        dump($buildSignature);
    }
}
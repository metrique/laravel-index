<?php

namespace Metrique\Index;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use Metrique\Index\Abstracts\EloquentRepositoryAbstract;
use Metrique\Index\Contracts\IndexRepositoryInterface;



class IndexRepositoryCache implements IndexRepositoryInterface
{
    private $cache;
    private $config;
    private $repository;

    public function __construct(IndexRepositoryEloquent $repository, Cache $cache, Config $config)
    {
        $this->cache = $cache;
        $this->config = $config;
        $this->repository = $repository;
    }

    public function all(array $columns = ['*'])
    {
        return $this->repository->all($columns);
    }

    public function paginate($perPage = 10, array $columns = ['*'])
    {
        return $this->repository->paginate($perPage, $columns);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function find($id, array $columns = ['*'])
    {
        return $this->repository->find($id, $columns);
    }

    public function destroy($id)
    {
        return $this->repository->$destroy($id);
    }

    public function getNamespace()
    {
        return $this->repository->getNamespace();
    }

    public function setNamespace($namespace = null)
    {
        $this->repository->setNamespace($namespace);

        return $this;
    }

    public function setOrder($column = 'order', $order = 'desc')
    {
        $this->repository->setOrder($column, $order);

        return $this;
    }

    public function getSlug()
    {
        return $this->repository->getSlug();
    }

    public function setSlug($slug = null)
    {
        $this->repository->setSlug($slug);

        return $this;
    }

    public function findTypes(array $types)
    {
        $signature = $this->buildSignature([
            __FUNCTION__,
            $this->getNamespace(),
            $this->getSlug(),
            json_encode($types)
        ]);

        return $this->cache->remember($signature, $this->config->get('index.cdn.ttl', 5), function() use($types) {
            return $this->repository->findTypes($types);
        });
    }

    public function findAndNestTypes(array $types)
    {
        return $this->repository->createNestedIndex($this->findTypes($types), ['slug' => $this->getSlug()]);
    }

    private function buildSignature(array $signature)
    {
        array_unshift($signature, __CLASS__);

        return md5(implode('::', $signature));
    }
}
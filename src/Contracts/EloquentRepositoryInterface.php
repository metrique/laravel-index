<?php

namespace Metrique\Index\Contracts;

/**
 * EloquentRepositoryInterface provides the standard
 * functions to be expected of any eloquent repository.
 */
interface EloquentRepositoryInterface { 
    public function all($columns = ['*']);
    public function paginate($perPage = 10, $columns = ['*']);
    public function create(array $data);
    public function update($id, array $data);
    public function find($id, $columns = ['*']);
    public function destroy($id);
}
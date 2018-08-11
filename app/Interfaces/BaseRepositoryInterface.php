<?php
namespace App\Interfaces;

interface BaseRepositoryInterface
{
    public function all($columns = array('*'));
 
    public function paginate($perPage = 15, $columns = array('*'));
 
    public function create(array $data);
 
    public function update(array $data, $id);
 
    public function delete($id);
 
    public function find($id, $columns = array('*'));
    
    public function findOneOrFail($id, $columns = array('*'));

    public function findOneBy($field, $value, $columns = array('*'));    

    public function findOneByOrFail($field, $value, $columns = array('*'));    

    public function paginateArrayResults(array $data, int $perPage = 50);
}
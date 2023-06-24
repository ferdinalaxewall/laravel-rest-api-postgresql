<?php

namespace App\Repositories;

class BaseRepository
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    // Find All Data in Database with table($model)
    public function findAll()
    {
        return $this->model->all();
    }

    // Find All Data in Database with table($model), but with One Specific Column (Where Condition)
    public function findAllByOneSpecificColumn($columnName, $value)
    {
        return $this->model->where($columnName, $value)->get();
    }
    
    // Find All One in Database with table($model), but with One Specific Column (Where Condition)
    public function findOneByOneSpecificColumn($columnName, $value)
    {
        return $this->model->where($columnName, $value)->first();
    }

    /*  Create new data in Database with table($model)
        $dto = Data Transfer Object */
    public function create($dto)
    {
        return $this->model->create($dto);
    }
        
    /*  Update Data in Database with table($model), but with One Specific Column (Where Condition)
        $dto = Data Transfer Object */
    public function updateByOneSpecificColumn($columnName, $value, $dto)
    {
        $this->model->where($columnName, $value)->update($dto);
        return $this->findOneByOneSpecificColumn($columnName, $value);
    }

    // Delete Data in Database with table($model), but with One Specific Column (Where Condition)
    public function deleteByOneSpecificColumn($columnName, $value)
    {
        $this->model->where($columnName, $value)->delete();
    }
}
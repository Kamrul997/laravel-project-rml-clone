<?php
namespace App\Repositories;


use Spatie\Permission\Models\Role;

class RoleRepository
    // space that we can use the repository implements RepositoryInterface
{
    // space that we can use the repository from
    protected $model;

    public function __construct(Role $role)
    {
        // set the model
        $this->model = $role;
    }
    public function all(){
        return $this->model->get();
    }

    public function allAssociate(array $relation){
        return $this->model->with($relation)->get();
    }

    public function create(array $data){
        return $this->model->create($data);
    }

    public function update(array $data, $id){
        return $this->model->find($id)->update($data);
    }
    public function show($id){
        return $this->model->find($id);
    }

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    public function findAssociate($id,array $relation)
    {
        return $this->model->with($relation)->find($id);
    }
    public function allAssociateFilter(array $relation = [],$filter = [],$condition = "hard",$result = 'multiple'){
            $query = $this->model;
            if(!empty($relation)){
                $query->with($relation);
            }
            if(!empty($filter)){
                foreach ($filter as $column => $value){
                    if(!empty($value)){
                        if($condition == "hard"){
                            $query->where($column, '=', $value);
                        }else{
                          $query->where($column, 'like', '%' .$value . '%');
                        }
                    }
                }
            }
            if($result == 'single'){
                return $query->first();
            }else{
                return $query->get();
            }
    }
    public function allAssociateFilterPagignate(array $relation,$page = 1,$filter = [],$condition = "hard"){
        $perPage = 10;
        $start = 0;
        $skip = $page == 1 ? 0 : $page*$perPage;
        $query = $this->model;
        if(!empty($relation)){
            $query->with($relation);
        }
        if(!empty($filter)){
            foreach ($filter as $column => $value){
                if(!empty($value)){
                    if($condition == "hard"){
                        $query->where($column, '=', $value);
                    }else{
                      $query->where($column, 'like', '%' .$value . '%');
                    }
                }
            }
        }
        return $query->skip($skip)->take($perPage)->get();
    }


    public function findAssociateFilter($id,array $relation,$filter = [])
    {
        $query = $this->model->with($relation);
        if(!empty($filter)){
            foreach ($filter as $column => $value){
                if(!empty($value)){
                    $query->where($column, '=', $value);
                }

            }
        }
        return $query->get();
    }
}

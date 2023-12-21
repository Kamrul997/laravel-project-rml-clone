<?php
namespace App\Repositories;


use App\Models\User;

class UserRepository
    // space that we can use the repository implements RepositoryInterface
{
    // space that we can use the repository from
    protected $model;

    public function __construct(User $User)
    {
        // set the model
        $this->model = $User;
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
    public function findAssociate($id,$relation = [])
    {
        return $this->model->with($relation)->find($id);
    }
    public function allAssociateFilter($relation = [],$filter = [],$condition = "hard",$result = 'multiple'){
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
    public function allAssociateFilterPagignate($relation = [],$page = 1,$filter = [],$condition = "hard"){
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
    public function findAssociateFilter($id,$relation = [],$filter = [])
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
    public function allAssociateRelationFilter($relationFilter = [],$relation = [],$filter = [],$condition = "hard",$result = 'multiple'){

        $query = $this->model;
        if(!empty($relation)){
            foreach ($relation as $relationItem ){
                $query = $query->with($relationItem);
            }
        }
        if(!empty($filter)){
            foreach ($filter as $column => $value){
                if(!empty($value)){
                    if($condition == "hard"){
                        $query = $query->where($column, '=', $value);
                    }else{
                        $query = $query->where($column, 'like', '%' .$value . '%');
                    }
                }
            }
        }
        /*******Relation Filter Structure*********
         * ['relation'=>[
         *      'filterColumn' => 'FilterColumnName',
         *      'filterCondition' => 'FilterCondition',
         *      'filterConditionValue' => 'FilterConditionValue',
         * ]]
         * ***************************************/

        if(!empty($relationFilter)){

            foreach ($relationFilter as $relation => $items){
                $query =  $query->whereHas($relation, function($q) use ($items)
                {
                    $q->where($items['filterColumn'],$items['filterCondition'], $items['filterConditionValue']);
                });
            }
        }
        if($result == 'single'){
            return $query->first();
        }else{
            return $query->get();
        }
    }
}

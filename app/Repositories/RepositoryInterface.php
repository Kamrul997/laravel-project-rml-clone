<?php
/**
 * Created by PhpStorm.
 * User: Hashibul Hasan
 * Date: 01-Jul-19
 * Time: 1:40 PM
 */

namespace App\Repositories;


interface RepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function show($id);
}

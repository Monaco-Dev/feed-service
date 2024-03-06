<?php

namespace App\Repositories\Support\Traits;

trait ModelResource
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Support\LazyCollection
     */
    public function all()
    {
        return $this->model->cursor();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $request)
    {
        return $this->model->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param mixed $model
     * @param bool $findOrFail
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find(mixed $model, bool $findOrFail = true)
    {
        if (optional($model)->id) return $model;

        return $findOrFail ? $this->model->findOrFail($model) : $this->model->find($model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param mixed $model
     * @param array $request
     * @return mixed
     */
    public function update(mixed $model, array $request)
    {
        $model = optional($model)->id ? $model : $this->model->findOrFail($model);

        return $model->update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $model
     * @return mixed
     */
    public function delete(mixed $model)
    {
        $model = optional($model)->id ? $model : $this->model->findOrFail($model);

        return $model->delete();
    }

    /**
     * Force remove the specified resource from storage.
     *
     * @param int|string $id
     * @return mixed
     */
    public function forceDelete($id)
    {
        $model = $this->model->findOrFail($id);

        return $model->forceDelete();
    }

    /**
     * Display the specified resource or store a newly created resource in storage.
     *
     * @param array $where
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $where = [], array $data = [])
    {
        return $this->model->firstOrCreate($where, $data);
    }
}

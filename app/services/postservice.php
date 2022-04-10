<?php

namespace Services;

use Repositories\PostRepository;

class PostService
{
    private PostRepository $repository;

    public function __construct()
    {
        $this->repository = new PostRepository();
    }

    public function getAll($offset, $limit) {
        return $this->repository->getAll($offset, $limit);
    }

    public function getByUserId($userId) {
        return $this->repository->getByUserId($userId);
    }

    public function updateOne($post, $id) {
        return $this->repository->updateOne($post, $id);
    }

    public function deleteOne($id) {
        $this->repository->deleteOne($id);
    }

    public function insert($post) {
        return $this->repository->insert($post);
    }
}
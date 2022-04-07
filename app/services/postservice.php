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
        $this->repository->getByUserId($userId);
    }
}
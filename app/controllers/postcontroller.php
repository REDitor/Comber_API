<?php

namespace Controllers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDOException;
use Services\PostService;

class PostController extends Controller
{
    private PostService $service;

    public function __construct()
    {
        $this->service = new PostService();
    }

    public function getAll()
    {
        $offset = null;
        $limit = null;

        if (isset($_GET['offset']) && is_numeric($_GET['offset']))
            $offset = $_GET['offset'];
        if (isset($_GET['limit']) && is_numeric($_GET['limit']))
            $limit = $_GET['limit'];

        $posts = $this->service->getAll($offset, $limit);

        $this->respond($posts);
    }

    public function getByUserId($userId) {
        if (!$this->checkForJwt())
            return;

        $posts = $this->service->getByUserId($userId);

        $this->respond($posts);
    }

    public function update($id) {
        try {
            $post = $this->createObjectFromPostedJson("Models\\Post");
            $post = $this->service->update($post, $id);
        } catch (PDOException $e) {
            $this->respondWithError(500, $e->getMessage());
        }
        $this->respond($post);
    }
}
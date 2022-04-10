<?php

namespace Repositories;

use http\Message;
use Models\Post;
use PDO;
use PDOException;

class PostRepository extends Repository
{
    private string $getAll = 'SELECT posts.id, posts.userId, posts.postedAt, posts.message, users.username
                                FROM posts
                                INNER JOIN users ON posts.userId = users.id
                                ORDER BY postedAt DESC';
    private string $getOne = 'SELECT id, userId, postedAt, message
                                FROM posts
                                WHERE id = :id';
    private string $getByUserId = 'SELECT id, userId, postedAt, message
                                    FROM posts
                                    WHERE userId = :userId
                                    ORDER BY postedAt DESC';
    private string $updateOne = 'UPDATE posts
                                    SET message = :message
                                    WHERE id = :id';

    public function getAll($offset, $limit) {
        try {
            if (isset($offset) && isset($limit))
                $this->getAll .= ' LIMIT :limit OFFSET :offset';

            $stmt = $this->connection->prepare($this->getAll);

            if (isset($offset) && isset($limit)) {
                $stmt->bindParam(':offset', $offset);
                $stmt->bindParam(':limit', $limit);
            }

            $stmt->execute();

            $posts = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)))
                $posts[] = $this->rowToPost($row);

            return $posts;
        } catch(PDOException $e) {
            echo $e;
        }
    }

    public function getOne($id) {
        try {
            $stmt = $this->connection->prepare($this->getOne);
            $stmt->bindParam(':id', $id);

            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();

            return $this->rowToPost($row);
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function rowToPost($row): Post
    {
        $post = new Post();
        $post->id = $row['id'];
        $post->userId = $row['userId'];
        $post->postedAt = $row['postedAt'];
        $post->message = $row['message'];
        if (isset($row['username']))
            $post->postedBy = $row['username'];

        return $post;
    }

    public function getByUserId($userId) {
        try {
            $stmt = $this->connection->prepare($this->getByUserId);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();

            $posts = array();
            while (($row = $stmt->fetch(PDO::FETCH_ASSOC)))
                $posts[] = $this->rowToPost($row);

            return $posts;
        } catch(PDOException $e) {
            echo $e;
        }
    }

    public function update($post, $id) {
        try {
            $stmt = $this->connection->prepare($this->updateOne);
            $stmt->bindParam(':message', $post->message);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $this->getOne($post->id);
        } catch (PDOException $e) {
            echo $e;
        }
    }
}

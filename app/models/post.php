<?php
namespace Models;

class Post
{
    public int $id;
    public int $userId;
    public string $postedBy;
    public string $postedAt;
    public string $message;
}
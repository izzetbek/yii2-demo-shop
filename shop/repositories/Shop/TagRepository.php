<?php
namespace shop\repositories\Shop;

use shop\entities\Shop\Tag;
use shop\repositories\NotFoundException;

class TagRepository
{
    public function findByName($name): Tag
    {
        if(!$tag = Tag::findOne(['name' => $name])) {
            throw new NotFoundException('Tag is not found.');
        }
        return $tag;
    }

    public function get($id): Tag
    {
        if(!$tag = Tag::findOne($id)) {
            throw new NotFoundException('Tag is not found.');
        }
        return $tag;
    }

    public function save(Tag $tag): void
    {
        if(!$tag->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Tag $tag): void
    {
        if(!$tag->delete()) {
            throw new \RuntimeException('Deleting error.');
        }
    }
}
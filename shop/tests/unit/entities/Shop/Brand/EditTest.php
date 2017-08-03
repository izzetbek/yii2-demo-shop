<?php

namespace shop\tests\unit\entities\Shop\Brand;

use Codeception\Test\Unit;
use shop\entities\Shop\Brand;
use shop\entities\Meta;

class EditTest extends Unit
{
    public function testSuccess()
    {
        $brand = Brand::create(
            $name = 'Name',
            $slug = 'Slug',
            $meta = new Meta('Title', 'Description', 'Keywords')
        );

        $brand->edit($name = 'New name', $slug = 'new-slug', $meta = new Meta('New title', 'New description', 'New keywords'));

        $this->assertEquals($name, $brand->name);
        $this->assertEquals($slug, $brand->slug);
        $this->assertEquals($meta, $brand->meta);
    }
}
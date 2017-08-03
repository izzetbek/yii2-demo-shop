<?php

namespace shop\services\manager\Shop;

use shop\entities\Shop\Product\Product;
use shop\entities\Meta;
use shop\forms\manager\Shop\Product\ProductCreateForm;
use shop\repositories\Shop\BrandRepository;
use shop\repositories\Shop\CategoryRepository;
use shop\repositories\Shop\ProductRepository;

class ProductManageService
{
    private $products;
    private $brands;
    private $categories;

    public function __construct(ProductRepository $products, BrandRepository $brands, CategoryRepository $categories)
    {
        $this->products = $products;
        $this->brands = $brands;
        $this->categories = $categories;
    }

    public function create(ProductCreateForm $form): Product
    {
        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main);

        $product = Product::create(
            $brand->id,
            $category->id,
            $form->code,
            $form->name,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $product->setPrice($form->price->new, $form->price->old);

        foreach($form->categories->others as $otherId) {
            $category = $this->categories->get($otherId);
            $product->assignCategory($category->id);
        }

        foreach($form->values as $value) {
            $product->setValue($value->id, $value->value);
        }

        foreach($form->photos->files as $file) {
            $product->addPhoto($file);
        }

        $this->products->save($product);

        return $product;
    }

    public function edit($id, ProductCreateForm $form): void
    {
        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main);
        $product = $this->products->get($id);

        $product->edit(
            $brand->id,
            $category->id,
            $form->code,
            $form->name,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );

        $product->setPrice($form->price->new, $form->price->old);

        $this->products->save($product);
    }

    public function remove($id): void
    {
        $product = $this->products->get($id);
        $this->products->remove($product);
    }
}
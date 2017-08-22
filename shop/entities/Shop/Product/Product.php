<?php

namespace shop\entities\Shop\Product;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use shop\entities\Shop\Brand;
use shop\entities\Shop\Category;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * @property integer $id
 * @property integer $created_at
 * @property string $code
 * @property string $name
 * @property integer $category_id
 * @property integer $brand_id
 * @property integer $price_old
 * @property integer $price_new
 * @property integer $rating
 *
 * @property Meta $meta
 * @property Brand $brand
 * @property Category $category
 * @property CategoryAssignment[] $categoryAssignments
 * @property TagAssignment[] $tagAssignments
 * @property Value[] $values
 * @property Photo[] $photos
 * @property RelatedProductAssignment[] $relatedProductAssignments
 * @property Modification[] $modifications
 * @property Review[] $reviews
 */
class Product extends ActiveRecord
{
    public $meta;

    public static function tableName()
    {
        return '{{%shop_products}}';
    }

    public function behaviors()
    {
        return [
            MetaBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'categoryAssignments',
                    'tagAssignments',
                    'values',
                    'photos',
                    'relatedProductAssignments',
                    'modifications',
                    'reviews'
                ],
            ],
        ];
    }

    public static function create($brandId, $categoryId, $code, $name, Meta $meta): self
    {
        $product = new static();
        $product->brand_id = $brandId;
        $product->category_id = $categoryId;
        $product->code = $code;
        $product->name = $name;
        $product->meta = $meta;
        $product->created_at = time();
        return $product;
    }

    public function edit($brandId, $code, $name, Meta $meta): void
    {
        $this->brand_id = $brandId;
        $this->code = $code;
        $this->name = $name;
        $this->meta = $meta;
    }

    public function setPrice($new, $old): void
    {
        $this->price_new = $new;
        $this->price_old = $old;
    }

    //Categories

    public function changeMainCategory($categoryId): void
    {
        $this->category_id = $categoryId;
    }

    public function assignCategory($id): void
    {
        $assignments = $this->categoryAssignments;
        foreach ($assignments as $assignment) {
            if($assignment->isForCategory($id)) {
                return;
            }
        }
        $assignments[] = CategoryAssignment::create($id);
        $this->categoryAssignments = $assignments;
    }

    public function revokeCategory($id): void
    {
        $assignments = $this->categoryAssignments;
        foreach ($assignments as $i => $assignment) {
            if($assignment->isForCategory($id)) {
                unset($assignments[$i]);
                $this->categoryAssignments = $assignments;
                return;
            }
        }
        throw new \DomainException('Assignment is not found.');
    }

    public function revokeCategories(): void
    {
        $this->categoryAssignments = [];
    }

    //Tags

    public function assignTag($id): void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $assignment) {
            if($assignment->isForTag($id)) {
                return;
            }
        }
        $assignments[] = TagAssignment::create($id);
        $this->tagAssignments = $assignments;
    }

    public function revokeTag($id): void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $i => $assignment) {
            if($assignment->isForTag($id)) {
                unset($assignments[$i]);
                $this->tagAssignments = $assignments;
                return;
            }
        }
        throw new \DomainException('Assignment is not found.');
    }

    public function revokeTags(): void
    {
        $this->tagAssignments = [];
    }

    //Modification

    public function getModification($id): Modification
    {
        foreach ($this->modifications as $modification) {
            if ($modification->isEqualTo($id)) {
                return $modification;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

    public function addModification($code, $name, $price): void
    {
        $modifications = $this->modifications;
        foreach ($modifications as $modification) {
            if ($modification->isCodeEqualTo($code)) {
                throw new \DomainException('Modification already exists.');
            }
        }
        $modifications[] = Modification::create($code, $name, $price);
        $this->modifications = $modifications;
    }

    public function editModification($id, $code, $name, $price): void
    {
        $modifications = $this->modifications;
        foreach ($modifications as $modification) {
            if ($modification->isEqualTo($id)) {
                $modification->edit($code, $name, $price);
                $this->modifications = $modifications;
                return;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

    public function removeModification($id)
    {
        $modifications = $this->modifications;
        foreach ($modifications as $i => $modification) {
            if ($modification->isEqualTo($id)) {
                unset($this->modifications[$i]);
                return;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

    public function removeModifications()
    {
        $this->modifications = [];
    }

    //Reviews

    public function addReview($userId, $vote, $text): void
    {
        $reviews = $this->reviews;
        $reviews[] = Review::create($userId, $vote, $text);
        $this->setReviews($reviews);
    }

    public function editReview($id, $vote, $text): void
    {
        $this->doWithReview($id, function (Review $review) use ($vote, $text) {
            $review->edit($vote, $text);
        });
    }

    public function activateReview($id): void
    {
        $this->doWithReview($id, function (Review $review) {
            $review->activate();
        });
    }

    public function draftReview($id): void
    {
        $this->doWithReview($id, function (Review $review) {
            $review->draft();
        });
    }

    public function removeReview($id): void
    {
        $reviews = $this->reviews;
        foreach ($reviews as $i => $review) {
            if($review->isIdEqualTo($id)) {
                unset($reviews[$i]);
                $this->setReviews($reviews);
                return;
            }
        }
        throw new \DomainException('Review is not found.');
    }

    private function doWithReview($id, $callback)
    {
        $reviews = $this->reviews;
        foreach ($reviews as $i => $review) {
            if($review->isIdEqualTo($id)) {
                $callback($review);
                $this->setReviews($reviews);
                return;
            }
        }
        throw new \DomainException('Review is not found.');
    }

    private function setReviews(array $reviews)
    {
        $amount = 0;
        $total = 0;

        foreach ($reviews as $review) {
            if($review->isActive()) {
                $amount++;
                $total += $review->getRating();
            }
        }

        $this->reviews = $reviews;
        $this->rating = $amount ? $total / $amount : null;
    }

    //Related Products

    public function assignRelatedProduct($id): void
    {
        $assignments = $this->relatedProductAssignments;
        foreach ($assignments as $assignment) {
            if($assignment->isForRelatedProduct($id)) {
                return;
            }
        }
        $assignments[] = RelatedProductAssignment::create($id);
        $this->relatedProductAssignments = $assignments;
    }

    public function revokeRelatedProduct($id): void
    {
        $assignments = $this->relatedProductAssignments;
        foreach ($assignments as $i => $assignment) {
            if($assignment->isForRelatedProduct($id)) {
                unset($assignments[$i]);
                $this->relatedProductAssignments = $assignments;
                return;
            }
        }
        throw new \DomainException('Related Product is not found.');
    }

    public function revokeRelatedProducts(): void
    {
        $this->relatedProductAssignments = [];
    }

    //Values

    public function setValue($id, $value): void
    {
        $values = $this->values;
        foreach($values as $val) {
            if($val->isForCharacteristic($id)) {
                return;
            }
        }
        $values[] = Value::create($id, $value);
        $this->values = $values;
    }

    public function getValue($id): Value
    {
        $values = $this->values;
        foreach($values as $val) {
            if($val->isForCharacteristic($id)) {
                return $val;
            }
        }
        return Value::blank($id);
    }

    //Photos

    public function addPhoto(UploadedFile $file): void
    {
        $photos = $this->photos;
        $photos[] = Photo::create($file);
        $this->setPhotos($photos);
    }

    public function removePhoto($id): void
    {
        $photos = $this->photos;
        foreach ($photos as $i => $photo) {
            if ($photo->isEqualTo($id)) {
                unset($photos[$i]);
                $this->setPhotos($photos);
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    public function removePhotos(): void
    {
        $this->setPhotos([]);
    }

    public function movePhotoUp($id): void
    {
        $photos = $this->photos;
        foreach ($photos as $i => $photo) {
            if ($photo->isEqualTo($id) && $prev = $photos[$i - 1] ?? null) {
                $photos[$i] = $prev;
                $photos[$i - 1] = $photo;
                $this->setPhotos($photos);
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    public function movePhotoDown($id): void
    {
        $photos = $this->photos;
        foreach ($photos as $i => $photo) {
            if ($photo->isEqualTo($id) && $next = $photos[$i + 1] ?? null) {
                $photos[$i] = $next;
                $photos[$i + 1] = $photo;
                $this->setPhotos($photos);
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    private function setPhotos(Photo $photos = null): void
    {
        foreach ($photos as $i => $photo) {
            $photo->setSort($i);
        }
        $this->photos = $photos;
    }

    ###########################################################

    public function getBrand(): ActiveQuery
    {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getCategoryAssignments(): ActiveQuery
    {
        return $this->hasMany(CategoryAssignment::class, ['product_id' => 'id']);
    }

    public function getTagAssignments(): ActiveQuery
    {
        return $this->hasMany(TagAssignment::class, ['product_id' => 'id']);
    }

    public function getValues(): ActiveQuery
    {
        return $this->hasMany(Value::class, ['product_id' => 'id']);
    }

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(Photo::class, ['product_id' => 'id'])->orderBy('sort');
    }

    public function getRelatedProductAssignments(): ActiveQuery
    {
        return $this->hasMany(RelatedProductAssignment::class, ['product_id' => 'id']);
    }

    public function getModifications(): ActiveQuery
    {
        return $this->hasMany(Modification::class, ['product_id' => 'id']);
    }

    public function getReviews(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['product_id' => 'id']);
    }

    ###########################################################

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
}
<?php

namespace App\Services\Stock;

use App\Http\Models\Stock\StockCategories;
use App\Http\Models\Stock\StockProducts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class StockProduct
 * @package App\Services\Stock
 */
class StockCategory
{
    /**
     * @var StockCategories
     */
    private StockCategories $model;

    /**
     * StockProduct constructor.
     */
    public function __construct()
    {
        $this->model = new StockCategories();
    }

    /**
     * @param string $external_code
     * @param $params
     * @return StockCategory
     */
    public function createOrUpdate(string $external_code, $params) :StockCategory
    {

        if(!$category = $this->model::where('externalCode',$external_code)->first()){
            $category = $this->model;
            $category->uuid = uuid_v4();
        }

        $category->fill($params);
        $category->save();
        return $this;
    }


    /**
     * @param int $id
     * @return StockCategory
     */
    public function find(int $id): StockCategory
    {
        return $this;
    }

    /**
     * @param string $external_code
     * @return StockCategory
     */
    public function findByExternal(string $external_code) :StockCategory
    {
        return $this;
    }

    public function setAllCatsProducts(){
        $cats = self::getUniqueCats();
        self::setSubCats($cats);
        self::setEndCats($cats);
        self::setProductCats();
        return true;
    }

    private function getUniqueCats(){

        $items = StockProducts::distinct()->select('pathName')->whereNotNull('pathName')
            ->orderBy('pathName','asc')->get()->pluck('pathName');

        $cats = collect([]);
        foreach ($items as $item) {
            $parsed = explode("/",$item);

            if (!empty($parsed[2]))
                $cat_name = $parsed[2];
            elseif (!empty($parsed[1]))
                $cat_name = $parsed[1];
            else
                $cat_name = $parsed[0];

            $parent = !empty($parsed[1]) ? $parsed[1] : $parsed[0];
            $cat_name = $parent != $cat_name ? $cat_name : null;

            $cats->add((object)[
                'origin' => $item,
                'parent' => !empty($parsed[1]) ? $parsed[1] : $parsed[0],
                'name' => $cat_name
            ]);
        }

        return $cats;

    }

    private function setEndCats(Collection $items){
        $cats = StockCategories::all();
        $items = $items->whereNotNull('name')->all();
        foreach ($items as $item) {
            $parent_id = $cats->whereNotNull('name')->where('name',$item->parent)->first()->id;
            $info = StockCategories::updateOrCreate([
                'origin' => $item->origin
            ],
                [
                    'name' => $item->parent,
                    'parent_id' => $parent_id,
                    'origin' => $item->origin,
                    'external_name' => $item->parent,
                    'visible' => true,
                ]);
        }
        return true;
    }

    private function setSubCats($cats){

        $first_level = StockCategories::whereNull('parent_id')->get();
        $second_level = $cats->unique('parent')->all();

        foreach ($second_level as $item) {
            $tmp = explode("/",$item->origin);
            $parent = $first_level->where('name',$tmp[0])->first();

            if (!empty($parent->id))
                $parent_id = $parent->id;
            else
                $parent_id = null;

            if (!empty($parent->name) && $item->parent != $parent->name) {
                $info = StockCategories::updateOrCreate([
                    'name' => $item->parent
                ],
                    [
                        'name' => $item->parent,
                        'origin' => $item->origin,
                        'parent_id' => $parent_id,
                        'external_name' => $item->parent,
                        'visible' => true,
                    ]);
            }

        }

        return true;
    }

    private function setProductCats(){

        $this->i = 0;
        $cats = StockCategories::whereNotNull('origin')->get();
        $products = StockProducts::whereNotNull('pathName')
            ->chunkById(100, function ($products) use ($cats) {
                foreach ($products as $product) {
                    $this->i++;

                    $cat_id = $cats->where('origin',$product->pathName)->first();
                    if (!empty($cat_id->id)) {

                        StockProducts::whereId($product->id)->update([
                            "category_id" =>  $cat_id->id
                        ]);
                    }
                }
            });
    }

}

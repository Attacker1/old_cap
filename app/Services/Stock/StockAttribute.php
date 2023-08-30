<?php

namespace App\Services\Stock;

use App\Http\Models\Stock\StockAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StockAttribute
 * @package App\Services\Stock
 */
class StockAttribute
{
    /**
     * @var StockAttributes
     */
    private StockAttributes $model;
    private array $rows = [];

    /**
     * StockProduct constructor.
     */
    public function __construct()
    {
        $this->model = new StockAttributes();
    }

    /**
     * @param string $external_id
     * @param $params
     * @return $this
     */
    public function createOrUpdate(string $external_id, $params) :StockAttribute
    {

        if(!$attribute = $this->model::where('external_id',$external_id)->first()){
            $attribute = $this->model;
            $attribute->uuid = uuid_v4();
            $attribute->external_id = $external_id;
        }

        $attribute->fill($params);
        $attribute->save();
        return $this;
    }

    /**
     * @param int $id
     */
    public function find(int $id)
    {
        return $this->model::findOrFail($id);
    }

    public function import() :bool
    {

        $api = new MoySklad();

        $this->rows = $api->getAttributes();

        foreach ($this->rows as $item){
            $attribute = new self();
            $params = array_filter((array)$item);
            $attribute->createOrUpdate($item->id, $params);
        }
        return true;
    }

}

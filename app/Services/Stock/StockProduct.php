<?php

namespace App\Services\Stock;

use App\Http\Controllers\Common\MoySkladController;
use App\Http\Models\Admin\ServiceToken;
use App\Http\Models\Stock\StockAttributes;
use App\Http\Models\Stock\StockLike;
use App\Http\Models\Stock\StockProducts;
use Bnb\Laravel\Attachments\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Ixudra\Curl\CurlService;

/**
 * Class StockProduct
 * @package App\Services\Stock
 */
class StockProduct
{
    /**
     * @var StockProducts
     */
    private StockProducts $model;
    private array $rows = [];
    private StockProducts $item;
    private array $problem_attributes = ['8c95e06e-fb61-11eb-0a80-03dd00201027'];


    /**
     * StockProduct constructor.
     */
    public function __construct()
    {
        $this->model = new StockProducts();
    }

    /**
     * @param string $external_code
     * @param $params
     * @return $this
     */
    public function createOrUpdate(string $external_code, $params): StockProduct
    {

        if (!$product = $this->model::where('externalCode', $external_code)->first()) {
            $product = $this->model;
            $product->uuid = uuid_v4();
        }

        $product->fill($params);
        $product->save();
        $this->item = $product;
        return $this;
    }

    /**
     * @param int $id
     */
    public function find(int $id)
    {
        if ($product =  $this->model::with('attributes')->findOrFail($id)) {
            if (auth()->guard('admin')->user()->id !== null) {
                $like = StockLike::isLikedByUser(auth()->guard('admin')->user()->id, $product->id)->first();
                $product['isLiked'] = $like !== null ? (bool)true : (bool)false;
                $images = [];
                $attachments = $product->attachments()->get();
                foreach($attachments as $att) {
                    if (!empty($att) && !empty($att->filepath)) {
                        array_push($images,
                            [
                                'img_preview' => @$att->preview_url ?? null,
                                'img_original' => config("filesystems.disks.s3.baseurl") . $att->filepath,
                                'main' => $att->main
                            ]
                        );
                    }
                }
                $product['images'] = count($images) > 0 ? $images : null;
                /*$img = $product->attachments()->first();
                if (!empty($img) && !empty($img->filepath)) {
                    $product['img'] = @$img->preview_url ?? null;
                    $product['img_original'] = config("filesystems.disks.s3.baseurl") . $img->filepath;
                }*/
            }
        }
        return $product;
    }

    // TODO: сделано для vuex вынести?
    public function grid()
    {
        $page = request('current_page') ?? 1;
        $per_page = request('per_page') ?? 15;
        $columns = request('columns') ?? ['*'];
        $pageName = 'page';
        $attributeFilters = request('filters') ?? null;
        $query = $this->model::with('attributes')->where('quantity', '>', 0);

        // запрос при наличии фильтров на атрибуты товара
        if ($attributeFilters) {
            foreach($attributeFilters as $filter) {

                // при фильтре по цене id = 0
                if ($filter['id'] == 0) {
                    $query = $query->where([
                        ['price', '>=', $filter['start'] * 100 ?? 0],
                        ['price', '<=', $filter['end'] * 100 ?? 1000000]
                    ]);
                }

                // при цифровых фильтрах
                if ($filter['type'] == 'double') {
                    $query = $query->whereHas('attributes', function($q) use ($filter) {
                        $q->where([
                            ['stock_product_attributes.attribute_id', $filter['id']],
                            ['stock_product_attributes.value', '>=', $filter['start']],
                            ['stock_product_attributes.value', '<=',  $filter['end']],
                        ]);
                    });
                }

                // при выборочных фильтрах
                if ($filter['type'] == 'choose' && count($filter['values']) > 0) {
                    $query = $query->whereHas('attributes', function($q) use ($filter) {
                        $q->where('stock_product_attributes.attribute_id', $filter['id']);
                        foreach($filter['values'] as $item) {
                            $q->where('stock_product_attributes.value', 'like', '%' . $item . '%');
                        }
                    });
                }
            }
        }

        $query = $query->paginate($per_page, $columns, $pageName, $page);

        $products = tap($query, function ($paginatedInstance) {
            return $paginatedInstance->getCollection()->transform(function (&$product) {
                if (auth()->guard('admin')->user()->id !== null) {
                    $like = StockLike::isLikedByUser(auth()->guard('admin')->user()->id, $product->id)->first();
                    $product['isLiked'] = $like !== null ? (bool)true : (bool)false;


                    $images = [];
                    $attachments = $product->attachments()->get();
                    foreach($attachments as $att) {
                        if (!empty($att) && !empty($att->filepath)) {
                            array_push($images,
                                [
                                    'img_preview' => @$att->preview_url ?? null,
                                    'img_original' => config("filesystems.disks.s3.baseurl") . $att->filepath,
                                    'main' => $att->main
                                ]
                            );
                        }
                    }
                    $product['images'] = count($images) > 0 ? $images : null;

                    return $product;}

            });
        });

        return $products;
    }

    /**
     * @param string $external_code
     */
    public function findByExternal(string $external_code)
    {
        $this->item = $this->model::where('externalCode', $external_code)->with('attributes')->first();
        return $this->item;
    }

    /**
     * 2417 секунд
     * @param array $attributes
     * @param false $item
     * @return false
     */
    public function updateAttributes(array $attributes, $item = false)
    {
        if (count($attributes) == 0)
            return false;

        $db_attributes = StockAttributes::all();
        $data = [];
        foreach ($attributes as $attribute) {
            $id = $db_attributes->where('external_id', $attribute['id'])->first()->id;
            $data[$id] = ['value' => json_encode($attribute['value'])];
        }
        if ($item)
            $this->item = $item;
        $this->item->attributes()->sync($data);
    }

    /**
     * Время выполнения ~560 сек с пустой БД ( самый быстрый результат )
     * Поменять связь на HasMany  пр.
     * @param array $attributes
     * @param false $item
     */
    public function updateAttributesJson(array $attributes, $item = false)
    {

        if ($item)
            $this->item = $item;

        $id = $attributes[0]['id'];
        $this->item->attributes()->updateOrCreate(['external_id' => $id], [
            'external_id' => $id,
            'value' => $attributes,
        ]);

    }

    /**
     * $entity:
     * - stock (остатки)
     * - product (товары + характеристики)
     * @param string $entity
     * @return bool
     */
    public function import($entity = 'stock', $limit = false)
    {

        try {
            $api = new MoySklad();
            $fn = $entity == 'stock' ? 'getAll' : 'getList';

            $api = $api->setEntity($entity, $fn)
                ->getSize();

            if ($limit)
                $this->rows = $api->getLimited();
            else
                $this->rows = $api->getAll();

            foreach ($this->rows as $item) {

                $product = new self();
                $params = array_filter((array)$item);
                $params['external_uuid'] = $item->id;
                $product->createOrUpdate($item->externalCode, $params);

                if ($entity == 'product')
                    self::updateAttributes($item->attributes, $product->item);
            }

            return count($this->rows);
        } catch (\Exception $e) {
            Log::channel('stock_cron')->info('Обновление.Ошибка:' . $entity.  $e->getMessage());
            dd($e->getMessage());
        }

    }

    /**
     * Обновить внешний идентификаторы для фотографий
     * @return bool
     */
    public function importImagesUri( $limit = false)
    {
        try {
            $api = new MoySklad();

            $api = $api->setEntity('product','getList')
                ->getSize();

            if ($limit)
                $this->rows = $api->getLimited(500);
            else
                $this->rows = $api->getAll();

            foreach ($this->rows as $item) {
                $params['external_uuid'] = $item->id;
                $this->model::where("externalCode", $item->externalCode)->update($params);

            }

            return true;
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
    }

    private function getContent($headers, $uri,$token){

        $opts = ['http' => ['method' => 'GET',
            'ignore_errors' => true,
            'header' => $headers,
        ]];
        $ctx = stream_context_create($opts);
        $resp = file_get_contents($uri, false, $ctx);
        if ($data = json_decode($resp))
            return $data; // Получаем пока только первую фотку, более не нужно?

        return false;
    }

    public function updateImages(StockProducts $product,$update = false): bool
    {

        $thumb_path = "";
        $is_main = true;
        $exist = Attachment::where("key",$product->external_uuid)->count();

        if ($exist && !$update)
            return true;

        $images_url = "https://online.moysklad.ru/api/remap/1.2/entity/product/{$product->external_uuid}/images";
        $token = ServiceToken::where('service', 'sklad')->first()->token;
        $headers = ['Content-Type: application/json', 'Authorization: Bearer ' . $token];
        $image = new StockImages();
        $local_filepath = $image->setPath($product->external_uuid);
        if ($items = $this->getContent($headers, $images_url, $token))
            foreach ($items->rows as $item) {

                if (!empty($item->meta) && !empty($item->meta->downloadHref)) {
                    $path = $image->downloadImage($local_filepath, $headers, $item->meta->downloadHref);
                    $thumb_path = $image->cropImage($local_filepath,$product->external_uuid);
                }
            }

        if (!empty($path))
            $image->attachImage($product,$local_filepath,$thumb_path,$is_main);

        return !empty($attachment);

    }


}

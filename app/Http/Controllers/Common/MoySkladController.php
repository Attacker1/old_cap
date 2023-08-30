<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Filters\Filter;
use App\Http\Models\Admin\ServiceToken;
use App\Services\Stock\MoySklad;
use App\Services\Stock\StockProduct;
use Illuminate\Support\Facades\Log;
use MoySklad\ApiClient;
use MoySklad\Entity\Agent\Agent;
use MoySklad\Entity\Agent\Counterparty;
use MoySklad\Entity\Agent\Organization;
use MoySklad\Entity\Document\CustomerOrder;
use MoySklad\Entity\Document\CustomerOrderAttribute;
use MoySklad\Entity\Document\CustomerOrderPosition;
use MoySklad\Entity\Document\CustomerOrderPositions;
use MoySklad\Entity\Document\Position;
use MoySklad\Entity\Meta;
use MoySklad\Entity\MetaEntity;
use MoySklad\Entity\Pack;
use MoySklad\Entity\Product\Product;
use MoySklad\Entity\Product\ProductStoreStock;
use MoySklad\Http\RequestExecutor;
use MoySklad\Util\Exception\ApiClientException;
use MoySklad\Util\Param\Limit;
use MoySklad\Util\Param\Offset;
use MoySklad\Util\Param\Order;
use MoySklad\Util\Param\Search;
use MoySklad\Util\Param\EntityFilter;
use MoySklad\Util\Param\StandardFilter;

/**
 * Работа с Моим Складом
 * Class LeadController
 * @package App\Http\Controllers\Common
 */
class MoySkladController extends Controller
{
    /**
     * @var ApiClient
     */
    public $api;

    /**
     * MoySkladController constructor.
     */
    public function __construct()
    {

        if (!$token = ServiceToken::where('service', 'sklad')->first()->token)
            return false;

        if (!$host = config('config.MOY_SKLAD_HOST'))
            return false;

        try {
            $this->api = new ApiClient($host, true, [
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getStockAllProducts( $limit = 1000, $offset = 0)
    {
        try {
            // Выясняем количество Остатков по товарам на стоке
            $stock = $this->api->entity()->stock()->getAll([
                Limit::eq(1),
                Offset::eq(0)
            ]);

            // Начальные параметры
            $rows = [];
            $size = $stock->getMeta()->size;

            do {
                $params = [
                    Limit::eq($limit),
                    Offset::eq($offset),
                ];

                if (!$tmp = $this->api->entity()->stock()->getAll($params))
                    break;

                $offset += $limit;
                array_push($rows,...$tmp->rows);

            } while ($offset <= $size);

            return $rows;

        } catch (\Exception $e) {
            Log::channel('ms')->critical('Остатки.Ошибка получения товаров: '. $e->getMessage());
            return false;
        }

    }

    public function getStockByID($external_code = '427a964c-1ba6-11ec-0a80-00f500140298')
    {

        try {
            $params = [
                Limit::eq(1),
                StandardFilter::eq('product', 'https://online.moysklad.ru/api/remap/1.2/entity/product/' . $external_code),
            ];

            if ($stock = $this->api->entity()->stock()->getAll($params))
                return $stock->rows[0];

            return false;

        } catch (\Exception $e) {

        }


    }

    /**
     * Список всех брендов и ID
     * @return bool
     * @throws ApiClientException
     */
    public function getBrands()
    {

        $params = [
            Limit::eq(100),
        ];

        if ($counterpartyList = $this->api->entity()->counterparty()->getList($params)) {
            if ($collection = collect($counterpartyList->rows))
                return $collection;
                dd($collection->pluck('name', 'id'));
        }

        return false;
    }

    /**
     * Поиск продукции
     */
    public function searchProduct($amo_id)
    {

        $params = [
            Limit::eq(100),
        ];

        if ($stock = self::searchLead($amo_id))
            dd($stock);
        if ($order = $this->api->entity()->customerorder()->get($stock->id))
            return $order;

        return false;
    }

    private function searchLead($amo_id)
    {
        $params = [
            Limit::eq(100),
        ];

        if ($post = $this->api->entity()->customerorder()->search($amo_id)) {
            if ($post && !empty($post->rows))
                return $post->rows;
        }
        return false;

    }

    /**
     * Поиск продукции
     * @return bool
     * @throws ApiClientException
     */
    public function createProduct()
    {

        $product = new Product();
        $product->name = '!!Тест продукт';

        if ($product = $this->api->entity()->product()->create($product)) {
            if ($collection = collect($product))
                dd($collection);
        }
        return false;
    }

    public function getOrder($uuid = '09676c6c-3c1e-11ec-0a80-03940005d338')
    {
        $order = $this->api->entity()->customerorder()->get($uuid);
        $order->positions->fetch($this->api);
        return $order;
    }

    /**
     * Поиск продукции
     * @return void
     * @throws ApiClientException
     */
    public function getGroups($id = 'e8f6e86b-a1d8-11eb-0a80-033f000f76de')
    {

        $params = [
            Limit::eq(100),
            //StandardFilter::like('code', 'e8f6e86b-a1d8-11eb-0a80-033f000f76de'),
        ];

        if ($orders = $this->api->entity()->productfolder()->get($id)) {
            if ($collection = collect($orders))
                dd($collection);
        }


    }

    public function reportStock()
    {

        $params = [
            Limit::eq(2),
        ];

        return $this->api->entity()->getByMeta();

    }

    public function getProducts( $limit = 1000, $offset = 0)
    {

        try {
            // Выясняем количество Остатков по товарам на стоке
            $products = $this->api->entity()->product()->getList([
                Limit::eq(1),
                Offset::eq(0)
            ]);
            // Начальные параметры
            $rows = [];
            $size = $products->getMeta()->size;

            do {
                $params = [
                    Limit::eq($limit),
                    Offset::eq($offset),
                ];

                if (!$tmp = $this->api->entity()->product()->getList($params))
                    break;

                $offset += $limit;
                array_push($rows,...$tmp->rows);

            } while ($offset <= $size);

            return $rows;

        } catch (\Exception $e) {
            Log::channel('ms')->critical('Продукция.Ошибка: '. $e->getMessage());
            return false;
        }



    }

    public function getProductByID($product_id)
    {
        return $this->api->entity()->product()->get($product_id);
    }

    public function getCategoriesAll( $limit = 1000, $offset = 0){

        try {
            // Выясняем количество Остатков по товарам на стоке
            $stock = $this->api->entity()->productfolder()->getList([
                Limit::eq(1),
                Offset::eq(0)
            ]);
            // Начальные параметры
            $rows = [];
            $size = $stock->getMeta()->size;

            do {
                $params = [
                    Limit::eq($limit),
                    Offset::eq($offset),
                ];

                if (!$tmp = $this->api->entity()->productfolder()->getList($params))
                    break;

                $offset += $limit;
                array_push($rows,...$tmp->rows);

            } while ($offset <= $size);

            return $rows;

        } catch (\Exception $e) {
            Log::channel('ms')->critical('Остатки.Ошибка получения категорий: '. $e->getMessage());
            return false;
        }
    }

    public function getImages($product_id = 'd21b7371-b8a1-11eb-0a80-0896002a4137'){
        return $this->api->entity()->product()->getImagesList($product_id);
    }

    public function createOrder(array $products){


        // AMO атрибут
        $attributes = (object)[
            "meta" => (object)[
                "href" => "https://online.moysklad.ru/api/remap/1.1/entity/customerorder/metadata/attributes/69bef690-744d-11ea-0a80-03ba0007d643",
                "type" => "attributemetadata",
                "mediaType" => "application/json"
            ],
            "name" => 'AMO_ID',
            "href" => null,
            "fileName" => null,
            "value" => '8888'
        ];

        $positions = [];
        foreach ($products as $item) {
            $product = (new StockProduct())->find($item);
            $product_meta = $this->api->entity()->product()->get($product->external_uuid)->getMeta();
            $position = new Product();
            $position->quantity = 1;
            $position->salePrice = $product->salePrice;
            $position->price = $product->price;
            $position->reserve = 1;
            $position->discount = 0;
            $position->assortment = (object)["meta" => $product_meta];
            $positions[] = $position;
        }

      // Переложить на
        $order = new CustomerOrder();
        $order->name = "Тест заказ " . uuid_v4();
        $order->description = 'Тестовый заказ из ЛК2';
        $order->positions = $positions;
        $ms = new MoySklad();
        $order->organization =  $ms->organization();
        $order->attributes =  [$attributes];
        // Пользователь=контрагент
        $order->agent = $this->api->entity()->counterparty()->get('72d52b94-9351-11ec-0a80-099600040b98');

        $response = $this->api->entity()->customerorder()->create($order);
        dd($response);


//        $products = $this->api->entity()->customerorder()->create();

    }

}
<?php

namespace App\Services\Stock;

use App\Http\Models\Stock\StockCarts;
use App\Http\Models\Stock\StockProducts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use MoySklad\Entity\Document\CustomerOrder;
use MoySklad\Entity\MetaEntity;
use MoySklad\Entity\Product\Product;


/**
 * Class StockProduct
 * @package App\Services\Stock
 */
class StockCart extends AbstractStock
{

    /**
     * @var StockCarts
     */
    private StockCarts $model;
    private array $rows = [];

    /**
     * StockCart constructor.
     */
    public function __construct()
    {
        $this->model = new StockCarts();

        // Инициализация api?
        $this->api = $this->api();
    }

    /**
     * @param string $external_code
     * @param $params
     * @return $this
     */
    public function createOrUpdate(string $external_code, $params) :StockCart
    {

        if(!$product = $this->model::where('externalCode',$external_code)->first()){
            $product = $this->model;
        }

        $product->fill($params);
        $product->save();
        return $this;
    }

    /**
     * @param int $id
     */
    public function find(int $id)
    {
        return $this->model::findOrFail($id);
    }

    // TODO: сделано для vuex вынести?
    public function grid()
    {
        $page = request('current_page') ?? 1;
        $per_page = request('per_page') ?? 15;
        $columns = request('columns') ?? ['*'];
        $pageName = 'page';
        return $this->model::paginate($per_page, $columns, $pageName, $page);
    }

    /**
     * @param string $external_code
     * @return StockProduct
     */
    public function findByExternal(string $external_code) :StockProduct
    {
        return $this;
    }

    public function import($entity = 'stock') :bool
    {

        $api = new MoySklad();
        $fn = $entity == 'stock' ? 'getAll' : false;
        $this->rows = $api->setEntity($entity,$fn)
            ->getSize()
            ->getAll();

        foreach ($this->rows as $item){
            $product = new self();
            $params = array_filter((array)$item);
            $product->createOrUpdate($item->externalCode, $params);
        }
        return true;
    }


    /**
     * Отправка в Мой Склад
     *
     */
    public function pushMoySklad($products, $amo_id = '', $description = '' ){

        try {
            $positions = [];
            $stock = new Stock();

            // Создаем позиции заказа
            foreach ($products as $item) {
                $capsula_product = (new StockProduct())->find($item);

                $position = new Product();
                $position->quantity = 1;
                $position->salePrice = $capsula_product->salePrice; // Согласовать какую ставим
                $position->price = $capsula_product->salePrice; // Согласовать какую ставим
                $position->reserve = 1;
                $position->discount = 0;
                $position->assortment = (object)["meta" => $stock->getProductMeta($capsula_product->external_uuid)];
                $positions[] = $position;
            }

            // Переложить на
            $order = new CustomerOrder();
            $order->name = config('moy_sklad.ORDER_NAME') . uuid_v4();
            $order->description = config('moy_sklad.ORDER_NAME') . $description;
            $order->positions = $positions;

            $order->organization = $stock->getOrganization();
            $order->attributes[] = $stock->attributeAmo($amo_id);
            $order->meta = (object) [
                "href" => null,
                "type" => null,
                "mediaType" => 'application/json',
                "metadataHref" => null,
                "uuidHref" => null,
                "downloadHref" => null,
                "size" => null,
                "limit" => null,
                "offset" => null,
            ];

            // Пользователь=контрагент - ТЕСТ = Жукова
            $order->agent = $stock->setStylist();
            dd($order);
            return $stock->setOrder($order);

        } catch (\Exception $e){
            Log::channel('stock')->critical('Отправка заказа в МойСклад: ' . $e->getMessage());
            Log::channel('stock')->critical('Отправка заказа в МойСклад: ' . $e->getFile());
            Log::channel('stock')->critical('Отправка заказа в МойСклад: ' . $e->getLine());
            Log::channel('stock')->critical(json_encode($e->getTrace()));
        }

    }



}

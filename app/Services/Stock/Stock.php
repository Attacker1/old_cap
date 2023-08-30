<?php

namespace App\Services\Stock;

use Illuminate\Support\Facades\Storage;
use MoySklad\Entity\Document\CustomerOrder;
use MoySklad\Entity\MetaEntity;


/**
 * Class StockProduct
 * @package App\Services\Stock
 */
class Stock extends AbstractStock
{

    private array $rows = [];

    /**
     * StockCart constructor.
     */
    public function __construct()
    {
        // Инициализация api?
        $this->api = $this->api();
    }


    /**
     * AMO атрибут
     * @param string $value
     * @return object
     */
    public function attributeAmo(string $value): object
    {
        return (object)[
            "meta" => (object)[
                "href" => "https://online.moysklad.ru/api/remap/1.1/entity/customerorder/metadata/attributes/69bef690-744d-11ea-0a80-03ba0007d643",
                "type" => "attributemetadata",
                "mediaType" => "application/json"
            ],
            "name" => 'AMO_ID',
            "href" => null,
            "fileName" => null,
            "value" => $value
        ];
    }


    /**
     * Получить метаданные Пользователя контрагента // переделать на статику?
     * @param string $ms_contraget_uuid
     * @return \MoySklad\Entity\MetaEntity
     * @throws \MoySklad\Util\Exception\ApiClientException
     */
    public function setStylist($contragent_uuid = '72d52b94-9351-11ec-0a80-099600040b98') //  Екатерина Жук
    {
        return $this->api->entity()->counterparty()->get($contragent_uuid);
    }

    /**
     * Метаданные продкции // Переложить на статику?
     * @param $ms_uuid
     * @return \MoySklad\Entity\Meta|null
     * @throws \MoySklad\Util\Exception\ApiClientException
     */
    public function getProductMeta($ms_uuid){
        return $this->api->entity()->product()->get($ms_uuid)->getMeta();
    }

    public function setOrder(CustomerOrder $order){
        return $this->api->entity()->customerorder()->create($order);
    }

    /**
     * Вынести в таблицу stock_settings
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \MoySklad\Util\Exception\ApiClientException
     */
    public function getOrganization(){

        if (!$this->api)
            $this->api();

        $stock_settings = Storage::disk('local')->exists('stock/organization.json');

        if (!$stock_settings) {
            $ms = $this->api->entity()->organization()->getList()->rows[0]; // настройки в МС по компании
            Storage::disk('local')->put("/stock/organization.json",json_encode($ms,JSON_HEX_QUOT));
        }

        $stock_settings = Storage::disk('local')->get('stock/organization.json');
        return json_decode($stock_settings) ?? null;
    }


}

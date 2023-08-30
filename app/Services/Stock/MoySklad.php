<?php

namespace App\Services\Stock;


use App\Http\Models\Admin\ServiceToken;
use App\Http\Models\Stock\StockWebhook;
use Illuminate\Support\Facades\Storage;
use MoySklad\ApiClient;
use MoySklad\Entity\Attribute;
use MoySklad\Entity\MetaEntity;
use MoySklad\Entity\Product\Product;
use MoySklad\Entity\WebHook;
use MoySklad\Util\Param\Limit;
use MoySklad\Util\Param\Offset;

class MoySklad
{
    private ApiClient $api;
    private array $rows = [];
    private int $size = 0;
    private int $limit = 1000;
    private int $offset = 0;
    private array $params = [];
    private string $entity;
    private string $listFunction; // Ф-ция получения общего списка

    /**
     * MoySklad constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $host = config('config.MOY_SKLAD_HOST');
        $token = ServiceToken::where('service', 'sklad')->firstOrFail()->token;
        $this->api = new ApiClient($host, true, ['token' => $token]);
        return $this;
    }

    public function setEntity($entity = 'productfolder', $function = 'getList') :MoySklad
    {
        $this->entity = $entity;
        $this->listFunction = $function;
        return $this;
    }

    /**
     * Установить Webhook
     * entities: product, category
     * @return MetaEntity
     * @throws \MoySklad\Util\Exception\ApiClientException
     */
    public function setWebhook($entity_type = 'customerorder',$action = 'CREATE', $diff = 'NONE') : MetaEntity
    {

        $uuid = uuid_v4();
        switch ($entity_type){
            default:
                $entity = new WebHook();
                $entity->url = config('moy_sklad.WEBHOOK_URI').$uuid;
                $entity->action = $action;
                $entity->entityType = $entity_type;
                if ($action == 'UPDATE')
                    $entity->diffType = $diff;
                break;
        }

        if($response = $this->api->entity()->webhook()->create($entity))
            if (!empty($response->id))
                self::storeWebhook($response,$uuid);

        return $response;
    }

    /**
     * @param $response
     * @param $uuid
     * @return void
     */
    private function storeWebhook($response, $uuid): void
    {

        $webhook = new StockWebhook();
        $webhook->uuid = $uuid;
        $webhook->url = @$response->url;
        $webhook->action = @$response->action;
        $webhook->external_id = @$response->id;
        $webhook->entity = @$response->entityType;
        $webhook->save();
    }

    /**
     * Список Webhook
     * @return MetaEntity
     * @throws \MoySklad\Util\Exception\ApiClientException
     */
    public function getWebhooks() : MetaEntity
    {
        return $this->api->entity()->webhook()->getList();
    }


    /**
     * Удаление Webhook
     * @param string $id
     * @throws \MoySklad\Util\Exception\ApiClientException
     */
    public function dropWebhooks(string $id)
    {
        $this->api->entity()->webhook()->delete($id);
    }

    /**
     * Размерность
     * @return $this
     */
    public function getSize() :MoySklad
    {
        $data = $this->api->entity()->{$this->entity}()->{$this->listFunction}([Limit::eq(1)]);
        $this->size = (int) $data->getMeta()->size;
        return $this;
    }

    /**
     * Получение всех записей
     * @return array
     */
    public function getAll() :array
    {
        do {
            $this->params = [
                Limit::eq($this->limit),
                Offset::eq($this->offset)
            ];

            if (!$tmp = $this->api->entity()->{$this->entity}()->{$this->listFunction}($this->params))
                break;

            $this->offset += $this->limit;
            array_push($this->rows,...$tmp->rows);

        } while ($this->offset <= $this->size);

        return $this->rows;
    }

    /**
     * Получение Ограниченного кол-ва записей
     * @return array | bool
     */
    public function getLimited($limit = 10) :array
    {

            $this->params = [
                Limit::eq($limit),
                Offset::eq($this->offset)
            ];

            if (!$tmp = $this->api->entity()->{$this->entity}()->{$this->listFunction}($this->params))
                return false;

            $this->offset += $this->limit;
            array_push($this->rows,...$tmp->rows);


        return $tmp->rows;
    }

    /**
     * @return mixed
     * @throws \MoySklad\Util\Exception\ApiClientException
     */
    public function getAttributes()
    {
        return $this->api->entity()->product()->getMetadataAttributes()->rows;
    }


    /**
     * Вынести в таблицу stock_settings
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \MoySklad\Util\Exception\ApiClientException
     */
    public function organization(){

        $stock_settings = Storage::disk('local')->exists('stock/organization.json');

        if (!$stock_settings) {
            $ms = $this->api->entity()->organization()->getList()->rows[0]; // настройки в МС по компании
            Storage::disk('local')->put("/stock/organization.json",json_encode($ms,JSON_HEX_QUOT));
        }

        $stock_settings = Storage::disk('local')->get('stock/organization.json');
        return json_decode($stock_settings) ?? null;
    }







}
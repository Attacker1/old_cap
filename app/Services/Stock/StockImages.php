<?php

namespace App\Services\Stock;

use App\Http\Models\Stock\StockAttributes;
use App\Http\Models\Stock\StockLike;
use App\Http\Models\Stock\StockOtherImages;
use App\Http\Models\Stock\StockProducts;
use App\Imports\CouponImport;
use App\Imports\StockOtherImagesImport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class StockImages
 * @package App\Services\Stock
 */
class StockImages
{

    /**
     * @var StockProducts
     */
    private StockProducts $model;
    private array $rows = [];
    private StockProducts $item;
    private array $problem_attributes = ['8c95e06e-fb61-11eb-0a80-03dd00201027'];

    /**
     * Локальный путь-переменная перед закачкой в S3
     * @var string
     */
    private string $temp_dir = "stock_temp_images";

    /**
     * StockProduct constructor.
     */
    public function __construct()
    {
        $this->model = new StockProducts();
    }

    /**
     * @param string $filename
     * @return string
     */
    public function setPath(string $filename){
        return $this->temp_dir . "/" . $filename;
    }

    /**
     * Получение фотографий из API
     * @param string $uri
     * @return bool|string
     */
    public  function cropImage(string $uri,$filename){

        try {
            ini_set('memory_limit', '4096M');
            $temp_file = Storage::disk('public')->get($uri);
            if ($temp_file) {

                $width = 210;
                $height = 280;
                $img = Image::make($temp_file);
                $img->backup();
                $img->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                if ($img->width() == $width && $img->height() >= $height)
                    $img->resizeCanvas($width, $height, 'center', false, 'ffffff')
                        ->encode('jpg',95);
                else {
                    $img->reset();
                    $img->resize(false, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->resizeCanvas($width, $height, 'center', false, 'ffffff')
                        ->encode('jpg',95);
                }

                $preview_path = "preview/{$filename}.jpg";
                $file = Storage::disk('public')->put($preview_path, $img);
                unset($img);
                return !empty($file)
                    ? $preview_path
                    : null;
            }
            else{
                return null;
            }
        }
        catch (\Exception $e){
            Log::channel('ms')->error($e);
            return false;
        }
    }

    /**
     *  Загрузка ОСНОВНОГО фото из МойСклад
     * @param $filepath
     * @param array $headers
     * @param $downloadHref
     * @return bool
     */
    public function downloadImage($filepath, array $headers, $downloadHref): bool
    {

        $opts_loc = ['http' => ['method' => 'GET',
            'ignore_errors' => true,
            'header' => $headers,
            'follow_location' => 0,
        ]];
        $ctx_loc = stream_context_create($opts_loc);
        $resp = file_get_contents($downloadHref, false, $ctx_loc);
        $arrHeader = $http_response_header;

        foreach($arrHeader as $h) {
            if (strpos($h, 'Location:') !== false) {
                $url_img = str_replace('Location: ', '', $h);
                break;
            }
        }

        if (!empty($url_img))
            if ($resp = file_get_contents($url_img))
                $file =  Storage::disk('public')->put($filepath,$resp);

        return !empty($file);
    }

    /**
     * Загрузка доп фото по URL
     * @param $filepath
     * @param $downloadHref
     * @return bool
     */
    public function downloadImageFromUrl($filepath, $downloadHref): bool
    {

        if ($resp = file_get_contents($downloadHref))
            $file = Storage::disk('public')->put($filepath, $resp);

        return !empty($file);
    }

    /**
     * Attach фотографии к продукту
     * @param StockProducts $product
     * @param string $local_filepath
     * @param string $thumb_url
     * @param null $is_main
     * @return \Bnb\Laravel\Attachments\Attachment|null
     * @throws \Exception
     */
    public function attachImage(StockProducts $product, string $local_filepath, string $thumb_url, $is_main = null ){

        $attachment = $product->attach(public_path("storage/" . $local_filepath), [
            'disk' => 's3',
            'title' => Str::limit($product->name,50),
            'description' => $product->description,
            'key' => $is_main ? $product->external_uuid : uuid_v4(),
            'main' => $is_main,
            'visibility' => 'public',
            'public' => true,
            'preview_url' => "/storage/" . $thumb_url
            //'preview_url' => config("filesystems.disks.s3.baseurl") . $preview_path
        ]);

        return $attachment;

    }

    /**
     * Импорт XLS файла с картинками доп изображений в таблицу "stock_other_images"
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function importOtherImagesXls(){

        try {

            ini_set('memory_limit', '4096M');
            set_time_limit(360);

            if (request()->post()) {

                if(!request()->file('xlsFile') ) {
                    toastr()->error('Нет вложенного файла или ошибочного формата');
                    return \redirect()->back();
                }

                Excel::import(new StockOtherImagesImport(), request()->file('xlsFile'));
                toastr()->info('Данные загружены');
                return \redirect()->back();
            }

            return view('admin.stock.import_other_images', [
                'title' => 'Загрузка доп. изображений для STOCK'
            ]);

        } catch (\Exception $e) {

        }
    }

    /**
     * Фоновая загрузка изображений из предварительно загруженной таблицы stock_other_images
     * ToDo-Дмитрий сделать автоочистку?
     */

    public function backgroundDownloadOtherImages(){

        try {

            $this->i = 0;
            StockOtherImages::with("product")
                ->whereNull('processed_at')
                ->chunkById(100, function ($images) {
                foreach ($images as $image) {
                    $this->i++;
                    if (empty($image->product) || empty($image->product->external_uuid)) // external_uuid в запрос?
                        continue;

                    // Постфикс для доп фото
                    $name_postfix = $image->product->external_uuid ."_". uuid_v4_short();
                    // Локальная временная папка и наименование файла
                    $local_path = $this->setPath($name_postfix);
                    // Скачанное изображение
                    $path = $this->downloadImageFromUrl($local_path,$image->url);
                    // Локальный файл с превью
                    $thumb_path = $this->cropImage($local_path,$name_postfix);
                    $attach = $this->attachImage($image->product,$local_path,$thumb_path);

                    // Уходим от ограничения по выборке в 2 раза. Оф. документация
                    StockOtherImages::whereId($image->id)->update([
                        "processed_at" =>  now()
                    ]);
                }
            });

            return $this->i;

        }catch (\Exception $e) {
            return $e->getMessage();
        }


    }


}

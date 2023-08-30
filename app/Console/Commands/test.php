<?php

namespace App\Console\Commands;

use App\Facades\LogsisFacade;
use App\Http\Classes\Common;
use App\Http\Classes\Message;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Controllers\Classes\BoxberryApi;
use App\Http\Controllers\Classes\DoliApi;
use App\Http\Controllers\Classes\LogSysApi;
use App\Http\Models\Admin\ServiceToken;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Catalog\Product;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\Payments;
use App\Http\Models\Sber\SberAnketa;
use App\Http\Models\Stock\StockAttributes;
use App\Http\Models\Stock\StockProducts;
use App\Imports\CouponImport;
use App\Imports\ProductSizeImport;
use App\Services\AnswersAdapter;
use App\Services\ClientService;
use App\Services\FeedbackAdapter;
use App\Services\LeadService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Ixudra\Curl\Facades\Curl;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Тестирование через команды
 * Class common
 * @package App\Console\Commands
 */

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тестирование через команды';


    /**
     * backup constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->start = microtime(true);
        $this->perc = 0;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {

        dd(self::amoTag());
        //dd(self::tableAttributes());
        //dd(self::s3test());
        ///dd(self::authTokens());
//        dd(self::bulkAuthTokenAmo());
//
//        $bb = new BoxberryApi();
//        $bb->amoToBoxberry();

    }



    private function amoTag(){
        $amo = new AmoCrm();
        dd($amo->purchasedProducts(30855473,8500,1,false));
        //dd($amo->add_tags_ids(30656365,[111]));
    }

    private function authTokens(){

        Common::genClientAuthToken();
        return "токены созданы";
    }

    private function tableAttributes(){

        $attributes = StockAttributes::whereIn('id', [1, 19, 20, 21, 22])->get();
        $attributes = $attributes->map(function ($object) {
            $object->values = collect(\DB::table('stock_product_attributes')
                ->where('attribute_id', $object->id)->select('attribute_id', 'value')
                ->distinct('value')
                ->get());

            return $object->values->map(function ($item) {
                return Str::lower($item->value);
            });
        });


            return $attributes;
    }

    private function bulkAuthTokenAmo(){

        return Common::bulkClientAuthTokenAmo();
    }

    private function s3test(){

        $prod = StockProducts::with('attachments')->find(3);

        //$path = $prod->attachments()->first()->filepath;
        $path = Storage::disk('s3')->url($prod->attachments()->first()->filepath);

        dd($path);
        dd($prod);



        ob_implicit_flush(true);
        $this->msg("Начали");

        $files = Storage::disk('local')->allFiles("attachments"); //"attachments"
        $cnt = count($files);
        $this->msg("Всего файлов: " . $cnt);
        $i = 0;
        foreach ($files as $filename) {
            $content = Storage::disk('local')->get($filename);
            Storage::disk('s3')->put($filename, $content,'public');
            $i++;
            $this->progressBar($i,$cnt);
            dd($filename);
        }
        $this->msg("Закончили ");
        dd(count($files));

    }
    private function progressBar($done, $total) {

        $perc = floor(($done / $total) * 100);
        $left = 100 - $perc;
        if ($this->perc != $perc) {
            $this->perc = $perc;
            echo $this->perc . "%" . PHP_EOL;
        }
    }

    private function logsys(){

        $api = new LogSysApi();
        //$api->

    }

    private function runtime(){
        return $this->end = round(microtime(true) - $this->start) . ' с.';
    }

    public function msg($msg = ': Started ')
    {

        $mem = round(memory_get_usage() / 1000000,2);
        dump($this->runtime() ." " . $msg);
    }
}

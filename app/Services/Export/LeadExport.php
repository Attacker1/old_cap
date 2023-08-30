<?php

namespace App\Services\Export;


use App\Http\Models\Common\Lead;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class LeadExport
 * @package App\Services\Export
 */
class LeadExport
{
    private static $date;

    public function __construct()
    {
        self::$date = now()->format("d_m_Y");
    }

    /**
     * Выгрузка штрихкодов для склада
     * Выгружались id всех заказов, которые в статусе "Готовится к отправке" в формате TSV
     * @param string $field
     * @param int $state_id
     */
    public static function barcode($field = 'amo_lead_id',$state_id = 27)
    {

        try {
            if(!$lead_array = Lead::where("state_id", $state_id)->pluck($field)->toArray())
            {
                toastr()->error("Нет данных для сохранения!");
                return redirect()->back();
            }
            $file_data = array_merge(['Штрихкод'],$lead_array);

            toastr()->error("Данные записываются!");
            $filename = "barcodes_" . self::$date . ".tsv";
            if ($export_data = implode("\n",$file_data)) {

                return response($export_data)
                    ->withHeaders([
                        'Content-disposition' => "attachment; filename=$filename",
                        'Access-Control-Expose-Headers' => 'Content-Disposition',
                        'Content-Type' => 'text/csv',
                    ]);
            }

            return response()->json(["result" => false], 500);
        }
        // Отдаем  в JS ошибку
        catch (\Exception $e){
            return response()->json(["result" => false, "message" => $e->getMessage()], 500);
        }

    }

}
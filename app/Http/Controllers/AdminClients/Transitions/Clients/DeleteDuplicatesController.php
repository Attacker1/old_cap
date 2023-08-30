<?php

namespace App\Http\Controllers\AdminClients\Transitions\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class DeleteDuplicatesController extends Controller
{

    public function main()
    {
        if (!auth()->guard('admin')->user()->can('manage-clients')) {
            return redirect()->route('admin.main.index');
        }

        dump('Количество записей в таблице : ' . $this->countRows());

        dump('Удаление лишних пробелов .....');
        $this->trimData(); //удаление лишних пробелов

        dump('Всего дубликатов ' . $this->countDuplicates());

        dump('Количество дубликатов которые надо удалить : ' . $this->countDuplicatesForDelete());

        dump('Удаление дубликатов.....');
        
        $this->deleteDuplicates();

        dump('Количество записей в таблице после удаления: ' . $this->countRows());

        return view('admin-clients.clients-transition', [
            'title'=>'Временная страница'
        ]);
    }

    /**
     * Убирает лишние пробелы (без этого не находит все дубли)
     *
     * @return void
     */

    public function trimData()
    {

        $clientsData = DB::table('temp_source_clients')->get();

        foreach ($clientsData as $item)
        {
            DB::table('temp_source_clients')
                ->where('id', $item->id)
                ->update([
                    'phone' => trim($item->phone),
                    'name' => trim($item->name),
                    'email' => trim($item->email)]);
        }
    }

    /**
     * Подсчитывает количество записей в таблице
     *
     * @return integer
     */

    public function countRows()
    {
        return DB::table('temp_source_clients')->count();
    }

    /**
     * Ищет одинаковые по всем столбцам записи
     *
     * @return \Illuminate\Support\Collection
     */

    public function searchDuplicates()
    {
        return  DB::table('temp_source_clients')
            ->select('name', 'phone', 'email', DB::raw('COUNT(*) AS `count`'))
            ->groupBy('name','phone','email')
            ->havingRaw('count > 1', [])
            ->get();
    }

    /**
     * Подсчитывает сколько дублей в базе
     *
     *
     * @return integer
     */

    public function countDuplicates()
    {
        $arr_duplicates = $this->searchDuplicates()->all();
        $column_count = array_column( $arr_duplicates, 'count');
        return  array_sum($column_count);
    }

    /**
     * Подсчитывает сколько дублей в базе будет удалено
     *
     * @return integer
     */

    public function countDuplicatesForDelete()
    {
        $arr_duplicates = $this->searchDuplicates()->all();
        $column_count = array_column( $arr_duplicates, 'count');
        return  array_sum($column_count) - count($column_count);
    }

    /**
     * Выводит дубли которые будут удалены
     *
     *
     * @return array
     */

    public function showDuplicatesForDelete()
    {
        return DB::select('
SELECT
	*
FROM  
	`temp_source_clients`
LEFT OUTER JOIN 
	(SELECT MIN(`id`) AS `id`, `name`, `phone`, `email` FROM `temp_source_clients` GROUP BY `name`, `phone`, `email`) AS `tmp` 
ON 
	`temp_source_clients`.`id` = `tmp`.`id`  
WHERE
	`tmp`.`id` IS NULL', []);
    }

    public function deleteDuplicates()
    {
        return DB::select('
DELETE
	`temp_source_clients`
FROM  
	`temp_source_clients`
LEFT OUTER JOIN 
	(SELECT MIN(`id`) AS `id`, `name`, `phone`, `email` FROM `temp_source_clients` GROUP BY `name`, `phone`, `email`) AS `tmp` 
ON 
	`temp_source_clients`.`id` = `tmp`.`id`  
WHERE
	`tmp`.`id` IS NULL', []);
    }

}
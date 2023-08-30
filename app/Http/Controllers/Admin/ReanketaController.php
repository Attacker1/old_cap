<?php

namespace App\Http\Controllers\Admin;

use App\Http\Classes\Anketa\QuestionDataToAnketa;
use App\Http\Controllers\Admin\Anketa\TabReferenceController;
use \App\Http\Controllers\Controller;

use App\Http\Models\AdminClient\AnketaStylistComment;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\AdminClient\Questionnaire;
use App\Helpers\AnketaHelper;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Vuex\Anketa\AnketaQuestion;
use Bnb\Laravel\Attachments\Attachment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Models\Common\Lead;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Vuex\Anketa\AnketaQuestionOption;

class ReanketaController extends Controller
{
    use AnketaHelper;

    private function pallete($id) {
        if(!$q = AnketaQuestionOption::find($id))
        return false;

        return $q->pallete;
    }

    public function list(Request $request)
    {
        if (!auth()->guard('admin')->user()->can('manage-anketa') && !auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            return redirect()->route('admin.main.index');
        }

        if (request()->ajax()) {
            $arr_columns = [
                1 => 'uuid',
                2 => 'amount',
                3 => 'created_at',
                4 => 'updated_at'];

            $request_params = $request->all();

            $order_column = $arr_columns[$request_params['order'][0]['column']];
            $order_dir = $request_params['order'][0]['dir'];

            $pagination_start = $request_params['start'];

            $pagination_length = $request_params['length'];

            $search_value = $request_params['search']['value'];

            session()->forget('anketa_data');
            session()->push('anketa_data', [
                'order_column' => $request_params['order'][0]['column'],
                'order_dir' => $order_dir,
                'search_value' => $search_value,
                'limit_menu' => $pagination_length,
                'paging_start' => $pagination_start
            ]);

            $anketa = Questionnaire::select($arr_columns);

            if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
                $stylist_id = auth()->guard('admin')->user()->id;
                $arr_anketa_ids = Lead::where('stylist_id', $stylist_id)->get()->pluck('anketa_uuid')->toArray();

                $anketa = $anketa->whereIn('uuid', $arr_anketa_ids);
            }

            if ($search_value != '') {
                $anketa = $anketa
                    ->where('uuid', 'LIKE', "%{$search_value}%")
                    ->orWhere('amount', 'LIKE', "%{$search_value}%");

                $recordsFiltered = $anketa->count();
            }

            $anketa = $anketa
                ->orderBy($order_column, $order_dir)
                ->take($pagination_length)
                ->skip($pagination_start)
                ->get()
                ->toArray();

            /*$anketa
                ->orderBy($order_column, $order_dir)
                ->take($pagination_length)
                ->skip($pagination_start)->dump();*/

            if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {

                $recordsTotal = Questionnaire::whereIn('uuid', $arr_anketa_ids)->where('source', 'reanketa')->count();

            } else $recordsTotal = Questionnaire::where('source', 'reanketa')->count();

            $recordsFiltered = $recordsFiltered ?? $recordsTotal;

            $res = [
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $anketa,
                'temp' => session()->get('anketa_data')
            ];

            return json_encode($res);
        }

        if (session('status_success')) {
            toastr()->success(session('status_success'));
        }

        if (session('status_error')) {
            toastr()->error(session('status_error'));
        }

        $datatable_params[0] = [
            'order_column' => '3',
            'order_dir' => 'desc',
            'search_value' => '',
            'limit_menu' => '10',
            'paging_start' => 0
        ];

        if (session()->has('anketa_data')) {
            $datatable_params = session()->get('anketa_data', 'default');
        }

        return view(
            'admin.reanketa.list', [
            'title' => 'Управление анкетами',
            'datatable_params' => $datatable_params[0]
        ]);
    }

    public function reset_datatable_settings()
    {
        session()->forget('anketa_data');
        return redirect()->route('admin.anketa.list.fill');
    }

    private function createParams($arr_params, $anketa, $key_slug) {
        if(!isset($anketa[$key_slug])) return $arr_params;
        if(is_array($anketa[$key_slug])) {
            if($arr_params[$key_slug]['answer'] != '') {
                $arr_params[$key_slug]['last'] = true;
            } elseif( $arr_params[$key_slug]['answer']== '') {

                for($i = 0; $i < count($anketa[$key_slug]); $i++) {
                    if($arr_params[$key_slug]['answer']!= '') $arr_params[$key_slug]['answer'].= ', ';
                    $arr_params[$key_slug]['answer'].= $anketa[$key_slug][$i]['text'];
                }
            }
        } elseif(is_string($anketa[$key_slug])) {
            if($arr_params[$key_slug]['answer'] != '' && $anketa[$key_slug] != '') {
                $arr_params[$key_slug]['last'] = true;
            } elseif($arr_params[$key_slug]['answer'] == '' && $anketa[$key_slug] != '') {
                $arr_params[$key_slug]['answer'] = $anketa[$key_slug];
            }
        }

        return $arr_params;
    }

    private function formatAnswer($answer) {
        $answer = trim($answer);
        $answer = str_replace('<br>', ' ', $answer);
        $answer = strip_tags($answer);

        return $answer;
    }

    public function show($uuid)
    {
        //нет прав ни на управление ни на просмотр своих анкет
        if (!auth()->guard('admin')->user()->can('manage-anketa') && !auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            toastr()->error('Нет прав на просмотр анкеты');
            return redirect()->route('admin.main.index');
        }

        //нет прав на просмотр данной анкеты
        if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            $check = Lead:: where('stylist_id', auth()->guard('admin')->user()->id)->where('anketa_uuid', $uuid)->count();
            if ($check === 0) {
                toastr()->error('Нет прав на просмотр анкеты');
                return redirect()->route('admin.main.index');;
            }
        }

        if(!$reanketa = Questionnaire::where('uuid', $uuid)->where('source', 'reanketa')->latest()->first()) {
            toastr()->error('повторная анкета не найдена');
            return view('admin.anketa.edit', [
                'error' =>'Повторная анкета не найдена',
                'title' => 'Просмотр анкеты'
            ]);
        }

        if(!$lead_reanketa= Lead::where('anketa_uuid', $reanketa->uuid)->first()) {
            toastr()->error('сделка по повторной не найдена');
            return view('admin.anketa.edit', [
                'error' =>'сделка по повторной не найдена',
                'title' => 'Просмотр анкеты'
            ]);
        }

        $leads = Lead::where('client_id', $reanketa->client_uuid)
            ->whereNotNull('anketa_uuid')
            //->where('state_id', '>', 5)
            ->where('created_at','<=', $lead_reanketa->created_at)
            ->orderBy('created_at','desc')
            ->get();

        $answers_values_arr_converter =  AnketaQuestionOption::get()->pluck('text', 'id');

        foreach($leads as $lead) {

            if (!$lead->questionnaire) continue;
            if(!$anketa = $lead->questionnaire->anketa) continue;

            $source = ($lead->questionnaire->source == 'reanketa') ? $lead->questionnaire->source : 'default';
            foreach ($anketa as $keyAnketa => $valueAnketa) {
                $image_data = '';
                if ($q_images = AnketaQuestion::where('slug', $keyAnketa)->first())
                    $image_data = $q_images->style_image;
                if (is_array($valueAnketa)) {

                    for ($i = 0; $i < count($valueAnketa); $i++) {
                        if (isset($answers_values_arr_converter[$valueAnketa[$i]])) {

                            $anketa[$keyAnketa][$i] = [];
                            $anketa[$keyAnketa][$i]['text'] = $answers_values_arr_converter[$valueAnketa[$i]];

                            $image = '';
                            if ($q = AnketaQuestionOption::find($valueAnketa[$i])) {
                                if ($q->image) {
                                    $anketa[$keyAnketa][$i]['image'] = config('config.ANKETA_URL') . '/storage/' . $q->image;
                                }
                            }

                            if ($pallete = self::pallete($valueAnketa[$i])) {
                                $anketa[$keyAnketa][$i]['pallete'] = $pallete;
                            }

                            if (strpos($keyAnketa, 'choosingStyle') === 0) {

                                if ($q = AnketaQuestionOption::find($valueAnketa[$i])) {
                                    $anketa[$keyAnketa]['answer'][] = $q->option_key;
                                    if ($image_data != '') $anketa[$keyAnketa]['image'] = config('config.ANKETA_URL') . '/storage/' . $image_data;
                                }

                            }

                        }
                    }
                }

            $anketas[$lead->questionnaire->uuid] = $anketa;
        }
        }

        if( count($anketas) == 1 && array_key_first($anketas) == $reanketa->uuid) {

            if($old_anketas = Questionnaire::where('client_uuid', $lead_reanketa->client_id)->whereNotNull('amo_id')->latest()->get()) {

                foreach ($old_anketas as $old_anketa) {
                    $questionDataToAnketa = new QuestionDataToAnketa();
                    $anketa = $questionDataToAnketa->convertFirst($old_anketa->uuid);

                    if($anketa) {

                        $source = $old_anketa->source;

                        foreach ($anketa as $keyAnketa => $valueAnketa) {
                            $image_data = '';
                            if ($q_images = AnketaQuestion::where('slug', $keyAnketa)->first())
                                $image_data = $q_images->style_image;
                            if (is_array($valueAnketa)) {

                                for ($i = 0; $i < count($valueAnketa); $i++) {
                                    if (isset($answers_values_arr_converter[$valueAnketa[$i]])) {

                                        $anketa[$keyAnketa][$i] = [];
                                        $anketa[$keyAnketa][$i]['text'] = $answers_values_arr_converter[$valueAnketa[$i]];

                                        $image = '';
                                        if ($q = AnketaQuestionOption::find($valueAnketa[$i])) {
                                            if ($q->image) {
                                                $anketa[$keyAnketa][$i]['image'] = config('config.ANKETA_URL') . '/storage/' . $q->image;
                                            }
                                        }

                                        if ($pallete = self::pallete($valueAnketa[$i])) {
                                            $anketa[$keyAnketa][$i]['pallete'] = $pallete;
                                        }

                                        if (strpos($keyAnketa, 'choosingStyle') === 0) {

                                            if ($q = AnketaQuestionOption::find($valueAnketa[$i])) {
                                                $anketa[$keyAnketa]['answer'][] = $q->option_key;
                                                if ($image_data != '') $anketa[$keyAnketa]['image'] = config('config.ANKETA_URL') . '/storage/' . $image_data;
                                            }

                                        }

                                    }
                                }
                            }
                        }

                        $anketas[$old_anketa->uuid] = $anketa;
                    }
                }
            }
        }

        // все сделки по этой анкете и клиенту
        $leads = '';

        $leads = Lead::where('client_id', $reanketa->client->uuid)->where('anketa_uuid', $reanketa->uuid)->get()->implode('amo_lead_id', ', ');

        //блок строгие нет
        $arr_strogie_net = ['noColor' =>[], 'printsDislike'=>[], 'capsulaNotWantAccessories'=>[], 'fabricsShouldAvoid'=>[], 'bodyPartsToHide'=>[]];

        $last_colors = 0;
        $set_last_colors = 0;
        $last_prints = 0;
        $set_last_prints = 0;
        $last_accessor = 0;
        $set_last_accessor = 0;
        $last_fabrics = 0;
        $set_last_fabrics = 0;
        $last_hide = 0;
        $set_last_hide = 0;

        foreach ($anketas as $key => $anketa) {

            //цвета
            if(isset($anketa['noColor'])) {
                for ($i = 0; $i < count($anketa['noColor']); $i++) {

                    if (!in_array($anketa['noColor'][$i], $arr_strogie_net['noColor'])) {
                        if ($last_colors === 0) $anketa['noColor'][$i]['last'] = true;
                        $set_last_colors = 1;
                        $arr_strogie_net['noColor'][] = $anketa['noColor'][$i];
                    }
                }
            }
            if ($set_last_colors === 1) $last_colors = 1;

            //принты
            if(isset($anketa['printsDislike'])) {
                for($i = 0; $i < count($anketa['printsDislike']); $i++) {
                    if(isset($anketa['printsDislike'][$i])) {
                        if(!in_array($anketa['printsDislike'][$i], $arr_strogie_net['printsDislike'])) {
                            if($last_prints === 0) $anketa['printsDislike'][$i]['last'] = true;
                            $set_last_prints = 1;
                            $arr_strogie_net['printsDislike'][] = $anketa['printsDislike'][$i];
                        }
                    }
                }
            }
            if($set_last_prints === 1) $last_prints = 1;
            //dd($anketa);
            //аксессуары
            if (isset($anketa['capsulaNotWantAccessories'])) {
                for ($i = 0; $i < count($anketa['capsulaNotWantAccessories']); $i++) {

                    if (!in_array($anketa['capsulaNotWantAccessories'][$i], $arr_strogie_net['capsulaNotWantAccessories'])) {
                        if ($last_accessor === 0) $anketa['capsulaNotWantAccessories'][$i]['last'] = true;
                        $set_last_accessor = 1;
                        $arr_strogie_net['capsulaNotWantAccessories'][] = $anketa['capsulaNotWantAccessories'][$i];
                    }
                }
            }
            if($set_last_accessor === 1) $last_accessor = 1;

            //ткани
            if(isset($anketa['fabricsShouldAvoid'])) {
                for($i = 0; $i < count($anketa['fabricsShouldAvoid']); $i++) {
                    if(!in_array($anketa['fabricsShouldAvoid'][$i], $arr_strogie_net['fabricsShouldAvoid'])) {
                        if($last_fabrics === 0) $anketa['fabricsShouldAvoid'][$i]['last'] = true;
                        $set_last_fabrics = 1;
                        $arr_strogie_net['fabricsShouldAvoid'][] = $anketa['fabricsShouldAvoid'][$i];
                    }
                }
            }

            if($set_last_fabrics === 1) $last_fabrics = 1;

            //скрыть
            if(isset($anketa['bodyPartsToHide'])) {
                for($i = 0; $i < count($anketa['bodyPartsToHide']); $i++) {
                    if(!in_array($anketa['bodyPartsToHide'][$i], $arr_strogie_net['bodyPartsToHide'])) {
                        if($last_hide === 0) $anketa['bodyPartsToHide'][$i]['last'] = true;
                        $set_last_hide = 1;
                        $arr_strogie_net['bodyPartsToHide'][] = $anketa['bodyPartsToHide'][$i];
                    }
                }
            }

            if($set_last_hide === 1) $last_hide = 1;
        }

        //блок Параметры
        $arr_params = [
            'bioHeight' => ['answer'=>''],
            'bioWeight' => ['answer'=>''],
            'bioBirth' => ['answer'=>''],
            'occupation' => ['answer'=>''],
            'hairColor' =>['answer'=>''],
            'sizeTop'=>['answer'=>''],
            'aboutTopStyle'=>['answer'=>''],
            'sizeBottom'=>['answer'=>''],
            'aboutBottomStyle'=>['answer'=>''],
            'bioChest'=>['answer'=>''],
            'bioWaist'=>['answer'=>''],
            'bioHips'=>['answer'=>''],
            'figura'=>['answer'=>''],
            'socials'=>['answer'=>'']];

        foreach ($anketas as $key => $anketa) {

            //рост 26
            $arr_params = self::createParams($arr_params, $anketa, 'bioHeight');

            //вес weight 70
            $arr_params = self::createParams($arr_params, $anketa, 'bioWeight');

            //birth 27
            $arr_params = self::createParams($arr_params, $anketa, 'bioBirth');

            //род деятельности occupation 28
            $arr_params = self::createParams($arr_params, $anketa, 'occupation');

            //цвет волос hairColor 29
            $arr_params = self::createParams($arr_params, $anketa, 'hairColor');

            //Размер верха sizeTop 30
            $arr_params = self::createParams($arr_params, $anketa, 'sizeTop');

            //Как носит верх aboutTopStyle 35
            $arr_params = self::createParams($arr_params, $anketa, 'aboutTopStyle');

            //Размер низа sizeBottom 31
            $arr_params = self::createParams($arr_params, $anketa, 'sizeBottom');

            //Как носит низ aboutBottomStyle 36
            $arr_params = self::createParams($arr_params, $anketa, 'aboutBottomStyle');

            //Объем груди chest 32
            $arr_params = self::createParams($arr_params, $anketa, 'bioChest');

            //Объем талии waist 33
            $arr_params = self::createParams($arr_params, $anketa, 'bioWaist');

            //Объем бедер hips 34
            $arr_params = self::createParams($arr_params, $anketa, 'bioHips');

            //Фото socials 68
            $arr_params = self::createParams($arr_params, $anketa, 'socials');

        }

        //рост petit средняя высокая
        if($arr_params['bioHeight']['answer'] != '') {
            if(!empty($anketa['bioHeight'])) {
                $arr_params['bioHeight']['answer'] .= ' ('. $this->calculateHeight((integer)trim($anketa['bioHeight'])) . ')';
            }
        }

        //дата рождения
        if(!isset($arr_params['bioBirth'])) {
            if(!empty($arr_params['bioBirth']['answer'])) {
                $arr_params['bioBirth']['answer']= $this->calculateAge($anketa['bioBirth']);
            }
        }

        //расчет фигуры
        $og = isset($anketa['bioChest']) ? trim($anketa['bioChest']) : '';
        $ot = isset($anketa['bioWaist']) ? trim($anketa['bioWaist']) : '';
        $ob = isset($anketa['bioHips']) ? trim($anketa['bioHips']) : '';

        $arr_params['figura']['answer'] = $this->calculateFigura($og, $ot, $ob);

        //блок остальные вопросы в таблице
        $arr_ankets = [];
        $number = 0;

        foreach (array_reverse(array_slice($anketas, 0, 5)) as $key => $anketa) {

            $arr_ankets[$key] = [];

            $arr_ankets[$key]['capsula_num']= '?';
            if($lead = Lead::where('anketa_uuid', $key)->latest()->first()){
                $arr_ankets[$key]['capsula_num'] = $lead->client_num ?? '?';
            }

            //Цель подборки whatPurpose 62
            if(empty($anketa['whatPurpose'])) {
                if(isset($whatPurpose))
                    $arr_ankets[$key]['whatPurpose'] = $whatPurpose;
            } else {

                if(isset($anketa['whatPurpose'][0]['text'])) {
                    $arr_ankets[$key]['whatPurpose'] = $whatPurpose = $anketa['whatPurpose'][0]['text'];
                } else $arr_ankets[$key]['whatPurpose'] = $whatPurpose = $anketa['whatPurpose'];

            }

            //Как действуем tryOtherOrSaveStyle 3
            if(empty($anketa['tryOtherOrSaveStyle'])) {
                if(isset($tryOtherOrSaveStyle))
                    $arr_ankets[$key]['tryOtherOrSaveStyle'] = $tryOtherOrSaveStyle;
            } else {
                if(isset($anketa['tryOtherOrSaveStyle'][0]['text'])) {
                    $arr_ankets[$key]['tryOtherOrSaveStyle'] = $tryOtherOrSaveStyle = $anketa['tryOtherOrSaveStyle'][0]['text'];
                } else $arr_ankets[$key]['tryOtherOrSaveStyle'] = $tryOtherOrSaveStyle = $anketa['tryOtherOrSaveStyle'];
            }

            //Не нужны в капсуле capsulaNotFirstOfAll 48

            if(empty($anketa['capsulaNotFirstOfAll'])) {
                if(isset($capsulaNotFirstOfAll))
                    $arr_ankets[$key]['capsulaNotFirstOfAll'] = $capsulaNotFirstOfAll;
            } else {

                $arr_ankets[$key]['capsulaNotFirstOfAll']= '';
                for($i = 0; $i < count($anketa['capsulaNotFirstOfAll']); $i++ ){
                    $arr_ankets[$key]['capsulaNotFirstOfAll'].= $anketa['capsulaNotFirstOfAll'][$i]['text']. ', ';
                }
                $arr_ankets[$key]['capsulaNotFirstOfAll'] = trim($arr_ankets[$key]['capsulaNotFirstOfAll'], ', ');
                $capsulaNotFirstOfAll = $arr_ankets[$key]['capsulaNotFirstOfAll'];

            }

            //Пожелания по вещам в капсуле capsulaFirstOfAll 47

            if (empty($anketa['capsulaFirstOfAll'])) {
                if(isset($capsulaFirstOfAll)) {
                    $arr_ankets[$key]['capsulaFirstOfAll'] = $capsulaFirstOfAll;
                }
            } else {

                $arr_ankets[$key]['capsulaFirstOfAll'] = '';
                for ($i = 0; $i < count($anketa['capsulaFirstOfAll']); $i++) {
                    $arr_ankets[$key]['capsulaFirstOfAll'] .= $anketa['capsulaFirstOfAll'][$i]['text'] . ', ';
                }
                $arr_ankets[$key]['capsulaFirstOfAll'] = trim($arr_ankets[$key]['capsulaFirstOfAll'], ', ');
                $capsulaFirstOfAll = $arr_ankets[$key]['capsulaFirstOfAll'];

            }

            //Комментарий к стилисту additionalNuances 53
            if(empty($anketa['additionalNuances'])) {
                if(isset($additionalNuances))
                    $arr_ankets[$key]['additionalNuances'] = $additionalNuances;
            } else $arr_ankets[$key]['additionalNuances'] = $additionalNuances = $anketa['additionalNuances'];

            //Цена блуза/ рубашки howMuchToSpendOnBlouseShirt 54

            if(empty($anketa['howMuchToSpendOnBlouseShirt'])) {
                if(isset($howMuchToSpendOnBlouseShirt)) {
                    $arr_ankets[$key]['howMuchToSpendOnBlouseShirt'] = $howMuchToSpendOnBlouseShirt;
                }
            } else {

                $arr_ankets[$key]['howMuchToSpendOnBlouseShirt']= '';

                for ($i = 0; $i < count($anketa['howMuchToSpendOnBlouseShirt']); $i++) {
                    $arr_ankets[$key]['howMuchToSpendOnBlouseShirt'] .= 'блуза/ рубашки- ' . $anketa['howMuchToSpendOnBlouseShirt'][$i]['text'] . ', ';
                }

                $arr_ankets[$key]['howMuchToSpendOnBlouseShirt'] = trim($arr_ankets[$key]['howMuchToSpendOnBlouseShirt'], ', ');
                $howMuchToSpendOnBlouseShirt = $arr_ankets[$key]['howMuchToSpendOnBlouseShirt'];

            }

            //Цена свитер/ джемпер howMuchToSpendOnSweaterJumperPullover 55

            if(empty($anketa['howMuchToSpendOnSweaterJumperPullover'])) {
                if(isset($howMuchToSpendOnSweaterJumperPullover))
                    $arr_ankets[$key]['howMuchToSpendOnSweaterJumperPullover'] = $howMuchToSpendOnSweaterJumperPullover;
            } else {

                $arr_ankets[$key]['howMuchToSpendOnSweaterJumperPullover']= '';
                if(is_array($anketa['howMuchToSpendOnSweaterJumperPullover'])) {
                    for ($i = 0; $i < count($anketa['howMuchToSpendOnSweaterJumperPullover']); $i++) {
                        $arr_ankets[$key]['howMuchToSpendOnSweaterJumperPullover'] .= 'свитер/ джемпер- ' . $anketa['howMuchToSpendOnSweaterJumperPullover'][$i]['text'] . ', ';
                    }
                } elseif(is_string($anketa['howMuchToSpendOnSweaterJumperPullover'])) {
                    $arr_ankets[$key]['howMuchToSpendOnSweaterJumperPullover'] .= 'свитер/ джемпер- ' . $anketa['howMuchToSpendOnSweaterJumperPullover'] . ', ';
                }
                $arr_ankets[$key]['howMuchToSpendOnSweaterJumperPullover'] = trim($arr_ankets[$key]['howMuchToSpendOnSweaterJumperPullover'], ', ');
                $howMuchToSpendOnSweaterJumperPullover = $arr_ankets[$key]['howMuchToSpendOnSweaterJumperPullover'];
            }

            //Цена платья/ сарафаны howMuchToSpendOnDressesSundresses 56

            if(empty($anketa['howMuchToSpendOnDressesSundresses'])) {
                if(isset($howMuchToSpendOnDressesSundresses))
                    $arr_ankets[$key]['howMuchToSpendOnDressesSundresses'] = $howMuchToSpendOnDressesSundresses;
            } else {

                $arr_ankets[$key]['howMuchToSpendOnDressesSundresses']= '';
                if(is_array($anketa['howMuchToSpendOnDressesSundresses'])) {
                    for ($i = 0; $i < count($anketa['howMuchToSpendOnDressesSundresses']); $i++) {
                        $arr_ankets[$key]['howMuchToSpendOnDressesSundresses'] .= 'платья/ сарафаны- ' . $anketa['howMuchToSpendOnDressesSundresses'][$i]['text'] . ', ';
                    }
                } elseif(is_string($anketa['howMuchToSpendOnDressesSundresses'])) {
                    $arr_ankets[$key]['howMuchToSpendOnDressesSundresses'] .= 'платья/ сарафаны- ' . $anketa['howMuchToSpendOnDressesSundresses'] . ', ';
                }
                $arr_ankets[$key]['howMuchToSpendOnDressesSundresses'] = trim($arr_ankets[$key]['howMuchToSpendOnDressesSundresses'], ', ');
                $howMuchToSpendOnDressesSundresses = $arr_ankets[$key]['howMuchToSpendOnDressesSundresses'];

            }

            //Цена жакет/ пиджак howMuchToSpendOnJacket 57

            if(empty($anketa['howMuchToSpendOnJacket'])) {
                if(isset($howMuchToSpendOnJacket))
                    $arr_ankets[$key]['howMuchToSpendOnJacket'] = $howMuchToSpendOnJacket;
            } else {

                $arr_ankets[$key]['howMuchToSpendOnJacket']= '';
                if(is_array($anketa['howMuchToSpendOnJacket'])) {
                    for ($i = 0; $i < count($anketa['howMuchToSpendOnJacket']); $i++) {
                        $arr_ankets[$key]['howMuchToSpendOnJacket'] .= 'жакет/ пиджак- ' . $anketa['howMuchToSpendOnJacket'][$i]['text'] . ', ';
                    }
                } elseif(is_string($anketa['howMuchToSpendOnJacket'])) {
                    $arr_ankets[$key]['howMuchToSpendOnJacket'] .= 'жакет/ пиджак- ' . $anketa['howMuchToSpendOnJacket'] . ', ';
                }
                $arr_ankets[$key]['howMuchToSpendOnJacket'] = trim($arr_ankets[$key]['howMuchToSpendOnJacket'], ', ');
                $howMuchToSpendOnJacket = $arr_ankets[$key]['howMuchToSpendOnJacket'];

            }

            //Цена джинсы/ брюки/ юбки howMuchToSpendOnJeansTrousersSkirts 58

            if(empty($anketa['howMuchToSpendOnJeansTrousersSkirts'])) {
                if(isset($howMuchToSpendOnJeansTrousersSkirts))
                    $arr_ankets[$key]['howMuchToSpendOnJeansTrousersSkirts'] = $howMuchToSpendOnJeansTrousersSkirts;
            } else {

                $arr_ankets[$key]['howMuchToSpendOnJeansTrousersSkirts']= '';
                if(is_array($anketa['howMuchToSpendOnJeansTrousersSkirts'])) {
                    for ($i = 0; $i < count($anketa['howMuchToSpendOnJeansTrousersSkirts']); $i++) {
                        $arr_ankets[$key]['howMuchToSpendOnJeansTrousersSkirts'] .= 'джинсы/ брюки/ юбки- ' . $anketa['howMuchToSpendOnJeansTrousersSkirts'][$i]['text'] . ', ';
                    }
                } elseif(is_string($anketa['howMuchToSpendOnJeansTrousersSkirts'])) {
                    $arr_ankets[$key]['howMuchToSpendOnJeansTrousersSkirts'] .= 'джинсы/ брюки/ юбки- ' . $anketa['howMuchToSpendOnJeansTrousersSkirts'] . ', ';
                }
                $arr_ankets[$key]['howMuchToSpendOnJeansTrousersSkirts'] = trim($arr_ankets[$key]['howMuchToSpendOnJeansTrousersSkirts'], ', ');
                $howMuchToSpendOnJeansTrousersSkirts = $arr_ankets[$key]['howMuchToSpendOnJeansTrousersSkirts'];

            }

            //Цена сумки howMuchToSpendOnBags 59
            if(empty($anketa['howMuchToSpendOnBags'])) {
                if(isset($howMuchToSpendOnBags))
                    $arr_ankets[$key]['howMuchToSpendOnBags'] = $howMuchToSpendOnBags;
            } else {

                $arr_ankets[$key]['howMuchToSpendOnBags']= '';
                if(is_array($anketa['howMuchToSpendOnBags'])) {
                    for ($i = 0; $i < count($anketa['howMuchToSpendOnBags']); $i++) {
                        $arr_ankets[$key]['howMuchToSpendOnBags'] .= 'сумки- ' . $anketa['howMuchToSpendOnBags'][$i]['text'] . ', ';
                    }
                } elseif(is_string($anketa['howMuchToSpendOnBags'])) {
                    $arr_ankets[$key]['howMuchToSpendOnBags'] .= 'сумки- ' . $anketa['howMuchToSpendOnBags'] . ', ';
                }
                $arr_ankets[$key]['howMuchToSpendOnBags'] = trim($arr_ankets[$key]['howMuchToSpendOnBags'], ', ');
                $howMuchToSpendOnBags = $arr_ankets[$key]['howMuchToSpendOnBags'];

            }

            //Цена серьги/ браслеты howMuchToSpendOnEarringsNecklacesBracelets 61
            if(empty($anketa['howMuchToSpendOnEarringsNecklacesBracelets'])) {
                if(isset($howMuchToSpendOnEarringsNecklacesBracelets))
                    $arr_ankets[$key]['howMuchToSpendOnEarringsNecklacesBracelets'] = $howMuchToSpendOnEarringsNecklacesBracelets;
            } else {

                $arr_ankets[$key]['howMuchToSpendOnEarringsNecklacesBracelets']= '';
                if(is_array($anketa['howMuchToSpendOnEarringsNecklacesBracelets'])) {
                    for ($i = 0; $i < count($anketa['howMuchToSpendOnEarringsNecklacesBracelets']); $i++) {
                        $arr_ankets[$key]['howMuchToSpendOnEarringsNecklacesBracelets'] .= 'cерьги/ браслеты- ' . $anketa['howMuchToSpendOnEarringsNecklacesBracelets'][$i]['text'] . ', ';
                    }
                } elseif(is_string($anketa['howMuchToSpendOnEarringsNecklacesBracelets'])) {
                    $arr_ankets[$key]['howMuchToSpendOnEarringsNecklacesBracelets'] .= 'cерьги/ браслеты- ' . $anketa['howMuchToSpendOnEarringsNecklacesBracelets'] . ', ';
                }
                $arr_ankets[$key]['howMuchToSpendOnEarringsNecklacesBracelets'] = trim($arr_ankets[$key]['howMuchToSpendOnEarringsNecklacesBracelets'], ', ');
                $howMuchToSpendOnEarringsNecklacesBracelets = $arr_ankets[$key]['howMuchToSpendOnEarringsNecklacesBracelets'];

            }

            //Цена ремни / шарфы / платки
            if(empty($anketa['howMuchToSpendOnBeltsScarvesShawls'])) {
                if(isset($howMuchToSpendOnBeltsScarvesShawls))
                    $arr_ankets[$key]['howMuchToSpendOnBeltsScarvesShawls'] = $howMuchToSpendOnBeltsScarvesShawls;
            } else {

                $arr_ankets[$key]['howMuchToSpendOnBeltsScarvesShawls']= '';
                if(is_array($anketa['howMuchToSpendOnBeltsScarvesShawls'])) {
                    for ($i = 0; $i < count($anketa['howMuchToSpendOnBeltsScarvesShawls']); $i++) {
                        $arr_ankets[$key]['howMuchToSpendOnBeltsScarvesShawls'] .= 'ремни / шарфы / платки- ' . $anketa['howMuchToSpendOnBeltsScarvesShawls'][$i]['text'] . ', ';
                    }
                } elseif(is_string($anketa['howMuchToSpendOnBeltsScarvesShawls'])) {
                    $arr_ankets[$key]['howMuchToSpendOnBeltsScarvesShawls'] .= 'ремни / шарфы / платки- ' . $anketa['howMuchToSpendOnBeltsScarvesShawls'] . ', ';
                }
                $arr_ankets[$key]['howMuchToSpendOnBeltsScarvesShawls'] = trim($arr_ankets[$key]['howMuchToSpendOnBeltsScarvesShawls'], ', ');
                $howMuchToSpendOnBeltsScarvesShawls = $arr_ankets[$key]['howMuchToSpendOnBeltsScarvesShawls'];

            }

            //Стиль в выходные, styleOnWeekend 1
            if(empty($anketa['styleOnWeekend'])) {
                if(isset($styleOnWeekend))
                    $arr_ankets[$key]['styleOnWeekend'] = $styleOnWeekend;
            } else {
                if(is_array($anketa['styleOnWeekend'])) {
                    $arr_ankets[$key]['styleOnWeekend']= '';
                    for($i = 0; $i < count($anketa['styleOnWeekend']); $i++ ){
                        $arr_ankets[$key]['styleOnWeekend'].= $anketa['styleOnWeekend'][$i]['text']. ', ';
                    }
                    $arr_ankets[$key]['styleOnWeekend'] = trim($arr_ankets[$key]['styleOnWeekend'], ', ');
                    $arr_ankets[$key]['styleOnWeekend'] = self::formatAnswer($arr_ankets[$key]['styleOnWeekend']);
                    $styleOnWeekend = $arr_ankets[$key]['styleOnWeekend'];
                } else {
                    $styleOnWeekend = $arr_ankets[$key]['styleOnWeekend'] = self::formatAnswer($anketa['styleOnWeekend']);
                }
            }

            // на работе styleOnWork 2
            if(empty($anketa['styleOnWork'])) {
                if(isset($styleOnWork))
                    $arr_ankets[$key]['styleOnWork'] = $styleOnWork;
            } else {
                if(is_array($anketa['styleOnWork'])) {
                    $arr_ankets[$key]['styleOnWork']= '';
                    for($i = 0; $i < count($anketa['styleOnWork']); $i++ ){
                        $arr_ankets[$key]['styleOnWork'].= $anketa['styleOnWork'][$i]['text']. ', ';
                    }
                    $arr_ankets[$key]['styleOnWork'] = trim($arr_ankets[$key]['styleOnWork'], ', ');
                    $arr_ankets[$key]['styleOnWork'] = self::formatAnswer($arr_ankets[$key]['styleOnWork']);
                    $styleOnWork = $arr_ankets[$key]['styleOnWork'];
                } else {
                    $styleOnWork = $arr_ankets[$key]['styleOnWork'] = self::formatAnswer($anketa['styleOnWork']);
                }
            }

            //Цветовая гамма choosingPalletes25 25
            if(empty($anketa['choosingPalletes25'])) {
                if(isset($choosingPalletes25))
                    $arr_ankets[$key]['choosingPalletes25'] = $choosingPalletes25;
            } else {
                if(is_array($anketa['choosingPalletes25'])) {
                    $arr_ankets[$key]['choosingPalletes25']= '';
                    for($i = 0; $i < count($anketa['choosingPalletes25']); $i++ ){
                        $arr_ankets[$key]['choosingPalletes25'].= $anketa['choosingPalletes25'][$i]['text']. ', ';
                    }
                    $arr_ankets[$key]['choosingPalletes25'] = trim($arr_ankets[$key]['choosingPalletes25'], ', ');
                    $choosingPalletes25 = $arr_ankets[$key]['choosingPalletes25'];
                } else {
                    $choosingPalletes25 = $arr_ankets[$key]['choosingPalletes25'] = $anketa['choosingPalletes25'];
                }
            }

            //Стиль джинс modelsJeans 37
            if(empty($anketa['modelsJeans'])) {
                if(isset($modelsJeans))
                    $arr_ankets[$key]['modelsJeans'] = $modelsJeans;
            } else {
                if(is_array($anketa['modelsJeans'])) {
                    $arr_ankets[$key]['modelsJeans']= '';
                    for($i = 0; $i < count($anketa['modelsJeans']); $i++ ){
                        $arr_ankets[$key]['modelsJeans'].= $anketa['modelsJeans'][$i]['text']. ', ';
                    }
                    $arr_ankets[$key]['modelsJeans'] = trim($arr_ankets[$key]['modelsJeans'], ', ');
                    $modelsJeans = $arr_ankets[$key]['modelsJeans'];
                } else {
                    $modelsJeans = $arr_ankets[$key]['modelsJeans'] = $anketa['modelsJeans'];
                }
            }

            //посадка trousersFit 38
            if(empty($anketa['trousersFit'])) {
                if(isset($trousersFit))
                    $arr_ankets[$key]['trousersFit'] = $trousersFit;
            } else {
                if(is_array($anketa['trousersFit'])) {
                    $arr_ankets[$key]['trousersFit']= '';
                    for($i = 0; $i < count($anketa['trousersFit']); $i++ ){
                        $arr_ankets[$key]['trousersFit'].= $anketa['trousersFit'][$i]['text']. ', ';
                    }
                    $arr_ankets[$key]['trousersFit'] = trim($arr_ankets[$key]['trousersFit'], ', ');
                    $trousersFit = $arr_ankets[$key]['trousersFit'];
                } else {
                    $trousersFit = $arr_ankets[$key]['trousersFit'] = $anketa['trousersFit'];
                }
            }

            //длина trouserslength 39
            if(empty($anketa['trouserslength'])) {
                if(isset($trouserslength))
                    $arr_ankets[$key]['trouserslength'] = $trouserslength;
            } else {
                if(is_array($anketa['trouserslength'])) {
                    $arr_ankets[$key]['trouserslength']= '';
                    for($i = 0; $i < count($anketa['trouserslength']); $i++ ){
                        $arr_ankets[$key]['trouserslength'].= $anketa['trouserslength'][$i]['text']. ', ';
                    }
                    $arr_ankets[$key]['trouserslength'] = trim($arr_ankets[$key]['trouserslength'], ', ');
                    $trouserslength = $arr_ankets[$key]['trouserslength'];
                } else {
                    $trouserslength = $arr_ankets[$key]['trouserslength'] = $anketa['trouserslength'];
                }
            }

            //Юбки, платья dressesType 40
            if(empty($anketa['dressesType'])) {
                if(isset($dressesType))
                    $arr_ankets[$key]['dressesType'] = $dressesType;
            } else {
                if(is_array($anketa['dressesType'])) {
                    $arr_ankets[$key]['dressesType']= '';
                    for($i = 0; $i < count($anketa['dressesType']); $i++ ){
                        $arr_ankets[$key]['dressesType'].= $anketa['dressesType'][$i]['text']. ', ';
                    }
                    $arr_ankets[$key]['dressesType'] = trim($arr_ankets[$key]['dressesType'], ', ');
                    $dressesType = $arr_ankets[$key]['dressesType'];
                } else {
                    $dressesType = $arr_ankets[$key]['dressesType'] = $anketa['dressesType'];
                }
            }

            //Бижутерия earsPierced 44
            if(empty($anketa['earsPierced'])) {
                if(isset($earsPierced))
                    $arr_ankets[$key]['earsPierced'] = $earsPierced;
            } else {
                if(is_array($anketa['earsPierced'])) {
                    $arr_ankets[$key]['earsPierced']= '';
                    for($i = 0; $i < count($anketa['earsPierced']); $i++ ){
                        if($anketa['earsPierced'][$i]['text'] == 'Да') $anketa['earsPierced'][$i]['text']= "Проколоты";
                        if($anketa['earsPierced'][$i]['text'] == 'Нет') $anketa['earsPierced'][$i]['text']= "Не проколоты";
                        $arr_ankets[$key]['earsPierced'].= $anketa['earsPierced'][$i]['text']. ', ';
                    }
                    $arr_ankets[$key]['earsPierced'] = trim($arr_ankets[$key]['earsPierced'], ', ');
                    $earsPierced = $arr_ankets[$key]['earsPierced'];
                } else {
                    if($anketa['earsPierced'] == 'Да') $anketa['earsPierced'] = "Проколоты";
                    if($anketa['earsPierced'] == 'Нет') $anketa['earsPierced'] = "Не проколоты";
                    $earsPierced = $arr_ankets[$key]['earsPierced'] = $anketa['earsPierced'];
                }
            }

            //Бижутерия готова bijouterie 46
            if(empty($anketa['bijouterie'])) {
                if(isset($bijouterie))
                    $arr_ankets[$key]['bijouterie'] = $bijouterie;
            } else {
                if(is_array($anketa['bijouterie'])) {
                    $arr_ankets[$key]['bijouterie']= '';
                    for($i = 0; $i < count($anketa['bijouterie']); $i++ ){
                        $arr_ankets[$key]['bijouterie'].= $anketa['bijouterie'][$i]['text']. ', ';
                    }
                    $arr_ankets[$key]['bijouterie'] = trim($arr_ankets[$key]['bijouterie'], ', ');
                    $bijouterie = $arr_ankets[$key]['bijouterie'];
                } else {
                    $bijouterie = $arr_ankets[$key]['bijouterie'] = $anketa['bijouterie'];
                }
            }

            //Бижутерия предпочитает jewelry 45
            if(empty($anketa['jewelry'])) {
                if(isset($jewelry))
                    $arr_ankets[$key]['jewelry'] = $jewelry;
            } else {
                if(is_array($anketa['jewelry'])) {
                    $arr_ankets[$key]['jewelry']= '';
                    for($i = 0; $i < count($anketa['jewelry']); $i++ ){
                        $arr_ankets[$key]['jewelry'].= $anketa['jewelry'][$i]['text']. ', ';
                    }
                    $arr_ankets[$key]['jewelry'] = trim($arr_ankets[$key]['jewelry'], ', ');
                    $jewelry = $arr_ankets[$key]['jewelry'];
                } else {
                    $jewelry = $arr_ankets[$key]['jewelry'] = $anketa['jewelry'];
                }
            }

            $number++;
            if($number >= 5) break;
        }

        //блок образы
        $arr_lifestyles = [];
        foreach($anketas as $key => $anketa) {

            foreach( [4, '4_1' , 5, '5_1', 6, '6_1', 7, '7_1', '8_1', 8, 9, 10, 11, 12, 13, 16, '16_1', 17, '17_1', 18, '18_1', 19, '19_1', 20, '20_1', 21, 22, 23, 24] as $key_lifestyle ) {
               if(!empty($anketa['choosingStyle'.$key_lifestyle])) {
                   $arr_lifestyles['choosingStyle'.$key_lifestyle] = $anketa['choosingStyle'.$key_lifestyle];
               }
            }

            if($arr_lifestyles != []) break;
        }

        //блок цвета и принты
        $arr_colors = [];
        foreach($anketas as $key => $anketa) {

            if(empty($arr_colors['choosingPalletes25'])) {
                if(!empty($anketa['choosingPalletes25'])) {
                    $arr_colors['choosingPalletes25'] = $anketa['choosingPalletes25'];
                }
            }

            if(empty($arr_colors['printsDislike'])) {
                if(!empty($anketa['printsDislike'])) {
                    $arr_colors['printsDislike'] = $anketa['printsDislike'];
                }
            }

            if(empty($arr_colors['noColor'])) {
                if(!empty($anketa['noColor'])) {
                    $arr_colors['noColor'] = $anketa['noColor'];
                }
            }
        }

        //блок фото
        $arr_fotoes = [];
        foreach(array_reverse($anketas) as $key => $anketa) {
            if(isset($anketa['clientPhotos'])) {
                $arr_fotoes[] = $this->createFotoPaths($key, $anketa['clientPhotos']);
            }
        }

        //блок история
        $arr_history = [];
        foreach ($anketas as $key => $anketa) {
            $arr_keys[] =  $key;
        }
        $leads_history = Lead::whereIn('anketa_uuid', $arr_keys)->orderBy('created_at', 'desc')->get();

        foreach ($leads_history as $lead) {
            if($stylist = AdminUser::find($lead->stylist_id))
                $stylist_name = $stylist->name;

            $oc_url = '';
            if($feedback_general = FeedbackgeneralQuize::where('lead_id', $lead->uuid)->latest()->first()) {
                $oc_url = route('feedback.show', $feedback_general->id);
            }

            if($note = Note::where('order_id', $lead->amo_lead_id)->orderBy('id', 'desc')->first()) {
                $note_id = $note->id;
                $note_url = route('notes.index', $note_id);
            }

            if($lead->questionnaire) {

                if($lead->questionnaire->source == 'reanketa') {
                    $reanketa_url = route('reanketa.show', $lead->questionnaire->uuid);
                }

                $arr_history[$lead->questionnaire->uuid] = [
                    'anketa_date' => Carbon::parse($lead->questionnaire->created_at)->format('d.m.Y'),
                    'lead_date' => Carbon::parse($lead->created_at)->format('d.m.Y'),
                    'amo' => $lead->amo_lead_id,
                    'anketa_url' => route('anketa.show', $lead->questionnaire->uuid),
                    'capsula_num' => $lead->client_num ?? '?',
                    'os_url' => $oc_url ?? '',
                    'stylist' => $stylist_name ?? '',
                    'note_url' => $note_url ?? ''
                ];
                continue;
            }

        }

        if( isset($old_anketas)) {

            foreach ($old_anketas as $old_anketa) {
                $arr_history[$old_anketa->uuid] = [
                    'anketa_date' => Carbon::parse($old_anketa->created_at)->format('d.m.Y'),
                    'lead_date' => '',
                    'amo' => $old_anketa->amo_id,
                    'anketa_url' => route('anketa.show', $old_anketa->uuid),
                    'capsula_num' => '?',
                    'os_url' => $oc_url ?? '',
                    'stylist' => '',
                    'note_url' => ''
                ];
            }
        }

                return view('admin.reanketa.show', [
            'anketas' => $anketas,
            'strogie_net' => $arr_strogie_net,
            'arr_params' => $arr_params,
            'arr_ankets' => array_reverse($arr_ankets),
            'arr_lifestyles' => $arr_lifestyles,
            'arr_colors' => $arr_colors,
            'arr_fotoes' => $arr_fotoes ?? [],
            'arr_history' => $arr_history,
            'client' => $reanketa->client,
            'tab_reference' => TabReferenceController::show($uuid),
            'amo_ids' => $leads ?? '',
            //'anketaComments' => $anketaComments,
            'title' => 'Просмотр анкеты',
            'uuid' => $uuid
        ]);

    }

    public function edit($uuid)
    {

        $stylist_id = auth()->guard('admin')->user()->id;

        if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            $check = Lead:: where('stylist_id', $stylist_id)->where('anketa_uuid', $uuid)->count();
            if ($check === 0) return redirect()->route('admin.anketa.list.fill');
        }


        $anketaData = $anketaComments = null;
        $anketaData = Questionnaire::find($uuid);
        if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            $anketaComments = AnketaStylistComment::where('anketa_uuid', $uuid)->where('stylist_id', $stylist_id)->first();
        }

        $stylistsData = $stylistSelected = null;
        if (auth()->guard('admin')->user()->can('manage-anketa')) {
            $stylistsData = AdminUser::whereHas('roles', function ($query) {
                $query->where('id', '3');
            })->get();
            $lead = Lead::where('anketa_uuid', $uuid)->first();
            if ($lead) {
                $stylistSelected = $lead->stylist_id;
            }
        }

        return view('admin.anketa.edit', [
            'anketa_uuid' => $uuid,
            'anketaData' => $anketaData,
            'anketaComments' => $anketaComments,
            'stylistsData' => $stylistsData,
            'stylistSelected' => $stylistSelected,
            'title' => 'Редактирование анкеты'
        ]);
    }

    public function update(Request $request, $anketa_uuid)
    {

        if (!auth()->guard('admin')->user()->can('manage-anketa') && !auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            return redirect()->route('admin.main.index');
        }

        if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            $check = Lead:: where('stylist_id', auth()->guard('admin')->user()->id)->where('anketa_uuid', $anketa_uuid)->count();
            if ($check === 0) return redirect()->route('admin.anketa.list.fill');
        }

        if (auth()->guard('admin')->user()->can('manage-anketa')) {
            /*$validated = $request->validate([
                'stylists' => 'required'
            ]);*/

            $success = null;

            $lead = Lead::where('anketa_uuid', $anketa_uuid)->first();
            if ($lead) {
                $lead->stylist_id = $request->input('stylists');
                $lead->save();
                toastr()->success('Стилист прикреплен к анкете');
            } else toastr()->error('Стилист не прикреплен. Сделка не найдена');

            $anketa = Reanketa::find($anketa_uuid);
            if ($anketa) {
                $anketa->manager_comment = $request->input('manager_comment');
                $anketa->save();
                toastr()->success('Комментарий сохранен');
            } else toastr()->error('Комментарий не сохранен');

            return redirect()->route('anketa.edit', $anketa_uuid);

        }

        if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {

            $validated = $request->validate([
                'content' => 'required'
            ]);

            $stylist_id = auth()->guard('admin')->user()->id;

            $comment = AnketaStylistComment::updateOrCreate([
                'anketa_uuid' => $anketa_uuid,
                'stylist_id' => $stylist_id
            ],

                [
                    'content' => $request->content
                ]);

            toastr()->success('Данные успешно сохранены');

            return redirect()->route('anketa.edit', $anketa_uuid);

        }
    }

    public function destroy($anketa_uuid)
    {
        if (!auth()->guard('admin')->user()->can('manage-anketa') && !auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            return redirect()->route('admin.main.index');
        }

        if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            $check = Lead:: where('stylist_id', auth()->guard('admin')->user()->id)->where('anketa_uuid', $anketa_uuid)->count();
            if ($check === 0) return redirect()->route('admin.anketa.list.fill');
        }

        $res = Reanketa::where('uuid', $anketa_uuid)->delete();

        if ($res) toastr()->success('Запись успешно удалена');
        else toastr()->error('Ошибка удаления');

        return redirect()->route('admin.anketa.list.fill');
    }

}

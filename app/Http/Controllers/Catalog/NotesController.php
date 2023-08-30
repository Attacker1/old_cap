<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Classes\Common;
use App\Http\Classes\Message;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Admin\Role;
use App\Http\Models\Catalog\Brand;
use App\Http\Models\Catalog\CategoriesTranslator;
use App\Http\Models\Catalog\Category;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Catalog\NotesAdvice;
use App\Http\Models\Catalog\Product;
use App\Http\Models\Common\ClientSettings;
use App\Http\Models\Common\Payments;
use App\Http\Requests\Catalog\BrandFormRequest;
use App\User;
use Bnb\Laravel\Attachments\Attachment;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\Notifiable;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;


/**
 * Контролле разписок стилиста
 * Class NotesController
 * @package App\Http\Controllers\Catalog
 */
class NotesController extends Controller
{
    use Notifiable;

    /**
     * @var array
     */
    protected $state = [
        0 => '<button class="btn btn-sm btn-outline-success">Новая</button>',
        1 => '<button class="btn btn-sm btn-outline-danger">Закрыта</button>',
        2 => 'Отправлена'
    ];

    private function get_discounts()
    {
        if($client_settings = ClientSettings::where('name', 'discounts')->first()) {
            if(!empty($client_settings->params)) return $client_settings->params;
        }
        return false;
    }

    /**
     * Записка стилиста
     *
     * @param $note_id
     * @return View
     * @throws Exception
     */
    public function index($note_id = false)
    {

        if (empty($note_id))
            $item = Note::with(['products', 'attachments', 'advice', 'customAdvice'])->where('state', 0)
                ->firstOrCreate(
                    ['user_id' => auth()->guard('admin')->user()->id
                    ],
                    [
                        'user_id' => auth()->guard('admin')->user()->id,
                    ]
                );
        else {
            try {

                $item = Note::with(['products', 'advice', 'leads'])
                    ->where('id', $note_id);

                if (auth()->guard('admin')->user()->hasRole('stylist'))
                    $item = $item->where('user_id', auth()->guard('admin')->user()->id);

                $item = $item->firstOrFail();


            } catch (Exception $e) {

                toastr()->error('Ошибка доступа или запись отсутствует в БД');
                return redirect()->route('notes.list');
            }
        }

        return view('admin.notes.index', [
            'title' => 'Записка:',
            'data' => $item,
            'products' => $item->products()->get()->pluck('id')->toArray() ?? false,
            'count' => $item->products()->get()->count() > 0 ? true : false,
            'price' => collect($item->products()->orderBy('order', 'asc')->get()),
            'advice' => $item->advice()->first(),
            'customAdvice' => $item->customAdvice()->first(),
            'promocode' => self::getPromocode($item) ?? false,
            'paid' => self::paymentForStylist($item),
            'client_discounts' => self::get_discounts()
        ]);
    }

    /**
     * Добавление продукции в записку стилиста
     *
     * @param $product_id
     * @return View
     */
    public function add($product_id)
    {

        $item = Note::where('state', 0)->firstOrCreate(
            ['user_id' => auth()->guard('admin')->user()->id
            ],
            [
                'user_id' => auth()->guard('admin')->user()->id,
            ]
        );

        $item->products()->syncWithoutDetaching($product_id);

        return response()->json([
            'result' => true
        ], 200);
    }

    /**
     * Удаление из записки стилиста
     *
     * @param Note $item
     * @param $product_id
     * @return View
     */
    public function remove(Note $item, $product_id)
    {

        $item->products()->detach($product_id);

        return redirect()->back();

        return response()->json([
            'result' => true
        ], 200);
    }

    /**
     * Сохранение информации
     *
     * @param Note $item
     * @return View
     */
    public function save(Note $item)
    {

        $item->comment = trim(\request()->input('comment'));
        $item->content = trim(\request()->input('content'));
        $item->content_advice = trim(\request()->input('content_advice'));
        $item->save();

        toastr()->success('Информация сохранена');
        return redirect()->back();

    }

    /**
     * Сортировка товаров
     *
     * @param Note $item
     * @return View
     */
    public function sort(Note $item)
    {
        foreach (\request()->input('order') as $k => $v) {
            $prod_id = (int)str_replace('order-', '', $v);
            $item->products()->syncWithoutDetaching([
                $prod_id => ['order' => $k],
            ]);
        }

        return response()->json([
            'result' => true,
            'data' => \request()->all()
        ], 200);
    }

    /**
     * Добавление фото по URL
     *
     * @param Note $item
     * @return View
     * @throws ValidationException
     */
    public function attach(Note $item)
    {

        $this->validate(request(), [
            'img_url' => 'required | url',
        ]);

        try {

            $flag = true;
            $try = 1;
            while ($flag && $try <= 3):
                try {

                    // Попытка в пропорции сжать, уже не нужно
                    $width = 600;
//                    $height = 800;
                    $img = Image::make(\request()->input('img_url'));
                    $img->backup();
                    $img->resize($width, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                        ->encode('jpg', 95);
//                    /*if ($img->width() == 600 && $img->height() >= 800)
//                        $img->resizeCanvas(600, 800, 'center', false, 'ffffff')
//                            ->encode('jpg',95);
//                    else{
//                        $img->reset();
//                        $img->resize(false, $height, function ($constraint) {
//                            $constraint->aspectRatio();
//                        });
//                        $img->resizeCanvas(600, 800, 'center', false, 'ffffff')
//                            ->encode('jpg',95);
//                    }*/

                    //$img = Image::make(\request()->input('img_url'));
                    /*$img->resizeCanvas(600, 800, 'center', false, 'ffffff')
                        ->encode('jpg',95);*/
                    $flag = false;
                    $path = 'images/' . uuid_v4_short() . '.jpg';
                    $file = Storage::disk('public')->put($path, $img);
                    $item->attach(Storage::disk('public')->path('/' . $path));

                } catch (\Exception $e) {
                    toastr()->error('Не получается создать изображение (');
                    return redirect()->back();
                }
                $try++;
            endwhile;

        } catch (Exception $e) {
            dd($e);
            toastr()->error('Не получается добавить фото');
            return redirect()->back();
        }

        toastr()->error('Изображение добавлено');
        return redirect()->back();

    }

    /**
     * Создание миниатюры
     * @param Attachment $attach
     * @return bool
     */
    protected function thumb(Attachment $attach)
    {

        if (isset($attach)) {
            // TODO: RESIZE and update в сохранение продукта перенести
            try {
                ini_set('memory_limit', '256M');
                $path = 'catalog/url_thumbs/' . uuid_v4_short() . '.jpg';
                Image::make(Storage::disk('local')->get($attach->filepath))->resize((int)config('config.PRODUCT_THUMB_SIZE'), null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path);
            } catch (Exception $e) {
                dd($e);
            }
            $attach->preview_url = $path;
            $attach->save();
        }

        return true;
    }

    /**
     * Удаление фото из URL листа
     *
     * @param $attach_key
     * @return View
     */
    public function detach($attach_key)
    {
        Attachment::where('key', $attach_key)->delete();
        return redirect()->back();

    }

    /**
     * Сохранить примеры сочетаний
     *
     * @param Note $item
     * @return View
     */
    public function advice(Note $item)
    {

        $collection = \request()->input('advices');
        foreach ($collection as $k => $v) {
            foreach ($v as $k2 => $v2) {

                if ($k2 == 'values' && !empty($v2)) {
                    $item->products()->syncWithoutDetaching([
                        $v['id'] => ['advice' => json_encode($v2)],
                    ]);
                }
            }
        }

        return response()->json([
            'result' => true,
        ], 200);

    }

    /**
     * Очистить примеры сочетаний
     *
     * @param Note $note
     * @return View
     * @throws ValidationException
     */
    public function unset(Note $note)
    {

        $this->validate(request(), [
            'product_id' => 'required | integer',
            'advice_ids' => 'nullable | array | min:1',
        ]);

        $advices = !empty(\request()->input('advice_ids')) ? json_encode(\request()->input('advice_ids')) : null;

        foreach ($note->products()->get() as $item) {
            if ($item->id == \request()->input('product_id'))
                $note->products()->syncWithoutDetaching([
                    $item->id => ['advice' => $advices],
                ]);
        }

        return response()->json([
            'result' => true,
        ], 200);

    }

    /**
     * @param $item
     * @return bool
     */
    protected function validation($item)
    {

        $request = new Request([
            'item' => (array)$item,
        ]);

        try {
            $this->validate($request, [
                'item.id' => 'required | string | min: 30 | max:36',
                'item.type' => 'nullable | string',
                'item.price' => 'required | numeric',
                'item.brand' => 'nullable | string',
                'item.image' => 'required | string',
            ]);
        } catch (Exception $e) {
            return false;
        }

        return true;

    }

    /**
     * @param $note_id
     * @return bool|RedirectResponse
     * @throws ValidationException
     */
    public function import($note_id)
    {

        $this->validate(request(), [
            'order_id' => 'required | integer',
        ]);

        if (!$api = Common::importByOrderId(\request()->input('order_id')))
            return false;

        try {

            $note = Note::where('id', $note_id)->firstOrFail();
            $note->products()->detach();

            $note->order_id = \request()->input('order_id');
            $note->paid = !empty($api->paid) ? $api->paid : 0;
            $note->save();

            foreach ($api->items as $item) {
                if (!self::validation($item)) {
                    return false;
                }

                if (empty($item->type))
                    $type = ' ';

                elseif (empty($type = CategoriesTranslator::translate($item->type)))
                    $type = ' ';

                $cat_id = Common::getCategoryId($type);
                $brand_id = Common::getBrandId($item->brand);
                $image_uri = Common::getImage($item->image);

                if (!$product = Product::where('external_id', $item->id)->first()) {

                    $product = Product::create([
                        'name' => $type ?? '',
                        'amo_name' => @$item->name ?? '-',
                        'category_id' => $cat_id,
                        'brand_id' => $brand_id ?? null,
                        'price' => $item->price ?? 0,
                        'user_id' => auth()->guard('admin')->user()->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'external_id' => $item->id,
                        'size' => @$item->size ?? null,
                    ]);

                } else {
                    $product->name = @$type ?? '';
                    $product->amo_name = @$item->name ?? '-';
                    $product->price = @$item->price ?? 0;
                    $product->size = @$item->size ?? null;
                    $product->updated_at = now();
                    $product->save();
                }

                $product->attachments()->get();

                if (empty($product->attachments()->where('main', 1)->count()) && $image_uri)
                    $product->attach(Storage::disk('public')->path('/' . $image_uri), ['main' => true]);

//                $product->attachments()->get();
//                if (empty($product->attachments()->where('main', 1)->count()) && $image_uri)
//                    $product->attach(Storage::disk('public')->path('/' . $image_uri), ['main' => true]);
//                else{
//                    $product->attachments()->where('main', 1)->delete();
//                    $product->attach(Storage::disk('public')->path('/' . $image_uri), ['main' => true]);
//                }

                $note->products()->syncWithoutDetaching($product->id);
            }

        } catch (Exception $e) {
            Log::channel('moysklad')->error($e);
        }

        return true;

    }

    /** Обновление сочетаний товаров между собой
     * @param Note $item
     * @return JsonResponse
     */
    public function productAdvice(Note $item)
    {
        $item->updateAdvice(\request()->input('advices'));

        return response()->json([
            'result' => \request()->all(),
            'data' => $item->advice()->get(),
        ], 200);

    }

    /** Обновление сочетаний товаров между собой
     * @param Note $item
     * @return JsonResponse
     */
    public function print(Note $item)
    {

        try {
            // TODO: Заплатка
            $prod = $item->products()->get();

            $attachments = Attachment::whereIn('model_id', $prod->pluck('id')->toArray())->get()->pluck('key', 'model_id')->toArray();
            $attachments2 = Attachment::whereIn('model_id', $prod->pluck('id')->toArray())->get()->pluck('filename', 'key')->toArray();

        } catch (\Exception $e) {
            toastr()->error('Ошибка доступа или запись отсутствует в БД');
            return redirect()->route('notes.list');
        }

        return view('admin.notes.print', [
            'title' => 'Записка:',
            'data' => $item,
            'products' => @$prod->pluck('id')->toArray() ?? false,
            'count' => @$item->products()->get()->count() > 0 ? true : false,
            'price' => collect($item->products()->orderBy('order', 'asc')->get()),
            'advice' => self::getAdvices($item) ?? false,
            'customAdvice' => $item->customAdvice()->first(),
            'brands' => Brand::array(),
            'prods' => @$prod->pluck('name', 'id')->toArray(),
            'attaches' => $attachments,
            'attachments2' => $attachments2 ?? false,
            'attaches_flip' => array_flip($attachments),
            'promocode' => self::getPromocode($item) ?? false,
            'paid' => self::paymentForStylist($item),
            'client_discounts' => self::get_discounts()
        ]);

    }

    /**
     * Получение имен и фотографий по сочетаниям
     * @param $item
     * @return bool
     */
    protected function getAdvices($item)
    {

        if ($advice = $item->advice()->where('note_id', $item->id)->first()) {

            try {
                foreach ($advice->value as $k => $v) {
                    foreach ($v as $k2 => $v2) {
                        if ($attachment = Attachment::where('key', $v2)->first()) {
                            if ($product = Product::with('attachments')->whereHas('attachments', function ($q) use ($v2) {
                                $q->where('key', $v2);
                            })->first()) {
                                $advices[$k][] = ["name" => $product->name ?? false, "url" => $attachment->url ?? false];
                            } else {
                                $advices[$k][] = ["name" => false, "url" => $attachment->url ?? false];
                            }
                        }
                    }
                }
                return $advices ?? false;
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Список Записок
     *
     * @return View
     * @throws Exception
     */
    public function list()
    {


        if (request()->ajax()) {

            if (auth()->guard('admin')->user()->hasRole('stylist'))
                $notes = Note::with(['products', 'users'])->where('user_id', auth()->guard('admin')->user()->id);
            else {
                $notes = Note::with(['products', 'users']);
            }

            // --- Фильтры
            if (!empty(\request()->input('date')))
                $notes->whereBetween('created_at', [
                    date("Y-m-d 00:00:00", strtotime(\request()->input('date'))),
                    date("Y-m-d 23:59:59", strtotime(\request()->input('date')))
                ]);

            if (!empty(\request()->input('order_id')))
                $notes->where('order_id', trim(\request()->input('order_id')));

            if (!empty(\request()->input('stylist_id')))
                $notes->where('user_id', trim(\request()->input('stylist_id')));
            // ----------------------------------------

            $dt = DataTables::of($notes);

            $dt->addColumn('order_id', function ($data) {
                return @$data->order_id ?? '—';
            });

            $dt->editColumn('created_at', function ($data) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d.m.Y');
            });

            $dt->addColumn('role', function ($data) {
                $roles = $data->users->roles()->get()->pluck('name');
                $html = '';
                foreach ($roles as $k => $v) {
                    $html .= '<button class="btn btn-outline-info btn-sm px-2 ml-1">' . $v . '</button >';
                }
                return $html;
            });

            $dt->addColumn('user', function ($data) {
                return @$data->users->name ?? '—';
            });

            $dt->addColumn('state', function ($data) {
                return $this->state[$data->state];
            });

            $dt->addColumn('action', function ($data) {
                $buttons = '';

                if (auth()->guard('admin')->user()->hasPermission('print-notes'))
                    $buttons .= '<a href = "' . route('notes.product.print', $data->id) . '" title = "Печать ' . $data->name . '" class="ml-1 print-note_btn_ " target="_blank" >
                        <i class="fas fa-print text-primary"></i></a >';

                if (auth()->guard('admin')->user()->hasPermission('edit-notes'))
                    $buttons .= '<a href = "' . route('notes.index', $data->id) . '" title = "Редактирование ' . $data->name . '" ><i class="ml-3 fa far fa-edit text-primary"></i></a >';

                if (auth()->guard('admin')->user()->hasPermission('destroy-notes'))
                    $buttons .= '<a href = "' . route('notes.delete', $data->id) . '" class="ml-5 confirm-delete " onclick="return confirm(\'Удалить записку?\')" title = "Удаление ' . $data->name . '" ><i class="fa far fa-trash-alt text-danger"></i></a>';

                return $buttons;

            });

            return $dt->make(true);
        }

        return view('admin.notes.list', [
            'title' => 'Записки стилиста',
            'stylists' => AdminUser::byRole('stylist')->pluck('name', 'id')
        ]);
    }

    /**
     * Создание новой записки
     * @return View
     * @throws ValidationException
     */
    public function create()
    {

        if (\request()->post()) {

            if (!Common::checkAmoId(\request()->input('order_id'))) {
                toastr()->error('Нет клиента или сделки!');
                return redirect()->route('notes.list.create');
            }

            $user_id = auth()->guard('admin')->user()->id;
            Note::where('state', 0)->where('user_id', $user_id)->update(['state' => 1]);
            $note = new Note();
            $note->user_id = $user_id;
            $note->save();

            if (!self::import($note->id)) {
                Note::find($note->id)->delete();
                toastr()->error('Ошибка создания записки из импорта');
            } else
                toastr()->success('Импорт прошел успешно!');

            return redirect()->route('notes.index');
        }

        return view('admin.notes.add', [
            'title' => 'Добавление записки',
        ]);
    }

    /**
     * Удаление записки стилиста
     * @param Note $item
     * @return RedirectResponse
     * @throws Exception
     */
    public function delete(Note $item)
    {

        $item->delete();
        toastr()->error('Записка удалена');
        return redirect()->route('notes.list');
    }

    /**
     * Перевод записки в статус «Закрыто»
     * @param Note $item
     * @return RedirectResponse
     */
    public function close(Note $item)
    {

        $item->update(['state' => 1]);
        toastr()->info('Записка переведена в статус закрыто');
        return redirect()->route('notes.list');
    }

    /** Обновление сочетаний товаров между собой
     * @param Note $item
     * @return JsonResponse
     */
    public function customAdvice(Note $item)
    {

        $item->updateCustomAdvice(\request()->input('advices'));

        return response()->json([
            'result' => \request()->all(),
            'data' => $item->advice()->get(),
        ], 200);

    }

    public function replaceImage(Request $request)
    {

        $newImageUrl = '';

        try {
            foreach ($request->slim as $slim) {
                $slim = json_decode($slim, true);
                $note = Note::find($slim['meta']['noteId']);

                if($newAttachment = $note->attach($request->file('slim_output_0'))){

                    $attachmentByKey = $note->attachment($slim['meta']['key']);
                    $attachmentByKey->delete();

                    $newAttachmentByKey = $note->attachment($newAttachment->key);
                    $newImageUrl = $newAttachmentByKey->url;

                }
            }
            return $newImageUrl;

        } catch (Exception $e) {
            Log::info($e, [self::class, 'replaceImage']);
            return json_encode(['status' => 'failure', 'message' => 'Ошибка сохранения фото']);
        }

    }

    private function getPromocode($note){

        try {
            if (!empty($lead = @$note->leads()->first())) {
                if ($client = $lead->clients()->first())
                    if ($bonus = $client->bonuses()->first())
                        return $promocode = $bonus->promocode;
            }
                return $note->order_id;
        }
        catch (\Exception $e){
            // Куда сообщить что нет промокода?
            return $note->order_id;
        }

    }

    private function paymentForStylist(Note $item){

        $paid = 0;
            if($lead =  $item->leads()->first())
                if ($payment = $lead->payments()->where("pay_for", "stylist")->first())
                    $paid =  number_format(round($payment->amount), 0, '.', ' ');
        return $paid;
    }

}

<?php


namespace App\Http\Filters\Leads;


use App\Http\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ListingFilter extends Filter
{
    public $booleanOptions = [2 => 'нет', 1 => 'есть'];
    public $secondBooleanOptions = [2 => 'нет', 1 => 'да'];


    public function stylistSelected(){
        $user = auth()->guard('admin')->user();
        return $user->hasRole('stylist')
            ?  ['disabled' => auth()->guard('admin')->user()->hasRole('stylist')]
            :  [];
    }

    public function filtersData()
    {
        return [
            'bulkActions' => [
                'name' => 'bulk',
                'label' => 'Групповые операции',
                'placeholder' => 'Групповые операции'],

            'byDate' => [
                'name' => 'byDate',
                'label' => 'Дата',
                'placeholder' => 'дата'],

            'clientLike' => [
                'name' => 'clientLike',
                'label' => 'Клиент',
                'placeholder' => 'фильтр'],

            'amoLike' => [
                'name' => 'amoLike',
                'label' => 'AMO ID',
                'placeholder' => 'фильтр'],

            'isPayment' => [
                'nameSelect' => 'isPayment',
                'label' => 'Оплата',
                'placeholderSelect' => '---', 'options' => $this->booleanOptions],

            'paymentSearch' => [
                'nameText' => 'paymentSearch',
                'placeholderText' => 'точная сумма',
//                'order' => 'payments.amount'
            ],

            'stylist' => [
                'name' => 'stylist',
                    'label' => 'Стилист',
                    'placeholder' => 'Выберите'] + $this->stylistSelected(),

            'deadline' => [
                'namePicker' => 'deadline',
                'name' => 'deadline',
                'label' => 'Дедлайн',
                'placeholderPicker' => 'дата'],

            'isDeadline' => [
                'nameSelect' => 'isDeadline',
                'placeholderSelect' => '---',
                'options' => $this->booleanOptions],

            'isCompletedDeadline' => [
                'name' => 'isCompletedDeadline',
                'label' => 'Был ли выполнен дедлайн',
                'placeholderSelect' => '---',
                'options' => $this->secondBooleanOptions],

            'states' => [
                'name' => 'states',
                'label' => 'Статус',
                'placeholder' => 'Выберите'],

            'tags' => [
                'name' => 'tags[]',
                'label' => 'Теги',
                'placeholder' => 'Выберите'],

            'deliveryAt' => [
                'namePicker' => 'deliveryAt',
                'label' => 'Доставка',
                'placeholderPicker' => 'дата',
                'order' => 'delivery_at',
            ],
            'isDeliveryAt' => [
                'nameSelect' => 'isDeliveryAt',
                'placeholderSelect' => '---',
                'options' => $this->booleanOptions],
        ];
    }

    public function clientLike()
    {
        return $this->builder->where(function ($query) {
            $query->whereHas('clients', function ($client) {
                $client->where('phone', 'like', '%' . request('clientLike') . '%')
                    ->orWhere('second_name', 'like', '%' . request('clientLike') . '%')
                    ->orWhere('phone', 'like', '%' . request('clientLike') . '%')
                    ->orWhere('email', 'like', '%' . request('clientLike') . '%');
            });
        });
    }

    public function amoLike()
    {
        return $this->builder->where(function ($query) {
            $query->where('amo_lead_id', 'like', '%' . request('amoLike') . '%');

        });
    }

    public function isPayment()
    {
        return $this->builder->where(function ($query) {
            if (request('isPayment') == 1) {
                $query->whereHas('payments', function ($payment) {
                    $payment->where('amount', '>=', 0);
                });
            } else {
                $query->doesntHave('payments');
            }
        });
    }

    public function paymentSearch()
    {
        return $this->builder->where(function ($query) {
            $query->whereHas('payments', function ($payment) {
                $payment->where('amount', '=', request('paymentSearch'));
            });
        });
    }

    public function stylist()
    {
        return $this->builder->where(function ($query) {
            if (request('stylist') == 'NO') {
                $query->doesntHave('stylists');
            } else {
                $query->whereHas('stylists', function ($stylists) {
                    $stylists->where('id', (int)request('stylist'));
                });
            }
        });
    }

    public function states()
    {
        return $this->builder->where(function ($query) {
            if (request('states') == 'NO') {
                $query->whereNull('state_id');
            } else {
                $query->whereHas('states', function ($stylists) {
                    $stylists->where('id', (int)request('states'));
                });
            }
        });
    }

    public function tags()
    {
        /*return $this->builder->where(function ($query) {
            $query->whereHas('tags', function ($tags) {
                $tags->whereIn('tags.id', request('tags'));
            });

        });*/
        return $this->builder->where(function ($query) {
            $query->whereHas('tags', function($q)
            {
                $q->whereIn('tags.id', request('tags'));
            }, '=', count(request('tags')));

        });
    }

    public function deadline()
    {
        return $this->builder->where(function ($query) {
            $query->whereBetween('deadline_at', [request('deadline').' 00:00:00',request('deadline').' 23:59:59']);
        });
    }

    public function byDate()
    {
        return $this->builder->where(function ($query) {
            $query->whereBetween('created_at', [request('byDate').' 00:00:00',request('byDate').' 23:59:59']);
        });
    }

    public function isDeadline()
    {
        if (request('isDeadline') == 1) { // yes
            return $this->builder->where(function ($query) {
                $query->whereNotNull('deadline_at');
            });
        }
        if(request('isDeadline') == 2) { // no
            return $this->builder->where(function ($query) {
                $query->whereNull('deadline_at');
            });
        }
    }

    public function isCompletedDeadline()
    {
        if (request('isCompletedDeadline') == 1) { // yes
            return $this->builder->where(function ($query) {
                $query->whereNotNull('deadline_at')
                    ->whereHas('revisionHistory', function ($history) use ($query) {
                        $history->where('key', '=', 'state_id')
                            ->where('new_value', '=', 6)
                            ->whereColumn('created_at', '<', 'deadline_at');
                    });
            });
        }
        if(request('isCompletedDeadline') == 2) { // no
            return $this->builder->where(function ($query) {
                $query
                    ->whereNotNull('deadline_at')
                    ->doesnthave('revisionHistory')
                    ->where('deadline_at', '<', date("Y-m-d H:i:s"))
                    ->orWhereNotNull('deadline_at')
                    ->whereHas('revisionHistory', function ($history) {
                        $history->where('key', '=', 'state_id')
                            ->where('new_value', '=', 6)
                            ->whereColumn('created_at', '>', 'deadline_at');
                    })
                    ->orWhereNotNull('deadline_at')
                    ->whereHas('states', function($state) {
                        $state->where('id', '<', '6');
                    })
                    ->where('deadline_at', '<', date("Y-m-d H:i:s"));
            });
        }
    }

    public function deliveryAt()
    {
        return $this->builder->where(function ($query) {
            $query->whereBetween('delivery_at', [request('deliveryAt').' 00:00:00',request('deliveryAt').' 23:59:59']);
        });
    }

    public function isDeliveryAt()
    {
        if (request('isDeliveryAt') == 1) { // yes
            return $this->builder->where(function ($query) {
                $query->whereNotNull('delivery_at');
            });
        }
        if(request('isDeadline') == 2) { // no
            return $this->builder->where(function ($query) {
                $query->whereNull('delivery_at');
            });
        }
    }

    public function orderBy()
    {
        return $this->builder->orderBy(request('orderBy'), request('orderDirection'));
    }

}

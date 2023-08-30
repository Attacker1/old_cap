<?php

namespace App\Http\Filters\Leads;

use App\Http\Filters\VuexFilter;

class LeadQueryBuilder extends VuexFilter
{

    public $booleanOptions = [2 => 'нет', 1 => 'есть'];
    public $secondBooleanOptions = [2 => 'нет', 1 => 'да'];

    public function amoLike($payload): LeadQueryBuilder
    {
        return $this->where(function ($query) use ($payload) {
            $query->where('amo_lead_id', 'like', '%' . $payload . '%');
        });
    }


    public function stylistSelected()
    {
        $user = auth()->guard('admin')->user();
        return $user->hasRole('stylist')
            ? ['disabled' => auth()->guard('admin')->user()->hasRole('stylist')]
            : [];
    }


    public function clientLike($payload)
    {
        return $this->where(function ($query) use ($payload) {
            $query->whereHas('clients', function ($client) use ($payload) {
                $client->where('phone', 'like', '%' . $payload . '%')
                    ->orWhere('second_name', 'like', '%' . $payload . '%')
                    ->orWhere('phone', 'like', '%' . $payload . '%')
                    ->orWhere('email', 'like', '%' . $payload . '%');
            });
        });
    }

    public function isPayment($payload)
    {
        return $this->where(function ($query) use ($payload) {
            if ($payload == 1) {
                $query->whereHas('payments', function ($payment) {
                    $payment->where('amount', '>=', 0);
                });
            } else {
                $query->doesntHave('payments');
            }
        });
    }

    public function paymentSearch($payload)
    {
        return $this->where(function ($query) use ($payload) {
            $query->whereHas('payments', function ($payment) use ($payload) {
                $payment->where('amount', '=', $payload);
            });
        });
    }

    public function stylist($payload)
    {
        return $this->where(function ($query) use ($payload) {
            if ($payload == 'NO') {
                $query->doesntHave('stylists');
            } else {
                $query->whereHas('stylists', function ($stylists) use ($payload) {
                    $stylists->where('id', (int)$payload);
                });
            }
        });
    }

    public function states($payload)
    {
        return $this->where(function ($query) use ($payload) {
            if ($payload == 'NO') {
                $query->whereNull('state_id');
            } else {
                $query->whereHas('states', function ($stylists) use ($payload) {
                    $stylists->where('id', (int)$payload);
                });
            }
        });
    }

    public function tags($payload)
    {
        return $this->where(function ($query) use ($payload) {
            $query->whereHas('tags', function ($q) use ($payload) {
                $q->whereIn('tags.id', $payload);
            }, '=', count($payload));

        });
    }

    public function deadline($payload)
    {
        return $this->where(function ($query) use ($payload) {
            $query->whereBetween('deadline_at', [$payload . ' 00:00:00', $payload . ' 23:59:59']);
        });
    }

    public function byDate($payload)
    {
        return $this->where(function ($query) use ($payload) {
            $query->whereBetween('created_at', [$payload . ' 00:00:00', $payload . ' 23:59:59']);
        });
    }

    public function isDeadline($payload)
    {
        if ($payload == 1) { // yes
            return $this->where(function ($query) {
                $query->whereNotNull('deadline_at');
            });
        }
        if ($payload == 2) { // no
            return $this->where(function ($query) {
                $query->whereNull('deadline_at');
            });
        }
    }

    public function isCompletedDeadline($payload)
    {
        if ($payload == 1) { // yes
            return $this->where(function ($query) {
                $query->whereNotNull('deadline_at')
                    ->whereHas('revisionHistory', function ($history) use ($query) {
                        $history->where('key', '=', 'state_id')
                            ->where('new_value', '=', 6)
                            ->whereColumn('created_at', '<', 'deadline_at');
                    });
            });
        }
        if ($payload == 2) { // no
            return $this->where(function ($query) {
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
                    ->whereHas('states', function ($state) {
                        $state->where('id', '<', '6');
                    })
                    ->where('deadline_at', '<', date("Y-m-d H:i:s"));
            });
        }
    }

    public function deliveryAt($payload)
    {
        return $this->where(function ($query) use ($payload) {
            $query->whereBetween('delivery_at', [$payload . ' 00:00:00', $payload . ' 23:59:59']);
        });
    }

    public function isDeliveryAt($payload)
    {
        if ($payload == 1) { // yes
            return $this->where(function ($query) {
                $query->whereNotNull('delivery_at');
            });
        }
        if ($payload == 2) { // no
            return $this->where(function ($query) {
                $query->whereNull('delivery_at');
            });
        }
    }
}

<?php


namespace App\Http\Filters\Questionnaire;


use App\Http\Filters\Filter;

class ListingFilter extends Filter
{

    public array $booleanOptions = ['нет', 'есть'];
    public array $sizesTop = [40, 42, 44, 46, 48];
    public array $sizesBottom = [40, 42, 44, 46, 48];

    public function filtersData()
    {
        return [
            'idLike' => ['name' => 'idLike', 'label' => 'ID', 'placeholder' => 'фильтр'],
            'clientFullName' => ['name' => 'clientFullName', 'label' => 'Клиент (фио, тел, email)', 'placeholder' => 'фильтр Клиент'],
            'photos' => ['name' => 'photos', 'options' => $this->booleanOptions, 'label' => 'Фото', 'placeholder' => 'Не выбрано'],
            'socials' => ['nameSelect' => 'socials', 'options' => $this->booleanOptions, 'placeholderSelect' => '---',],
            'socialsLike' => ['nameText' => 'socialsLike', 'placeholderText' => 'Фильтр'],
            'photosAndSocials' => ['name' => 'photosAndSocials', 'options' => $this->booleanOptions, 'label' => 'Фото и соц.', 'placeholder' => 'Не выбрано'],
            'sizesTop' => ['name' => 'sizesTop', 'options' => $this->sizesTop, 'label' => 'Размер верх', 'placeholder' => 'Не выбрано'],
            'sizesBottom' => ['name' => 'sizesBottom', 'options' => $this->sizesBottom, 'label' => 'Размер низ', 'placeholder' => 'Не выбрано'],
        ];
    }


    public function uuid($uuid)
    {
        return $this->builder->where('uuid', $uuid);
    }

    public function idLike($int)
    {
        return $this->builder->where(function ($query) use ($int) {
            $query->where('id', 'like', '%' . request('idLike') . '%');//->orWhere('uuid', 'like', '%' . $int . '%');
        });
    }

    public function socialsLike($str)
    {
        return $this->builder->where(function ($query) use ($str) {
            $query->where('data_social', 'like', '%'.$str.'%');
        });
    }

    public function socials($int = null)
    {
        if ($int) {
            return $this->builder->where(function ($query) use ($int) {
                $query->where('data_social', '<>', '')->whereRaw('LENGTH(data_social) > 4');
            });
        } else {
            return $this->builder->where(function ($query) use ($int) {
                $query->where('data_social', null)->orWhere('data_social', '')->orWhere('data_social', 'null');
            });
        }

    }

    public function clientFullName($str)
    {
        return $this->builder->where(function ($query) use ($str) {
            $query->whereHas('client', function ($client) use ($str) {
                $client->where('name', 'like', '%' . $str . '%')
                    ->orWhere('second_name', 'like', '%' . $str . '%')
                    ->orWhere('phone', 'like', '%' . $str . '%')
                    ->orWhere('email', 'like', '%' . $str . '%');
            });
        });
    }

    public function photos($int = null)
    {
        if ($int) {
            return $this->builder->where(function ($query) use ($int) {
                $query->where('photos_allow', "!=", 1);
            });
        } else {
            return $this->builder->where(function ($query) use ($int) {
                $query->where('photos_allow', 1);
            });
        }
    }

    public function photosAndSocials($int = null)
    {
        if ($int) {
            return $this->builder->where(function ($query) use ($int) {
                $query->where('photos_allow', "!=", 1);
            });
        } else {
            return $this->builder->where(function ($query) use ($int) {
                $query->where('photos_allow', 1);
            });
        }
    }

    public function sizesTop()
    {
        return $this->builder->where(function ($query) {
            $query->where('data_sizes_top', "like", '%' . request('sizesTop') . '%');
        });
    }

    public function sizesBottom()
    {
        return $this->builder->where(function ($query) {
            $query->where('data_sizes_bottom', "like", '%' . request('sizesBottom') . '%');
        });
    }

}

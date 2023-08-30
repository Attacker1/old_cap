<div class="analytics-item">
    @if(isset($titleTable))
        <div style="font-size: 20px;background-color: #ffff00;display: inline-block">
            {!! $titleTable !!}
        </div>
    @endif
    <table
            class="no-footer table table-hover table-striped table-bordered dataTable dtr-inline "
            style="width: 100%;border-color: #dedede">
        <thead style="background-color: #a6a6a6">

        <tr>
            <th class="text-center text-white">
                {!! $parentId ? 'Подстатус' : 'Статус' !!}
            </th>
            <th class="text-center text-white">
                Кол-во сделок СЕГОДНЯ
            </th>
            <th class="text-center text-white">
                Кол-во сделок ВЧЕРА
            </th>
            <th class="text-center text-white">
                Итого
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($statuses as $status)
            @if(($parentId && $status->parent_id == $parentId) || (!$parentId && !$status->parent_id))
                <?php
                $allLeads = [];
                $todayLeads = [];
                $yesterdayLeads = [];
                foreach ($data as $item) {
                    if (!$parentId) {
                        if ($item->states->id == $status->id && !$item->states->parent_id) {
                            array_push($allLeads, $item);
                            if ($item->created_at > new DateTime('today')) {
                                array_push($todayLeads, $item);
                            }
                            if ($item->created_at < new DateTime('today') && $item->created_at > new DateTime('yesterday')) {
                                array_push($yesterdayLeads, $item);
                            }
                        }
                    } else {
                        if (($status->parent_id == $parentId && $item->states->id == $status->id) || ($status->parent_id == $parentId && $item->substate_id == $status->id)) {
                            array_push($allLeads, $item);
                            if ($item->created_at > new DateTime('today')) {
                                array_push($todayLeads, $item);
                            }
                            if ($item->created_at < new DateTime('today') && $item->created_at > new DateTime('yesterday')) {
                                array_push($yesterdayLeads, $item);
                            }
                        }
                    }
                }
                ?>
                <tr role="row" class="analytics-status">
                    <th class="text-center">
                        {{ $status->name}}
                    </th>
                    <th class="text-center">
                        {{ count($todayLeads) }}
                    </th>
                    <th class="text-center">
                        {{ count($yesterdayLeads) }}
                    </th>
                    <th class="text-center">
                        {{ count($allLeads) }}
                    </th>
                </tr>
            @else
                @continue
            @endif
        @endforeach
        </tbody>
    </table>
</div>
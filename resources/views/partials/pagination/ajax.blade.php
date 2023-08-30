<div class="dataTables_paginate paging_simple_numbers row" id="datatable_paginate">
    <div class="col-md-2">
        <div class="dataTables_length" style="float: left">
            <select name="perPage" class="custom-select custom-select-sm form-control form-control-sm">
                @foreach([20,50,100] as $cnt)
                    <option @if($paginator->perPage() == $cnt) selected @endif  value="{{$cnt}}">{{$cnt}}</option>
                @endforeach

            </select>
        </div>
    </div>
    <div class="col-md-10">

        @if($paginator->total() > $paginator->perPage())
            <ul class="pagination">


                @if(@$paginator->currentPage() != 1 )
                    <li class="paginate_button page-item previous " id="datatable_previous">
                        <a href="javascript:" rel="{{$paginator->currentPage()-1}}" class="page-link">Предыдущая</a>
                    </li>
                @endif


                @isset($elements[0])
                    @foreach(@$elements[0] as $k => $elem)
                        <li class="paginate_button page-item @if($k == $paginator->currentPage()) active @endif">
                            <a href="javascript:" rel="{{$k}}" class="page-link">{{$k}} </a>
                        </li>
                    @endforeach
                @endisset

                @isset($elements[1])
                    <li class="paginate_button page-item disabled" id="datatable_ellipsis">
                        <a href="javascript:" class="page-link">…</a>
                    </li>
                @endisset

                @isset($elements[2])
                    @foreach(@$elements[2] as $k => $elem)
                        <li class="paginate_button page-item @if($k == $paginator->currentPage()) active @endif">
                            <a href="javascript:" rel="{{$k}}" class="page-link">{{$k}} </a>
                        </li>
                    @endforeach
                @endisset
                @isset($elements[3])
                    <li class="paginate_button page-item disabled" id="datatable_ellipsis">
                        <a href="javascript:" class="page-link">…</a>
                    </li>
                @endisset

                @isset($elements[4])
                    @foreach(@$elements[4] as $k => $elem)
                        <li class="paginate_button page-item @if($k == $paginator->currentPage()) active @endif">
                            <a href="javascript:" rel="{{$k}}" class="page-link">{{$k}}
                            </a>
                        </li>
                    @endforeach
                @endisset

                @if(@$paginator->currentPage() < $paginator->lastPage())
                    <li class="paginate_button page-item next" id="datatable_next">
                        <a href="javascript:"
                           rel="{{$paginator->currentPage() >= $paginator->lastPage() ? 1 :  $paginator->currentPage()+1}}"
                           class="page-link">Следующая</a>
                    </li>
                @endif
            </ul>
        @endif

    </div>

</div>


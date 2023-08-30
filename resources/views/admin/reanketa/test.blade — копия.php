@extends('admin.layout-metronic.default')

@section('title'){{ $title }}@endsection


@section('content')
    @if($error)
        <div>{{$error}}</div>
        <div>Показывактся заглушка</div>
    @endif
    <div class="row">
        <div class="card card-custom col-12 mb-6">
            <div class="card-body pt-4 font-size-h4">
                {{$data->question[0]->answer}}
            </div>
        </div>

        @if($blocks['general_questions']['show'])
        <div class="card card-custom col-lg-6 mb-6">
            <div class="card-header">
                <div class="card-title">Базовые вопросы</div>
            </div>
            <div class="card-body">
                @for ($i = $blocks['general_questions']['start']; $i < $blocks['general_questions']['end']; $i++)
                <div class="row" style="border-bottom: 1px solid #ebedf3">
                    <div class="col-lg-6 mb-4 pt-4">{{ $data->question[$i]->label }}</div>
                    <div class="col-lg-6 mb-4 pt-4">
                        {{ strip_tags($data->question[$i]->option[$data->question[$i]->answer]->text) }}</div>
                </div>
                @endfor
            </div>
        </div>
        @endif

        @if($blocks['compillation_images']['show'] || $blocks['compillation_images_more']['show'])
        <div class="card card-custom col-lg-12 mb-6">
            <div class="card-header card-header-tabs-line">
                <div class="card-title ">Образы</div>
                <div class="card-toolbar">
                    <ul class="nav nav-tabs nav-bold nav-tabs-line">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_1_2">Образы</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_2_2">Цвета</a>
                        </li>
                    </ul>
                </div>    
            </div>
            <div class="card-body pt-4">
                <div class="tab-content">
                    <div class="tab-pane active" id="kt_tab_pane_1_2" role="tabpanel">
                        <div class= "row">    
                            @if($blocks['compillation_images']['show'])
                                @for ($i = $blocks['compillation_images']['start']; $i < $blocks['compillation_images']['end']; $i++)
                                    @if(isset($data->question[$i]->answer[0]))
                                    <div class="col-lg-3 col-sm-4 pb-6">
                                        <img class="card-img img-fluid mb-1" src={{$img_url.$data->question[$i]->image}}>
                                        {{strip_tags($data->question[$i]->option[$data->question[$i]->answer[0]]->text)}}
                                    </div>
                                    @endif
                                @endfor
                            @endif    
                            @if($blocks['compillation_images_more']['show'])
                                @for ($i = $blocks['compillation_images_more']['start']; $i < $blocks['compillation_images_more']['end']; $i++)
                                    @if(isset($data->question[$i]->answer[0]))
                                    <div class="col-lg-3 col-sm-4 pb-6">
                                        <img class="card-img img-fluid mb-1" src={{$img_url.$data->question[$i]->image}}>
                                        {{strip_tags($data->question[$i]->option[$data->question[$i]->answer[0]]->text)}}
                                    </div>
                                    @endif   
                                @endfor
                            @endif
                        </div><!-- row -->    
                    </div><!-- kt_tab_pane_1_2 -->
                    <div class="tab-pane" id="kt_tab_pane_2_2" role="tabpanel">
                        <div class="row">
                            @if($blocks['colors']['show'])
                                <?php $question_number = $blocks['colors']['start'] ?>
                                @for ($i = 0; $i < count($data->question[$question_number]->answer); $i++)
                                     <?php $answer_number = $data->question[$question_number]->answer[$i] ?>
                                    <div class="col-lg-3 pb-6" style="display: flex; flex-wrap: wrap;">
                                        @for ($j = 0; $j < count($data->question[$question_number]->option[$answer_number]->colors); $j++)
                                            <div style="
                                                width:50px; 
                                                height: 50px; 
                                                background-color:{{$data->question[$question_number]->option[$answer_number]->colors[$j]}}" class="mr-2 mb-2">
                                                
                                            </div>
                                        @endfor
                                    </div>
                                @endfor
                            @endif
                        </div><!-- row --> 
                    </div><!-- kt_tab_pane_2_2 -->
                </div><!-- tab-content -->
            </div><!-- card-body -->
        </div><!-- card card-custom -->
        @endif

    </div>
@endsection
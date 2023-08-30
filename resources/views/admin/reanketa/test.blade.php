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

        @for($i = 0; $i < count($blocks); $i++)
            <?php $block = $blocks[$i] ?>
            @if($block['type'] == '2columns_block')
            <div class="card card-custom col-lg-5 mb-6" style = "margin-right: 20px">
                <div class="card-header">
                    <div class="card-title">{{$blocks[$i]['name']}}</div>
                </div>
                 <div class="card-body">
                    @for ($j = 0; $j < count($block['questions_answers']); $j++)
                    <?php $question_answer = $block['questions_answers'][$j] ?>
                    <div class="row" style="border-bottom: 1px solid #ebedf3">
                        <div class="col-lg-6 mb-4 pt-4">{{$question_answer['question_text']}}</div>
                        <div class="col-lg-6 mb-4 pt-4">
                            @if(isset($question_answer['answer']))
                                @for($k = 0; $k < count($question_answer['answer']); $k ++ )
                                    <p>{{strip_tags($question_answer['answer'][$k]['text'])}}</p>
                                @endfor
                            @endif        
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            @endif
        @endfor


        <div class="card card-custom col-lg-12 mb-6">
            
                <div class="card-header card-header-tabs-line">
                    <div class="card-title ">Образы</div>
                    <div class="card-toolbar">
                        <ul class="nav nav-tabs nav-bold nav-tabs-line">
                            @for($i = 0; $i < count($blocks); $i++)
                                @if($blocks[$i]['type'] == '1column_images_colors_block')
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_{{$i}}_2">{{$blocks[$i]['name']}}</a>
                                </li>
                                @endif
                            @endfor
                        </ul>
                    </div>    
                </div>
                <div class="card-body col-12 pt-4">
                    <div class="tab-content">
                        @for($i = 0; $i < count($blocks); $i++)
                            <?php $block = $blocks[$i] ?>
                            @if($block['type'] == '1column_images_colors_block')
                                @if($block['name'] == 'Образы')
                                <div class="tab-pane active" id="kt_tab_pane_{{$i}}_2" role="tabpanel">
                                    <div class= "row">    
                                        @for ($j = 0; $j < count($block['questions_answers'][0]['answer']); $j++)  
                                            <?php $answer = $block['questions_answers'][0]['answer'][$j] ?>
                                            @if($answer['text']!='') 
                                            <div class="col-lg-3 col-sm-4 pb-6">
                                                <img class="card-img img-fluid mb-1" src="{{$img_url.$answer['image']}}">
                                                {{$answer['text']}}
                                            </div>
                                            @endif
                                        @endfor        
                                    </div><!-- row -->    
                                </div><!-- kt_tab_pane_1_2 -->
                                @endif

                                @if($block['name'] == 'Цвета')
                                <div class="tab-pane" id="kt_tab_pane_{{$i}}_2" role="tabpanel">
                                    <div class="row">
                                        @for ($j = 0; $j < count($block['questions_answers'][0]['answer']); $j++)
                                            <?php $answer = $block['questions_answers'][0]['answer'][$j] ?>
                                            <div class="col-lg-3 pb-6">
                                                <div style="display: flex; flex-wrap: wrap;">
                                                @for ($k = 0; $k < count($answer['colors']); $k++)
                                                    <div style="
                                                        width:50px; 
                                                        height: 50px; 
                                                        background-color:{{$answer['colors'][$k]}}" class="mr-2 mb-2">
                                                        
                                                    </div>
                                                @endfor
                                                </div>
                                                <div>{{$answer['text']}}</div>
                                            </div>
                                            
                                        @endfor
                                        
                                    </div><!-- row --> 
                                </div><!-- kt_tab_pane_2_2 -->
                                @endif
                            @endif
                        @endfor    
                    </div><!-- tab-content -->
                </div><!-- card-body -->
                
        </div><!-- card -->    
            
    </div>
@endsection
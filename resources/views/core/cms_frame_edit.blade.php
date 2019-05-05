{{--
 * CMSフレーム編集画面
 *
 * @param obj $frames 表示すべきフレームの配列
 * @param obj $page 現在表示中のページ
 * @author 永原　篤 <nagahara@opensource-workshop.jp>
 * @copyright OpenSource-WorkShop Co.,Ltd. All Rights Reserved
 * @category コア
--}}
{{-- フレーム(編集) --}}
@php
    // エリアが左か右の場合、フレーム編集画面のbootstrap グリッドを使わない。(LABEL が折り返されて見にくくなるため)
    if ($frame->area_id == 1 || $frame->area_id == 3) {
        $class_label = "";
        $class_input = "";
    }
    else {
        $class_label = "col-md-3 control-label";
        $class_input = "col-md-9";
    }
@endphp
{{-- <table class="table"><tr><td> --}}
    <div class="panel-body">
        <ul class="nav nav-tabs">
            {{-- プラグイン側のフレームメニュー --}}
            {{$action_core_frame->includeFrameTab($page, $frame, $action)}}

            {{-- コア側のフレームメニュー --}}
            <li role="presentation" class="active"><a href="{{URL::to('/')}}/plugin/blogs/frame_setting/{{$page->id}}/{{ $frame->id }}#{{ $frame->id }}">フレーム編集</a></li>
            <li role="presentation"><a href="{{URL::to('/')}}/plugin/blogs/frame_delete/{{$page->id}}/{{ $frame->id }}#{{ $frame->id }}">フレーム削除</a></li>
{{--
            <li role="presentation" class="active"><a href="{{URL::to($page->permanent_link)}}/?action=frame_setting&frame_id={{ $frame->frame_id }}#{{ $frame->frame_id }}">フレーム編集</a></li>
            <li role="presentation"><a href="{{URL::to($page->permanent_link)}}/?action=frame_delete&frame_id={{ $frame->frame_id }}#{{ $frame->frame_id }}">フレーム削除</a></li>
--}}
        </ul>
    </div>

    <div class="panel-body">
        <form action="/core/frame/update/{{$page->id}}/{{ $frame->frame_id }}" name="form_{{ $frame->frame_id }}_setting" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="page-name" class="{{$class_label}}">フレームタイトル</label>
                <div class="{{$class_input}}">
                    <input type="text" name="frame_title" id="frame_title" class="form-control" value="{{$frame->frame_title}}">
                </div>
            </div>

            <div class="form-group">
                <label for="page-name" class="{{$class_label}}">フレームデザイン</label>
                <div class="{{$class_input}}">
                    <select class="form-control" name="frame_design" id="frame_design">
                        <option value="">Choose...</option>
                        <option value="none"    @if($frame->frame_design=="none")    selected @endif>None</option>
                        <option value="default" @if($frame->frame_design=="default") selected @endif>Default</option>
                        <option value="primary" @if($frame->frame_design=="primary") selected @endif>Primary</option>
                        <option value="success" @if($frame->frame_design=="success") selected @endif>Success</option>
                        <option value="info"    @if($frame->frame_design=="info")    selected @endif>Info</option>
                        <option value="warning" @if($frame->frame_design=="warning") selected @endif>Warning</option>
                        <option value="danger"  @if($frame->frame_design=="danger")  selected @endif>Danger</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="page-name" class="{{$class_label}}">フレーム幅</label>
                <div class="{{$class_input}}">
                    <select class="form-control" name="frame_col" id="frame_col">
                        <option value="">Choose...</option>
                        <option value="0"  @if($frame->frame_col==0)    selected @endif>100%</option>
                        <option value="1"  @if($frame->frame_col==1)    selected @endif>1</option>
                        <option value="2"  @if($frame->frame_col==2)    selected @endif>2</option>
                        <option value="3"  @if($frame->frame_col==3)    selected @endif>3</option>
                        <option value="4"  @if($frame->frame_col==4)    selected @endif>4</option>
                        <option value="5"  @if($frame->frame_col==5)    selected @endif>5</option>
                        <option value="6"  @if($frame->frame_col==6)    selected @endif>6</option>
                        <option value="7"  @if($frame->frame_col==7)    selected @endif>7</option>
                        <option value="8"  @if($frame->frame_col==8)    selected @endif>8</option>
                        <option value="9"  @if($frame->frame_col==9)    selected @endif>9</option>
                        <option value="10" @if($frame->frame_col==10)   selected @endif>10</option>
                        <option value="11" @if($frame->frame_col==11)   selected @endif>11</option>
                        <option value="12" @if($frame->frame_col==12)   selected @endif>12</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="{{$class_label}}">テンプレート</label>
                <div class="{{$class_input}}">
                    <select class="form-control" name="template" id="template">
                        <option value="default">default</option>
                        @foreach ($action_core_frame->getTemplates() as $template_name)
                            <option value="{{$template_name}}"@if($frame->template == $template_name) selected @endif>{{$template_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="page-name" class="{{$class_label}}">プラグ</label>
                <div class="{{$class_input}}">
                    <select class="form-control" name="plug_name" id="plug_name">
                        <option value="">プラグは使わない。</option>
                        <option value="OswsRss" @if($frame->plug_name=="OswsRss") selected @endif>株式会社オープンソース・ワークショップの新着情報</option>
                        <option value="TestDb"  @if($frame->plug_name=="TestDb")  selected @endif>テストのデータベース読み込み</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-3 {{$class_input}}">
                    <button type="submit" class="btn btn-primary form-horizontal"><span class="glyphicon glyphicon-ok"></span> 更新</button>
                    <button type="button" class="btn btn-default form-horizontal" onclick="location.href='{{URL::to($page->permanent_link)}}'"><span class="glyphicon glyphicon-remove"></span> キャンセル</button>
                </div>
            </div>
        </form>
    </div>
{{-- </td></tr></table> --}}

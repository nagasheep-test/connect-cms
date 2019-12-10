{{--
 * ページ選択画面テンプレート
 *
 * @author 永原　篤 <nagahara@opensource-workshop.jp>
 * @copyright OpenSource-WorkShop Co.,Ltd. All Rights Reserved
 * @category メニュープラグイン
 --}}

{{-- 機能選択タブ --}}
<ul class="nav nav-tabs">
    {{-- プラグイン側のフレームメニュー --}}
    @include('plugins.user.menus.menus_frame_edit_tab')

    {{-- コア側のフレームメニュー --}}
    @include('core.cms_frame_edit_tab')
</ul>

<form action="/plugin/menus/saveSelect/{{$page->id}}/{{$frame->frame_id}}#frame-{{$frame->id}}" name="contents_buckets_form" method="POST" class="mt-3">
    {{ csrf_field() }}

    <div class="form-group">
        <label class="col-form-label">ページの表示</label><br />
        <div class="custom-control custom-radio custom-control-inline">
            @if(isset($menu) && $menu->select_flag == 0)
                <input type="radio" value="0" id="select_on" name="select_flag" class="custom-control-input" checked="checked">
            @else
                <input type="radio" value="0" id="select_on" name="select_flag" class="custom-control-input">
            @endif
            <label class="custom-control-label" for="select_on">全て</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
            @if(isset($menu) && $menu->select_flag == 1)
                <input type="radio" value="1" id="select_off" name="select_flag" class="custom-control-input" checked="checked">
            @else
                <input type="radio" value="1" id="select_off" name="select_flag" class="custom-control-input">
            @endif
            <label class="custom-control-label" for="select_off">選択したもののみ</label>
        </div>
    </div>

@if ($pages)
    <div class="form-group">
        <label class="col-form-label">ページの選択</label><br />
    <div class="list-group" style="margin-bottom: 0;">
    @foreach($pages as $page)

        {{-- 非表示のページは対象外 --}}
        @if ($page->display_flag == 1)

            {{-- 子供のページがある場合 --}}
            @if (count($page->children) > 0)

                {{-- リンク生成。メニュー項目全体をリンクにして階層はその中でインデント表記したいため、a タグから記載 --}}
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="page_select{{$page->id}}" name="page_select[]" value="{{$page->id}}" @if ($menu && $menu->onPage($page->id)) checked @endif />
                    <label class="custom-control-label" for="page_select{{$page->id}}">

                    {{-- 各ページの深さをもとにインデントの表現 --}}
                    @for ($i = 0; $i < $page->depth; $i++)
                        @if ($i+1==$children->depth) <i class="fas fa-chevron-right"></i> @else <span class="px-2"></span>@endif
                    @endfor
                    {{$page->page_name}}
                    </label>
                </div>

                {{-- 子要素を再帰的に表示するため、別ファイルに分けてinclude --}}
                @foreach($page->children as $children)
                    @include('plugins.user.menus.default.menus_select_children',['children' => $children, 'page_id' => $page_id])
                @endforeach
            @else

                {{-- リンク生成。メニュー項目全体をリンクにして階層はその中でインデント表記したいため、a タグから記載 --}}
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="page_select{{$page->id}}" name="page_select[]" value="{{$page->id}}" @if ($menu && $menu->onPage($page->id)) checked @endif />
                    <label class="custom-control-label" for="page_select{{$page->id}}">

                    {{-- 各ページの深さをもとにインデントの表現 --}}
                    @for ($i = 0; $i < $page->depth; $i++)
                        @if ($i+1==$children->depth) <i class="fas fa-chevron-right"></i> @else <span class="px-2"></span>@endif
                    @endfor
                    {{$page->page_name}}
                    </label>
                </div>
            @endif
        @endif
    @endforeach
    </div>
    </div>
@endif

    <div class="form-group row mx-0">
        <div class="offset-md-3">
            <button type="button" class="btn btn-secondary form-horizontal mr-2" onclick="location.href='{{URL::to($current_pages->permanent_link)}}'"><i class="fas fa-times"></i> キャンセル</button>
            <button type="submit" class="btn btn-primary form-horizontal"><i class="fas fa-check"></i> 更新</button>
        </div>
    </div>
</form>

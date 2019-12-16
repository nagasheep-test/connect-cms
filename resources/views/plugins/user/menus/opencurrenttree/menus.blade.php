{{--
 * メニュー表示画面
 *
 * @param obj $pages ページデータの配列
 * @author 永原　篤 <nagahara@opensource-workshop.jp>
 * @copyright OpenSource-WorkShop Co.,Ltd. All Rights Reserved
 * @category メニュープラグイン
--}}
@if ($pages)

    <div class="list-group" style="margin-bottom: 0;">
    @foreach($pages as $page)

        {{-- 非表示のページは対象外 --}}
        @if ($page->isView())

            {{-- 子供のページがある場合 --}}
            @if (count($page->children) > 0)


                {{-- リンク生成。メニュー項目全体をリンクにして階層はその中でインデント表記したいため、a タグから記載 --}}
                @if ($page->id == $page_id)
                <a href="{{ url("$page->permanent_link") }}" class="list-group-item active">
                @else
                <a href="{{ url("$page->permanent_link") }}" class="list-group-item">
                @endif
                    {{-- 各ページの深さをもとにインデントの表現 --}}
                    @for ($i = 0; $i < $page->depth; $i++)
                        @if ($i+1==$children->depth) <i class="fas fa-chevron-right"></i> @else <span class="px-2"></span>@endif
                    @endfor
                    {{$page->page_name}}

                    {{-- カレントもしくは自分のルート筋なら＋、違えば－を表示する --}}
                    @if ($page->isAncestorOf($current_page) || $current_page->id == $page->id)
                        <i class="fas fa-minus"></i>
                    @else
                        <i class="fas fa-plus"></i>
                    @endif
                </a>

                {{-- カレントもしくは自分のルート筋なら表示する --}}
                @if ($page->isAncestorOf($current_page) || $current_page->id == $page->id)
                    {{-- 子要素を再帰的に表示するため、別ファイルに分けてinclude --}}
                    @foreach($page->children as $children)
                        @include('plugins.user.menus.opencurrenttree.menu_children',['children' => $children, 'page_id' => $page_id])
                    @endforeach
                @endif
            @else

                {{-- リンク生成。メニュー項目全体をリンクにして階層はその中でインデント表記したいため、a タグから記載 --}}
                @if ($page->id == $page_id)
                <a href="{{ url("$page->permanent_link") }}" class="list-group-item active">
                @else
                <a href="{{ url("$page->permanent_link") }}" class="list-group-item">
                @endif
                    {{-- 各ページの深さをもとにインデントの表現 --}}
                    @for ($i = 0; $i < $page->depth; $i++)
                        @if ($i+1==$children->depth) <i class="fas fa-chevron-right"></i> @else <span class="px-2"></span>@endif
                    @endfor
                    {{$page->page_name}}
                </a>

            @endif
        @endif
    @endforeach
    </div>
@endif
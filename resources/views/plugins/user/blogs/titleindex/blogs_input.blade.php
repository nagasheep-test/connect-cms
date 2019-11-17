{{--
 * ブログ記事登録画面テンプレート。
 *
 * @author 永原　篤 <nagahara@opensource-workshop.jp>
 * @copyright OpenSource-WorkShop Co.,Ltd. All Rights Reserved
 * @category コンテンツプラグイン
 --}}

{{-- WYSIWYG 呼び出し --}}
@include('plugins.common.wysiwyg')

{{-- 一時保存ボタンのアクション --}}
<script type="text/javascript">
    function save_action() {
        @if (empty($blogs_posts->id))
            form_blogs_posts.action = "/plugin/blogs/temporarysave/{{$page->id}}/{{$frame_id}}";
        @else
            form_blogs_posts.action = "/plugin/blogs/temporarysave/{{$page->id}}/{{$frame_id}}/{{$blogs_posts->id}}";
        @endif
        form_blogs_posts.submit();
    }
</script>

{{-- 投稿用フォーム --}}
@if (empty($blogs_posts->id))
    <form action="/plugin/blogs/save/{{$page->id}}/{{$frame_id}}" method="POST" class="" name="form_blogs_posts">
@else
    <form action="/plugin/blogs/save/{{$page->id}}/{{$frame_id}}/{{$blogs_posts->id}}" method="POST" class="" name="form_blogs_posts">
@endif
    {{ csrf_field() }}
    <input type="hidden" name="blogs_id" value="{{$blog_frame->blogs_id}}">

    <div class="form-group">
        <label class="control-label">タイトル <label class="badge badge-danger">必須</label></label>
        <input type="text" name="post_title" value="{{old('post_title', $blogs_posts->post_title)}}" class="form-control">
        @if ($errors && $errors->has('post_title')) <div class="text-danger">{{$errors->first('post_title')}}</div> @endif
    </div>

    <div class="form-group">
        <label class="control-label">投稿日時 <label class="badge badge-danger">必須</label></label>
        <input type="text" name="posted_at" value="{{old('posted_at', $blogs_posts->posted_at)}}" class="form-control">
{{--
        <div class="input-group date" data-provide="datepicker">
            <input type="text" name="posted_at" value="{{old('posted_at', $blogs_posts->posted_at)}}" class="form-control datepicker">
            <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
        </div>
--}}
        @if ($errors && $errors->has('posted_at')) <div class="text-danger">{{$errors->first('posted_at')}}</div> @endif
    </div>

    <div class="form-group">
        <label class="control-label">本文 <label class="badge badge-danger">必須</label></label>
        <textarea name="post_text">{!!old('post_text', $blogs_posts->post_text)!!}</textarea>
        @if ($errors && $errors->has('post_text')) <div class="text-danger">{{$errors->first('post_text')}}</div> @endif
    </div>

    <div class="form-group">
        <label class="control-label">タグ</label>
        <input type="text" name="tags" value="{{old('tags', $blogs_posts_tags)}}" class="form-control">
        @if ($errors && $errors->has('tags')) <div class="text-danger">{{$errors->first('tags')}}</div> @endif
        <small class="form-text text-muted">カンマ区切りで複数指定可能</small>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <div class="text-center">
                    <button type="button" class="btn btn-secondary mr-2" onclick="location.href='{{URL::to($page->permanent_link)}}'"><i class="fas fa-times"></i> キャンセル</button>
                    <button type="button" class="btn btn-info mr-2" onclick="javascript:save_action();"><i class="far fa-save"></i> 一時保存</button>
                    <input type="hidden" name="bucket_id" value="">
                    @if (empty($blogs_posts->id))
                        @if ($blog_frame->approval_flag == 0 ||
                             Auth::user()->can('role_article',[[null, 'blogs']]) ||
                             Auth::user()->can('role_article_admin',[[null, 'blogs']]))
                            <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> 登録確定</button>
                        @else
                            <button type="submit" class="btn btn-success"><i class="far fa-edit"></i> 登録申請</button>
                        @endif
                    @else
                        @if ($blog_frame->approval_flag == 0 ||
                             Auth::user()->can('role_article',[[null, 'blogs']]) ||
                             Auth::user()->can('role_article_admin',[[null, 'blogs']]))
                            <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> 変更確定</button>
                        @else
                            <button type="submit" class="btn btn-success"><i class="far fa-edit"></i> 変更申請</button>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-sm-3 pull-right text-right">
                @if (!empty($blogs_posts->id))
                    <a data-toggle="collapse" href="#collapse{{$blogs_posts->id}}">
                        <span class="btn btn-danger"><i class="fas fa-trash-alt"></i> <span class="hidden-xs">削除</span></span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</form>

<div id="collapse{{$blogs_posts->id}}" class="collapse" style="margin-top: 8px;">
    <div class="panel panel-danger">
        <div class="panel-body">
            <span class="text-danger">データを削除します。<br>元に戻すことはできないため、よく確認して実行してください。</span>

            <div class="text-center">
                {{-- 削除ボタン --}}
                <form action="{{url('/')}}/plugin/blogs/delete/{{$page->id}}/{{$frame_id}}/{{$blogs_posts->id}}" method="POST">
                    {{csrf_field()}}
                    <button type="submit" class="btn btn-danger" onclick="javascript:return confirm('データを削除します。\nよろしいですか？')"><i class="fas fa-check"></i> 本当に削除する</button>
                </form>
            </div>

        </div>
    </div>
</div>
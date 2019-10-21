<?php

namespace App\Plugins\User\Contents;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

use DB;

use App\Buckets;
use App\Models\User\Contents\Contents;
use App\Frame;

use App\Plugins\User\UserPluginBase;

/**
 * コンテンツプラグイン
 *
 * 固定エリアのデータ登録ができるプラグイン。
 *
 * @author 永原　篤 <nagahara@opensource-workshop.jp>
 * @copyright OpenSource-WorkShop Co.,Ltd. All Rights Reserved
 * @category コンテンツプラグイン
 * @package Contoroller
 */
class ContentsPlugin extends UserPluginBase
{

    /* オブジェクト変数 */

    /**
     * POSTデータ
     */
    public $post = null;

    /* コアから呼び出す関数 */

    /**
     *  関数定義（コアから呼び出す）
     */
    public function getPublicFunctions()
    {
        // 標準関数以外で画面などから呼ばれる関数の定義
        $functions = array();
        $functions['get']  = [];
        $functions['post'] = [];
        return $functions;
    }

    /**
     *  編集画面の最初のタブ
     *
     *  スーパークラスをオーバーライド
     */
    public function getFirstFrameEditAction()
    {
        return "edit";
    }

    /**
     *  データ取得
     */
    public function getPost($frame_id)
    {

        // 一度読んでいれば、そのPOSTを再利用する。
        if (!empty($this->post)) {
            return $this->post;
        }

        // 認証されているユーザの取得
        $user = Auth::user();

        // 管理者権限の場合は、一時保存も対象
        //if (!empty($user) && $this->isCan('admin_system')$user->role == config('cc_role.ROLE_SYSTEM_MANAGER')) {
        if (!empty($user) && $this->isCan('admin_system')) {

            // フレームID が渡されるので、そのフレームに応じたデータを返す。
            // 表示するデータ、バケツ、フレームをJOIN して取得
            $contents = DB::table('contents')
                        ->select('contents.*', 'buckets.id as bucket_id', 'frames.page_id as page_id')
                        ->join('buckets', 'buckets.id', '=', 'contents.bucket_id')
                        ->join('frames', function ($join) {
                            $join->on('frames.bucket_id', '=', 'buckets.id');
                        })
                        ->where('frames.id', $frame_id)
                        ->where('contents.deleted_at', null)
                        // 権限があるときは、アクティブ、一時保存、承認待ちを or で取得
                        ->where(function($query){ $query->where('contents.status', 0)->orWhere('contents.status', 1)->orWhere('contents.status', 2); })
                        ->orderBy('id', 'desc')
                        ->first();
        }
        else {

            // フレームID が渡されるので、そのフレームに応じたデータを返す。
            // 表示するデータ、バケツ、フレームをJOIN して取得
            $contents = DB::table('contents')
                        ->select('contents.*', 'buckets.id as bucket_id', 'frames.page_id as page_id')
                        ->join('buckets', 'buckets.id', '=', 'contents.bucket_id')
                        ->join('frames', function ($join) {
                            $join->on('frames.bucket_id', '=', 'buckets.id');
                        })
                        ->where('frames.id', $frame_id)
                        ->where('contents.deleted_at', null)
                        ->where('contents.status', 0)
                        ->orderBy('id', 'desc')
                        ->first();
        }
        return $contents;
    }

    /* 画面アクション関数 */

    /**
     *  データ初期表示関数
     *  コアがページ表示の際に呼び出す関数
     */
    public function index($request, $page_id, $frame_id)
    {
        // データ取得
        $contents = $this->getPost($frame_id);

        // 表示テンプレートを呼び出す。
        return $this->view(
            'contents', [
            'contents' => $contents,
        ]);
    }

    /**
     * データ編集用表示関数
     * コアが編集画面表示の際に呼び出す関数
     */
    public function edit($request, $page_id, $frame_id, $id = null)
    {
        // データ取得
        $contents = $this->getPost($frame_id);

        // データがない場合は、新規登録用画面
        if (empty($contents)) {
            // 新規登録画面を呼び出す
            return $this->view(
                'contents_create', [
            ]);

        }
        else {
            // 編集画面テンプレートを呼び出す。
            return $this->view(
                'contents_edit', [
                'contents' => $contents,
            ]);
        }
    }

    /**
     *  データ詳細表示関数
     *  コアがデータ削除の確認用に呼び出す関数
     */
    public function show($request, $page_id, $frame_id, $id = null)
    {
        // 権限チェック
        // 固定記事プラグインの特別処理。削除のための表示であり、フレーム画面のため、個別に権限チェックする。
        if ($this->can('frames.delete')) {
            return $this->view_error(403);
        }

        // データ取得
        $contents = $this->getPost($frame_id);

        // データの存在確認をして、画面を切り替える
        if (empty($contents)) {

            // データなしの表示テンプレートを呼び出す。
            return $this->view(
                'contents_edit_nodata', [
                'contents' => null,
            ]);
        }

        // 表示テンプレートを呼び出す。
        return $this->view(
            'contents_show', [
            'contents' => $contents,
        ]);
    }

   /**
    * データ新規登録関数
    */
    public function store($request, $page_id = null, $frame_id = null, $id = null, $status = 0)
    {
        // バケツの登録
        $bucket_id = DB::table('buckets')->insertGetId([
              'bucket_name' => '無題',
              'plugin_name' => 'contents'
        ]);

        // コンテンツデータの登録
        $contents = new Contents;
        $contents->bucket_id    = $bucket_id;
        $contents->content_text = $request->contents;
        $contents->status       = $status;
        $contents->save();

        // FrameのバケツIDの更新
        Frame::where('id', $frame_id)
                  ->update(['bucket_id' => $bucket_id]);

        return;
    }

   /**
    * データ更新（確定）関数
    */
    public function update($request, $page_id = null, $frame_id = null, $id = null)
    {
        // 新しいレコードの登録（旧レコードのコピー＆内容の入れ替え）
        $oldrow = Contents::find($id);

        // 旧レコードのstatus 更新(Activeなもの(status:0)は、status:9 に更新。他はそのまま。)
        Contents::where('bucket_id', $oldrow->bucket_id)->where('status', 0)->update(['status' => 9]);
        //Contents::where('id', $oldrow->id)->update(['status' => 9]);

        // 新しいレコードの登録（旧レコードのコピー＆内容の入れ替え）
        $newrow = $oldrow->replicate();
        $newrow->content_text = $request->contents;
        $newrow->status       = 0;
        $newrow->save();

        return;
    }

   /**
    * データ一時保存関数
    */
    public function temporarysave($request, $page_id = null, $frame_id = null, $id = null)
    {
        // 新規で一時保存しようとしたときは id、レコードがまだない。
        if (empty($id)) {
            $status = 1;
            $this->store($request, $page_id, $frame_id, $id, $status);
        }
        else {

            // 旧データ取得
            $oldrow = Contents::find($id);

            // 旧レコードが表示でなければ、履歴に更新（表示を履歴に更新すると、画面に表示されなくなる）
// 過去のステータスも残す方式にする。
//            if ($oldrow->status != 0) {
//                Contents::where('id', $id)->update(['status' => 9]);
//            }

            // 新しいレコードの登録（旧レコードのコピー＆内容の入れ替え）
            $newrow = $oldrow->replicate();
            $newrow->content_text = $request->contents;
            $newrow->status = 1; //（一時保存）
            $newrow->save();
        }
        return;
    }

   /**
    * データ削除関数
    */
    public function delete($request, $page_id = null, $frame_id = null, $id = null)
    {
        // id がある場合、コンテンツを削除
        if ( $id ) {

            // Contents データ
            $content = Contents::where('id', $id)->first();

            // フレームも同時に削除するがチェックされていたらフレームを削除する。
            if ( $request->frame_delete_flag == "1" ) {
                Frame::destroy($frame_id);
            }

            // 論理削除のため、コンテンツデータを status:9 に変更する。バケツデータは削除しない。
// 過去のステータスも残す方式にする。
//            Contents::where('id', $id)->update(['status' => 9]);

            // 削除ユーザの更新
            Contents::where('bucket_id', $content->bucket_id)->update(['deleted_id' => Auth::user()->id, 'deleted_name' => Auth::user()->name]);

            // 同じbucket_id のものを削除
            Contents::where('bucket_id', $content->bucket_id)->delete();

        }
        return;
    }

    /**
     * データ選択表示関数
     */
    public function listBuckets($request, $page_id, $frame_id, $id = null)
    {
        // ソート設定に初期設定値をセット
        $sort_inits = [
            "contents_updated_at" => ["desc", "asc"],
            "page_name" => ["desc", "asc"],
            "bucket_name" => ["asc", "desc"],
            "frame_title" => ["asc", "desc"],
            "content_text" => ["asc", "desc"],
        ];

        // 要求するソート指示。初期値として更新日の降順を設定
        $request_order_by = ["contents_updated_at", "desc"];

        // 画面からのソート指定があれば使用(ソート指定があった項目は、ソート設定の内容を入れ替える)
        if ( !empty( $request->sort ) ) {
            $request_order_by = explode('|', $request->sort);
            if ($request_order_by[1] == "asc") {
                $sort_inits[$request_order_by[0]]=["asc", "desc"];
            }
            else {
                $sort_inits[$request_order_by[0]]=["desc", "asc"];
            }
        }

        // 画面でのリンク用ソート指示(ソート指定されている場合はソート指定を逆転したもの)
        $order_link = array();
        foreach ( $sort_inits as $order_by_key => $order_by ) {
            if ( $request_order_by[0]==$order_by_key && $request_order_by[1]==$order_by[0]) {
                $order_link[$order_by_key] = array_reverse($order_by);
            }
            else {
                $order_link[$order_by_key] = $order_by;
            }
        }

        // データリストの場合の追加処理
        // * status は 0 のもののみ表示（データリスト表示はそれで良いと思う）
        $buckets = DB::table('buckets')
                    ->select('buckets.*', 'contents.id as contents_id', 'contents.content_text', 'contents.updated_at as contents_updated_at', 'frames.id as frames_id',  'frames.frame_title', 'pages.page_name')
                    ->leftJoin('contents', function ($join) {
                        $join->on('contents.bucket_id', '=', 'buckets.id');
                        $join->where('contents.status', '=', 0);
                    })
                    ->leftJoin('frames', 'buckets.id', '=', 'frames.bucket_id')
                    ->leftJoin('pages', 'pages.id', '=', 'frames.page_id')
                    ->where('buckets.plugin_name', 'contents')
                    ->orderBy($request_order_by[0],        $request_order_by[1])
                    ->paginate(10);

        return $this->view(
            'contents_list_buckets', [
            'buckets'           => $buckets,
            'order_link'        => $order_link,
            'request_order_str' => implode( '|', $request_order_by )
        ]);
    }

   /**
    * データ紐づけ変更関数
    */
    public function changeBuckets($request, $page_id = null, $frame_id = null, $id = null)
    {
        // FrameのバケツIDの更新
        Frame::where('id', $frame_id)
               ->update(['bucket_id' => $request->select_bucket]);
        return;
    }
}

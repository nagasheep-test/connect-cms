<?php

namespace App\Models\User\Learningtasks;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Userable;

class LearningtasksPosts extends Model
{
    // 論理削除
    use SoftDeletes;

    // 保存時のユーザー関連データの保持
    use Userable;

    // 日付型の場合、$dates にカラムを指定しておく。
    protected $dates = ['posted_at'];

    /**
     *  リスト表示用タイトル
     *  改行を取り除いたもの。
     */
    public function getNobrPostTitle()
    {
        return str_ireplace(['<br>', '<br />'], ' ', $this->post_title);
    }
}

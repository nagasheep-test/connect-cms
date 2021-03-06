{{--
 * コード管理のメインテンプレート
 *
 * @author 牟田口 満 <mutaguchi@opensource-workshop.jp>
 * @copyright OpenSource-WorkShop Co.,Ltd. All Rights Reserved
 * @category コード管理
 --}}
{{-- 管理画面ベース画面 --}}
@extends('plugins.manage.manage')

{{-- 管理画面メイン部分のコンテンツ section:manage_content で作ること --}}
@section('manage_content')

<div class="card">
<div class="card-header p-0">

{{-- 機能選択タブ --}}
@include('plugins.manage.code.code_manage_tab')

</div>
<div class="card-body">

<div class="text-right"><span class="badge badge-pill badge-light">全 {{ $codes->total() }} 件</span></div>
<table class="table table-bordered table_border_radius table-hover cc-font-90">
<tbody>
    <tr class="bg-light d-none d-sm-table-row">
        <th class="d-block d-sm-table-cell text-break">プラグイン</th>
{{--
        <th class="d-block d-sm-table-cell text-break">データ名</th>
        <th class="d-block d-sm-table-cell text-break">buckets_id</th>
--}}
        <th class="d-block d-sm-table-cell text-break">prefix</th>
        <th class="d-block d-sm-table-cell text-break">type_name</th>
        <th class="d-block d-sm-table-cell text-break">type_code1</th>
{{--
        <th class="d-block d-sm-table-cell text-break">type_code2</th>
        <th class="d-block d-sm-table-cell text-break">type_code3</th>
        <th class="d-block d-sm-table-cell text-break">type_code4</th>
        <th class="d-block d-sm-table-cell text-break">type_code5</th>
--}}
        <th class="d-block d-sm-table-cell text-break">コード</th>
        <th class="d-block d-sm-table-cell text-break">値</th>
        <th class="d-block d-sm-table-cell text-break">additional1</th>
{{--
        <th class="d-block d-sm-table-cell text-break">additional2</th>
        <th class="d-block d-sm-table-cell text-break">additional3</th>
        <th class="d-block d-sm-table-cell text-break">additional4</th>
        <th class="d-block d-sm-table-cell text-break">additional5</th>
--}}
        <th class="d-block d-sm-table-cell text-break">並び順</th>
    </tr>
    @foreach($codes as $code)
    <tr>
        <th class="d-block d-sm-table-cell bg-light">
{{--
            <a href="{{url('/')}}/manage/code/edit/{{$code->id}}"><i class="far fa-edit"></i></a>
--}}
            <a href="{{url('/')}}/manage/code/edit/{{$code->id}}?page={{$paginate_page}}"><i class="far fa-edit"></i></a>
            <span class="d-sm-none">プラグイン：</span>{{$code->plugin_name_full}}
        </th>
{{--
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">データ名：</span>{{$code->bucket_name}}</td>
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">buckets_id：</span>{{$code->buckets_id}}</td>
--}}
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">prefix：</span>{{$code->prefix}}</td>
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">type_name：</span>{{$code->type_name}}</td>
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">type_code1：</span>{{$code->type_code1}}</td>
{{--
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">type_code2：</span>{{$code->type_code2}}</td>
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">type_code3：</span>{{$code->type_code3}}</td>
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">type_code4：</span>{{$code->type_code4}}</td>
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">type_code5：</span>{{$code->type_code5}}</td>
--}}
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">コード：</span>{{$code->code}}</td>
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">値：</span>{{$code->value}}</td>
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">additional1：</span>{{$code->additional1}}</td>
{{--
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">additional2：</span>{{$code->additional2}}</td>
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">additional3：</span>{{$code->additional3}}</td>
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">additional4：</span>{{$code->additional4}}</td>
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">additional5：</span>{{$code->additional5}}</td>
--}}
        <td class="d-block d-sm-table-cell"><span class="d-sm-none">並び順：</span>{{$code->display_sequence}}</td>
    </tr>
    @endforeach
</tbody>
</table>

{{ $codes->links() }}

</div>
</div>

@endsection

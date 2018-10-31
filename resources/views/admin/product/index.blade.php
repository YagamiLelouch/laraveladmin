@extends('admin.layouts.base')

@section('title','控制面板')

@section('css')
    <link rel="stylesheet" href="/plugins/jstree/dist/themes/default/style.min.css">
@stop

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')

    <div class="row page-title-row" style="margin:5px;">
        <div class="col-md-6">
        </div>
        <div class="col-md-6 text-right">
            @if(Gate::forUser(auth('admin')->user())->check('admin.product.create'))
                <a href="/admin/product/create" class="btn btn-success btn-md">
                    <i class="fa fa-plus-circle"></i> 添加产品
                </a>
            @endif
        </div>
    </div>

    <div class="row page-title-row" style="margin:5px;">
        <div class="col-md-6">
        </div>
        <div class="col-md-6 text-right">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="box">
                <div id="productTree">

                </div>

                {{--<ul class="list-group">--}}
                {{--<ul class="list components">--}}
                    {{--@foreach($trees as $tree)--}}
                        {{--<li class="list-group-item">--}}
                            {{--<a href="#{{ $tree['id'] }}"  data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">{{ $tree['name'] }}</a>--}}
                            {{--<ul class="collapse list" id="{{ $tree['id'] }}">--}}
                                {{--@foreach($tree['child'] as $v)--}}
                                    {{--<li class="list-group-item">--}}
                                        {{--<a href="#{{ $v['id'] }}" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">{{ $v['name'] }}</a>--}}
                                        {{--<ul class="collapse list" id="{{ $v['id'] }}">--}}
                                            {{--@foreach($v['child'] as $v1)--}}
                                                {{--<li class="list-group-item">--}}
                                                    {{--<a href="#{{ $v1['id'] }}" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle product" cid="{{ $v1['id'] }}" >{{ $v1['name'] }}</a>--}}
                                                {{--</li>--}}
                                            {{--@endforeach--}}
                                        {{--</ul>--}}
                                    {{--</li>--}}
                                {{--@endforeach--}}
                            {{--</ul>--}}

                        {{--</li>--}}
                    {{--@endforeach--}}
                {{--</ul>--}}
                {{--</ul>--}}
            </div>
        </div>
        <div class="col-sm-8">
            <div class="box">
                @include('admin.partials.errors')
                @include('admin.partials.success')
                <div class="box-body">
                    <table id="tags-table" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th data-sortable="false" class="hidden-sm"></th>
                            <th class="hidden-sm">产品名称</th>
                            <th class="hidden-sm">状态</th>
                            <th class="hidden-md">创建者</th>
                            <th class="hidden-md">创建时间</th>
                            <th class="hidden-md">修改者</th>
                            <th class="hidden-md">修改时间</th>
                            <th data-sortable="false">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="modal-delete" tabIndex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        ×
                    </button>
                    <h4 class="modal-title">提示</h4>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        <i class="fa fa-question-circle fa-lg"></i>
                        确认要删除这个产品吗?
                    </p>
                </div>
                <div class="modal-footer">
                    <form class="deleteForm" method="POST" action="/admin/product">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-times-circle"></i>确认
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
            @stop

            @section('js')
                <script src="/plugins/jstree/dist/jstree.min.js"></script>
                <script>
                    $(function () {

                        $('#productTree').jstree({
                            'core': {
                                'data': {
                                    "animation": 0,
                                    "check_callback": true,
                                    "themes": {"stripes": true},
                                    'url': function (node) {
                                        return '/admin/product/getcategory';
                                    },
                                    'dataType': 'json'
                                },

                                'multiple': false,
                                'themes': {
                                    'variant': 'large'
                                }
                            },
                            "plugins": [
                                "contextmenu", "dnd", "search",
                                "state", "types", "wholerow"
                            ]
                        });

                        // $('#productTree').jstree({
                        //     core: {
                        //         data : function (node, cb) {
                        //             $.ajax({ url : '/admin/product/getcategory' }).done(function (data) {
                        //                 cb([{ "id" : data.id, "text" : data.name }])
                        //             });
                        //         }
                        //     }
                        // })

                        $('.product').on('click', function () {
                            cid = $(this).attr('cid');

                            var table = $("#tags-table").DataTable({
                                destroy: true,
                                language: {
                                    "sProcessing": "处理中...",
                                    "sLengthMenu": "显示 _MENU_ 项结果",
                                    "sZeroRecords": "没有匹配结果",
                                    "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                                    "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                                    "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                                    "sInfoPostFix": "",
                                    "sSearch": "搜索:",
                                    "sUrl": "",
                                    "sEmptyTable": "表中数据为空",
                                    "sLoadingRecords": "载入中...",
                                    "sInfoThousands": ",",
                                    "oPaginate": {
                                        "sFirst": "首页",
                                        "sPrevious": "上页",
                                        "sNext": "下页",
                                        "sLast": "末页"
                                    },
                                    "oAria": {
                                        "sSortAscending": ": 以升序排列此列",
                                        "sSortDescending": ": 以降序排列此列"
                                    }
                                },
                                order: [[1, "desc"]],
                                serverSide: true,
                                ajax: {
                                    url: '/admin/product/index',
                                    data: {
                                        cid: cid
                                    },
                                    type: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                    }
                                },
                                "columns": [
                                    {"data": "id"},
                                    {"data": "name"},
                                    {"data": "status"},
                                    {"data": "creator.name"},
                                    {"data": "created_at"},
                                    {"data": "updater.name"},
                                    {"data": "updated_at"},
                                    {"data": "action"}
                                ],
                                columnDefs: [
                                    {
                                        'targets': -1, "render": function (data, type, row) {
                                            var row_edit = {{Gate::forUser(auth('admin')->user())->check('admin.product.edit') ? 1 : 0}};
                                            var row_delete = {{Gate::forUser(auth('admin')->user())->check('admin.product.destroy') ? 1 :0}};
                                            var str = '';

                                            //编辑
                                            if (row_edit) {
                                                str += '<a style="margin:3px;" href="/admin/product/' + row['id'] + '/edit" class="X-Small btn-xs text-success "><i class="fa fa-edit"></i> 编辑</a>';
                                            }

                                            //删除
                                            if (row_delete) {
                                                str += '<a style="margin:3px;" href="#" attr="' + row['id'] + '" class="delBtn X-Small btn-xs text-danger"><i class="fa fa-times-circle"></i> 删除</a>';
                                            }

                                            return str;

                                        }
                                    }
                                ]
                            });

                            table.on('preXhr.dt', function () {
                                loadShow();
                            });

                            table.on('draw.dt', function () {
                                loadFadeOut();
                            });

                            table.on('order.dt search.dt', function () {
                                table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                                    cell.innerHTML = i + 1;
                                });
                            }).draw();
                            $("table").delegate('.delBtn', 'click', function () {
                                var id = $(this).attr('attr');
                                $('.deleteForm').attr('action', '/admin/product/' + id);
                                $("#modal-delete").modal();
                            });
                        })
                    });
                </script>
@stop
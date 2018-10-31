@extends('admin.layouts.base')

@section('title','控制面板')

@section('css')
    <link rel="stylesheet" href="/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/plugins/jstree/dist/themes/default/style.min.css">
    <link rel="stylesheet" href="/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="/plugins/jQuery-tagEditor/jquery.tag-editor.css">
    <link rel="stylesheet" href="/plugins/jqgrid/ui.jqgrid.css">
    <link rel="stylesheet" href="/plugins/jqgrid/ui.jqgrid-bootstrap.css">
@stop

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
<div class="main animsition">
    <div class="container-fluid">

        <div class="row">
            <div class="">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ $api['title'] ?? '创建API' }}</h3>
                    </div>
                    <div class="panel-body">

                        @include('admin.partials.errors')
                        @include('admin.partials.success')
                        <form class="form-horizontal" role="form" method="POST" action="/admin/product/{{ $api['id'] ?? ''}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="{{$api['method'] ?? 'POST'}}">
                            <input type="hidden" name="id" value="{{ $api['id'] ?? '' }}">
                            <input type="hidden" name="level" value="4">

                            <div class="form-group">
                                <label for="tag" class="col-md-3 control-label">中文名称</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="cname" id="cname" value="{{ $api['cname'] ?? '' }}"
                                           autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tag" class="col-md-3 control-label">英文名称</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="ename" id="ename" value="{{ $api['ename'] ?? '' }}"
                                           autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                {{--显示分类--}}
                                <label for="tag" class="col-md-3 control-label">API分类</label>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" rows="3" id="pinput"
                                           
                                           data-toggle="modal" data-target="#exampleModal">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tag" class="col-md-3 control-label">Url</label>
                                <div class="col-md-5">
                                    <input name="url" type="text" class="form-control" rows="3" id="url" value="{{ $api['url'] ?? ''}}">

                                </div>
                            </div>

                            {{--实际提交分类--}}
                            <input type="hidden" name="category_id" id="categoryVal" value="{{$api['category_id'] ?? ''}}">

                            <div class="form-group">
                                <label for="tag" class="col-md-3 control-label">API标签</label>
                                <div class="col-md-5">
                                    <input type="text"  class="form-control" name="tag" rows="3" id="tag" value="{{ $api['tags'] ?? '' }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tag" class="col-md-3 control-label">API简介</label>
                                <div class="col-md-5">
                                    <input name="describe" type="text"  class="form-control" rows="3" id="describe" value="{{ $api['intro'] ?? ''}}">

                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label">输入参数</label>
                                <div class="col-sm-10">
                                    <table id="table_list_in"></table>
                                </div>
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-10" style="margin-top: 6px;">
                                    <label title="上传图片" for="inputFile" class="btn btn-primary">
                                        <input type="file" accept="excel" name="inputFile" id="inputFile" class="hide" onchange="fileSelected(1);"><i class="fa fa-upload"></i> 点击上传
                                    </label>
                                    <a href="/uploads/in/in.xlsx"><label class="btn btn-primary"><i class="fa fa-download"></i> 下载模板</label></a>
                                    <button class="btn btn-primary" id="tableTest">test</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">输出参数</label>
                                <div class="col-sm-10">
                                    <table id="table_list_out"></table>
                                </div>
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-10" style="margin-top: 6px;">
                                    <label title="上传图片" for="outputFile" class="btn btn-primary">
                                        <input type="file" accept="xlsx/*" name="outputFile" id="outputFile" class="hide" onchange="fileSelected(2);"><i class="fa fa-upload"></i> 点击上传
                                    </label>
                                    <a href="/uploads/out/out.xlsx"><label class="btn btn-primary"><i class="fa fa-download"></i> 下载模板</label></a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tag" class="col-md-3 control-label">请求样例</label>
                                <div class="col-md-5">
                                    <input name="request_sample" type="text"  class="form-control" rows="3" id="request_sample" value="{{ $api['intro'] ?? ''}}">

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tag" class="col-md-3 control-label">返回样例</label>
                                <div class="col-md-5">
                                    <input name="reback_sample" type="text"  class="form-control" rows="3" id="reback_sample" value="{{ $api['intro'] ?? ''}}">

                                </div>
                            </div>




                            {{--<div class="form-group">--}}
                            {{--<label for="tag" class="col-md-3 control-label">产品图标</label>--}}
                            {{--<div class="col-md-5">--}}
                            {{--<input name="picon" class="form-control" rows="3" id="url">--}}

                            {{--</div>--}}
                            {{--</div>--}}


                            <div class="form-group">
                                <div class="col-md-7 col-md-offset-3">
                                    <button id="save" class="btn btn-primary btn-md">
                                        <i class="fa fa-plus-circle"></i>
                                        保存
                                    </button>
                                </div>
                            </div>


                        </form>


                        <div>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <div id="productTree">

                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
    <script src="/plugins/jstree/dist/jstree.min.js"></script>
    <script src="/plugins/jQuery-tagEditor/jquery.caret.min.js"></script>
    <script src="/plugins/jQuery-tagEditor/jquery.tag-editor.js"></script>
    <script src="/plugins/jQueryUI/jquery-ui.js"></script>
    <script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="/plugins/jqgrid/i18n/grid.locale-cn.js"></script>
    <script src="/plugins/jqgrid/jquery.jqGrid.js"></script>

    <script>
        var jsonReader = {
            root: "data",
            total: "last_page",
            records: "total"
        };
        $(function () {
            // $.ajax({
            //     url: '/admin/product/getcategory',
            //     dataType: 'json',
            //     success: function (data) {
            //        console.log(data);
            //     }
            // })
            $("#pinput").on('click', function () {
                $('#productTree').jstree({
                    'core': {
                        'data': {
                            "animation": 0,
                            "check_callback": true,
                            "themes": {"stripes": true},
                            'url': '/admin/api/getcategory',
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
            })

            $("#productTree").on('select_node.jstree', function (e, data) {
                node = data.node.original;
            }).on('dblclick', '.jstree-anchor', function (e, data) {
                $('#pinput').val(node.full_name).attr('cid', node.id);
                $('#categoryVal').val(node.id);
                $('#exampleModal').modal('hide');
            });


            inData =  {!! $inData ?? '[{}]' !!};
            outData = {!! $outData ?? '[{}]' !!};


            $("#table_list_in").jqGrid({
                data: inData,
                datatype: "local",
                autowidth: true,
                height: "100%",
                styleUI : 'Bootstrap',
                colModel: [
                    {name: 'option', label: "操作", width: 50, sortable: false, align: 'center', formatter: optionsInFormate},
                    {name: 'ename', label: "英文名", width: 60, sortable: false, editable: true, align: 'center'},
                    {name: 'cname', label: "中文名", width: 60, sortable: false, editable: true, align: 'center'},
                    {name: 'type', label: "类型", width: 60, sortable: false, editable: true, align: 'center'},
                    {name: 'accuracy', label: "精度", width: 60, sortable: false, editable: true, align: 'center'},
                    {name: 'dimension', label: "量纲", width: 40, sortable: false, editable: true, align: 'center'},
                    {name: 'is_required', label: "是否必填", width: 50, sortable: false, align: 'center', editable: true, edittype: 'select', editoptions: {value: {'1':'是','0':'否'}}, formatter: function (a) {return 1 == a ? "是" :"否"}},
                    {name: 'describe', label: "描述", width: 100, sortable: false, editable: true, align: 'center'}
                ],
                viewrecords: true,
                jsonReader: jsonReader,
                cellEdit: true,
                'cellsubmit': 'clientArray'
            });

            $("#table_list_in").on('click', '.inAdd', function () {
                var rowDatas = $("#table_list_in").jqGrid('getDataIDs'),
                    getRowData = $("#table_list_in").getRowData(),
                    length = parseInt(rowDatas[getRowData.length - 1]);
                $('#table_list_in').jqGrid('addRowData', length + 1, {}, 'last');
            });
            $("#table_list_in").on('click', '.inDel', function () {
                var getRowData = $("#table_list_in").getRowData();
                if (getRowData.length > 1) {
                    var id = $(this).parents('tr').attr('id');
                    $('#table_list_in').jqGrid("delRowData", id);
                } else {
                    $.dialog.warn('最少保留一行数据');
                }
            });

            $("#table_list_out").jqGrid({
                data: outData,
                datatype: "local",
                autowidth: true,
                height: "100%",
                styleUI : 'Bootstrap',
                colModel: [
                    {name: 'option', label: "操作", width: 45, sortable: false, align: 'center', formatter: optionsOutFormate},
                    {name: 'ename', label: "英文名", width: 60, sortable: false, editable: true, align: 'center'},
                    {name: 'cname', label: "中文名", width: 60, sortable: false, editable: true, align: 'center'},
                    {name: 'type', label: "类型", width: 60, sortable: false, editable: true, align: 'center'},
                    {name: 'accuracy', label: "精度", width: 60, sortable: false, editable: true, align: 'center'},
                    {name: 'dimension', label: "量纲", width: 40, sortable: false, editable: true, align: 'center'},
                    {name: 'describe', label: "描述", width: 100, sortable: false, editable: true, align: 'center'}
                ],
                viewrecords: true,
                jsonReader: jsonReader,
                cellEdit: true,
                'cellsubmit': 'clientArray'
            });
            function optionsOutFormate(){
                return "<i class='btn p-n-2 fa fa-plus outAdd'></i><i class='btn p-n-2 fa fa-times outDel'></i>";
            }
            $("#table_list_out").on('click', '.outAdd', function () {
                var rowDatas = $("#table_list_out").jqGrid('getDataIDs'),
                    getRowData = $("#table_list_out").getRowData(),
                    length = parseInt(rowDatas[getRowData.length - 1]);
                $('#table_list_out').jqGrid('addRowData', length + 1, {}, 'last');
            });
            $("#table_list_out").on('click', '.outDel', function () {
                var getRowData = $("#table_list_out").getRowData();
                if (getRowData.length > 1) {
                    var id = $(this).parents('tr').attr('id');
                    $('#table_list_out').jqGrid("delRowData", id);
                } else {
                    $.dialog.warn('最少保留一行数据');
                }
            });

            $('#tableTest').on('mouseover', function () {
                var listIn = $("#table_list_in").getRowData(), listOut = $("#table_list_out").getRowData(), inName = [], outName = [], inParameter = [], outParameter = [];
                console.log(listIn);
            });


            function optionsInFormate(){
                return "<i class='btn p-n-2 fa fa-plus inAdd'></i><i class='btn p-n-2 fa fa-times inDel'></i>";
            }
            // $('#addproduct').on('click', function () {
            //     $.ajax({
            //         type: 'post',
            //         url: '/admin/product',
            //         data: {
            //             _token: $('input[name="_token"]').val(),
            //             _method: $('input[name="_method"]').val(),
            //             category_id: $('#pinput').attr('cid'),
            //             name: $('#pname').val()
            //
            //         },
            //         success: function (data) {
            //             window.location.href = "/admin/product";
            //         }
            //     })
            // })

            //提交保存
            $("#save,#check,#online").on('click', function (e) {
                e.preventDefault();
                var categoryId = $("#categoryVal").val(),
                    token = $('input[name="_token"]').val(),
                    ename = $("#ename").val(),
                    cname = $("#cname").val(),
                    url = $("#url").val(),
                    requestSample = $("#request_sample").val(),
                    rebackSample = $("#reback_sample").val();
                // if (!categoryId) return $.dialog.warn("接口分类不能为空");
                // if (!ename) return $.dialog.warn("接口英文名称不能为空");
                // if (!cname) return $.dialog.warn("接口中文名称不能为空");
                // if (!url) return $.dialog.warn("接口地址不能为空");
                // if (!requestSample) return $.dialog.warn("请求样例不能为空");
                // if (!rebackSample) return $.dialog.warn("返回样例不能为空");
                var listIn = $("#table_list_in").getRowData(), listOut = $("#table_list_out").getRowData(), inName = [], outName = [], inParameter = [], outParameter = [];
                $.each(listIn, function (i) {
                    if (inName.indexOf(this.ename) > -1) {
                        // return $.dialog.warn("输入参数英文名称重复");
                        return false;
                    }
                    inName.push(this.ename);
                    inParameter[i] = {ename: this.ename, cname: this.cname, type: this.type, accuracy: this.accuracy, dimension: this.dimension, is_required: this.is_required == '是' ? 1 : 0, describe: this.describe};
                });
                $.each(listOut, function (i) {
                    if (outName.indexOf(this.ename) > -1) {
                        // return $.dialog.warn("输出参数英文名称重复");
                        return false;
                    }
                    outName.push(this.ename);
                    outParameter[i] = {ename: this.ename, cname: this.cname, type: this.type, accuracy: this.accuracy, dimension: this.dimension, describe: this.describe};
                });
                var status = 1;
                if ('check' == $(this).attr('id')) {
                    status = 2;
                } else if('online' == $(this).attr('id')) {
                    status = 3;
                }
                var data = {_token: token,
                        category_id: categoryId,
                        ename: ename,
                        cname: cname,
                        url: url,
                        tag: $("#tag").val(),
                        describe: $("#describe").val(),
                        in_parameter: JSON.stringify(inParameter),
                        out_parameter: JSON.stringify(outParameter),
                        request_sample: requestSample,
                        reback_sample: rebackSample,
                        status: status
                    },
                    url = '/admin/api',
                    type = 'POST';
                if (typeof id !== 'undefined') {
                    url = '/admin/api/' + id;
                    type = 'PUT';
                }
                $.ajax({
                    type: type || "POST",
                    url: url,
                    cache: false,
                    data: data,
                    success: function (data) {
                        if (data.status) {
                            if (id != 0) {
                                return $.dialog.success("修改成功");
                            } else {
                                ($(this).attr('id') == "add" ? $.dialog.success("保存成功") : $.dialog.success("提交成功")) && window.location.reload();
                            }
                        } else {
                            return $.dialog.error(data.info);
                        }
                    },
                    error: function (data) {
                        419 == data.status && location.reload();
                        $.dialog.error('操作失败了哦！');
                    }
                });
            });

            $('#sandbox-container input').datepicker({
                format: "yyyy-mm-dd"
            });

            $('input[name="tag"]').tagEditor();

        })
    </script>
    <script>
        function fileSelected(type) {
            var formData = new FormData();
            if (1 == type) {
                formData.append("file", $("#inputFile")[0].files[0]);
            } else {
                formData.append("file", $("#outputFile")[0].files[0]);
            }
            formData.append("type", type);

            $.ajax({
                {{--url : '{{ route('admin.api.readExcel') }}',--}}
                url: '/admin/api/readExcel',
                type : 'POST',
                async : false,
                data : formData,
                processData : false,  // 告诉jQuery不要去处理发送的数据
                contentType : false,  // 告诉jQuery不要去设置Content-Type请求头
                success : function(data) {
                    if(data.status){
                        if (1 == type) {
                            $('#table_list_in').jqGrid("delRowData", 1);
                            $.each(data.data, function (k, v) {
                                $("#table_list_in").jqGrid("addRowData", k, v);
                            });
                        } else {
                            $('#table_list_out').jqGrid("delRowData", 1);
                            $.each(data.data, function (k, v) {
                                $("#table_list_out").jqGrid("addRowData", k, v);
                            });
                        }
                    }else{
                        return $.dialog.error("上传失败");
                    }
                }
            });
        }
    </script>
@stop
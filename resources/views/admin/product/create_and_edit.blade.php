@extends('admin.layouts.base')

@section('title','控制面板')

@section('css')
    <link rel="stylesheet" href="/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/plugins/jstree/dist/themes/default/style.min.css">
    <link rel="stylesheet" href="/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="/plugins/jQuery-tagEditor/jquery.tag-editor.css">
    <style>
        .cke_top, .cke_bottom { display: none !important }
    </style>
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
                            <h3 class="panel-title">{{ $product['title'] ?? '创建产品' }}</h3>
                        </div>
                        <div class="panel-body">

                            @include('admin.partials.errors')
                            @include('admin.partials.success')
                            <form class="form-horizontal" role="form" method="POST"
                                  action="/admin/product/{{ $product['id'] ?? ''}}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="{{$product['method'] ?? 'POST'}}">
                                <input type="hidden" name="id" value="{{ $product['id'] ?? '' }}">
                                <input type="hidden" name="level" value="4">

                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">产品名称</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="name" id="pname"
                                               value="{{ $product['name'] ?? '' }}"
                                               autofocus>
                                    </div>
                                </div>


                                <div class="form-group">
                                    {{--显示分类--}}
                                    <label for="tag" class="col-md-3 control-label">产品分类</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" rows="3" id="pinput"

                                               data-toggle="modal" data-target="#exampleModal">

                                    </div>
                                </div>

                                {{--实际提交分类--}}
                                <input type="hidden" name="category_id" id="categoryVal"
                                       value="{{$product['category_id'] ?? ''}}">

                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">产品标签</label>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="tag" rows="3" id="url"
                                               value="{{ $product['tags'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-group" id="sandbox-container">
                                    <label class="col-md-3 control-label">更新时间</label>
                                    <div class="col-md-5">
                                        <input name="update_time" type="text" class="form-control" rows="3"
                                               value="{{ $product['update_time'] ?? ''}}">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">Url</label>
                                    <div class="col-md-5">
                                        <input name="url" type="text" class="form-control" rows="3" id="url"
                                               value="{{ $product['url'] ?? ''}}">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">服务方式</label>
                                    <div class="col-md-5">
                                        <input name="service_mode" type="text" class="form-control" rows="3"
                                               id="url" value="{{ $product['service_mode'] ?? ''}}">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">服务商</label>
                                    <div class="col-md-5">
                                        <input name="company" type="text" class="form-control" rows="3" id="url"
                                               value="{{ $product['service_provider'] ?? ''}}">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">咨询电话</label>
                                    <div class="col-md-5">
                                        <input name="hotline" type="text" class="form-control" rows="3" id="url"
                                               value="{{ $product['package_price'] ?? '' }}">

                                    </div>
                                </div>

                                {{--<div class="form-group">--}}
                                {{--<label for="tag" class="col-md-3 control-label">产品图标</label>--}}
                                {{--<div class="col-md-5">--}}
                                {{--<input name="picon" class="form-control" rows="3" id="url">--}}

                                {{--</div>--}}
                                {{--</div>--}}

                                {{--<div class="form-group">--}}
                                {{--<label for="tag" class="col-md-3 control-label">产品简介</label>--}}
                                {{--<div class="col-md-5">--}}
                                {{--<input name="intro" type="text"  class="form-control" rows="3" id="url" value="{{ $product['intro'] ?? ''}}">--}}

                                {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group">
                                    <label for="tag" class="col-md-3 control-label">产品简介</label>
                                    <div class="col-md-9">
                                    <textarea name="editor1" id="editor1" rows="10" cols="80">
                This is my textarea to be replaced with CKEditor.
            </textarea>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-7 col-md-offset-3">
                                        <button type="submit" class="btn btn-primary btn-md">
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

                                                <div id="apiTree">

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
    <script src="/plugins/ckeditor/ckeditor.js"></script>
    <script>
        $(function () {
            // $.ajax({
            //     url: '/admin/product/getcategory',
            //     dataType: 'json',
            //     success: function (data) {
            //        console.log(data);
            //     }
            // })

            CKEDITOR.replace( 'editor1', {
                {{--extraPlugins: 'image2,uploadimage',--}}

                {{--filebrowserBrowseUrl: '',--}}
                {{--filebrowserUploadUrl: '{{ route('uploadImg')  }}?command=QuickUpload&type=Images'--}}
            });

            $("#pinput").on('click', function () {
                $('#apiTree').jstree({
                    'core': {
                        'data': {
                            "animation": 0,
                            "check_callback": true,
                            "themes": {"stripes": true},
                            'url': '/admin/product/getcategory',
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

            $("#apiTree").on('select_node.jstree', function (e, data) {
                node = data.node.original;
            }).on('dblclick', '.jstree-anchor', function (e, data) {
                $('#pinput').val(node.full_name).attr('cid', node.id);
                $('#categoryVal').val(node.id);
                $('#exampleModal').modal('hide');
            });
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

            $('#sandbox-container input').datepicker({
                format: "yyyy-mm-dd"
            });

            $('input[name="tag"]').tagEditor();


        })
    </script>
@stop
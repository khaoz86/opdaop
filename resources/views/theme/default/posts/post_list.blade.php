@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                News And Updates
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">News And Updates</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    @if (session('success_delete'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-check"></i> Success!</h4>
                            {{ session('success_delete') }}
                        </div>
                    @endif
                    <div class="panel panel-default">
                        <div class="panel-body table-responsive">
                            @auth
                                @if(auth()->user()->can('MANAGE_POST'))
                                    <a href="{{ route('news_and_updates.create') }}" class="btn btn-primary margin-bottom">Compose</a>
                                @endif
                            @endauth
                            <table class="table table-bordered table-hover" id="posts-table" style="font-size: small">
                                <thead>
                                <tr>
                                    @auth
                                        @if(auth()->user()->isAdmin())
                                            <th></th>
                                        @endif
                                    @endauth
                                    <th>Title</th>
                                    <th>Post By</th>
                                    <th>Status</th>
                                    <th>Last Update</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        @auth
                            @if(auth()->user()->isAdmin())
                                <div class="panel-footer">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default">Action</button>
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="#" data-toggle="modal" data-target="#modal-delete_post">
                                                    Delete
                                                </a>
                                            </li>
                                            <li><a href="#">Another action</a></li>
                                            <li><a href="#">Something else here</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a></li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->

        @auth
            @if(auth()->user()->isAdmin())
                <div class="modal modal-danger fade" id="modal-delete_post">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Delete Confirmation</h4>
                            </div>
                            <div class="modal-body">
                                <p>Delete Selected Post?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-outline" id="delete_post">Delete</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal remove_reseller -->
            @endif
        @endauth

    </div>
    <!-- /.content-wrapper -->
@endsection

@push('styles')
    <link href="//datatables.yajrabox.com/css/datatables.bootstrap.css" rel="stylesheet">
    <link href="//cdn.datatables.net/select/1.2.3/css/select.dataTables.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/select/1.2.3/js/dataTables.select.min.js"></script>
    <script src="//datatables.yajrabox.com/js/datatables.bootstrap.js"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var oTable = $('#posts-table').DataTable({
                order: [ 4, 'desc' ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('news_and_updates.raw_list') }}',
                    method: 'POST'
                },
                @auth
                @if(auth()->user()->isAdmin())
                columnDefs: [ {
                    searchable: false,
                    orderable: false,
                    className: 'select-checkbox',
                    targets:   0
                } ],
                @endif
                @endauth
                columns: [
                    @auth
                    @if(auth()->user()->isAdmin())
                    { data: 'check', name: 'check' },
                    @endif
                    @endauth
                    { data: 'title', name: 'title' },
                    { data: 'user', name: 'user.username' },
                    { data: 'is_public', name: 'is_public' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'created_at', name: 'created_at' }
                ],
                @auth
                @if(auth()->user()->isAdmin())
                select: {
                    style:    'multi',
                    selector: 'td:first-child'
                }
                @endif
                @endauth
            });
            @auth
            @if(auth()->user()->isAdmin())
            $("#delete_post").click(function () {
                var rowcollection =  oTable.$("tr.selected");
                //var user_ids = [];
                var delete_form_builder  = '';
                rowcollection.each(function(index,elem){
                    //Do something with 'checkbox_value'
                    var post_id = $(this).find(".post_id").val();
                    delete_form_builder += '<input type="hidden" name="post_ids[]" value="' + post_id + '">';
                });
                $('<form id="form_delete_user" action="{{ route('news_and_updates.delete') }}" method="post">')
                    .append('{{ csrf_field() }}')
                    .append(delete_form_builder)
                    .append('</form>')
                    .appendTo($(document.body)).submit();
            });
            @endif
            @endauth
        });
    </script>
@endpush
@extends('theme.default.layouts.panel')

@section('panel_content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Credit Logs
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Account</a></li>
                <li class="active">Credit Logs</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="box box-primary">
                        @include('theme.default.layouts.sidebar.account')
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        @include('theme.default.layouts.menu.account')
                        <div class="tab-content">
                            <div class="active">
                                @if(!auth()->user()->isActive())
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                        Account is Inactive.
                                    </div>
                                @endif
                                @if(auth()->user()->isActive())
                                        <div class="panel-body table-responsive">
                                            <table class="table table-hover" id="account-logs-credit" style="font-size: small">
                                                <thead>
                                                <tr>
                                                    <th>Username</th>
                                                    <th>Type</th>
                                                    <th>Direction</th>
                                                    <th>Credit Used</th>
                                                    <th>Duration</th>
                                                    <th>Credit Before</th>
                                                    <th>Credit After</th>
                                                    <th>DateTime</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                @endif
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>

    <!-- /.content-wrapper -->
@endsection

@if(auth()->user()->isActive())
@push('styles')
    <link href="//datatables.yajrabox.com/css/datatables.bootstrap.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="//datatables.yajrabox.com/js/datatables.bootstrap.js"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#account-logs-credit').DataTable({
                order: [ 7, 'desc' ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('account.credit_logs.list') }}',
                    method: 'POST'
                },
                columns: [
                    { data: 'user_related', name: 'user_related.username' },
                    { data: 'type', name: 'type' },
                    { data: 'direction', name: 'direction' },
                    { data: 'credit_used', name: 'credit_used' },
                    { data: 'duration', name: 'duration' },
                    { data: 'credit_before', name: 'credit_before' },
                    { data: 'credit_after', name: 'credit_after' },
                    { data: 'created_at', name: 'created_at' }
                ]
            });
        });
    </script>
@endpush
@endif
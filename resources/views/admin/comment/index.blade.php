@extends('layouts.admin.master')

@section('title')
    Comment
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Comment</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="category" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">User</th>
                                <th>Product</th>
                                <th>Comment</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
@endsection

@push('script')
    <script>
        let table

        $(function() {
            table = $('.table').DataTable({
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('comment.data') }}'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'user',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'product'
                    },
                    {
                        data: 'content'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    }
                ]
            });
        })
    </script>
@endpush

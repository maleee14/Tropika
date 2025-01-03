@extends('layouts.admin.master')

@section('title')
    Order
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Order</li>
@endsection

@push('style')
    <style>
        table,
        th {
            text-align: center
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                {{-- <div class="box-header">
                    <button onclick="addForm('{{ route('product.store') }}')" class="btn btn-success btn-sm"><i
                            class="fa fa-plus"> Tambah</i></button>
                </div> --}}
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="table-order" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Payment Status</th>
                                <th>Status</th>
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
    @includeIf('admin.order.detail')
@endsection

@push('script')
    <script>
        let table;
        let table2;

        $(function() {
            table = $('#table-order').DataTable({
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('order.data') }}'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'phone',
                        sortable: false
                    },
                    {
                        data: 'grand_total',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'payment_method',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'payment_status',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'status',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    }
                ]
            });

            table2 = $('#table-detail').DataTable({
                processing: true,
                bsort: false,
                dom: 'Brt',
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'unit_amount',
                        sortable: false
                    },
                    {
                        data: 'quantity',
                        sortable: false
                    },
                    {
                        data: 'total_amount',
                        searchable: false,
                        sortable: false
                    },
                ]
            });
        });

        function showDetail(url) {
            $('#modal-detail').modal('show');
            table2.ajax.url(url);
            table2.ajax.reload();
        }
    </script>
@endpush

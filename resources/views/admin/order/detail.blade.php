@extends('layouts.admin.master')

@section('title')
    Order Detail
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Order Detail</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <table>
                        <tr>
                            <td>Costumer</td>
                            <td>: {{ $addresses->firstname }} {{ $addresses->lastname }}</td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td>: {{ $addresses->address }}, {{ $addresses->city }}, {{ $addresses->zip_code }}</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>: {{ $addresses->created_at->format('d F Y') }}</td>
                        </tr>
                    </table>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="table-order" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Product</th>
                                <th>Unit Amount</th>
                                <th>Quantity</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>Rp {{ number_format($item->unit_amount, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
@endsection

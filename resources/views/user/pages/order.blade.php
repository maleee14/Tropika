@extends('layouts.frontend.master')

@section('title')
    My Order
@endsection

@section('content')
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">My Order</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">My Order</li>
        </ol>
    </div>
    <!-- Single Page Header End -->


    <!-- Cart Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Total</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($order->count() > 0)
                            @foreach ($order as $item)
                                <tr>
                                    <td>
                                        <p class="mb-0 mt-4">{{ $loop->iteration }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 mt-4">{{ $item->address->firstname }} {{ $item->address->lastname }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="mb-0 mt-4">{{ $item->address->address }}, {{ $item->address->city }},
                                            {{ $item->address->zip_code }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 mt-4">{{ $item->address->phone }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 mt-4">Rp {{ number_format($item->grand_total, 0, ',', '.') }}</p>
                                    </td>
                                    <td>
                                        <a href="" class="btn btn-md rounded-circle bg-light border mt-4">
                                            <i class="fa fa-eye text-primary"></i>
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" style="text-align: center">
                                    <h2>No Order</h2>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Cart Page End -->
@endsection

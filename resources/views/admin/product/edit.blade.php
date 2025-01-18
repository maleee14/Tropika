@extends('layouts.admin.master')

@section('title')
    Create Product
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Create Product</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        @method('put')
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name"
                                value="{{ $product->name }}">
                            @error('name')
                                <i class="text text-danger">{{ $message }}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="form-control">
                                @foreach ($category as $key => $item)
                                    <option value="{{ $key }}"
                                        {{ $product->category_id == $key ? 'selected' : '' }}>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <i class="text text-danger">{{ $message }}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" name="price"
                                id="price"value="{{ $product->price }}">
                            @error('price')
                                <i class="text text-danger">{{ $message }}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" class="form-control" name="stock" id="stock"
                                value="{{ $product->stock }}">
                            @error('stock')
                                <i class="text text-danger">{{ $message }}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" name="image" id="image">
                            @error('image')
                                <i class="text text-danger">{{ $message }}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="3">{{ $product->description }}</textarea>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
@endsection

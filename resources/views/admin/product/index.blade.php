@extends('layouts.admin.master')

@section('title')
    Product
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Product</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <button onclick="addForm('{{ route('product.store') }}')" class="btn btn-success btn-sm"><i
                            class="fa fa-plus"> Tambah</i></button>
                    {{-- <a href="{{ route('product.create') }}" class="btn btn-success btn-sm"><i class="fa fa-plus">
                            Tambah</i></a> --}}
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="product" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
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
    @includeIf('admin.product.form')
@endsection

@push('script')
    <script>
        let table;

        $(function() {
            table = $('.table').DataTable({
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('product.data') }}'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'image'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'category'
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'stock'
                    },
                    {
                        data: 'action',
                        searchable: false,
                        sortable: false
                    }
                ]
            });

            $('#modal-form').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                //Untuk pengiriman form dengan file, Perlu menggunakan FormData dan mengatur contentType dan processData 
                // di AJAX request agar pengiriman file dapat diproses dengan benar.

                let formData = new FormData($('#modal-form form')[0]);

                $.ajax({
                    type: 'POST',
                    url: $('#modal-form form').attr('action'),
                    data: formData,
                    contentType: false, // Membiarkan browser mengatur header Content-Type yang benar
                    processData: false, // Mencegah jQuery mengubah data menjadi query string
                    success: function(response) {
                        $('#modal-form').modal('hide');
                        $('.text-danger').remove();
                        table.ajax.reload();
                    },
                    error: function(errors) {
                        if (errors.status === 422) {
                            // Validation error, process and display the errors
                            let response = errors.responseJSON;
                            displayFormErrors(response.errors);
                        } else {
                            alert('Tidak dapat menyimpan data');
                        }
                    }
                });
            });

            function displayFormErrors(errors) {
                // Clear previous error messages
                $('.text-danger').remove();

                // Loop through each error and display it in the form
                $.each(errors, function(field, messages) {
                    let fieldElement = $('[name="' + field + '"]');
                    let errorMessage = $('<span class="text text-danger">' + messages[0] + '</span>');
                    fieldElement.after(errorMessage);
                });
            }
        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Add Product');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=name]').focus();
            $('.text-danger').remove();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Product');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=name]').focus();
            $('.text-danger').remove();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=name]').val(response.name);
                    $('#modal-form [name=category_id]').val(response.category_id);
                    $('#modal-form [name=price]').val(response.price);
                    $('#modal-form [name=stock]').val(response.stock);
                    $('#modal-form [name=description]').val(response.description);
                })
                .fail((errors) => {
                    alert('Tidak Dapat Menampilkan Data');
                });
        }
    </script>
@endpush

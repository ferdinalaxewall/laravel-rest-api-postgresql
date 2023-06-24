@extends('layout.master')

@section('title', 'Halaman Produk')

@section('content')
<div class="table-responsive">
    <table class="table table-striped" id="product-table">
        <thead>
            <tr>
                <th class="bg-dark text-white">ID Produk</th>
                <th class="bg-dark text-white">Nama Produk</th>
                <th class="bg-dark text-white">Stok Saat Ini</th>
                <th class="bg-dark text-white">Stok Terjual</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function(){
        $.ajax({
            method : 'GET',
            url : '{{ route('api.produk.findAll') }}',
            success: (response) => {
                const { data } = response;

                $("#product-table tbody").empty();
                data.map(order => {
                    const tableRowHtml = `<tr>
                        <td>${order.produk_id}</td>    
                        <td>${order.nama_produk ?? "-"}</td>    
                        <td>${order.stok_sekarang}</td>    
                        <td>${order.stok_terjual}</td>    
                    </tr>`

                    $("#product-table").append(tableRowHtml);
                });
            },
            error: (response) => {
                console.error(response)
                closeLoader();
            }
        }).then(() => {
            $("#product-table").DataTable();
            closeLoader();
        });
    })
</script>
@endpush
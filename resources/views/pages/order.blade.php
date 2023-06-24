@extends('layout.master')

@section('title', 'Halaman Pesanan')

@section('content')
<div class="table-responsive">
    <table class="table table-striped" id="order-table">
        <thead>
            <tr>
                <th class="bg-dark text-white">ID Pesanan</th>
                <th class="bg-dark text-white">No. Pesanan</th>
                <th class="bg-dark text-white">Tgl Pesanan</th>
                <th class="bg-dark text-white">Nama Lengkap User</th>
                <th class="bg-dark text-white">Total Harga</th>
                <th class="bg-dark text-white">Jumlah Produk</th>
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
            url : '{{ route('api.pesanan.findAll') }}',
            success: (response) => {
                const { data } = response;

                $("#order-table tbody").empty();
                data.map(order => {
                    const tableRowHtml = `<tr>
                        <td>${order.pesanan_id}</td>    
                        <td>${order.no_pesanan ?? "-"}</td>    
                        <td>${order.tgl_pesanan}</td>    
                        <td>${order.nama_lengkap_user}</td>    
                        <td>${order.total_harga}</td>    
                        <td>${order.jumlah_produk}</td>    
                    </tr>`

                    $("#order-table").append(tableRowHtml);
                });
            },
            error: (response) => {
                console.error(response)
                closeLoader();
            }
        }).then(() => {
            $("#order-table").DataTable();
            closeLoader();
        });
    })
</script>
@endpush
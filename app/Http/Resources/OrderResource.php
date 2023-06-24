<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'pesanan_id' => $this->pesanan_id,
            'nama_lengkap_user' => $this->user->nama_depan . ' ' . $this->user->nama_belakang,
            'kode_voucher' => $this->kode_voucher,
            'no_pesanan' => $this->no_pesanan,
            'tgl_pesanan' => $this->tgl_pesanan,
            'tgl_pembayaran_lunas' => $this->tgl_pembayaran_lunas,
            'tgl_dibatalkan' => $this->tgl_dibatalkan,
            'total_harga' => $this->productOrders->map(function ($item){
                return ['subtotal' => $item->jumlah * $item->product->harga];
            })->sum('subtotal'),
            'jumlah_produk' => $this->productOrders->sum('jumlah'),
        ];
    }
}

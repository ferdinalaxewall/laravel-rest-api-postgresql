<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'produk_id' => $this->produk_id,
            'nama_produk' => $this->nama,
            'brand' => $this->brand,
            'harga' => $this->harga,
            'slug' => $this->slug,
            'stok_sekarang' => !is_null($this->detail) ? $this->detail->stok : 0,
            'stok_terjual' => $this->orders->sum('jumlah'),
            'tgl_dibuat' => $this->tgl_dibuat,
            'tgl_diubah' => $this->tgl_diubah,
            'tgl_release' => $this->tgl_release,
        ];
    }
}

<?php

namespace App\Livewire;

use App\Models\Shoe;
use Livewire\Component;
use App\Services\OrderService;

class OrderForm extends Component
{
    public Shoe $shoe;
    public $orderData;
    public $subTotalAmount;
    public $promoCode = null;
    public $promoCodeId = null;
    public $quantity = 1;
    public $discount = 0;
    public $grandTotalAmount = 0;
    public $totalDiscountAmount;
    public $name;
    public $email;

    protected $orderService;

    public function boot(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    //untuk state awal saat halaman di render
    public function mount(Shoe $shoe ,$orderData) {
        $this->shoe = $shoe;
        $this->orderData = $orderData;
        $this->subTotalAmount = $shoe->price;
        $this->grandTotalAmount = $shoe->price;
    }

    public function updatedQuantity() {
        $this->validateOnly('quantity', [
            'quantity' => 'required|integer|min:1|max:' . $this->shoe->stock,
        ],[
            'quantity.max' => 'Stock Tidak Tersedia',
        ]);

        $this->calculateTotal();
    }

    public function calculateTotal(): void {
        $this->subTotalAmount = $this->shoe->price * $this->quantity;
        $this->grandTotalAmount = $this->subTotalAmount - $this->discount;
    }

    //fungsi untuk tombol tambah stok/quantity
    public function incrementQuantity() {
        if($this->quantity < $this->shoe->stock) {
            $this->quantity++;
            $this->calculateTotal();
        }
    }

    //fungsi untuk tombol kurangi stok/quantity
    public function decrementQuantity() {
        if($this->quantity > 1) {
            $this->quantity--;
            $this->calculateTotal();
        }
    }

    public function updatedPromoCode() {
        $this->applyPromoCode();
    }

    //fungsi untuk menerapkan kode promo
    public function applyPromoCode() {
        //jika promo code tidak ada maka reset diskonnya dengan menjalankan method resetDiscount()
        if(!$this->promoCode) {
            $this->resetDiscount();
        }

        //jalankan service applyPromoCode dengan meletakkan value dari promo code dan subtotalamount
        $result = $this->orderService->applyPromoCode($this->promoCode, $this->subTotalAmount);

        //jikalau error atau tidak tersedia
        if(isset($result['error'])) {
            //maka tampilkan pesan flash dengabn menampilkan isi dari variable error yang ada di service applyPromoCode()
            session()->flash('error', $result['error']);
            //dan reset discount
            $this->resetDiscount();
        } else {
            //jikalau promo code ada
            //maka tampilkan pesan flash di bawah ini
            session()->flash('message', 'Kode Promo Tersedia Yeay!');
            // setelah itu ganti property discount dengan variable discount yang ada di service
            $this->discount = $result['discount'];
            // setelah itu kalkulasi kan totalnya
            $this->calculateTotal();
            //// setelah itu ganti property promoCodeId dengan variable promoCodeId yang ada di service
            $this->promoCodeId = $result['promoCodeId'];
            // setelah itu ganti property totalDiscountAmount dengan variable discount yang ada di service
            $this->totalDiscountAmount = $result['discount'];
        }
    }

    public function resetDiscount() {
        $this->discount = 0;
        $this->calculateTotal();
        $this->promoCodeId = null;
        $this->totalDiscountAmount = 0;
    }

    public function render()
    {
        return view('livewire.order-form');
    }
}

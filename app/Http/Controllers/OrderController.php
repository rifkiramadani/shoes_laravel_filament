<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Models\ProductTransaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\StoreCustomerDataRequest;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    //ketika pengguna menekan tombol 'continue' di halaman details sepatu
    public function saveOrder(StoreOrderRequest $request, Shoe $shoe) {
        //validasi request dari user
        $validated = $request->validated();

        //ambil id sepatu sebagai patokan
        $validated['shoe_id'] = $shoe->id;

        //jalankan service begin order yaitu berfungsi untuk menyimpan data yang di inputkan
        //di detail sepatu seperti sepatu yang di pilih dan ukuran sepatu ke dalam session
        $this->orderService->beginOrder($validated);

        return redirect()->route('front.booking', $shoe->slug);
    }

    //untuk halaman booking
    public function booking() {
        $data = $this->orderService->getOrderDetails();
        dd($data);
        return view('order.order', $data);
    }

    //untuk halaman detail dari booking yaitu delivery
    public function customerData() {
        $data = $this->orderService->getOrderDetails();
        return view('order.customer_data', $data);
    }

    //ketika user menekan tombol continue, session langsung diperbarui
    public function saveCustomerData(StoreCustomerDataRequest $request) {
        $validated = $request->validated();
        $this->orderService->updateCustomerData($validated);

        return redirect()->route('front.payment');
    }

    //untuk halaman payment yaitu final review dari yang harus dibayarkan
    public function payment() {
        $data = $this->orderService->getOrderDetails();
        return view('front.payment');
    }

    public function paymentConfirm(StorePaymentRequest $request) {
        $validated = $request->validated();
        $productTransactionId = $this->orderService->paymentConfirm();

        if($productTransactionId) {
            return redirect('front.order_finished', $productTransactionId);
        }

        return redirect('front.index')->withErrors(['error' => 'payment failed. Please try again.']);
    }

    public function orderFinished(ProductTransaction $productTransaction) {
        dd($productTransaction);
    }
}

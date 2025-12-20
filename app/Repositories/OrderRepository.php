<?php
namespace App\Repositories;

use App\Models\ProductTransaction;
use App\Repositories\Contracts\ShoeRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface {
    public function createTransaction($data) {
        return ProductTransaction::create($data);
    }

    public function findByIdAndPhoneNumber($bookingTrxId, $phoneNumber) {
        return ProducTransaction::where('booking_trx_id', $bookingTrxId)
                                ->where('phone', $phoneNumber)
                                ->first();
    }

    public function saveToSession($data) {
        Session::put('orderData', $data);
    }

    public function getOrderDataFromSession() {
        return session('orderData', []);
    }

    public function updateSessionData($data) {
        //ambil data session
        $orderData = session('orderData',[]);
        //gabungkan data lama dengan data baru tambahan
        $orderData = array_merge($orderData, $data);
        //simpan ke data tersebut ke session
        session(['orderData' => $orderData]);
    }
}

?>

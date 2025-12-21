<?php
namespace App\Repositories;

use App\Models\ProductTransaction;
use Illuminate\Support\Facades\Session;
use App\Repositories\Contracts\OrderRepositoryInterface;
// use App\Repositories\Contracts\ShoeRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface {
    public function createTransaction($data) {
        return ProductTransaction::create($data);
    }

    //untuk check transaction nanti
    public function findByTrxIdAndPhoneNumber($bookingTrxId, $phoneNumber) {
        return ProducTransaction::where('booking_trx_id', $bookingTrxId)
                                ->where('phone', $phoneNumber)
                                ->first();
    }

    //simpan ke session
    public function saveToSession($data) {
        Session::put('orderData', $data);
    }

    //ambil data sesson yang telah disimpan
    public function getOrderDataFromSession() {
        return session('orderData', []);
    }

    //update session atau perbarui data session
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

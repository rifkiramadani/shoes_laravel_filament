<?php
namespace App\Repositories\Contracts;

interface OrderRepositoryInterface {
    //membuat transaksi
    public function createTransaction(array $data);

    //mengambil data transaksi berdasarkan $bookingTrxId dan $phoneNumber
    public function findByTrxIdAndPhoneNumber($bookingTrxId, $phoneNumber);

    //simpan data transaksi ke session
    public function saveToSession(array $data);

    //fungsi untuk update data yang telah di simpan ke session sebelumnya misalnya user memilih sepatu dan ukuran setelah itu masuk ke
    //order details dan ada data yang harus di simpan lagi maka otomatis session harus mengupdate data baru
    public function updateSessionData(array $data);

    //fungsi untuk mengambil data order dari session (untuk menampilkan data dari session)
    public function getOrderDataFromSession();

}



?>

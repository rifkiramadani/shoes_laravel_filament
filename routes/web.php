<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\OrderController;


Route::get('/', [FrontController::class, 'index'])->name('front.index');

Route::get('/browse/{category:slug}', [FrontController::class, 'category'])->name('front.category');

Route::get('/details/{shoe:slug}', [FrontController::class, 'details'])->name('front.details');

//memulai menyimpan data sepatu ke session seperti sepatu yang dipilih dan ukurannya
Route::post('/order/begin/{shoe:slug}', [OrderController::class, 'saveOrder'])->name('front.saveOrder');

//setelah memilih data sepatu dan menyimpan nya ke session maka di akan di arahkan ke halaman booking yang dimana
//untuk mengisi data diri pembeli yang sudah memilih sepatu tersebut (nama, email, jumlah atau qty sepatu dan bahkan kode promo)
Route::get('/order/booking', [OrderController::class, 'booking'])->name('front.booking');

//setelah data diri di isi maka di arahkan ke halman delivery akan mengisikan (address, phone number, city dan post code)
Route::get('/order/booking/customer-data', [OrderController::class, 'customerData'])->name('front.customerData');
//simpan semua data yang sudah di input kan tadi ke db setelah di session
Route::post('/order/booking/customer-data/save', [OrderController::class, 'saveCustomerData'])->name('front.saveCustomerData');

//mengarahkan ke halaman payment yang dimana berisikan detail dari jumlah yang harus di bayarkan
//dan juga harus mengisikan proof atau bukti transfer dengan mengupload kan gambar bukti transfer
Route::get('/order/payment', [OrderController::class, 'payment'])->name('front.payment');
//simpan data proof tadi ke db
Route::post('/order/payment/confirm', [OrderController::class, 'paymentConfirm'])->name('front.paymentConfirm');

//terakhir arahkan ke halaman finish order menggunakan id dari productTransaction
Route::get('/order/finished/{productTransaction:id}', [OrderController::class, 'orderFinished'])->name('front.orderFinished');

//route untuk mengarahkan ke form check booking
Route::get('/check-booking', [OrderController::class, 'checkBooking'])->name('front.checkBooking');
//route untuk mengambil detail booking
Route::post('/check-booking/details', [OrderController::class, 'checkBookingDetails'])->name('front.checkBookingDetails');


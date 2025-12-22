<?php
namespace App\Services;

use App\Models\ProductTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PromoCodeRepositoryInterface;
use App\Repositories\Contracts\ShoeRepositoryInterface;

class OrderService {
    protected $categoryRepository;
    protected $orderRepository;
    protected $promoCodeRepository;
    protected $shoeRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        OrderRepositoryInterface $orderRepository,
        PromoCodeRepositoryInterface $promoCodeRepository,
        ShoeRepositoryInterface $shoeRepository,
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->orderRepository = $orderRepository;
        $this->promoCodeRepository = $promoCodeRepository;
        $this->shoeRepository = $shoeRepository;
    }

    //fungsi untuk memulai order saat user menekan tombol buy now di product details
    public function beginOrder(array $data) {
        //buat kerangka awal session berisikan sepatu dan ukuran sepatu yang di pilih di shoe details
        $orderData = [
            'shoe_size' => $data['shoe_size'],
            'size_id' => $data['size_id'],
            'shoe_id' => $data['shoe_id']
        ];

        //setelah itu simpan kerangka session tersebut di session menggunakan method
        //saveToSession() menggunakan variable $orderData yang berisikan kerangka session awal yang di buat
        $this->orderRepository->saveToSession($orderData);
    }

    public function getOrderDetails() {
        //ambil kembali session yang telah dibuat sebelum nya yaitu di method begin order
        $orderData = $this->orderRepository->getOrderDataFromSession();
        //ambil juga sepatu berdasarkan id yang sudah dipilih dari session yang disimpan
        $shoe = $this->shoeRepository->find($orderData['shoe_id']);

        $quantity = isset($orderData['quantity']) ? $orderData['quantity'] : 1;
        $subTotalAmount = $shoe->price * $quantity; //1jt

        $taxRate = 0.11;
        $totalTax = $subTotalAmount * $taxRate; //1jt * 0.11 = 110.000

        $grandTotalAmount = $subTotalAmount + $totalTax; //1jt + 110.000 = 1.110.0000

        //tambahkan data lagi pada session berisikan data dibawah ini
        $orderData['sub_total_amount'] = $subTotalAmount;
        $orderData['total_tax'] = $totalTax;
        $orderData['grand_total_amount'] = $grandTotalAmount;

        return compact('orderData', 'shoe');
    }

    public function applyPromoCode(string $code, int $subTotalAmount) {
        //ambil discount berdasarkan kode
        $promo = $this->promoCodeRepository->findByCode($code);

        //jika kode promo ada maka
        if($promo) {
            // ambil data diskon
            $discount = $promo->discount_amount;
            //setelah itu kurang sub total dengan discount
            $grandTotalAmount = $subTotalAmount - $discount;
            //setelah itu ambil id promo code nya
            $promoCodeId = $promo->id;

            // terakhir ambil semua data nya ($discount, $grandTotalAmount, $promoCodeId)
            return [
                'discount' => $discount,
                'grandTotalAmount' => $grandTotalAmount,
                'promoCodeId' => $promoCodeId
            ];
        }

        //kalaau promo code tidak ada maka buatkan variable yang berisikan pesan error
        return ['error' => 'Kode Promo Tidak Tersedia'];
    }

    //setelah semua method order di jalankan PILIH SALAH SATU ingin save session
    //atau update session lagi yaitu mengedit data session (data customer)

    // SIMPAN SESSION (SAVE SESSION)
    public function saveBookingTransaction(array $data) {
        $this->orderRepository->saveToSession($data);
    }

    //PERBARUI SESSION (UPDATE SESSION)
    public function updateCustomerData(array $data) {
        $this->orderRepository->updateSessionData($data);
    }

    public function paymentConfirm(array $validated) {
        //ambil data dari session yang telah di buat
        $orderData = $this->orderRepository->getOrderDataFromSession();

        //set $productTransactionId awal ke null
        $productTransactionId = null;

        try {//closure based transaction
            DB::transaction(function () use($validated, $orderData, $productTransactionId) {
                //fungsi untuk upload gambar
                if(isset($validated['proof'])) {
                    $proofPath = $validated['proof']->store('proofs', 'public');
                    $validated['proof'] = $proofPath;
                };

                //ambil tiap tiap data dari session dan masukkan ke tiap tiap attribute table db
                $validated['name'] = $orderData['name'];
                $validated['email'] = $orderData['email'];
                $validated['phone'] = $orderData['phone'];
                $validated['address'] = $orderData['address'];
                $validated['post_code'] = $orderData['post_code'];
                $validated['city'] = $orderData['city'];
                $validated['quantity'] = $orderData['quantity'];
                $validated['sub_total_amount'] = $orderData['sub_total_amount'];
                $validated['grand_total_amount'] = $orderData['grand_total_amount'];
                $validated['discount_amount'] = $orderData['discount_amount'];
                $validated['promo_code_id'] = $orderData['promo_code_id'];
                $validated['shoe_id'] = $orderData['shoe_id'];
                $validated['shoe_size'] = $orderData['shoe_size'];
                $validated['is_paid'] = $orderData['is_paid'];
                $validated['booking_trx_id'] = ProductTransaction::generateUniqueTrxId();

                $validated['is_paid'] = false;
                //setelah itu masukkan data tadi ke dalam db
                $newTransaction = $this->orderRepository->createTransaction($validated);

                //ambil id dari transaksi baru untuk di tampilkan di halaman sukses
                $productTransactionId = $newTransaction->id;

            });
        } catch (\Exception $e) {
            Log::error("Error In Payment Confirmation: " . $e->getMessage());
            session()->flash('error', $e->getMessage());
            return null;
        }

        //kasih nilai kembalian dari productTransactionId agar bisa di pakai id nya
        return $productTransactionId;
    }

}


?>

<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Keranjang;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    //
    public function index()
    {
        $keranjang = DB::table('keranjang')
            ->join('products', 'keranjang.kode_produk', '=', 'products.id')
            ->select('keranjang.*', 'products.harga', 'products.nama_produk')
            ->get();
        return response()->json([
            'status' => 200,
            'messages' => 'Success',
            'data' => $keranjang
        ], 200);
    }

    public function store(Request $request)
    {
        $keranjang = new Keranjang;
        $keranjang->kode_produk = $request->kode_produk;
        $keranjang->quantity = $request->quantity;
        $keranjang->total_produk = $request->total_produk;
        $keranjang->save();

        return response()->json([
            'status' => 200,
            'messages' => 'Keranjang Added',
            'data' => $keranjang
        ], 200);
    }

    public function sumkeranjang()
    {
        $total = Keranjang::sum('total_produk');
        if ($total != 0) {
            return response()->json([
                'status' => 200,
                'messages' => 'Ada keranjang',
                'data' => $total
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'messages' => 'Tidak ada keranjang',
            ], 404);
        }
    }

    public function deleteKeranjang()
    {
        Keranjang::truncate();
        return response()->json([
            'status' => 200,
            'messages' => 'Keranjang Deleted',
        ], 200);
    }

    public function processKeranjang(Request $request)
    {
        // memindahkan keranjang -> detail transaksi
        $detail = DB::transaction(function () use ($request) {
            $dataFromKeranjang = DB::table('keranjang')->select('kode_produk', 'quantity')->get();
            $detailData = []; // Array to store detail data

            foreach ($dataFromKeranjang as $data) {
                $databaru = (array)$data; // Convert stdClass object to array
                $databaru['kode_transaksi'] = $request->kode_transaksi;
                $detailData[] = DetailTransaksi::create($databaru);
            }

            return $detailData; // Return the detail data
        });

        // membuat row transaksi baru
        $transactions = new Transactions;
        $now = Carbon::now();
        $totalamount = Keranjang::sum('total_produk');
        $transactions->kode_transaksi = $request->kode_transaksi;
        $transactions->transaction_date = $now->toDateString();
        $transactions->transactions_time = $now->toTimeString();
        $transactions->total_amount = $totalamount;
        $transactions->branch = $request->branch; // Assumed 'brManch' was a typo
        $transactions->save();

        // delete all rows in table keranjang
        Keranjang::truncate();

        // Attach detail data to the transactions object
        $transactions->item = $detail;

        return response()->json([
            'status' => 200,
            'messages' => 'Keranjang berhasil dimasukkan ke transaksi',
            'data' => $transactions,
        ], 200);
    }
}

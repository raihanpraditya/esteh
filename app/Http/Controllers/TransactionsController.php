<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    //
    public function index()
    {
        $dataFromTransactions = DB::transaction(function () {
            $transactions = DB::table('transactions')->select('*')->get();

            foreach ($transactions as $transaction) {
                $kodetransaksi = $transaction->kode_transaksi;
                $detail = DB::table('detail_transaksi')
                    ->join('products', 'detail_transaksi.kode_produk', '=', 'products.id')
                    ->select('products.nama_produk', 'detail_transaksi.quantity')
                    ->where('kode_transaksi', $kodetransaksi)
                    ->get();
                $transaction->item = $detail; // Add as a property to each transaction
            }

            return $transactions; // Return the modified transactions
        });

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $dataFromTransactions
        ]);
    }
    public function show($id)
    {
        $dataFromTransactions = DB::transaction(function () use ($id) {
            $transactions = DB::table('transactions')
                ->where('kode_transaksi', $id)
                ->select('*')
                ->first();

            if ($transactions) {
                $detail = DB::table('detail_transaksi')
                    ->join('products', 'detail_transaksi.kode_produk', '=', 'products.id')
                    ->select('products.nama_produk', 'detail_transaksi.quantity')
                    ->where('kode_transaksi', $id)
                    ->get();
                $transactions->item = $detail; // Attach the detail directly to the transaction object
            }

            return $transactions;
        });

        if ($dataFromTransactions) {
            return response()->json([
                'status' => 200,
                'message' => 'Success',
                'data' => $dataFromTransactions
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Transaction not found',
            ]);
        }
    }
    public function setPaid($id)
    {
        $setpaid = DB::table('transactions')
            ->where('kode_transaksi', $id)
            ->update([
                'status_pembayaran' => 'paid',
            ]);
        if ($setpaid) {
            $dataFromTransactions = DB::transaction(function () use ($id) {
                $transactions = DB::table('transactions')
                    ->where('kode_transaksi', $id)
                    ->select('*')
                    ->first();

                if ($transactions) {
                    $detail = DB::table('detail_transaksi')
                        ->join('products', 'detail_transaksi.kode_produk', '=', 'products.id')
                        ->select('products.nama_produk', 'detail_transaksi.quantity')
                        ->where('kode_transaksi', $id)
                        ->get();
                    $transactions->item = $detail; // Attach the detail directly to the transaction object
                }

                return $transactions;
            });
            if ($dataFromTransactions) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Success',
                    'data' => $dataFromTransactions
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Transaction not found',
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'messages' => 'record not found',
            ], 404);
        }
    }
}

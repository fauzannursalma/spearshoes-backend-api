<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        if (request()->ajax()) {
            $query = Transaction::with(['user']);

            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                        <a href="' . route('dashboard.transaction.show', $item->id) . '" class="inline-block border border-blue-500 bg-blue-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-blue-600 focus:outline-none focus:shadow-outline">
                            <i class="fas fa-receipt"></i> Transaction
                        </a>

                        <a href="' . route('dashboard.transaction.edit', $item->id) . '" class="inline-block border border-gray-500 bg-green-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-green-600 focus:outline-none focus:shadow-outline">
                            <i class="fas fa-edit text-lg"></i> Edit
                        </a>';
                })
                ->editColumn('total_price', function ($item) {
                    return number_format($item->total_price);
                })
                ->editColumn('created_at', function ($item) {
                    return $item->created_at->format('d F Y H:i');
                })
                ->editColumn('status', function ($item) {
                    $color = '';
                    switch ($item->status) {
                        case 'PENDING':
                            $color = 'gray';
                            break;
                        case 'SHIPPING':
                        case 'SHIPPED':
                            $color = 'blue';
                            break;
                        case 'SUCCESS':
                            $color = 'green';
                            break;
                        case 'CANCELLED':
                        case 'FAILED':
                            $color = 'red';
                            break;
                        default:
                            $color = 'gray';
                            break;
                    }
                    
                    return '<span class="inline-block border border-' . $color . '-500 bg-' . $color . '-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-' . $color . '-600 focus:outline-none focus:shadow-outline">' . $item->status . '</span>';
                })
                ->rawColumns(['action', 'status'])
                ->make();
        }
        return view('pages.dashboard.transaction.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction, Product $product)
    {
        if(request()->ajax()){
            $query = TransactionItem::with(['product'])->where('transactions_id', $transaction->id);

            return DataTables::of($query)
                    ->editColumn('product.price', function($item){
                        return number_format($item->product->price);
                    })
                    ->make();
        }
        return view('pages.dashboard.transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        return view('pages.dashboard.transaction.edit',[
            'item' => $transaction
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $data = $request->all();

        $transaction->update($data);

        return redirect()->route('dashboard.transaction.index')->with('success', 'Transaction has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}

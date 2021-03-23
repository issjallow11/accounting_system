<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;

class TransactionController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        // $this->authorize('isAdmin');

        $credits = Transaction::latest()->paginate(10);

        return $this->sendResponse($credits, 'Transaction list');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credit = Transaction::create([
            'date' => $request['date'],
            'receipt_no' => $request['receipt_no'],
            'customer_name' => $request['customer_name'],
            'payment' => $request['payment'],
            'payment_date' => $request['payment_date'],

        ]);
        $accountUpdate = Account::find(1);
        $accountUpdate->update(['amount'=>$accountUpdate->amount + $credit->payment]);


        return $this->sendResponse($credit, 'Account Credited Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $credit = Transaction::findOrFail($id);
        // delete the user

        $credit->delete();

        return $this->sendResponse([$credit], 'Transaction successfully deleted');
    }
}

<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Debit;
use App\Models\Account;
use Illuminate\Http\Request;

class DebitController extends BaseController
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

        $debits = Debit::latest()->paginate(10);

        return $this->sendResponse($debits, 'Transaction list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credit = Debit::create([

            'account_type' => $request['account_type'],
            'date' => $request['date'],
            'receipt_no' => $request['receipt_no'],
            'customer_name' => $request['customer_name'],
            'payment' => $request['payment'],
            'payment_date' => $request['payment_date'],

        ]);
        $accountUpdate = Account::find(1);
        $accountUpdate->update(['amount'=>$accountUpdate->amount - $credit->payment]);
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
        //
    }
}

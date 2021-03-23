<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\SubAccount;

use Illuminate\Http\Request;

class SubAccountController extends BaseController
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
        $subAccounts = SubAccount::latest()->paginate(10);
        return $this->sendResponse($subAccounts, 'Sub Accounts  list');
    }
    public function Credits(){
        // $subAccounts = SubAccount::where('type','inward_payment')->orderBy('name')->paginate(10)->get();
        $subAccounts = SubAccount::where('type','inward_payment')->latest()->paginate(10);
        return $this->sendResponse($subAccounts, 'Sub Accounts  list');    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $account = SubAccount::create([
            'name' => $request->get('name'),
            'type' => $request->get('type'),

        ]);

        return $this->sendResponse($account, 'Sub Account Created Successfully');

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

        $subAccount = SubAccount::findOrFail($id);
        // delete the subaccount

        $subAccount->delete();

        return $this->sendResponse([$subAccount], 'Sub Account has been Deleted');
    }
}

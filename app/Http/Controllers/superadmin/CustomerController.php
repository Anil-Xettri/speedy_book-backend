<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomerController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Customer';
        $this->resources = 'superadmin.customers.';
        parent::__construct();
        $this->route = 'customers.';
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function ($data) {
                    $imgUrl = $data->profile_image ? asset($data->profile_image) : asset('images/user-placeholder.png');
                    return '<a target="_blank" href="' . $imgUrl . '"><img style="height: 30%; width: 30%; object-fit: contain" src="' . $imgUrl . '" alt="logo"></a>';
                })
                ->rawColumns(['image'])
                ->make(true);
        }

        $info = $this->crudInfo();
        $info['hideCreate'] = true;
        return view($this->indexResource(), $info);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info['item'] = Customer::findOrFail($id);
        return redirect()->route($this->showResource(), $info);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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

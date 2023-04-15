<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PaymentController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Payment';
        $this->resources = 'vendors.payments.';
        parent::__construct();
        $this->route = 'payments.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Payment::where('vendor_id', auth('vendor')->user()->id)->orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('customer_id', function ($data) {
                    if ($data->booking) {
                        return $data->booking->customer ? $data->booking->customer->name : '-';
                    } else {
                        return '-';
                    }
                })
                ->editColumn('email', function ($data) {
                    if ($data->booking) {
                        return $data->booking->customer ? $data->booking->customer->email : '-';
                    } else {
                        return '-';
                    }
                })
                ->editColumn('booking_id', function ($data) {
                    return $data->booking ? '<a target="_blank" href="' . route('bookings.show', $data->booking->id) . '">#' . $data->booking->id . '</a>' : '-';
                })
                ->editColumn('created_at', function ($data) {
                    return $data->created_at ? $data->created_at->diffForHumans() : '-';
                })
                ->rawColumns(['created_at', 'customer_id', 'email', 'booking_id'])
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

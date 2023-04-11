<?php

namespace App\Http\Controllers\vendor;

use App\Helpers\NotifyHelper;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Theater;
use App\Models\Movie;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BookingController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Booking';
        $this->resources = 'vendors.bookings.';
        parent::__construct();
        $this->route = 'bookings.';
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Booking::where('vendor_id', auth('vendor')->user()->id)->orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('customer_id', function ($data) {
                    return $data->customer ? $data->customer->name : '-';
                })
                ->editColumn('theater_id', function ($data) {
                    return $data->theater ? '<a target="_blank" href="' . route('theaters.show', $data->theater->id) . '">' . $data->theater->name . '</a>' : '-';
                })
                ->editColumn('movie_id', function ($data) {
                    return $data->movie ? '<a target="_blank" href="' . route('movies.show', $data->movie->id) . '">' . $data->movie->title . '</a>' : '-';
                })
                ->editColumn('show_time', function ($data) {
                    return $data->show_time ?: '-';
                })
                ->addColumn('action', function ($data) {
                    return view('templates.index_actions', [
                        'id' => $data->id, 'route' => $this->route, 'hideEdit' => true
                    ])->render();
                })
                ->rawColumns(['action', 'theater_id', 'movie_id', 'customer_id', 'show_time'])
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
//        $info = $this->crudInfo();
//        $info['cinemaHalls'] = Theater::where(['vendor_id' => auth('vendor')->user()->id, 'status' => 'Active'])->with('movies')->get();
//        $info['movies'] = Movie::where(['vendor_id' => auth('vendor')->user()->id, 'status' => 'Active'])->with('showTimes')->get();
//        return view($this->createResource(), $info);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $request->validate([
//            'cinema_hall_id' => 'required',
//            'movie_id' => 'required',
//            'customer_name' => 'required',
//            'customer_email' => 'required|unique:bookings,customer_email',
//            'customer_phone' => 'required|min:8|max:11',
//        ]);
//        $data = $request->all();
//        $movie = Movie::where(['vendor_id' => auth('vendor')->user()->id, 'id' => 'movie_id'])->first();
//        if ($movie) {
//            $data['sub_total'] = $data['quantity'] * $data['ticket_price'];
//            if ($data['discount']) {
//                $data['total'] = $data['sub_total'] + $data['discount'];
//            } else {
//                $data['total'] = $data['sub_total'];
//            }
//
//            if ($data['tax_amount']) {
//                $data['total'] += $data['tax_amount'];
//            }
//        }
//
//        $booking = new Booking($data);
//        $booking->vendor_id = auth('vendor')->user()->id;
//        $booking->save();
//
//        NotifyHelper::addSuccess();
//        return redirect()->route($this->indexRoute());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info = $this->crudInfo();
        $info['item'] = Booking::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
        $info['hideEdit'] = true;
        return view($this->showResource(), $info);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        $info = $this->crudInfo();
//        $info['item'] = Booking::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
//        $info['cinemaHalls'] = Theater::where(['vendor_id' => auth('vendor')->user()->id, 'status' => 'Active'])->with('movies')->get();
//        $info['movies'] = Movie::where(['vendor_id' => auth('vendor')->user()->id, 'status' => 'Active'])->with('showTimes')->get();
//        return view($this->editResource(), $info);
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
        $request->validate([
//            'cinema_hall_id' => 'required',
//            'movie_id' => 'required',
//            'customer_name' => 'required',
//            'customer_email' => 'required|unique:bookings,customer_email',
//            'customer_phone' => 'required|min:8|max:11',
            'status' => 'required'
        ]);
//        $data = $request->all();
//        $movie = Movie::where(['vendor_id' => auth('vendor')->user()->id, 'id' => 'movie_id'])->first();
//        if ($movie) {
//            $data['sub_total'] = $data['quantity'] * $data['ticket_price'];
//            if ($data['discount']) {
//                $data['total'] = $data['sub_total'] + $data['discount'];
//            } else {
//                $data['total'] = $data['sub_total'];
//            }
//
//            if ($data['tax_amount']) {
//                $data['total'] += $data['tax_amount'];
//            }
//        }

        $booking = Booking::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
        $booking->status = $request->status;
        $booking->update();

        NotifyHelper::updateSuccess();
        return redirect()->route($this->indexRoute());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $booking = Booking::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
        $booking->delete();

        NotifyHelper::deleteSuccess();
        return redirect()->route($this->indexRoute());
    }
}

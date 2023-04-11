<?php

namespace App\Http\Controllers\vendor;

use App\Helpers\ImagePostHelper;
use App\Helpers\NotifyHelper;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Theater;
use App\Models\Seat;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TheaterController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Theater';
        $this->resources = 'vendors.theaters.';
        parent::__construct();
        $this->route = 'theaters.';
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
            $data = Theater::where('vendor_id', auth('vendor')->user()->id)->orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('templates.index_actions', [
                        'id' => $data->id, 'route' => $this->route
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $info = $this->crudInfo();
        return view($this->indexResource(), $info);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $info = $this->crudInfo();
        $info['routeName'] = "Create";
        return view($this->createResource(), $info);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'name' => 'required',
            'total_rows' => 'required',
            'total_columns' => 'required'
        ]);

        $seats = json_decode($request->seats, true);
//        dd($seats);

        $theater = new Theater();
        $theater->name = $request->name;
        $theater->status = $request->status;
        $theater->rows = $request->total_rows;
        $theater->columns = $request->total_columns;
        $theater->vendor_id = auth('vendor')->user()->id;
        $theater->save();
        foreach ($seats ?? [] as $i => $seat) {
            $seatData = new Seat([
                'vendor_id' => auth('vendor')->user()->id,
                'theater_id' => $theater->id,
                'row_no' => $seat['row'],
                'column_no' => $seat['column'],
                'seat_name' => $seat['seat'],
            ]);
            $seatData->save();
        }

        NotifyHelper::addSuccess();
        return redirect()->route($this->indexRoute());
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
        $info['item'] = Theater::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
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
        $info = $this->crudInfo();
        $info['item'] = Theater::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
        $info['routeName'] = "Edit";
        return view($this->editResource(), $info);
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
            'name' => 'required',
            'total_rows' => 'required',
            'total_columns' => 'required'
        ]);
        $seats = json_decode($request->seats, true);
//        dd($seats);

        $theater = Theater::findOrFail($id);
        $theater->name = $request->name;
        $theater->status = $request->status;
        $theater->rows = $request->total_rows;
        $theater->columns = $request->total_columns;
        $theater->vendor_id = auth('vendor')->user()->id;
        $theater->update();
        $oldSeatIndex = [];

//        Seat::where('theater_id', $theater->id)->delete();
        $oldSeats = Seat::where('theater_id', $theater->id)->get();
        foreach ($oldSeats ?? [] as $s => $oldSeat) {
            $oldSeatIndex[] = $s;
            if (isset($seats[$s])) {
                $oldSeat->vendor_id = auth('vendor')->user()->id;
                $oldSeat->theater_id = $theater->id;
                $oldSeat->row_no = $seats[$s]['row'];
                $oldSeat->column_no = $seats[$s]['column'];
                $oldSeat->seat_name = $seats[$s]['seat'];
                $oldSeat->update();
            } else {
                $oldSeat->delete();
            }
        }
        foreach ($seats ?? [] as $i => $seat) {
//            $seatData = new Seat();
            if (in_array($i, $oldSeatIndex)) {

            } else {
                $seatData = new Seat();
                $seatData->vendor_id = auth('vendor')->user()->id;
                $seatData->theater_id = $theater->id;
                $seatData->row_no = $seat['row'];
                $seatData->column_no = $seat['column'];
                $seatData->seat_name = $seat['seat'];
                $seatData->save();
            }
        }


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
        $theater = Theater::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
        $theater->delete();

        NotifyHelper::deleteSuccess();
        return redirect()->route($this->indexRoute());
    }
}

<?php

namespace App\Http\Controllers\vendor;

use App\Helpers\ImagePostHelper;
use App\Helpers\NotifyHelper;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\CinemaHall;
use App\Models\Seat;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CinemaHallController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Cinema Hall';
        $this->resources = 'vendors.cinema-halls.';
        parent::__construct();
        $this->route = 'cinema-halls.';
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
            $data = CinemaHall::where('vendor_id', auth('vendor')->user()->id)->orderBy('id', 'DESC');
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
        $request->validate([
            'name' => 'required',
        ]);

        $cinemaHall = new CinemaHall();
        $cinemaHall->name = $request->name;
        $cinemaHall->email = $request->email;
        $cinemaHall->phone = $request->phone;
        $cinemaHall->status = $request->status;
        $cinemaHall->seat_calculation = $request->seat_calculation;
        $cinemaHall->rows = $request->total_rows;
        $cinemaHall->columns = $request->total_columns;
        $cinemaHall->vendor_id = auth('vendor')->user()->id;
        $cinemaHall->save();

        foreach ($request->seats ?? [] as $i => $seat) {
            $seatData = new Seat([
                'vendor_id' => auth('vendor')->user()->id,
                'cinema_hall_id' => $cinemaHall->id,
                'row_no' => $request->rows[$i],
                'column_no' => $request->columns[$i],
                'seat_name' => $seat,
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
        $info['item'] = CinemaHall::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
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
        $info['item'] = CinemaHall::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
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
        ]);
        $cinemaHall = CinemaHall::findOrFail($id);
        $cinemaHall->name = $request->name;
        $cinemaHall->email = $request->email;
        $cinemaHall->phone = $request->phone;
        $cinemaHall->status = $request->status;
        $cinemaHall->seat_calculation = $request->seat_calculation;
        $cinemaHall->rows = $request->total_rows;
        $cinemaHall->columns = $request->total_columns;
        $cinemaHall->vendor_id = auth('vendor')->user()->id;
        $cinemaHall->update();

        foreach ($request->seats ?? [] as $i => $seat) {
            $seatData = Seat::findOrFail($request->seat_ids[$i]);
            $seatData->vendor_id = auth('vendor')->user()->id;
            $seatData->cinema_hall_id = $cinemaHall->id;
            $seatData->row_no = $request->rows[$i];
            $seatData->column_no = $request->columns[$i];
            $seatData->seat_name = $seat;
            $seatData->update();
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
        $cinemaHall = CinemaHall::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
        $cinemaHall->delete();

        NotifyHelper::deleteSuccess();
        return redirect()->route($this->indexRoute());
    }
}

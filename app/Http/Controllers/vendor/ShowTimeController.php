<?php

namespace App\Http\Controllers\vendor;

use App\Helpers\NotifyHelper;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Theater;
use App\Models\Movie;
use App\Models\ShowTime;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ShowTimeController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Show Time';
        $this->resources = 'vendors.show-times.';
        parent::__construct();
        $this->route = 'show-times.';
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
            $data = ShowTime::where('vendor_id', auth('vendor')->user()->id)->orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('theater_id', function ($data) {
                    return $data->theater ? '<a target="_blank" href="' . route('theaters.show', $data->theater->id) . '">' . $data->theater->name . '</a>' : '-';
                })
                ->editColumn('movie_id', function ($data) {
                    return $data->movie ? '<a target="_blank" href="' . route('movies.show', $data->movie->id) . '">' . $data->movie->title . '</a>' : '-';
                })
                ->editColumn('show_details', function ($data) {
                    return json_decode($data->show_details, true);
                })
                ->addColumn('action', function ($data) {
                    return view('templates.index_actions', [
                        'id' => $data->id, 'route' => $this->route
                    ])->render();
                })
                ->rawColumns(['action', 'theater_id', 'movie_id', 'show_details'])
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
        $info['theaters'] = Theater::where(['vendor_id' => auth('vendor')->user()->id, 'status' => 'Active'])->with('movies')->get();
        $info['movies'] = Movie::where(['vendor_id' => auth('vendor')->user()->id, 'status' => 'Active'])->with('showTimes')->get();
        $info['routeName'] = 'Create';
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
            'theater_id' => 'required',
            'movie_id' => 'required',
        ]);

        $showDetails = [];

        foreach ($request->show_date as $i => $showDate) {
            $showDetails[] = [
                "show_date" => $showDate,
                "show_time" => $request->show_time[$i],
                "ticket_price" => $request->ticket_price[$i],
            ];
        }

        $showTime = new ShowTime();
        $showTime->vendor_id = auth('vendor')->user()->id;
        $showTime->theater_id = $request->theater_id;
        $showTime->movie_id = $request->movie_id;
        $showTime->show_details = json_encode($showDetails, true);
        $showTime->save();

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
        $info['item'] = ShowTime::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
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
        $info['item'] = ShowTime::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
        $info['theaters'] = Theater::where(['vendor_id' => auth('vendor')->user()->id, 'status' => 'Active'])->with('movies')->get();
        $info['movies'] = Movie::where(['vendor_id' => auth('vendor')->user()->id, 'status' => 'Active'])->with('showTimes')->get();
        $info['routeName'] = 'Edit';
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
            'theater_id' => 'required',
            'movie_id' => 'required',
        ]);

        $showDetails = [];

        foreach ($request->show_date as $i => $showDate) {
            $showDetails[] = [
                "show_date" => $showDate,
                "show_time" => $request->show_time[$i],
                "ticket_price" => $request->ticket_price[$i],
            ];
        }

        $showTime = ShowTime::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
        $showTime->vendor_id = auth('vendor')->user()->id;
        $showTime->theater_id = $request->theater_id;
        $showTime->movie_id = $request->movie_id;
        $showTime->show_details = json_encode($showDetails, true);
        $showTime->update();

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
        $showTime = ShowTime::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
        $showTime->delete();

        NotifyHelper::deleteSuccess();
        return redirect()->route($this->indexRoute());
    }
}

<?php

namespace App\Http\Controllers\vendor;

use App\Helpers\ImagePostHelper;
use App\Helpers\NotifyHelper;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\CinemaHall;
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
                ->editColumn('image', function ($data) {
                    $imgUrl = $data->image ? asset($data->image) : asset('images/placeholder-image.jpg');
                    return '<a target="_blank" href="' . $imgUrl . '"><img style="height: 60%; width: 60%; object-fit: contain" src="' . $imgUrl . '" alt="logo"></a>';
                })
                ->addColumn('action', function ($data) {
                    return view('templates.index_actions', [
                        'id' => $data->id, 'route' => $this->route
                    ])->render();
                })
                ->rawColumns(['action', 'image'])
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
            'image' => 'nullable|mimes:jpeg,jpg,png|max:10000'
        ]);
        $data = $request->all();
        $cinemaHall = new CinemaHall($data);
        $cinemaHall->vendor_id = auth('vendor')->user()->id;
        $cinemaHall->save();

        if ($request->hasFile('image') && $request->image != '') {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;

            $filename = ImagePostHelper::saveImage($file, '/cinema-halls/images', $filename);
            $cinemaHall->image = $filename;

            $cinemaHall->update();
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
        return view($this->createResource(), $info);
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
            'image' => 'nullable|mimes:jpeg,jpg,png|max:10000'
        ]);
        $data = $request->all();
        $cinemaHall = CinemaHall::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
        $cinemaHall->vendor_id = auth('vendor')->user()->id;
        $cinemaHall->update($data);

        if ($request->hasFile('image') && $request->image != '') {
            ImagePostHelper::deleteImage($cinemaHall->image);

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;

            $filename = ImagePostHelper::saveImage($file, '/cinema-halls/images', $filename);
            $cinemaHall->image = $filename;

            $cinemaHall->update();
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
        ImagePostHelper::deleteImage($cinemaHall->image);
        $cinemaHall->delete();

        NotifyHelper::deleteSuccess();
        return redirect()->route($this->indexRoute());
    }
}

<?php

namespace App\Http\Controllers\superadmin;

use App\Helpers\ImagePostHelper;
use App\Helpers\NotifyHelper;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Theater;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MovieController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Movie';
        $this->resources = 'superadmin.movies.';
        parent::__construct();
        $this->route = 'movie.';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Movie::orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function ($data) {
                    $imgUrl = $data->getImage() ? asset($data->getImage()) : asset('images/placeholder-image.jpg');
                    return '<a target="_blank" href="' . $imgUrl . '"><img style="height: 60%; width: 60%; object-fit: contain" src="' . $imgUrl . '" alt="logo"></a>';
                })
                ->editColumn('vendor_id', function ($data) {
                    return $data->vendor ? '<a target="_blank" href="' . route('vendors.show', $data->vendor->id) . '">' . $data->vendor->name . '</a>' : '-';
                })
                ->editColumn('theater_id', function ($data) {
                    return $data->theater ? '<a target="_blank" href="' . route('theaters.show', $data->theater->id) . '">' . $data->theater->name . '</a>' : '-';
                })
                ->addColumn('action', function ($data) {
                    return view('templates.index_actions', [
                        'id' => $data->id, 'route' => $this->route
                    ])->render();
                })
                ->rawColumns(['action', 'image', 'vendor_id', 'theater_id'])
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
        $info = $this->crudInfo();
        $info['item'] = Movie::findOrFail($id);
        return view($this->showResource(), $info);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = $this->crudInfo();
        $info['item'] = Movie::findOrFail($id);
        $info['theaters'] = Theater::all();
        return view($this->editResource(), $info);
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
        $request->validate([
            'theater_id' => 'required',
            'title' => 'required',
            'duration' => 'required',
            'image' => 'mimes:jpeg,jpg,png|max:10000',
            'trailer' => 'file|mimes:mp4,mov,ogg,qt|max:20000'
        ]);
        $data = $request->all();
        $movie = Movie::findOrFail($id);
        $movie->update($data);

        if ($request->image) {
            $movie->clearMediaCollection();
            $movie->addMediaFromRequest('image')->toMediaCollection();
        }

        if ($request->trailer) {
            $movie->clearMediaCollection('trailer');
            $movie->addMediaFromRequest('trailer')->toMediaCollection('trailer');
        }

        NotifyHelper::updateSuccess();
        return redirect()->route($this->indexRoute());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->clearMediaCollection();
        $movie->clearMediaCollection('trailer');
        $movie->delete();

        NotifyHelper::deleteSuccess();
        return redirect()->route($this->indexRoute());
    }
}

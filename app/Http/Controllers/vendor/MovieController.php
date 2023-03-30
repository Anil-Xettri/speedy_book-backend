<?php

namespace App\Http\Controllers\vendor;

use App\Helpers\ImagePostHelper;
use App\Helpers\NotifyHelper;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Theater;
use App\Models\Movie;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MovieController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Movie';
        $this->resources = 'vendors.movies.';
        parent::__construct();
        $this->route = 'movies.';
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
            $data = Movie::where('vendor_id', auth('vendor')->user()->id)->orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function ($data) {
                    $imgUrl = $data->image ? asset($data->image) : asset('images/placeholder-image.jpg');
                    return '<a target="_blank" href="' . $imgUrl . '"><img style="height: 60%; width: 60%; object-fit: contain" src="' . $imgUrl . '" alt="logo"></a>';
                })
                ->editColumn('theater_id', function ($data) {
                    return $data->theater ? '<a target="_blank" href="' . route('theaters.show', $data->theater->id) . '">' . $data->theater->name . '</a>' : '-';
                })
                ->addColumn('action', function ($data) {
                    return view('templates.index_actions', [
                        'id' => $data->id, 'route' => $this->route
                    ])->render();
                })
                ->rawColumns(['action', 'image', 'theater_id'])
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
        $info['theaters'] = Theater::where(['vendor_id' => auth('vendor')->user()->id, 'status' => 'Active'])->get();
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
            'title' => 'required',
            'duration' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png|max:10000',
            'trailer' => 'required|file||mimes:mp4,mov,ogg,qt|max:20000'
        ]);
        $data = $request->all();
        $movie = new Movie($data);
        $movie->vendor_id = auth('vendor')->user()->id;
        $movie->save();

        if ($request->hasFile('image') && $request->image != '') {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;

            $filename = ImagePostHelper::saveImage($file, '/movies/images', $filename);
            $movie->image = $filename;

            $movie->update();
        }

        if ($request->hasFile('trailer') && $request->trailer != '') {
            $file = $request->file('trailer');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;

            $filename = ImagePostHelper::saveImage($file, '/movies/trailers', $filename);
            $movie->trailer = $filename;

            $movie->update();
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
        $info['item'] = Movie::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
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
        $info['item'] = Movie::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
        $info['theaters'] = Theater::where(['vendor_id' => auth('vendor')->user()->id, 'status' => 'Active'])->get();
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
            'title' => 'required',
            'duration' => 'required',
            'image' => 'mimes:jpeg,jpg,png|max:10000',
            'trailer' => 'file|mimes:mp4,mov,ogg,qt|max:20000'
        ]);
        $data = $request->all();
        $movie = Movie::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
        $movie->vendor_id = auth('vendor')->user()->id;
        $movie->update($data);

        if ($request->hasFile('image') && $request->image != '') {
            ImagePostHelper::deleteImage($movie->image);

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;

            $filename = ImagePostHelper::saveImage($file, '/movies/images', $filename);
            $movie->image = $filename;

            $movie->update();
        }

        if ($request->hasFile('trailer') && $request->trailer != '') {
            ImagePostHelper::deleteImage($movie->trailer);

            $file = $request->file('trailer');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;

            $filename = ImagePostHelper::saveImage($file, '/movies/trailers', $filename);
            $movie->trailer = $filename;

            $movie->update();
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
        $movie = Movie::where('vendor_id', auth('vendor')->user()->id)->findOrFail($id);
        ImagePostHelper::deleteImage($movie->image);
        ImagePostHelper::deleteImage($movie->trailer);
        $movie->delete();

        NotifyHelper::deleteSuccess();
        return redirect()->route($this->indexRoute());
    }
}

<?php

namespace App\Http\Controllers\superadmin;

use App\Helpers\ImagePostHelper;
use App\Helpers\NotifyHelper;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VendorController extends BaseController
{
    public function __construct()
    {
        $this->title = 'Vendor';
        $this->resources = 'superadmin.vendors.';
        parent::__construct();
        $this->route = 'vendors.';
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
            $data = Vendor::orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function ($data) {
                    $imgUrl = $data->getImage() ? asset($data->getImage()) : asset('images/placeholder-image.jpg');
                    return '<a target="_blank" href="' . $imgUrl . '"><img style="height: 60%; width: 60%; object-fit: contain" src="' . $imgUrl . '" alt="logo"></a>';
                })
                ->editColumn('phone', function ($data) {
                    return $data->phone ?: '-';
                })
                ->editColumn('address', function ($data) {
                    return $data->address ?: '-';
                })
                ->addColumn('action', function ($data) {
                    return view('templates.index_actions', [
                        'id' => $data->id, 'route' => $this->route
                    ])->render();
                })
                ->rawColumns(['action', 'image', 'address', 'phone'])
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
            'email' => 'required|email|unique:vendors,email',
            'image' => 'nullable|mimes:jpeg,jpg,png|max:10000',
            'password' => 'required|min:8'
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $vendor = new Vendor($data);
        $vendor->save();

        if ($request->image) {
            $vendor->addMediaFromRequest('image')->toMediaCollection();
        }

        if ($request->banner_image) {
            $vendor->addMediaFromRequest('banner_image')->toMediaCollection('banner-image');
        }

//        if ($request->hasFile('image') && $request->image != '') {
//            $file = $request->file('image');
//            $extension = $file->getClientOriginalExtension();
//            $filename = time() . '.' . $extension;
//
//            $filename = ImagePostHelper::saveImage($file, '/vendors/images', $filename);
//            $vendor->image = $filename;
//
//            $vendor->update();
//        }
//
//        if ($request->hasFile('banner_image') && $request->banner_image != '') {
//            $bannerFile = $request->file('banner_image');
//            $bannerExtension = $bannerFile->getClientOriginalExtension();
//            $bannerFilename = time() . '.' . $bannerExtension;
//
//            $bannerFilename = ImagePostHelper::saveImage($bannerFile, '/vendors/banner-images', $bannerFilename);
//            $vendor->banner_image = $bannerFilename;
//
//            $vendor->update();
//        }

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
        $info['item'] = Vendor::findOrFail($id);

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
        $info['item'] = Vendor::findOrFail($id);
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
            'name' => 'required',
            'email' => 'required|email',
            'image' => 'nullable|mimes:jpeg,jpg,png|max:10000'
        ]);

        if ($request->password != null) {
            $data = $request->all();
        } else {
            $data = $request->except(['password']);
        }
        $vendor = Vendor::findOrFail($id);
        $vendor->update($data);

        if ($request->image) {
            $vendor->clearMediaCollection();
            $vendor->addMediaFromRequest('image')->toMediaCollection();
        }

        if ($request->banner_image) {
            $vendor->clearMediaCollection('banner-image');
            $vendor->addMediaFromRequest('banner_image')->toMediaCollection('banner-image');
        }

//        if ($request->hasFile('image') && $request->image != '') {
//
//            ImagePostHelper::deleteImage($vendor->image);
//
//            $file = $request->file('image');
//            $extension = $file->getClientOriginalExtension();
//            $filename = time() . '.' . $extension;
//
//            $filename = ImagePostHelper::saveImage($file, '/vendors/images', $filename);
//            $vendor->image = $filename;
//            $vendor->update();
//        }
//
//        if ($request->hasFile('banner_image') && $request->banner_image != '') {
//
//            ImagePostHelper::deleteImage($vendor->banner_image);
//
//            $bannerFile = $request->file('banner_image');
//            $bannerExtension = $bannerFile->getClientOriginalExtension();
//            $bannerFilename = time() . '.' . $bannerExtension;
//
//            $bannerFilename = ImagePostHelper::saveImage($bannerFile, '/vendors/banner-images', $bannerFilename);
//
//            $vendor->banner_image = $bannerFilename;
//
//            $vendor->update();
//        }

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
        $vendor = Vendor::findOrFail($id);
        $vendor->clearMediaCollection();
        $vendor->clearMediaCollection('banner-image');
        $vendor->delete();

        NotifyHelper::deleteSuccess();
        return redirect()->route($this->indexRoute());
    }
}

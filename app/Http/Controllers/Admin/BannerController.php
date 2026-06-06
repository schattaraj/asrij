<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image'
        ]);

        $file = $request->file('image');

        $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $imagePath = $file->storeAs(
            'uploads/banners',
            $imageName,
            'public'
        );

        Banner::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'sort_order' => $request->sort_order,
            'status' => $request->status ?? 1
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image'
        ]);
        $data = $request->except('image');

        if ($request->hasFile('image')) {

            // Delete old image
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $file = $request->file('image');

            $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Store in public disk
            $path = $file->storeAs(
                'uploads/banners',
                $imageName,
                'public'
            );

            $data['image'] = $path;
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();

        return back()->with('success', 'Banner deleted successfully');
    }
}

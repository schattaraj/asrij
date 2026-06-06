<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodCamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BloodCampController extends Controller
{
    public function index()
    {
        $camps = BloodCamp::latest()->get();
        return view('admin.camps.index', compact('camps'));
    }

    public function create()
    {
        return view('admin.camps.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'camp_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required',
            'image' => 'nullable|image',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $data['image'] = $file->storeAs('uploads/camps', $name, 'public');
        }

        BloodCamp::create($data);

        return redirect()->route('admin.camps.index')
            ->with('success', 'Blood camp created successfully');
    }

    public function show(string $id)
    {
        //
    }
    public function edit(BloodCamp $camp)
    {
        return view('admin.camps.edit', compact('camp'));
    }

    public function update(Request $request, BloodCamp $camp)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {

            if ($camp->image && Storage::disk('public')->exists($camp->image)) {
                Storage::disk('public')->delete($camp->image);
            }

            $file = $request->file('image');
            $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $data['image'] = $file->storeAs('uploads/camps', $name, 'public');
        }

        $camp->update($data);

        return redirect()->route('admin.camps.index')
            ->with('success', 'Blood camp updated successfully');
    }

    public function destroy(BloodCamp $camp)
    {
        if ($camp->image && Storage::disk('public')->exists($camp->image)) {
            Storage::disk('public')->delete($camp->image);
        }

        $camp->delete();

        return back()->with('success', 'Deleted successfully');
    }
}
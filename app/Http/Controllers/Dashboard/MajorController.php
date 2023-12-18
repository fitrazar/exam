<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $majors = Major::all();
        return view('dashboard.major.index', compact('majors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.major.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'acronym' => 'required|string',
            'slug' => 'required|string|unique:majors,slug',
        ]);

        Major::create([
            'name' => $validatedData['name'],
            'acronym' => $validatedData['acronym'],
            'slug' => $validatedData['slug'],
            'status' => $request->status == true ? 0 : 1,
        ]);

        return redirect('/dashboard/major')->with('success', 'Jurusan Berhasil Ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Major $major)
    {
        return view('dashboard.major.edit', compact('major'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Major $major)
    {
        $rules = [
            'name' => 'required|string',
            'acronym' => 'required|string',
            'slug' => 'required|string|unique:majors,slug,' . $major->id,
        ];

        $validatedData = $request->validate($rules);

        Major::findOrFail($major->id)->update([
            'name' => $validatedData['name'],
            'acronym' => $validatedData['acronym'],
            'slug' => $validatedData['slug'],
            'status' => $request->status == true ? 0 : 1,
        ]);

        return redirect('/dashboard/major')->with('success', 'Jurusan Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Major $major)
    {
        Major::destroy($major->id);
        return redirect('/dashboard/major')->with('success', 'Jurusan Berhasil Dihapus');
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = Grade::all();
        return view('dashboard.grade.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.grade.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:grades,slug',
        ]);

        Grade::create([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'status' => $request->status == true ? 0 : 1,
        ]);

        return redirect('/dashboard/grade')->with('success', 'Kelas Berhasil Ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        return view('dashboard.grade.edit', compact('grade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        $rules = [
            'name' => 'required|string',
            'slug' => 'required|string|unique:grades,slug,' . $grade->id,
        ];

        $validatedData = $request->validate($rules);

        Grade::findOrFail($grade->id)->update([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'status' => $request->status == true ? 0 : 1,
        ]);

        return redirect('/dashboard/grade')->with('success', 'Kelas Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        Grade::destroy($grade->id);
        return redirect('/dashboard/grade')->with('success', 'Kelas Berhasil Dihapus');
    }
}

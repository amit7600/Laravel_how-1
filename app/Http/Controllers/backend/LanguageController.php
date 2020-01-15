<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\AllLanguage;
use DB;
use Illuminate\Http\Request;
use Sentinel;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = AllLanguage::get();

        return view('backEnd.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.languages.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'language_name' => 'required',
        ]);
        try {
            DB::beginTransaction();
            AllLanguage::create([
                'language_name' => $request->get('language_name'),
                'notes' => $request->get('notes'),
                'created_by' => Sentinel::check()->id,
            ]);
            DB::commit();

            return redirect()->to('languages')->with('success', 'Language added successfully!');
        } catch (\Throwable $th) {
            return redirect()->to('languages')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $language = AllLanguage::whereId($id)->first();

        return view('backEnd.languages.edit', compact('language'));
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
        $this->validate($request, [
            'language_name' => 'required',
        ]);
        try {
            DB::beginTransaction();
            AllLanguage::whereId($id)->update([
                'language_name' => $request->get('language_name'),
                'notes' => $request->get('notes'),
                'updated_by' => Sentinel::check()->id,
            ]);
            DB::commit();

            return redirect()->to('languages')->with('success', 'Language updated successfully!');
        } catch (\Throwable $th) {
            return redirect()->to('languages')->with('error', $th->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            AllLanguage::whereId($id)->update([
                'deleted_by' => Sentinel::check()->id,
            ]);
            AllLanguage::whereId($id)->delete();
            DB::commit();
            return redirect()->to('languages')->with('success', 'Language deleted successfully!');

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->to('languages')->with('error', $th->getMessage());
        }
    }
}

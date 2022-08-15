<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Setting;
use SettingGroup;
use SettingTrait;

class Settings extends Controller
{
    use SettingTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $setting_groups = SettingGroup::all();        
        return view('pages.advanced.main',compact('setting_groups'));       
        
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
        Setting::create([
                            "setting_group_id" => $request->setting_group_id,
                            "name" => $request->value,
                            "value" => $request->value,
                            "key" => $request->key
        ]);

        $settings  = Setting::where('setting_group_id',$request->setting_group_id)->get();
        return redirect()->route('product.index');
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
        //
        $setting_group = SettingGroup::find($id);      
        return view('pages.advanced.create',compact('setting_group'));

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        //
        $setting = Setting::find($id);
        $setting->delete();        
        return back();
    }
}

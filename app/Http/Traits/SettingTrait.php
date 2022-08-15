<?php

namespace App\Http\Traits;
use App\Models\Setting;

trait SettingTrait {
    public function setting($group) {
        return Setting::select('id','group','name','key','options','value')->where('group',$group)->get();        
    }

}
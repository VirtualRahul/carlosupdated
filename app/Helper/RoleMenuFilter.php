<?php

namespace App\Helper;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Illuminate\Support\Facades\Auth;
//use App\Http\Controllers\Auth;
//use Laratrust\Laratrust;

class RoleMenuFilter implements FilterInterface
{
    public function transform($item)
    {
        if (!$this->isVisible($item)) {
            return false;
        }

        if (isset($item['header'])) {
            $item = $item['header'];
        }

        return $item;
    }

    protected function isVisible($item) {     
        try{
            
                return true;
            
        }catch(\Exception $ex){
            return false;
        }
            
     }
}
    
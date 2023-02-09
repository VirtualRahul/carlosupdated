<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Tag;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        View::composer(['layouts/includes/header'], function ($view) {
            $category_band=Tag::all();
		    $category_event=Category::where("page_type","Event")->where('parent_id','!=','0')->get();
		   
            $view->with(['bands'=>$category_band,'events'=>$category_event]);
        });
      

      
    }
}

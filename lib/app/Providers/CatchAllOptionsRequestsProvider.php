<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * If the incoming request is an OPTIONS request
 * we will register a handler for the requested route
 */
class CatchAllOptionsRequestsProvider extends ServiceProvider {

  public function register()
  {

    /** @var \Illuminate\Http\Request $request */
    $request = $this->app->make('request');
    if($request->isMethod('OPTIONS')) {
		  $this->app->options($request->path(), function(){
			  return response('OK', 200);
		  });
	  }
  }
}
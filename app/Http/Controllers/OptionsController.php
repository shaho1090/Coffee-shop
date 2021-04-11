<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
   public function store(Request $request)
   {
       (new Option())->createNew($request->toArray());
   }
}

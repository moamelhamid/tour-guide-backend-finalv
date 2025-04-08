<?php

namespace App\Http\Controllers;

use App\Models\department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function addDept(Request $req){
        $validatedData = $req->validate([
            'name'=>'required|string|max:255',
        ]);
        $name=$validatedData['name'];
        
       $dep= department::create([
            'name'=>$name,]);
        return $dep;
        
    }
    public function getDepts(){
        $depts = department::all();
        return $depts;
    }
}
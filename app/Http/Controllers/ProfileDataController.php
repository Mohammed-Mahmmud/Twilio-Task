<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileDataController extends Controller
{
    //
 public function index (){
   
   $users=USER::all();
   return $users;
        }


        
}



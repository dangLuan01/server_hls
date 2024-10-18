<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Queue\Worker;
use Queue;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $totalVideo = Video::count();
        $countWork = Queue::size();
        
        return view("home",compact('totalVideo','countWork'));
    }
}

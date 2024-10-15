<?php

namespace App\Http\Livewire\Video;

use App\Models\Video;
use Livewire\Component;

class AllVideo extends Component
{
    public Video $video;
    public function mount(Video $video){
        $this->video = $video;
    }
    public function render()
    {
        return view('livewire.video.all-video')->extends('main');
    }
}

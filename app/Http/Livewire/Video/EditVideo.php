<?php

namespace App\Http\Livewire\Video;

use App\Models\Video;
use Livewire\Component;

class EditVideo extends Component
{
    public Video $video;
    protected $rules = [
        'video.title'=>'required|max:255',
        'video.description'=>'required|max:255',
        'video.visibility'=>'required',
        'processed_file' => 'required'

    ];
    public function render()
    {
        return view('livewire.video.edit-video')->extends('main');
    }
    public function mount($video)
    {
        $this->video = $video;
    }
    public function update(Video $video)
    {
        $this->validate();
        $this->video->update([
            'title'=>$this->video->title,
            'description'=>$this->video->description,
            'visibility'=>$this->video->visibility,
        ]);
        
        session()->flash('message','Video was update');
    }
}

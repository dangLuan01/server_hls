<?php

namespace App\Http\Livewire\Video;

use App\Jobs\ConvertVideoForStreaming;
use App\Models\Video;
use FFMpeg;
use Livewire\Component;
use Livewire\WithFileUploads;
use FFMpeg\Format\Video\X264;

class CreateVideo extends Component
{
    use WithFileUploads;
    public Video $video;
    public $videoFile;
    protected $rules = [
        'videoFile' => 'mimes:mp4,mkv'  
    ];
    public function render()
    {
        return view('livewire.video.create-video')->extends('main');
    }
    public function fileCompleted()
    {
        $this->validate();
        $path= $this->videoFile->store('videos-temp');
        $originalName = pathinfo($this->videoFile->getClientOriginalName(), PATHINFO_FILENAME); 
        $uid = uniqid(true);
        $this -> video = Video::create([

            'title'=>$originalName,

            'description'=>$uid.now()->format('Y-m-d'),

            'uid'=>$uid,

            'visibility'=>'public',

            'path' => explode('/',$path)[1],

        ]);
        
        ConvertVideoForStreaming::dispatch($this -> video);
     
        return redirect()->route('video.edit',['video'=>$this->video]);
    }
    
}

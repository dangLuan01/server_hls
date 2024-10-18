<?php

namespace App\Http\Livewire\Video;

use App\Models\Video;
use Livewire\Component;
use Livewire\WithPagination;
use Storage;

class AllVideo extends Component
{
    use WithPagination;
    // protected $paginationTheme = 'bootstrap';
    public function render()
    {
        return view('livewire.video.all-video',['videos'=>Video::orderBy('created_at','DESC')->get()])->extends('main');
    }
    public function delete($id)
    {
        $v = Video::find($id);
        Storage::disk('videos')->deleteDirectory($v->description);
        Video::destroy($id);
        $this->emit('videoDeleted');
    }
    public function refreshVideos()
    {
        $this->render();
    }
}

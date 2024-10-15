<?php

namespace App\Http\Livewire\Channel;

use Livewire\Component;
use Request;

class EditChannel extends Component
{
    public $name="ll";
    public function render()
    {
       
        return view('livewire.channel.edit-channel');
    }
    public function submit(Request $request){
        $names=$request;
        dd($names);
    }
}

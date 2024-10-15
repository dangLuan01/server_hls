<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\Format\Video\X264;
use FFMpeg;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $video;

     public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $destination = '/' . $this->video->uid.now()->format('Y-m-d').'/'.$this->video->uid.'.m3u8';

        $low = (new X264('aac'))->setKiloBitrate(500);
        $high = (new X264('aac'))->setKiloBitrate(1000);

        FFMpeg::fromDisk('videos-temp')

        ->open($this->video->path)

        ->exportForHLS()->addFormat($low,function($filters){

            $filters->resize(640,480);

        })->addFormat($high,function($filters){

            $filters->resize(1280,720);

        })->toDisk('videos')

        ->onProgress(function($progress){

           $this->video->update([

            'processing_precentage'=> $progress

           ]); 
        })

        ->save($destination);

        $this->video->update([

            'processed' => true,

            'processed_file' => $destination,

           ]);

        Storage::disk('videos-temp')->delete($this->video->path);
        $files = Storage::disk('local')->files('livewire-tmp');
           
        foreach ($files as $file) {
            Storage::disk('local')->delete($file);
        }
    }
}

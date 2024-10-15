<?php

namespace App\Console\Commands;

use FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Console\Command;

class VideoEncode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video-encode:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $low = (new X264('aac'))->setKiloBitrate(500);
        // $high = (new X264('aac'))->setKiloBitrate(1000);
        // FFMpeg::fromDisk('videos-temp')
        // ->open('test.mp4')
        // ->exportForHLS()->addFormat($low,function($filters){
        //     $filters->resize(640,480);
        // })->addFormat($high,function($filters){
        //     $filters->resize(1280,720);
        // })->toDisk('videos-temp')
        // ->onProgress(function($progress){
        //     $this->info("Progress={$progress}%");
        // })
        // ->save('/hls/master.m3u8');
    }
}

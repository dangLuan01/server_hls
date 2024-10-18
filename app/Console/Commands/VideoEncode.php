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
        // $low = (new X264('aac'))->setKiloBitrate(2500);
        $high = (new X264('aac'))->setKiloBitrate(3000);
        FFMpeg::fromDisk('videos-temp')
        ->open('the.wild.robot.2024.1080p.web.h264-scrupulousslyearwigofmaturity_MutiAudio.mkv')
        
        ->exportForHLS()
        ->addFormat($high)
        ->toDisk('videos-temp')
        ->onProgress(function($progress){
            $this->info("Progress={$progress}%");
        })
        ->save('/hls/master.m3u8');
    }
}

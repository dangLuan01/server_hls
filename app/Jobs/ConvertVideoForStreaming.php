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
        

        $low = (new X264('aac'))->setKiloBitrate(2500);
        $high = (new X264('aac'))->setKiloBitrate(3500);

        FFMpeg::fromDisk('videos-temp')

        ->open($this->video->path)

        ->exportForHLS()
        ->addFormat($high)
        ->toDisk('videos')

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
        $this->renameTsToPng($this->video->uid);
        Storage::disk('videos-temp')->delete($this->video->path);
        $files = Storage::disk('local')->files('livewire-tmp');
           
        foreach ($files as $file) {
            Storage::disk('local')->delete($file);
        }
    }
    public function renameTsToPng($uid)
    {
        // Đường dẫn tới thư mục chứa các file video được phân đoạn
        $directory = storage_path('app/videos/' . $uid . now()->format('Y-m-d'));

        // Lấy tất cả các file có đuôi .ts trong thư mục
        $files = \File::files($directory);

        foreach ($files as $file) {
            if ($file->getExtension() === 'ts') {
                // Đổi phần mở rộng từ .ts sang .png
                $newName = str_replace('.ts', '.png', $file->getPathname());
                \File::move($file->getPathname(), $newName);
            }
        }

        // Cập nhật lại file .m3u8 để trỏ tới các file .png
        $this->updateM3U8Playlist($uid);
    }
    public function updateM3U8Playlist($uid)
    {
        $destination = '/' . $this->video->uid.now()->format('Y-m-d').'/'.$this->video->uid.'_0_3500.m3u8';
        // Đọc nội dung của file .m3u8
        $playlistPath = storage_path('app/videos/' . $destination);
        $playlistContent = \File::get($playlistPath);

        // Thay đổi tất cả các phần mở rộng từ .ts thành .png trong file .m3u8
        $updatedContent = preg_replace_callback('/(' . preg_quote($uid) . '_.*)\.ts/', function($matches) use ($uid) {
            // Tạo URL mới với phần mở rộng .png
            // return url('/videos/' . $uid . now()->format('Y-m-d') . '/' . $matches[1] . '.png');
            return ('http://127.0.0.1:8000/videos/' . $uid . now()->format('Y-m-d') . '/' . $matches[1] . '.png');
        }, $playlistContent);
       

        // Lưu lại file m3u8 với nội dung đã được cập nhật
        \File::put($playlistPath, $updatedContent);

        ///////////////////******************************//////////////////////////
        // $destinations = '/' . $this->video->uid.now()->format('Y-m-d').'/'.$this->video->uid.'_1_3000.m3u8';
        // // Đọc nội dung của file .m3u8
        // $playlistPaths = storage_path('app/videos/' . $destinations);
        // $playlistContents = \File::get($playlistPaths);

        // // Thay đổi tất cả các phần mở rộng từ .ts thành .png trong file .m3u8
        // $updatedContents = preg_replace_callback('/(' . preg_quote($uid) . '_.*)\.ts/', function($matchess) use ($uid) {
        //     // Tạo URL mới với phần mở rộng .png
        //     // return url('/videos/' . $uid . now()->format('Y-m-d') . '/' . $matches[1] . '.png');
        //     return ('http://127.0.0.1:8000/videos/' . $uid . now()->format('Y-m-d') . '/' . $matchess[1] . '.png');
        // }, $playlistContents);

        // // Lưu lại file m3u8 với nội dung đã được cập nhật
        // \File::put($playlistPaths, $updatedContents);
    }
}

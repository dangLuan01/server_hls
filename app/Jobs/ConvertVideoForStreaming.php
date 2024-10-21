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
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;

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
        $folderVideo = $this->video->uid.now()->format('Y-m-d');
        

        $veryhigh = (new X264('aac'))->setKiloBitrate(3500);
        // $high = (new X264('aac'))->setKiloBitrate(2000);

        FFMpeg::fromDisk('videos-temp')
        ->open($this->video->path)
        ->exportForHLS()
        ->addFormat($veryhigh)
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
        $this->renameTsToPng($this->video->uid,$folderVideo);
        $this->video->update([
            'cloud' => '1'
        ]);
        $this->saveToGdrive($folderVideo,$this->video->uid);
        $this->video->update([
            'cloud' => '2'
        ]);
        Storage::disk('videos-temp')->delete($this->video->path);
        $files = Storage::disk('local')->files('livewire-tmp');
           
        foreach ($files as $file) {
            Storage::disk('local')->delete($file);
        }
    }
    public function gDrive(){
        Storage::extend('google',function($app,$config){
            $client = new \Google_Client;
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);
            $service = new \Google_Service_Drive($client);
            $adapter = new GoogleDriveAdapter($service,$config['folderId']);
            return new Filesystem($adapter);
        });
    }
    public function saveToGdrive($folderVideo,$uid){
        $this->gDrive();
        Storage::disk('google')->makeDirectory($folderVideo);
        $folders=Storage::disk('google')->directories();
        foreach($folders as $first){
            $detail[] = Storage::disk('google')->getMetadata($first);
        }   
        $directoryVideo = storage_path('app/videos/' . $folderVideo);
        $files = \File::files($directoryVideo);
        foreach($detail as $folder){
            if($folder['name'] == $folderVideo){
                $folderPath = $folder['path'];
                foreach ($files as $file) {
                    if ($file->getExtension() === 'png') {
                           // Lấy tên file và đường dẫn tạm thời của file
                           $fileName = $file->getFilename();
                           $filePath = $file->getRealPath();
                          
                           // Upload file lên Google Drive
                           Storage::disk('google')->put($folder['path'].'/'.$fileName, fopen($filePath, 'r+'));
                           sleep(1);
                    }
                }
            }
        }
        sleep(2);
        $this->updateM3U8Playlist($uid,$folderVideo,$folderPath);
        
    }
    public function renameTsToPng($uid,$folderVideo)
    {
        // Đường dẫn tới thư mục chứa các file video được phân đoạn
        $directory = storage_path('app/videos/' . $folderVideo);

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
        
    }
    public function updateM3U8Playlist($uid,$folderVideo,$folderPath)
    {
        $this->gDrive();
        $destination = '/' . $folderVideo.'/'.$this->video->uid.'_0_3500.m3u8';
        // Đọc nội dung của file .m3u8
        $playlistPath = storage_path('app/videos/' . $destination);
        $playlistContent = \File::get($playlistPath);
        
        $files=Storage::disk('google')->listContents($folderPath);

       // Thay đổi tất cả các phần mở rộng từ .ts thành .png trong nội dung của file .m3u8
        $updatedContent = preg_replace_callback('/(' . preg_quote($uid) . '_.*)\.ts/', function($matches) use ($files) {
            foreach ($files as $file) {
                // Kiểm tra nếu tên file từ Google Drive khớp với đoạn tìm kiếm từ .m3u8
                if (strpos($file['name'], $matches[1]) !== false) {
                    // Tạo URL mới với tên file tương ứng
                    return 'http://127.0.0.1:8000/proxy?id=' . urlencode($file['basename']);
                }
            }

        }, $playlistContent);
        
        // Lưu lại file m3u8 với nội dung đã được cập nhật
        \File::put($playlistPath, $updatedContent);
        // PUBLIC FILE
        Storage::disk('google')->url($folderPath);
        
        ///////////////////*********LUU CLOUD GDRIVE********//////////////////////////
        // $destinations = '/' . $folderVideo.'/'.$this->video->uid.'_1_3500.m3u8';
        // $playlistPaths = storage_path('app/videos/' . $destinations);
        // $playlistContents = \File::get($playlistPaths);

        // $updatedContents = preg_replace_callback('/(' . preg_quote($uid) . '_.*)\.ts/', function($matchess) use ($files) {
        //     foreach ($files as $file) {
        //         if (strpos($file['name'], $matchess[1]) !== false) {
        //             return 'http://127.0.0.1:8000/proxy?id=' . urlencode($file['basename']);
        //         }
        //     }
        // }, $playlistContents);
        // \File::put($playlistPaths, $updatedContents);

        ////////////LUU STORE LOCAL //////////
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

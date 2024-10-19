<?php

namespace App\Http\Livewire\Video;

use GuzzleHttp\Client;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;
use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;
class ShowVideo extends Component
{
    use WithFileUploads;
    public $videoFile;
    
    public function render()
    {
        return view('livewire.video.show-video')->extends('main');
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
    public function uploadToGdrive()
    {
       
         $this->gDrive();
         // Kiểm tra nếu có file upload
         if ($this->videoFile) {
            // Lấy tên file và đường dẫn tạm thời của file
            $fileName = $this->videoFile->getClientOriginalName();
            $filePath = $this->videoFile->getRealPath();
           
            // Upload file lên Google Drive
            Storage::disk('google')->put($fileName, fopen($filePath, 'r+'));

            // Thông báo sau khi upload thành công
            session()->flash('message', 'Video uploaded successfully to Google Drive.');
        } else {
            session()->flash('error', 'No video file selected.');
        }
    }
    public function showAll(){
        $this->gDrive();
        $file=Storage::disk('google')->allFiles();
        // $firstFile=$file[4];
        foreach($file as $first){
            $detail[] = Storage::disk('google')->url($first);
        }
       
        dump($detail);
    }
    public function proxy(){
        $file_id = $_GET['id'];
        $client = new Client();
        $response = $client->get("https://drive.google.com/uc?id={$file_id}");
        // Xuất nội dung để stream
        echo $response->getBody();
    }

}

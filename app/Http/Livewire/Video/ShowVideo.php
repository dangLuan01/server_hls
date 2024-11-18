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
            // // Lấy tên file và đường dẫn tạm thời của file
            // $fileName = $this->videoFile->getClientOriginalName();
            // $filePath = $this->videoFile->getRealPath();
           
            // $files=Storage::disk('google')->directories();
            // //$firstFile=$file[0];
            
            // foreach($files as $first){
            //     $detail[] = Storage::disk('google')->getMetadata($first);
            // }
    
            // foreach($detail as $folder){
            //     if($folder['name']=='123452024-1-1'){
            //         Storage::disk('google')->put($folder['path'].'/'.$fileName, fopen($filePath, 'r+'));
            //     }
                
            // }
            // Upload file lên Google Drive
            $fileName = $this->videoFile->getClientOriginalName();
            $filePath =  $this->videoFile->getRealPath();
           
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
        // $folders=Storage::disk('google')->directories();
        // foreach($folders as $first){
        //     $detail[] = Storage::disk('google')->getMetadata($first);
        // }   
        // $directoryVideo = storage_path('app/videos/16714aef79e74d2024-10-20');
        // $files = \File::files($directoryVideo);
        // foreach($detail as $folder){
        //     if($folder['name'] == '16714aef79e74d2024-10-20'){
        //         foreach ($files as $file) { 
        //                 if($file->getExtension() === 'png'){
        //                         // Lấy tên file và đường dẫn tạm thời của file
        //                         $fileName = $file->getFilename();
        //                         $filePath = $file->getRealPath();
                               
        //                         // Upload file lên Google Drive
        //                         Storage::disk('google')->put($folder['path'].'/'.$fileName, fopen($filePath, 'r+'));
        //                         sleep(1);
        //                 }
        //             }
                   
        //         }
        //     }
        //  $files=Storage::disk('google')->directories();
        // //$firstFile=$file[0];
        
        // foreach($files as $first){
        //     $detail[] = Storage::disk('google')->getMetadata($first);
            
        // }

        // foreach($detail as $folder){
        //     if($folder['name']=='16713c0e9026c72024-10-19'){
        //         $filess[] = Storage::disk('google')->allFiles($folder['path']);
        //     }
        //     // $folders[]=[
        //     //     'name'=> $folder['name'],
        //     //     'id'=> $folder['path'],
        //     // ];
            
        // }
        // $files = Storage::disk('google')->allFiles('1HntGWT0BejDfnFGdNjkur4vSN8vx9UYC');
        // foreach($files as $file){
        //     $publicFile[] = Storage::disk('google')->url($file);
        // }
        $folder=Storage::disk('google')->directories('1L0fTrjRzh3o0ReTbo7ZprIbrJBScO1Zt');
        dump($folder);
    }
    public function proxy(){
        $file_id = $_GET['id'];
        $client = new Client();
        $response = $client->get("https://drive.google.com/uc?id={$file_id}");
        // Xuất nội dung để stream
        echo $response->getBody();
    }
    public function stream(){
        return view('livewire.video.stream');
    }

}

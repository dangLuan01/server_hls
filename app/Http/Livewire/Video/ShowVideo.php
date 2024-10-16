<?php

namespace App\Http\Livewire\Video;

use GuzzleHttp\Client;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShowVideo extends Component
{
    use WithFileUploads;
    public $videoFile;
    
    public function render()
    {
        return view('livewire.video.show-video')->extends('main');
    }
    public function uploadToTikTok()
    {
        // Validate file (optional)
        $this->validate([
            'videoFile' => 'required|file|mimes:png,jpg|max:102400', // Max 100MB
        ]);

        // Lấy file từ Livewire
        $file = $this->videoFile->getRealPath();  // Get real path of the file
        $filename = $this->videoFile->getClientOriginalName();  // Get original file name
        // Gửi request POST với file lên TikTok API
        $client = new Client();

        try {
            $response = $client->request('POST', 'https://ads.tiktok.com/api/v2/i18n/material/image/upload/', [
                'query' => [
                    'aadvid' => '7426281908855865362',
                    'msToken' => '48cEOI0FWQ5yi1AyyYTrQeCn3eN2MkIzF4tEi3ysDSxapIrIkGUUpBDOXilp9FnJ15FpUvTKeGg6Kh4S_rSL6asfQuruU2MbFiO_6ZdEb0o_NqjTfbC9OIbEQwI=',
                    'X-Bogus' => 'DFSzsIcL-egKWqBqtBz3Vt9WcBns',  // Nếu cần thiết
                ],
                'headers' => [
                    'Authorization' => 'Bearer 48cEOI0FWQ5yi1AyyYTrQeCn3eN2MkIzF4tEi3ysDSxapIrIkGUUpBDOXilp9FnJ15FpUvTKeGg6Kh4S_rSL6asfQuruU2MbFiO_6ZdEb0o_NqjTfbC9OIbEQwI=',
                    'X-CSRF-TOKEN' => csrf_token(),
                ],
                'multipart' => [
                    [
                        'name'     => 'Filedata',
                        'contents' => fopen($file, 'r'),  // Mở file để gửi
                        'filename' => $filename,
                    ],
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            session()->flash('message', 'Video uploaded successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Upload failed: ' . $e->getMessage());
        }
    }
}

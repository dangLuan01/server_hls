<div class="container" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
    x-on:livewire-upload-finish="isUploading = false , $wire.fileCompleted()"
    x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
    <div class="mb-3">
        <div x-show="isUploading">
            <h4 class="small font-weight-bold">Upload Video <span class="float-right" x-text="progress + '%'"></span></h4>
            <div class="progress mb-4">
                <div class="progress-bar" role="progressbar" x-bind:style="'width: ' + progress + '%'"></div>
            </div>
        </div>

        <br>
        <form x-show="!isUploading" class="">
            <label for="exampleFormControlTextarea1" class="form-label">Select File Upload (support: mp4,mkv,mov,ts)</label>
            <input class="form-control" type="file" wire:model="videoFile">
        </form>
    </div>
    @error('videoFile')
    <div class="alert alert-danger">
        {{ $message }}
    </div>

    @enderror
    @php
    $uploadMaxFilesize = ini_get('upload_max_filesize');
    echo "Max upload file size: $uploadMaxFilesize";
    @endphp

</div>
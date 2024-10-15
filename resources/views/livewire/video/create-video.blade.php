<div class="container" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
    x-on:livewire-upload-finish="isUploading = false , $wire.fileCompleted()"
    x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
    <div class="mb-3">
        <div class="progress" x-show="isUploading">
            <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                x-bind:style="'width: ' + progress + '%'">
            </div>
        </div>

        <br>
        <form x-show="!isUploading">
            <label for="exampleFormControlTextarea1" class="form-label">Upload</label>
            <input type="file" wire:model="videoFile">
        </form>
    </div>
    @error('videoFile')
    <div class="alert alert-danger">
        {{ $message }}
    </div>

    @enderror
    @php
    $uploadMaxFilesize = ini_get('upload_max_filesize');
    $postMaxSize = ini_get('post_max_size');

    echo "Max upload file size: $uploadMaxFilesize";
    echo "Max post size: $postMaxSize";
    @endphp
    
</div>
<div class="container">
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <form wire:submit.prevent="uploadToTikTok">
        <input type="file" wire:model="videoFile">

        @error('videoFile')
            <span class="error">{{ $message }}</span>
        @enderror

        <button type="submit">Upload to TikTok</button>
    </form>
</div>
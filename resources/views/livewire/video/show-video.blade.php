<div class="container">
    <form wire:submit.prevent="uploadToGdrive">
        <input type="file" wire:model="videoFile">

        @error('videoFile')
        <span class="error">{{ $message }}</span>
        @enderror

        <button type="submit">Upload to Gdrive</button>
    </form>
    @if (session()->has('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if (session()->has('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <form wire:submit.prevent="showAll">
        {{-- <input type="file" wire:model="videoFile"> --}}

        <button type="submit">All</button>
    </form>
</div>
<div @if ($video->processing_precentage < 100) wire:poll @endif>

        <form wire:submit.prevent="update">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Process</label>
                <p>{{ $this->video->processing_precentage }}%</p>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Title</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" wire:model="video.title">
            </div>
            @error('video.title')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"
                    wire:model="video.description"></textarea>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">file m3u8</label>
                <p>{{ asset('videos'.$video->processed_file) }}</p>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Visibility</label>
                <select wire:model="video.visibility">
                    <option value="public">public</option>
                    <option value="private">private</option>
                </select>
            </div>
            <div class="mb-3">
                <button type="submit">SAVE</button>
            </div>
            @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
            @endif
        </form>
</div>
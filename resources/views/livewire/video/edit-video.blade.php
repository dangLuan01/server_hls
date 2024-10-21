<div @if ($video->cloud <= 1) wire:poll @endif class="container">

        <form wire:submit.prevent="update">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">@if($this->video->processing_precentage < 100)
                        EnCodeing @else Encoded @endif</label>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{
                                    $this->video->processing_precentage }}%</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: {{ $this->video->processing_precentage }}%"></div>
                                </div>
                            </div>
                        </div>
            </div>
            @if($video->cloud == 1)
            <div class="mb-3">
                <label class="form-label">Uploading Gdrive</label>
            </div>
            @elseif($video->cloud == 2)
            <div class="mb-3">
                <label class="form-label">Uploaded Success</label>
            </div> 
            @endif
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Name</label>
                <input type="text" class="form-control form-control-sm" id="exampleFormControlInput1"
                    wire:model="video.title">
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
                <label for="exampleFormControlTextarea1" class="form-label">Link Hls</label>
                <p>{{ asset('videos'.$video->processed_file) }}</p>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Visibility</label>
                <select wire:model="video.visibility" class="form-select" style="width: 10%">
                    <option value="public">public</option>
                    <option value="private">private</option>
                </select>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-success btn-circle btn-lg"><i class="fas fa-check"></i></button>
            </div>

            @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
            @endif
        </form>
</div>
{{-- <script src="https://cdn.tutorialjinni.com/hls.js/1.2.1/hls.min.js"></script> --}}
<div class="container">
    @if (!$videos->count())
    <h2>Không có video nào được upload</h2>
    @else
    <table class="table caption-top">
        <caption>List of videos</caption>
        <thead>
            <tr>
                <th scope="col">STT</th>
                <th scope="col">Tile</th>
                <th scope="col">Description</th>
                <th scope="col">HLS</th>
                <th scope="col">Process</th>
                <th scope="col">View</th>
                <th scope="col">Created</th>

                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($videos as $key => $video)
            <tr>
                <th scope="row">{{ $key + 1 }}</th>
                <td>{{ $video->title }}</td>
                <td>{{ $video->description }}</td>
                <td>{{ asset('/videos'.$video->processed_file) }}</td>
                <td>{{ $video->processing_precentage.'%' }}</td>
                <td><video id="video" 
                    width='480px' height='360px' controls autoplay
                    src="{{ asset('/videos'.$video->processed_file) }}" type="application/x-mpegURL">
             </video>
                </td>
                <td>{{ $video->created_at }}</td>
                <td><a href="{{ route('video.edit', $video->uid) }}"><button
                            class="btn btn-warning">EDIT</button></a>||<button wire:click="delete({{ $video->id }})"
                        class="btn btn-danger">Delete</button></td>
            </tr>
            @endforeach
            {{ $videos->links() }}


        </tbody>
    </table>
    @endif
    <script>
        if (Hls.isSupported()) {
          var video = document.getElementById('video');
          var hls = new Hls();
          hls.loadSource(video.src);
          hls.attachMedia(video);
        }else{
            alert("Cannot stream HLS, use another video source");
        }
    </script>
</div>
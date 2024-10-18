<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">My List Videos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @if (!$videos->count())
                <h2>Not found</h2>
                @else
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Stt</th>
                            <th>Name</th>
                            <th>Link HLS</th>
                            <th>Process</th>
                            <th>View</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Stt</th>
                            <th>Name</th>
                            <th>Link HLS</th>
                            <th>Process</th>
                            <th>View</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($videos as $key => $video)
                        <tr id="video{{ $video->id }}">
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $video->title }}</td>
                            <td>{{ asset('/videos'.$video->processed_file) }}</td>
                            <td>{{ $video->processing_precentage.'%' }}</td>
                            <td><video id="video" width='480px' height='360px' controls autoplay
                                    src="{{ asset('/videos'.$video->processed_file) }}" type="application/x-mpegURL">
                                </video>
                            </td>
                            <td>{{ $video->created_at }}</td>
                            @if($video->processing_precentage < 100) <td><a
                                    href="{{ route('video.edit', $video->uid) }}"><button
                                        class="btn btn-warning">EDIT</button></a></td>
                                @else
                                <td><a href="{{ route('video.edit', $video->uid) }}"><button
                                            class="btn btn-warning">EDIT</button></a>||<button
                                        wire:click="delete({{ $video->id }})" data-id="{{ $video->id }}"
                                        onclick="deleteVideo(this)" class="btn btn-danger">Delete</button></td>
                                @endif

                        </tr>
                        @endforeach
                        {{-- {{ $videos->links() }} --}}
                    </tbody>
                </table>

                @endif
            </div>
        </div>
    </div>

</div>
<script>
    function deleteVideo(button) {
        let videoId = button.getAttribute('data-id');
        setTimeout(function() {
            window.location.reload();
        }, 2000); 
    }
</script>
{{-- <script src="https://cdn.tutorialjinni.com/hls.js/1.2.1/hls.min.js"></script>
<script>
    if (Hls.isSupported()) {
      var video = document.getElementById('video');
      var hls = new Hls();
      hls.loadSource(video.src);
      hls.attachMedia(video);
    }else{
        alert("Cannot stream HLS, use another video source");
    }
</script> --}}
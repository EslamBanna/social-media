@section('css')
    @include('posts.css')
@endsection
@include('posts.post_card')
@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function deletePost(id) {
            let response = confirm('are you sure');
            if (response == true) {
                $('#deletePost' + id).submit();
            }
        }
        $('.likePost').on('click', function(){
            var postId = $(this).attr("data-postid");
            $.ajax({
                   url: "{{ url('/post-like') }}" + '/' + postId,
                   type: 'post',
                   data: '',
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   },
                   success: function(data) {
                    var likeElementCount = `.post-likes-count-${postId}`;
                       $(likeElementCount).html(data.data);
                   },
                   error: function(data) {
                       console.log('Error:');
                   }
               });
        });
    </script>
@endsection

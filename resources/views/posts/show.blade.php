   @extends('layouts.app')

   @section('content')
       <div class="container">
           <div class="row">
               <div class="col-12">
                   <div class="card mb-3">
                       @if (count($post->images) > 0)
                           <div id="postCarousel" class="carousel slide" data-ride="carousel">
                               <div class="carousel-inner">
                                   <div class="carousel-item active">
                                       <img src="{{ $post->images[0]['image'] }}" class="d-block w-100" alt="Post Image 1">
                                   </div>
                                   @for ($i = 1; $i < count($post->images); $i++)
                                       <div class="carousel-item">
                                           <img src="{{ $post->images[$i]['image'] }}" class="d-block w-100"
                                               alt="Post Image {{ $i + 1 }}">
                                       </div>
                                   @endfor
                               </div>
                               <a class="carousel-control-prev" href="#postCarousel" role="button" data-slide="prev">
                                   <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                   <span class="sr-only">Previous</span>
                               </a>
                               <a class="carousel-control-next" href="#postCarousel" role="button" data-slide="next">
                                   <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                   <span class="sr-only">Next</span>
                               </a>
                           </div>
                       @endif
                       <div class="card-body">
                           <div class="row">
                               <div class="col-2">
                                   <img src="{{ $post->author->photo }}" alt="Author Profile Picture" width="100"
                                       height="100" class=" rounded-circle">
                               </div>
                               <div class="col-10">
                                   <h5 class="card-title">{{ $post->author->name }}</h5>
                                   <p class="card-text"><small class="text-muted">Posted on
                                           {{ $post->created_at->format('Y-m-d H:i') }}</small>
                                   </p>
                                   <p class="card-text">{{ $post->content }}</p>
                                   <div class="d-flex justify-content-between align-items-center">
                                       <div>
                                           <i class="fas fa-heart"></i> {{ $post->likes_count }} Likes
                                           <button class="btn btn-sm btn-outline-primary">Like</button>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           @if ($post->user_id == Auth::user()->id)
                               <div class="row text-center">
                                   <div class="col-2">
                                       <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary p-2">
                                           <i class="bi bi-pencil-square"></i>
                                       </a>
                                       <form action="{{ route('posts.delete', $post->id) }}" method="POST"
                                           style="display: inline-block" id="deletePost{{ $post->id }}">
                                           @csrf
                                           <a class="btn btn-danger p-2" onclick="deletePost({{ $post->id }})">
                                               <i class="bi bi-trash3"></i>
                                           </a>
                                       </form>

                                   </div>
                               </div>
                           @endif
                       </div>
                   </div>
               </div>
           </div>
           <div class="row mt-4">
               <div class="col-12">
                   <h3>Comments</h3>
                   <div class="comments">
                       @foreach ($post->comments as $comment)
                           <div class="comment mb-3">
                               <div class="row">
                                   <div class="col-2">
                                       <img src="{{ $comment->user->photo }}"
                                           alt="{{ $comment->user->name }}'s Profile Picture" class=" rounded-circle"
                                           width="100" height="100">
                                   </div>
                                   <div class="col-10">
                                       <h5 class="comment-author">{{ $comment->user->name }}</h5>
                                       <p class="comment-text">{{ $comment->comment }}</p>
                                       <p class="comment-date text-muted">
                                           <small>{{ $comment->created_at->format('Y-m-d H:i') }}</small></p>
                                   </div>
                               </div>
                           </div>
                           <hr>
                       @endforeach
                       <div class="new-comment">
                           <label for="new-comment-input">Add a new comment:</label>
                           <textarea id="new-comment-input" rows="3"></textarea>
                           <button onclick="addComment()">Submit</button>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   @endsection
   @section('script')
       <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
       <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
       <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
       <script>
           function deletePost(id) {
               let response = confirm('are you sure');
               if (response == true) {
                   $('#deletePost' + id).submit();
               }
           }

           function addComment() {
               const commentInput = document.getElementById('new-comment-input');
               const newComment = commentInput.value.trim();

               if (newComment !== '') {
                   const commentsList = document.querySelector('.comments');
                   const newCommentElement = document.createElement('li');
                   newCommentElement.innerHTML = `
        <h4>New Comment</h4>
        <p>${newComment}</p>
      `;
                   commentsList.appendChild(newCommentElement);
                   commentInput.value = '';
               }
           }
       </script>
   @endsection

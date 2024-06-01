@foreach ($posts as $post)
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
                                <img src="{{ $post->author->photo }}" alt="Author Profile Picture"
                                    class="img-fluid rounded-circle">
                            </div>
                            <div class="col-10">
                                <h5 class="card-title">{{ $post->author->name }}</h5>
                                <p class="card-text"><small class="text-muted">Posted on
                                        {{ $post->created_at->format('Y-m-d H:i') }}</small>
                                </p>
                                <p class="card-text">{{ $post->content }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-heart"></i>
                                        <a href="{{ route('posts.likes', $post->id) }}">
                                            <span class="post-likes-count-{{$post->id}}">{{ $post->likes_count }} </span> Likes
                                        </a>
                                        {{-- <form action="{{ route('posts.like', $post->id) }}" method="POST" --}}
                                            {{-- style="display: inline-block"> --}}
                                            {{-- @csrf --}}
                                            <button type="submit" class="btn btn-sm btn-outline-primary likePost" data-postid="{{$post->id}}">Like</button>
                                        {{-- </form> --}}
                                    </div>
                                    <div>
                                        <i class="fas fa-comments"></i> {{ $post->comments_count }} Comments
                                        <a href="{{ route('posts.show', $post->id) }}"
                                            class="btn btn-sm btn-outline-primary">Comment</a>
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
    </div>
@endforeach

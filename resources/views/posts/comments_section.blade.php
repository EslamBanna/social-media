<div class="row mt-4">
    <div class="col-12">
        <h3>Comments</h3>
        <div class="comments">
            @foreach ($comments as $comment)
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
                                <small>{{ $comment->created_at->format('Y-m-d H:i') }}</small>
                            </p>
                        </div>
                    </div>
                </div>
                <hr>
            @endforeach
            <div class="new-comment">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="new-comment-input"
                        placeholder="Enter Comment">
                </div>
                <button onclick="addComment()">Submit</button>
            </div>
        </div>
    </div>
</div>
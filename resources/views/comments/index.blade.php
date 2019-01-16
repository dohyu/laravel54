<div class="page-header">
    <h4>댓글</h4>
</div>

<div class="form__new__comment">
    @if ($currentUser)
        @include('comments.partial.create')
    @else
        @include('comments.partial.login')
    @endif
</div>

<div class="list__comment">
    @forelse($comments as $comment)
        @include('comments.partial.comment', [
            'parentId' => $comment->id,
            'isReply' => false,
        ])
    @empty
    @endforelse
</div>

@section('script')
    @parent

    <script>
        // 댓글 삭제
        $('.btn__delete__comment').on('click', function(e) {
            var commentId = $(this).closest('.item__comment').data('id'),
                articleId = $('#item__article').data('id');

            if (confirm('댓글을 삭제합니다.')) {
                $.ajax({
                    type: 'DELETE',
                    url: '/comments/' + commentId,
                }).then(function() {
                    $('#comment_' + commentId).fadeOut(1000, function () { $(this).remove(); });
                });
            }
        })

        // 대댓글 수정 폼 토글
        $('.btn__edit__comment').on('click', function(e) {
            var el__create = $(this).closest('.item__comment').find('.media__create__comment').first();
            var el__edit = $(this).closest('.item__comment').find('.media__edit__comment').first();

            el__create.hide('fast');
            el__edit.toggle('fast').end().find('textarea').first().focus();
        });

        // 대댓글 폼 토글
        $('.btn__reply__comment').on('click', function(e) {
            var el__create = $(this).closest('.item__comment').find('.media__create__comment').first();
            var el__edit = $(this).closest('.item__comment').find('.media__edit__comment').first();

            el__edit.hide('fast');
            el__create.toggle('fast').end().find('textarea').focus();
        });

        // votes
        $('.btn__vote__comment').on('click', function(e) {
            var self = $(this),
                commentId = self.closest('.item__comment').data('id');

            $.ajax({
                type: 'POST',
                url: '/comments/' + commentId + '/votes',
                data: {
                    vote: self.data('vote')
                }
            }).then(function (data) {
                console.log(data);
                self.find('span').html(data.value).fadeIn();
                self.attr('disabled', 'disabled');
                self.siblings().attr('disabled', 'disabled');
            });
        });
    </script>
@endsection

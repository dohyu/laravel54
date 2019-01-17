<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
    <label for="title">제목</label>
    <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" class="form-control" />
    {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('tags') ? 'has-error' : '' }}">
    <label for="tags">태그</label>
    <select name="tags[]" id="tags" class="form-control" multiple>
        @foreach($allTags as $tag)
        <option value="{{ $tag->id }}" {{ $article->tags->contains($tag->id) ? 'selected="selected"' : '' }}>{{ $tag->name }}</option>
        @endforeach
    </select>
    {!! $errors->first('tags', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
    <label for="">본문</label>
    <textarea name="content" id="content" cols="30" rows="10" class="form-control">{{ old('content', $article->content) }}</textarea>
    {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
</div>

@section('script')
    @parent
    <script>
        var form = $('form').first();
        var dropzone = $('div.dropzone');
        var dzControl = $('label[for=my-dropzone]>small');

        // dropzone
        Dropzone.autoDiscover = false;

        // 드롭존 인스턴스 생성
        var myDropzone = new Dropzone('div#my-dropzone', {
            url: '/attachments',
            paramName: 'files',
            maxFilesize: 3,
            acceptedFiles: '.{{ implode(',.', config('project.mimes')) }}',
            uploadMultiple: true,
            params: {
                _token: $('meta[name=csrf-token]').attr('content'),
                article_id: '{{ $article->id }}'
            },
            dictDefaultMessage: '<div class="text-center text-muted">' +
                '<h2>첨부할 파일을 끌어다 놓으세요!</h2>' +
                '<p>(또는 클릭하셔도 됩니다.)</p></div>',
            dictFileTooBig: '파일당 최대 크기는 3MB입니다.',
            dicInvalidFileType: '{{ implode(',', config('project.mimes')) }} 파일만 가능합니다.',
            addRemoveLinks: true
        });

        // 파일 업로드 성공 이벤트 리스너
        myDropzone.on('successmultiple', function(file, data) {
            for (var i = 0, len = data.length; i < len; i++) {
                handleFormElement(data[i].id);

                // 성공한 파일 애트리뷰트를 파일 인스턴스에 추가
                file[i]._id = data[i].id;
                file[i]._name = data[i].filename;
                file[i]._url = data[i].url;

                // 이미지 파일일 경우 handleContent() 호출
                if (/^image/.test(data[i].mime)) {
                    handleContent('content', data[i].url);
                }
            }
        });

        // 파일 삭제 이벤트 리스너
        myDropzone.on('removedfile', function(file) {
            // 사용자가 이미지를 삭제하면 UI의 DOM레벨에서 사라진다.
            // 서버에서도 삭제해야 하므로 Ajax 요청
            $.ajax({
                type: 'DELETE',
                url: '/attachments/' + file._id,
                data: { _token: $('meta[name=csrf-token]').attr('content') }
            }).then(function(data) {
                handleFormElement(data.id, true);

                if (/^image/.test(data.mime)) {
                    handleContent('content', data.url, true);
                }
            });
        });

        // attachments[] 숨은 필드 생성 또는 제거
        var handleFormElement = function(id, remove) {
            if (remove) {
                $('input[name="attachments[]"][value="' + id +'"]').remove();
                return ;
            }

            $('<input>', {
                type: 'hidden',
                name: 'attachments[]',
                value: id
            }).appendTo(form);
        };

        // 컨텐트 영역의 캐럿 위치에 이미지 마크다운 삽입
        var handleContent = function(objId, imgUrl, remove) {
            var caretPos = document.getElementById(objId).selectionStart;
            var content = $('#' + objId).val();
            var imgMarkdown = '![](' + imgUrl + ')';

            if (remove) {
                $('#' + objId).val(
                    content.replace(imgMarkdown, '')
                );
                return;
            }

            $('#' + objId).val(
                content.substring(0, caretPos) +
                imgMarkdown + '\n' +
                content.substring(caretPos)
            );
        };

        // toggle
        dzControl.on('click', function(e) {
            dropzone.fadeToggle(0);
            dzControl.fadeToggle(0);
        });

        $("#tags").select2({
            placeholder: '태그를선택하세요(최대3개)',
            maximumSelectionLength: 3
        });
  </script>
@endsection

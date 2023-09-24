@extends('admin.layout.default')
{{-- @section('styles')
    <script src="https://cdn.tiny.cloud/1/cow8fdojqf1kv74w7720bt6qezp69g78azpg83f28lcgpua2/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
@endsection --}}
@section('content')
    <div class="content">

        @include('admin.layout.breadcrumb')

        <div class="card">

            {{-- <textarea>Welcome to TinyMCE!</textarea> --}}

            <div class="card-body">
                <form class="needs-validation" novalidate="" action="{{ route('cms.edit',[$type]) }}" method="post">
                    @csrf
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="type" value="{{ $type }}">
                                <textarea name="content" id="editor" required>{{ old('content') != '' && old('content') != cmsdata($type) ? old('content') : cmsdata($type) }}</textarea>
                                @error('type')
                                    <span class="text-danger">{{ $message }}</span><br>
                                @enderror
                                @error('content')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            {!! form_action_buttons(route('dashboard')) !!}
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script src="{{ url('resources/views/admin/settings/settings.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.12.1/ckeditor.js"></script>
    <script type="text/javascript">
        $(function(params) {
            CKEDITOR.replace('editor', {
                height: '200',
            });
            setTimeout(() => $('#cke_46').remove(), 400);
        })
    </script>
    {{-- <script>
        tinymce.init({
          selector: 'textarea',
          plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
          toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
          tinycomments_mode: 'embedded',
          tinycomments_author: 'Author name',
          mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
          ],
        });
      </script> --}}
@endsection

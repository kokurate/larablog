@extends('back.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Add New Post')
@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <h2 class="page-title">
            Add New Post
          </h2>
        </div>
      </div>
    </div>
</div>

  <form action="{{ route('author.posts.create') }}" method="post" id="addPostForm" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <div class="mb-3">
                        <label class="form-label">Post title</label>
                        <input type="text" class="form-control" name="post_title" placeholder="Enter post title">
                        <span class="text-danger error-text post_title_error"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Post Content </label>
                        <textarea class="ckeditor form-control" name="post_content" rows="6" placeholder="Content.." id="post_content"></textarea>
                        <span class="text-danger error-text post_content_error"></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-label">Post category</div>
                        <select class="form-select" name="post_category">
                          <option value="">No Selected</option>
                          {{-- @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                          @endforeach --}}
                          @foreach(\App\Models\SubCategory::all() as $category)
                            <option value="{{ $category->id }}">{{ $category->subcategory_name }}</option>
                          @endforeach
                        </select>
                        <span class="text-danger error-text post_category_error"></span>
                    </div>
                    <div class="mb-3">
                        <div class="form-label">Featured image</div>
                        <input type="file" class="form-control" name="featured_image" id="image-input">
                        <span class="text-danger error-text featured_image_error"></span>
                    </div>
                    <div class="image_holder mb-2" style="max-width: 250px">
                        <img src="" alt="" class="img-thumbnail" id="image-previewer" data-ijabo-default-img=''>
                    </div>
                    <div class="mb-3">
                        <lavel class="form-label">Post Tags</lavel>
                        <input type="text" class="form-control" name="post_tags">
                    </div>
                    <button type="submit" class="btn btn-primary">Save post</button>
                </div>
            </div>
        </div>
    </div>
  </form>

@endsection

@push('scripts')

  <!-- CK Editor -->
  <script src="/ckeditor/ckeditor.js"></script>

  <script>

    // $(function (){
    //     $('input[type="file"][name="featured_image"]').ijaboViewer({
    //         preview:'#image-previewer',
    //         imageShape:'rectangular',
    //         allowedExtensions:['jpg','jpeg','png'],
    //         onErrorShape:function (message, element) {
    //             alert(message);
    //         },
    //         onInvalidType:function(message,element){
    //             alert(message);
    //         }
    //     });
    // });

    // image preview

    $(function () {
    // Get references to the file input and image element
    var fileInput = document.getElementById('image-input');
    var imagePreview = document.getElementById('image-previewer');

    // Add an event listener to the file input
    fileInput.addEventListener('change', function () {
        // Check if a file is selected
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                // Update the image preview
                imagePreview.src = e.target.result;
            };

            reader.readAsDataURL(fileInput.files[0]);

            // Check allowed extensions
            var allowedExtensions = ['jpg', 'jpeg', 'png'];
            var fileExtension = fileInput.files[0].name.split('.').pop().toLowerCase();
            var isAllowedExtension = allowedExtensions.indexOf(fileExtension) !== -1;

            if (!isAllowedExtension) {
                alert('Allowed image extensions: jpg, jpeg, png.');
                // Clear the file input to prevent submission
                fileInput.value = '';
            } else {
                // Check image shape (assuming you have specific dimensions for rectangular images)
                var image = new Image();
                image.src = window.URL.createObjectURL(fileInput.files[0]);
                image.onload = function () {
                    var width = this.width;
                    var height = this.height;

                    // Define your rectangular shape criteria here
                    var isRectangular = width > height;

                    if (!isRectangular) {
                        alert('Image shape should be rectangular.');
                        // Clear the file input to prevent submission
                        fileInput.value = '';
                    }
                };
            }
        }
    });
});


    $('form#addPostForm').on('submit', function(e){
        e.preventDefault();
        toastr.remove(); 
        var post_content = CKEDITOR.instances.post_content.getData();
        var form = this;
        var fromdata = new FormData(form);
            fromdata.append('post_content', post_content);

        $.ajax({
            url:$(form).attr('action'),
            method:$(form).attr('method'),
            data:fromdata,
            processData:false,
            dataType :'json',
            contentType:false,
            beforeSend:function(){
                $(form).find('span.error-text').text('');
            },
            success:function(response){
                toastr.remove();
                if(response.code == 1){
                    $(form)[0].reset();
                    $('div.image_holder').html('');
                    // CKEDITOR.instances.post_content.setData('';)
                    // $('div.image_holder').find('img').attr('src','');
                    CKEDITOR.instances.post_content.setData('');
                    $('input[name="post_tags"]').amsifySuggestags();
                    toastr.success(response.msg);
                }else{
                    toastr.error(response.msg);
                }
            },
            error:function(response){
                toastr.remove();
                $.each(response.responseJSON.errors, function(prefix,val){
                    $(form).find('span.'+prefix+'_error').text(val[0]);
                });
            }
        });
    });


  </script>

@endpush
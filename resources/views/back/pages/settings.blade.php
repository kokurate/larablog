@extends('back.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Settings')
@section('content')

    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title mb-3">
                Settings
            </h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
          <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            <li class="nav-item" role="presentation">
              <a href="#tabs-home-8" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">General Settings</a>
            </li>
            <li class="nav-item" role="presentation">
              <a href="#tabs-profile-8" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Logo & Favicon</a>
            </li>
            <li class="nav-item" role="presentation">
              <a href="#tabs-activity-8" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">Social Media</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane fade active show" id="tabs-home-8" role="tabpanel">
              <div>
                @livewire('author-general-settings')
              </div>
            </div>
            <div class="tab-pane fade" id="tabs-profile-8" role="tabpanel">
              <div>
                <div class="row">
                  <div class="col-md-6">
                    <h3>Set blog logo</h3>
                    <div class="mb-2" style="max-width: 200px">
                      {{-- <img src="" alt="" class="img-thumbnail" id="logo-image-preview" data-ijabo-default-img="{{ \App\Models\Setting::find(1)->blog_logo }}"> --}}
                      <img src="" alt="" class="img-thumbnail" id="logo-image-preview">
                    </div>
                      <form action="{{ route('author.change-blog-logo') }}" method="POST" id="changeBlogLogoForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                          <input type="file" name="blog_logo" class="form-control" id="logo-file-input">
                        </div>
                        <button type="submit" class="btn btn-primary">Change Logo</button>
                      </form>
                    </div>
                    <div class="col-md-6">
                      <h3>Set blog favicon</h3>
                      <div class="mb-2" style="max-width: 50px">
                        <img src="" alt="" class="img-thumbnail" id="favicon-image-preview">
                      </div>
                      <form action="{{ route('author.change-blog-favicon') }}" method="POST" id="changeBlogFaviconForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                          <input type="file" name="blog_favicon" class="form-control" id="favicon-file-input">
                        </div>
                        <button class="btn btn-primary">Change favicon</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="tabs-activity-8" role="tabpanel">
              <div>
              @livewire('author-blog-social-media-form')
              </div>
            </div>
          </div>
        </div>
      </div>

@endsection

@push('scripts')
  <!-- Ijabo viewer -->
      {{-- <script>
        $('input[name="blog_logo"]').ijaboViewer({
            preview: '#logo-image-preview',
            imageShape: 'rectangular',
            allowedExtensions:['jpg','jpeg','png'],
            onErrorShape: function(message, element){
              alert(message);
            }
            onInvalidType: function(message, element){
              alert(message);
            }
            onSuccess:function(message, element){

            }
        })
      </script> --}}

    <script>
       // Function to update the image preview
  function updateImagePreview(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $('#logo-image-preview').attr('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  // Attach an event listener to the file input
  $('#logo-file-input').change(function () {
    // Get the selected file
    var file = this.files[0];

    // Check allowed extensions
    var allowedExtensions = ['jpg', 'jpeg', 'png'];
    var fileExtension = file.name.split('.').pop().toLowerCase();
    var isAllowedExtension = allowedExtensions.indexOf(fileExtension) !== -1;

    if (!isAllowedExtension) {
      alert('Allowed image extensions: jpg, jpeg, png.');
      // Clear the file input to prevent submission
      $('#logo-file-input').val('');
    } else {
      // Load the image and update the image preview
      updateImagePreview(this);

      // Check image shape (assuming you have specific dimensions for rectangular images)
      var image = new Image();
      image.src = window.URL.createObjectURL(file);
      image.onload = function () {
        var width = this.width;
        var height = this.height;

        // Define rectangular shape criteria
        var isRectangular = width > height;

        if (!isRectangular) {
          alert('Image shape should be rectangular.');
          // Clear the file input to prevent submission
          $('#logo-file-input').val('');
        }
      };
    }
  });

  // Handle form submission with AJAX
  $('#changeBlogLogoForm').submit(function (e) {
    e.preventDefault(); // Prevent the default form submission

    var formData = new FormData(this);

    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data: formData,
      contentType: false,
      processData: false,
      success: function (data) {
        // Handle the success response from the backend
        toastr.remove();
        if (data.status == 1) {
          toastr.success(data.msg);
          $('#changeBlogLogoForm')[0].reset();
          Livewire.emit('updateTopHeader');
        } else {
          toastr.error(data.msg);
        }
      },
      error: function (error) {
        // Handle the error response from the backend
        console.error('Image upload failed:', error);
      },
    });
  });

    </script>


    <!-- Fav Icon  -->
    <!-- Preview -->
    <script>
          $(document).ready(function () {
          // Function to update the favicon image preview
          function updateFaviconPreview(input) {
            if (input.files && input.files[0]) {
              var reader = new FileReader();

              reader.onload = function (e) {
                $('#favicon-image-preview').attr('src', e.target.result);
              };

              reader.readAsDataURL(input.files[0]);
            }
          }

          // Attach an event listener to the favicon file input
          $('#favicon-file-input').change(function () {
            // Update the favicon image preview when a file is selected
            updateFaviconPreview(this);
          });
        });

    // Handle the json
     // Handle form submission with AJAX
    $('#changeBlogFaviconForm').submit(function (e) {
      e.preventDefault(); // Prevent the default form submission

      var formData = new FormData(this);

      $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
          // Handle the success response from the backend
          toastr.remove();
          if (data.status == 1) {
            toastr.success(data.msg);
            // $('#changeBlogFaviconForm')[0].reset();

            // Add a delay of 2 seconds before redirecting
            setTimeout(function () {
              // Redirect back to the previous page
              var currentURL = window.location.href;
              window.location.href = currentURL;
            }, 2000);
            
          } else {
            toastr.error(data.msg);
          }
        },
        error: function (error) {
          // Handle the error response from the backend
          console.error('Image upload failed:', error);
        },
      });
    });

    </script>

@endpush
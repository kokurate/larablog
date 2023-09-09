@extends('back.layouts.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'All Posts')
@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <h2 class="page-title">
            All Posts
          </h2>
        </div>
      </div>
    </div>
</div>

@livewire('all-posts')

@endsection

@push('scripts')

  <script>
     window.addEventListener('deletePost', function(event) {
        Swal.fire({
            title: event.detail.title,
            html: event.detail.html,
            icon: 'warning', // Use a warning icon
            showCloseButton: true,
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Yes, delete',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            width: '400px',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('deletePostAction', event.detail.id);
            }
        });
    });
  </script>

@endpush
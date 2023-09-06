@extends('back.layouts.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pagetitle : 'Login' )
@section('content')

<div class="page page-center">
    <div class="container container-tight py-4">
      <div class="text-center mb-4">
        <a href="." class="navbar-brand navbar-brand-autodark"><img src="{{ \App\Models\Setting::find(1)->blog_logo }}" height="36" alt="Kokurate"></a>
      </div>
      <div class="card card-md">
        <div class="card-body">
          <h2 class="h2 text-center mb-4">Login to your account</h2>
          @livewire('author-login-form')
        </div>

    </div>
  </div>

@endsection


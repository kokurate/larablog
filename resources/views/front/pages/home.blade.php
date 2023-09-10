@extends('front.layouts.pages-layout')
@section('pageTitle',isset($pageTitle) ? $pageTitle : 'Welcome to Larablog')
@section('content')


        <div class="row no-gutters-lg">
          <div class="col-12">
            <h2 class="section-title">Latest Articles</h2>
          </div>
          <div class="col-lg-8 mb-5 mb-lg-0">
            <div class="row">
              <div class="col-12 mb-4">
                @if(single_latest_post())
                <article class="card article-card">
                  <a href="{{ route('read_post', single_latest_post()->post_slug) }}">
                    <div class="card-image">
                      <div class="post-info">
                        <span class="text-uppercase">{{ date_formatter(single_latest_post()->created_at) }}</span>
                        <span class="text-uppercase">{{ readDuration(single_latest_post()->post_title,single_latest_post()->post_content) }} @choice('min|mins', readDuration(single_latest_post()->post_title, single_latest_post()->post_content)) read</span>
                      </div>
                      <img loading="lazy" decoding="async" src="/storage/images/post_images/{{ single_latest_post()->featured_image }}" alt="Post Thumbnail" class="w-100">
                    </div>
                  </a>
                  <div class="card-body px-0 pb-1">
                    <h2 class="h1">
                      <a class="post-title" href="{{ route('read_post', single_latest_post()->post_slug) }}">{{ single_latest_post()->post_title }}</a>
                    </h2>
                    <p class="card-text">{!! Str::ucfirst(words(single_latest_post()->post_content, 35)) !!}</p>
                    <div class="content">
                      <a class="read-more-btn" href="{{ route('read_post', single_latest_post()->post_slug) }}">Read Full Article</a>
                    </div>
                  </div>
                </article>
                @endif
              </div>
              @foreach(latest_home_6posts() as $item )
              <div class="col-md-6 mb-4">
                <article class="card article-card article-card-sm h-100">
                  <a href="{{ route('read_post', $item->post_slug) }}">
                    <div class="card-image">
                      <div class="post-info">
                        <span class="text-uppercase">{{ date_formatter($item->created_at) }}</span>
                        <span class="text-uppercase">{{ readDuration($item->post_title, $item->post_content) }} @choice('min|mins', readDuration($item->post_title, $item->post_content)) read</span>
                      </div>
                      <img loading="lazy" decoding="async" src="/storage/images/post_images/{{ $item->featured_image }}" alt="Post Thumbnail" class="w-100">
                    </div>
                  </a>
                  <div class="card-body px-0 pb-0">
                    <ul class="post-meta mb-2">
                      <li>
                        <a href="{{ route('category_posts', $item->subcategory->slug) }}">{{ $item->subcategory->subcategory_name }}</a>
                      </li>
                    </ul>
                    <h2>
                      <a class="post-title" href="{{ route('read_post', $item->post_slug) }}">{{ $item->post_title }}</a>
                    </h2>
                    <p class="card-text">{!! Str::ucfirst(words($item->post_content,25)) !!}</p>
                    <div class="content">
                      <a class="read-more-btn" href="{{ route('read_post', $item->post_slug) }}">Read Full Article</a>
                    </div>
                  </div>
                </article>
              </div>
              @endforeach
          
            </div>
          </div>
          <div class="col-lg-4">
            <div class="widget-blocks">
              <div class="row">
                <div class="col-lg-12">
                  <div class="widget">
                    <div class="widget-body">
                      <img loading="lazy" decoding="async" src="/front/images/author.jpg" alt="About Me" class="w-100 author-thumb-sm d-block">
                      <h2 class="widget-title my-3">Hootan Safiyari</h2>
                      <p class="mb-3 pb-2">Hello, I’m Hootan Safiyari. A Content writer, Developer and Storyteller. Working as a Content writer at CoolTech Agency. Quam nihil …</p> <a href="about.html" class="btn btn-sm btn-outline-primary">Know More</a>
                    </div>
                  </div>
                </div>
                @if(recommended_posts())
                <div class="col-lg-12 col-md-6">
                  <div class="widget">
                    <h2 class="section-title mb-3">Recommended</h2>
                    <div class="widget-body">
                      <div class="widget-list">
                        @foreach(recommended_posts() as $item)
                        <a class="media align-items-center" href="{{ route('read_post', $item->post_slug) }}">
                          <img loading="lazy" decoding="async" src="/storage/images/post_images/{{ $item->featured_image }}" alt="Post Thumbnail" class="w-100">
                          <div class="media-body ml-3">
                            <h3 style="margin-top:-5px">{{ $item->post_title }}</h3>
                            <p class="mb-0 small">{!! Str::ucfirst(words($item->post_content,7)) !!}</p>
                          </div>
                        </a>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>
                @endif
                <div class="col-lg-12 col-md-6">
                  <div class="widget">
                    <h2 class="section-title mb-3">Categories</h2>
                    <div class="widget-body">
                      <ul class="widget-list">
                        <li><a href="#!">computer<span class="ml-auto">(3)</span></a></li>
                        <li><a href="#!">cruises<span class="ml-auto">(2)</span></a></li>
                        <li><a href="#!">destination<span class="ml-auto">(1)</span></a></li>
                        <li><a href="#!">internet<span class="ml-auto">(4)</span></a></li>
                        <li><a href="#!">lifestyle<span class="ml-auto">(2)</span></a></li>
                        <li><a href="#!">news<span class="ml-auto">(5)</span></a></li>
                        <li><a href="#!">telephone<span class="ml-auto">(1)</span></a></li>
                        <li><a href="#!">tips<span class="ml-auto">(1)</span></a></li>
                        <li><a href="#!">travel<span class="ml-auto">(3)</span></a></li>
                        <li><a href="#!">website<span class="ml-auto">(4)</span></a></li>
                        <li><a href="#!">hugo<span class="ml-auto">(2)</span></a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>  

@endsection
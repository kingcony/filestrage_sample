@extends('layouts.main')
@section("title")
書籍
@endsection

@section("content")
<h1>書籍</h1>
<div class="d-flex flex-wrap">
  @foreach($books as $book)
  <div class="card mb-3 ml-3" style="width: 18rem;">
    <a href="{{ url(Storage::disk('icons')->url($book->img)) }}" target="_blank">
      <img class="card-img-top" src="{{ url(Storage::disk('icons')->url($book->img)) }}">
    </a>
    <div class="card-body">
      <h5 class="card-title">{{ $book->title }}</h5>
      <p class="card-text">{{ $book->memo }}</p>
    </div>
  </div>
  @endforeach
</div>

@endsection

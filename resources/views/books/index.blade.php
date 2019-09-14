@extends('layouts.main')
@section("title")
書籍
@endsection

@section("content")
<h1>書籍</h1>
<table class="table table-stripe">
  <tr>
    <th>ID</th>
    <th>画像</th>
    <th>タイトル</th>
  </tr>
  @foreach($books as $book)
  <tr>
    <td>{{ $book->id }}</td>
    <td>
      @if ( $book->img != "" )
      <a href="{{ url(Storage::disk('icons')->url($book->img)) }}" target="_blank">
        <img width="50" src="{{ url(Storage::disk('icons')->url($book->img)) }}">
      </a>
      @endif
    </td>
    <td>{{ $book->title }}</td>
    <td>
      <a href="{{ route('books.edit',$book->id) }}" class="btn btn-primary">更新</a>
      <form action="{{ route('books.destroy',$book->id) }}" id="form_{{ $book->id }}"
        method="post" style="display:inline">
        {{ csrf_field() }}
        {{ method_field('delete') }}
        <a href="#" data-id="{{ $book->id }}" onClick="delAdmin(this);" class="btn btn-danger">削除</a>
      </form>
    </td>
  </tr>
  @endforeach
</table>
<a href="{{ route('books.create') }}" class="btn btn-primary">追加</a>
@endsection

@section('js')
  <script>
    function delAdmin(e) {
      if ( !confirm("削除しますか？") ) {
        return;
      }
      document.getElementById("form_"+e.dataset.id).submit();
    }
  </script>
@endsection
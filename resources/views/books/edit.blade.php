@extends('layouts.main')
@section("title")
更新
@endsection

@section("content")
<div class="container">
  <h1>更新</h1>
  <form method="post" action="{{ route('books.update',$book->id) }}" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
      <label for="title">タイトル</label>
      <input type="text" class="form-control" name="title" id="title" value="{{ old('title',$book->title) }}">
    </div>
    <div class="form-group">
      <label for="memo">内容</label>
      <textarea class="form-control" rows="3" name="memo" id="memo">{{ old('memo',$book->memo) }}</textarea>
    </div>
    <div class="form-group">
      <label for="imghead">作品アイコン</label>
      <div id="imghead">
        <button id="btnImgHead" class="btn btn-primary" type="button">画像アップロード</button>
      </div>
    </div>
    <input type="submit" class="btn btn-primary" value="登録">
    <a href="{{ route('books.index') }}" class="btn btn-primary">一覧へ</a>
  </form>
</div>

<div class="modal" id="modal-imgarea" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">画像アップロード</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frmImgUpload" action="" method="post" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="x1" id="x1" value="">
          <input type="hidden" name="y1" id="y1" value="">
          <input type="hidden" name="sx" id="sx" value="">
          <input type="hidden" name="sy" id="sy" value="">
          <input type="hidden" name="w" id="w" value="">
          <input type="hidden" name="h" id="h" value="">
          <input type="hidden" name="route" id="route" value="">
          <div class="form-group">
            <label for="imgf">画像</label>
            <input type="file" name="imgf" id="imgf" class="image">
          </div>
          <div id="imgprevArea" class="">
            <img id="prvimg" style="display:none">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
        <button type="button" class="btn btn-primary" id="btnImgCropedSave">保存</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('css')
  <link href="{{ mix('css/cropper.min.css') }}" rel="stylesheet">
@stop

@section('js')
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ mix('js/cropper.min.js') }}"></script>
<script>
$(function() {
  $("#icon").on("change",function(e) {
    var file = e.target.files[0];
    var fr = new FileReader();
    fr.onload = function() {
      var dataUri = this.result;
      $("#iconview").attr("src",dataUri);
    }
    fr.readAsDataURL(file);
  });

  $("#btnImgHead").on("click",function(e){
    openImgModal();
  });

  var p = $("#prvimg");
  $("body").on("change",".image",function(){
    var imgReader = new FileReader();
    imgReader.readAsDataURL(document.querySelector(".image").files[0]);
    imgReader.onload = function(evt) {
      p.attr("src",evt.target.result).fadeIn();
      console.log("drawed");
      setCropImg();
    }
  });

  $("#btnImgCropedSave").on("click",function(e) {
    uploadCropedImage();
  });
});

function openImgModal() {
  $("#modal-imgarea").modal();
}

var cropper = null;
function setCropImg() {
  var p = $("#prvimg");
  p.cropper("destroy");
  p.cropper({
    aspectRatio: 1/1,
    crop: function(evt) {
      console.log("crop");
      $("#x1").val(evt.detail.x);
      $("#y1").val(evt.detail.y);
      $("#w").val(evt.detail.width);
      $("#h").val(evt.detail.height);
      $("#sx").val(evt.detail.scaleX);
      $("#sy").val(evt.detail.scaleY);
      $("#route").val(evt.detail.rotate);
    }
  });
  var cropper = p.data('cropper');
}

function uploadCropedImage() {
  var frm = new FormData($("#frmImgUpload").get(0));
  $.ajax({
    url: "{{ route('books.upload',$book->id) }}",
    type: "POST",
    dataType: "json",
    data: frm,
    cache: false,
    processData: false,
    contentType: false,
    success: function(rec) {
      console.log(rec);
      if ( rec.ret == "false" ) {
        alert(rec.mess);
      } else {
        alert("保存しました");
      }
    },
    error: function() {

    }
  });
}
</script>
@endsection
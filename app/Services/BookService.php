<?php
namespace App\Services;

use App\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class BookService {
  public function uploadImg(Request $req, book $book) {
    if ( !$req->hasFile("imgf") ) {
      return "";
    }
    if ( !$req->file("imgf")->isValid() ) {
      return "";
    }
    $fnm = md5("icn_".$book->id);
    $ext = $req->imgf->extension();
    $fnm .= ".".$ext;

    $fpath = storage_path('app/public/icons')."/".$fnm;
    $this->execImgCrop($req,"imgf",$fnm);

    return $fnm;
  }

  private function execImgCrop(Request $req, $itm, $fnm ) {
    $file = $req->file($itm);
    list($w,$h,$type) = getimagesize($file);
    switch($type) {
      case IMAGETYPE_JPEG:
        $img = imagecreatefromjpeg($file);
        break;
      case IMAGETYPE_PNG:
        $img = imagecreatefrompng($file);
        break;
      case IMAGETYPE_GIF:
        $img = imagecreatefromgif($file);
        break;
      default:
        return false;
    }

    $dst = imagecreatetruecolor(500,500);
    ImageCopyResampled($dst,$img,0,0,$req->x1,$req->y1,500,500,$req->w,$req->h);

    $this->saveImgFile($dst,$fnm,IMAGETYPE_JPEG);
    imagedestroy($img);
    imagedestroy($dst);
  }

  private function saveImgFile($dst, $fnm, $type) {
    $fpath  = Storage::disk("icons")->getDriver()->getAdapter()->getPathPrefix();
    $fpath .= $fnm;
    Log::debug(print_r($fpath,true));

    switch($type) {
      case IMAGETYPE_JPEG:
        imagejpeg($dst,$fpath);
        break;
      case IMAGETYPE_PNG:
        imagepng($dst,$fpath);
        break;
      case IMAGETYPE_GIF:
        imagegif($dst,$fpath);
        break;
    }
  }
}
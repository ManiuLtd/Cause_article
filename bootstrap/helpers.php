<?php

/**
 * @title base64转图片
 */
function base64ToImage( $base64, $path )
{
    //匹配出图片的格式
    if ( preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result) ) {
        $type = $result[ 2 ];
        $day = date('Ymd', time());
        $new_file = "../public_html/uploads/" . $path . "/" . $day ."/";
        if ( !file_exists($new_file) ) {
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0777, true);
        }
        $filename = time() . rand(1000, 9999) . ".{$type}";
        $new_file = $new_file . $filename;
        if ( file_put_contents($new_file, base64_decode(str_replace($result[ 1 ], '', $base64))) ) {
            return [ 'state' => 0, 'path' => '/uploads/' . $path. '/' . $day . '/' . $filename ];
        } else {
            echo '新文件保存失败';
        }
    } else {
        return false;
    }
}

/**
 * @title 普通上传的图片生成缩略图保存
 * @param $width
 * @param $height
 * @param $file
 * @param $path
 * @return array
 */
function thumdImageUpload( $width, $height, $file, $path )
{
    $day = date('Ymd', time());
    $dir = '../public_html/uploads/' . $path . "/" . $day . '/';
    if ( !file_exists($dir) ) {
        mkdir($dir, 0777, true);
    }
    $filename = date('Ymd') . substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    $extension = $file->extension();
    $thumdimage = \App\Classes\Gdimage\Images::open($file);
    $thumdimage->thumb($width, $height);
    $thumdimage->save($dir . $filename . "." . $extension);

    return [ 'state' => 0, 'path' => '/uploads/' . $path . '/' . $day . '/' . $filename . "." . $extension ];
}
<?php
function createThumbGD($filepath, $thumbPath, $postfix, $maxwidth, $maxheight, $format='jpg', $quality=75)
{	
	if($maxwidth<=0 && $maxheight<=0)
	{
		return 'No valid width and height given';
	}
	
	$gd_formats	= array('jpg','jpeg','png','gif');//web formats
	$file_name	= pathinfo($filepath);
	if(empty($format)) $format = $file_name['extension'];
	
	if(!in_array(strtolower($file_name['extension']), $gd_formats))
	{
		return false;
	}
	
	$thumb_name	= $file_name['filename'].$postfix.'.'.$format;
	
	if(empty($thumbPath))
	{
		$thumbPath=$file_name['dirname'];	
	}
	$thumbPath.= (!in_array(substr($thumbPath, -1), array('\\','/') ) )?DIRECTORY_SEPARATOR:'';//normalize path
	
	// Get new dimensions
	list($width_orig, $height_orig) = getimagesize($filepath);
	if($width_orig>0 && $height_orig>0)
	{
		$ratioX	= $maxwidth/$width_orig;
		$ratioY	= $maxheight/$height_orig;
		$ratio 	= min($ratioX, $ratioY);
		$ratio	= ($ratio==0)?max($ratioX, $ratioY):$ratio;
		$newW	= $width_orig*$ratio;
		$newH	= $height_orig*$ratio;
			
		// Resample
		$thumb = imagecreatetruecolor($newW, $newH);
		$image = imagecreatefromstring(file_get_contents($filepath));
			
		imagecopyresampled($thumb, $image, 0, 0, 0, 0, $newW, $newH, $width_orig, $height_orig);
		
		// Output
		switch (strtolower($format)) {
			case 'png':
				imagepng($thumb, $thumbPath.$thumb_name, 9);
			break;
			
			case 'gif':
				imagegif($thumb, $thumbPath.$thumb_name);
			break;
			
			default:
				imagejpeg($thumb, $thumbPath.$thumb_name, $quality);;
			break;
		}
		imagedestroy($image);
		imagedestroy($thumb);
	}
	else 
	{
		return false;
	}
}


//for image magick
function createThumbIM($filepath, $thumbPath, $postfix, $maxwidth, $maxheight, $format)
{
	$file_name	= pathinfo($filepath);
	$thumb_name	= $file_name['filename'].$postfix.'.'.$format;
	
	if(empty($thumbPath))
	{
		$thumbPath=$file_name['dirname'];	
	}
	$thumbPath.= (!in_array(substr($thumbPath, -1), array('\\','/') ) )?DIRECTORY_SEPARATOR:'';//normalize path
	
	$image = new Imagick($filepath);
	$image->thumbnailImage($maxwidth, $maxheight);
	$images->writeImages($thumbPath.$thumb_name);
}


function checkFilename($fileName, $size)
{
	global $allowExt, $uploadPath, $maxFileSize;
	
	//------------------max file size check from js
	$maxsize_regex = preg_match("/^(?'size'[\\d]+)(?'rang'[a-z]{0,1})$/i", $maxFileSize, $match);
	$maxSize=4*1024*1024;//default 4 M
	if($maxsize_regex && is_numeric($match['size']))
	{
		switch (strtoupper($match['rang']))//1024 or 1000??
		{
			case 'K': $maxSize = $match[1]*1024; break;
			case 'M': $maxSize = $match[1]*1024*1024; break;
			case 'G': $maxSize = $match[1]*1024*1024*1024; break;
			case 'T': $maxSize = $match[1]*1024*1024*1024*1024; break;
			default: $maxSize = $match[1];//default 4 M
		}
	}

	if(!empty($maxFileSize) && $size>$maxSize)
	{
		echo json_encode(array('name'=>$fileName, 'size'=>$size, 'status'=>'error', 'info'=>'File size not allowed.'));
		return false;
	}
	//-----------------End max file size check
	
	
	//comment if not using windows web server
	$windowsReserved	= array('CON', 'PRN', 'AUX', 'NUL','COM1', 'COM2', 'COM3', 'COM4', 'COM5', 'COM6', 'COM7', 'COM8', 'COM9',
            				'LPT1', 'LPT2', 'LPT3', 'LPT4', 'LPT5', 'LPT6', 'LPT7', 'LPT8', 'LPT9');    
	$badWinChars		= array_merge(array_map('chr', range(0,31)), array("<", ">", ":", '"', "/", "\\", "|", "?", "*"));

	$fileName	= str_replace($badWinChars, '', $fileName);
    $fileInfo	= pathinfo($fileName);
    $fileExt	= $fileInfo['extension'];
    $fileBase	= $fileInfo['filename'];
    
    //check if legal windows file name
	if(in_array($fileName, $windowsReserved))
	{
		echo json_encode(array('name'=>$fileName, 'size'=>0, 'status'=>'error', 'info'=>'File name not allowed. Windows reserverd.'));	
		return false;
	}
	
    //check if is allowed extension
	if(!in_array($fileExt, $allowExt) && count($allowExt))
	{
		echo json_encode(array('name'=>$fileName, 'size'=>0, 'status'=>'error', 'info'=>"Extension [$fileExt] not allowed."));	
		return false;
	}
    
	$fullPath = $uploadPath.$fileName;
    $c=0;
	while(file_exists($fullPath))
	{
		$c++;
		$fileName	= $fileBase."($c).".$fileExt;
		$fullPath 	= $uploadPath.$fileName;
	}
	return $fullPath;
}
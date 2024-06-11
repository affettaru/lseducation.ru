<?
// $waterPlace = "center";
// $file = $_SERVER["DOCUMENT_ROOT"].'/upload/watermark.png';
// $filter = Array(
// 	array(
// 		"name" => "watermark",
// 		"position" => $waterPlace, // Положение
// 		"type" => "image",
// 		"size" => "real",
// 		"file" => $file, // Путь к картинке
// 		"fill" => "exact",
// 	)
// );
function res($img,$w,$h,$id=0, $filter = Array()){
	$res = CFile::ResizeImageGet($img,Array("width"=>$w,"height"=>$h),BX_RESIZE_IMAGE_PROPORTIONAL,true, $filter);
	return ($id)?$res['src']:$res;
}
function crop($img,$w,$h,$id=0, $filter = Array()){
	$res = CFile::ResizeImageGet($img,Array("width"=>$w,"height"=>$h),BX_RESIZE_IMAGE_EXACT,true, $filter);
	return ($id)?$res['src']:$res;
}


function reswm($img,$w,$h,$id=0){
	$res = CFile::ResizeImageGet($img,Array("width"=>$w,"height"=>$h),BX_RESIZE_IMAGE_PROPORTIONAL,true,Array(
		Array( 'name' => 'watermark',
		  'position' => 'br',
		  'size'=>'real',
		  'type'=>'image',
		  'alpha_level'=>'100',
		  'file'=>$_SERVER['DOCUMENT_ROOT'].'/inc/includes/mask/logo-for-pics.png', 
		  ),	
	));
	return ($id)?$res['src']:$res;
}

function cropwm($img,$w,$h,$id=0){
	$res = CFile::ResizeImageGet($img,Array("width"=>$w,"height"=>$h),BX_RESIZE_IMAGE_EXACT,true,Array(
		Array( 
		  'name' => 'watermark',
		  'position' => 'br',
		  'size'=>'real',
		  'type'=>'image',
		  'alpha_level'=>'100',
		  'file'=>$_SERVER['DOCUMENT_ROOT'].'/inc/includes/mask/logo-for-pics.png', 
		  ),	
	));
	return ($id)?$res['src']:$res;
}

function cropwm1($img,$w,$h,$id=0){
	$res = CFile::ResizeImageGet($img,Array("width"=>$w,"height"=>$h),BX_RESIZE_IMAGE_EXACT,true);
	$src = $img['src'];
	$img['src'] = $img['src'].'1.png';
	if(is_file($_SERVER['DOCUMENT_ROOT'].$img['src'])) return ($id)?$img['src']:$img;

	$image = imagecreatefromstring(file_get_contents($_SERVER['DOCUMENT_ROOT'].$src));
	$mask = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'].'/inc/includes/mask/mask_wm.png');
	$dest = imagecreatetruecolor($width,$height);

	imagewm($dest,$image,$mask,$width,$height);

	return ($id)?$res['src']:$res;
}


function cropbas($img,$width,$height,$id){
	$img = crop($img,$width,$height);
	$src = $img['src'];
	$img['src'] = $img['src'].'3.png';
	if(is_file($_SERVER['DOCUMENT_ROOT'].$img['src'])) return ($id)?$img['src']:$img;


	$image = imagecreatefromstring(file_get_contents($_SERVER['DOCUMENT_ROOT'].$src));
	$mask = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'].'/inc/includes/mask/mask_crop.png');
	$mask2 = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'].'/inc/includes/mask/mask_wm.png');

	$dest = imagecreatetruecolor($width,$height);

	imagecropmask($dest,$image,$mask,$width,$height);
	//imagewm($dest,$dest,$mask2,$width,$height);

	

	imagepng($dest,$_SERVER['DOCUMENT_ROOT'].$img['src']);

	return ($id)?$img['src']:$img;
}


function imagecropmask($dest,$image,$mask,$width,$height){
	// Код самой функции маскирования:
	
	$tc = imagecolorallocate($dest,0,0,0);
	imagecolortransparent($dest,$tc);

	imageAlphaBlending($dest, false);
	imageSaveAlpha($dest, true);
	
	for($i=0;$i<$width;$i++){
		for($j=0;$j<$height;$j++){
			$c = imagecolorat($image,$i,$j);
			$color = imagecolorsforindex($image,$c);
			
			$c = imagecolorat($mask,$i,$j);
			$mcolor = imagecolorsforindex($mask,$c);
			
			//if (!$mcolor['alpha']!=0){
				$c = imagecolorallocatealpha($dest,$color['red'],$color['green'],$color['blue'], $mcolor['alpha']);
				//$c = imagecolorallocatealpha($dest,($color['red']+$mcolor['red'])/2,($color['green']+$mcolor['green'])/2,($color['blue']+$mcolor['blue'])/2, $mcolor['alpha']);
				//if($i==10 && $j == 10) print_r($mcolor);
				imagesetpixel($dest,$i,$j,$c);
			//} 
		}
	}
}
function imagewm($dest,$image,$mask,$width,$height){
	// создание водяного знака в формате png
	imagealphablending($image, true);
	imagealphablending($png, true);
	imagecopy($dest, $mask, 0, 0, 0, 0, $width, $height);

}



function crop_man($source, $w, $h, $dest=false,$fromtop=false){
	$size = getimagesize($source);
	if(!$dest && $size[0]==$w && $size[1]==$h) return true;
	if(!$dest) $dest =  $source;



		
	if($source=="") return false;

	$size = getimagesize($source);
	switch($size[2]){
		case 1: $img = imagecreatefromgif($source); break;
		case 2: $img = imagecreatefromjpeg($source); break;
		case 3: $img = imagecreatefrompng($source); break;
		case 6: $img = imagecreatefromwbmp($source); break;
	}
	$bw = $size[0];
	$bh = $size[1];

	/*Расчёт с кадрированием*/
	if($bw - $w < $bh - $h){
		$nw = $bw;
		$nh = floor($nw * $h / $w);
		$x = 0;
		$y = floor(($bh - $nh) / 2);
	}else{
		$nh = $bh;
		$nw = floor($w * $nh / $h);
		$y = 0;
		$x = floor(($bw - $nw) / 2);
	}
	//print 'x='.$x.' y='.$y.' w='.$w.' h='.$h.' nw='.$nw.' nh='.$nh.' bw='.$bw.' bh='.$bh.'<br>';
	

	$img2 = imagecreatetruecolor($w, $h);
	imagesavealpha($img2, true);
	imagealphablending($img2, false);	
	

	$col = imagecolorallocate($img2, 255, 255, 255);
	imagefill($img2, 0, 0, $col);
	imagecopyresampled($img2, $img, 0, 0, $x, $y, $w, $h, $nw, $nh);
	
	
	imagepng ($img2, $dest);
}




function roundcrop($img,$width,$height,$id){
	if(is_array($img)) $img = $img['ID'];
	$source = $_SERVER['DOCUMENT_ROOT'].CFile::GetPath($img);

	$crop = CFile::ResizeImageGet($img,Array('width'=>$width,'height'=>$height),BX_RESIZE_IMAGE_PROPORTIONAL,true);
	$crop['width'] = $width;
	$crop['height'] = $height;

	$desc = $_SERVER['DOCUMENT_ROOT'].$crop['src'];
	if($source==$desc) return $crop;
	
	copy($source,$desc);
	crop_man($desc,$width,$height);
	
	
	$filename = $desc;
	$radius = ceil($width/2);

	/**
	* Чем выше rate, тем лучше качество сглаживания и больше время обработки и
	* потребление памяти.
	*
	* Оптимальный rate подбирается в зависимости от радиуса.
	*/
	$rate = 3;

	$img = imagecreatefromstring(file_get_contents($filename));
	imagealphablending($img, false);
	imagesavealpha($img, true);

	$width = imagesx($img);
	$height = imagesy($img);

	$rs_radius = $radius * $rate;
	$rs_size = $rs_radius * 2;

	$corner = imagecreatetruecolor($rs_size, $rs_size);
	imagealphablending($corner, false);

	$trans = imagecolorallocatealpha($corner, 255, 255, 255, 127);
	imagefill($corner, 0, 0, $trans);

	$positions = array(
	array(0, 0, 0, 0),
	array($rs_radius, 0, $width - $radius, 0),
	array($rs_radius, $rs_radius, $width - $radius, $height - $radius),
	array(0, $rs_radius, 0, $height - $radius),
	);

	foreach ($positions as $pos) {
		imagecopyresampled($corner, $img, $pos[0], $pos[1], $pos[2], $pos[3], $rs_radius, $rs_radius, $radius, $radius);
	// imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
	}

	$lx = $ly = 0;
	$i = -$rs_radius;
	$y2 = -$i;
	$r_2 = $rs_radius * $rs_radius;

	for (; $i <= $y2; $i++) {

	$y = $i;
	$x = sqrt($r_2 - $y * $y);

	$y += $rs_radius;
	$x += $rs_radius;

	imageline($corner, $x, $y, $rs_size, $y, $trans);
	imageline($corner, 0, $y, $rs_size - $x, $y, $trans);

	$lx = $x;
	$ly = $y;
	}

	foreach ($positions as $i => $pos) {
		imagecopyresampled($img, $corner, $pos[2], $pos[3], $pos[0], $pos[1], $radius, $radius, $rs_radius, $rs_radius);
	}

	imagepng($img,$desc);
	return ($id)?$crop['src']:$crop;
}




// Обработчики для картинок
//AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("VidClass", "OnAfterIBlockElementAddHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("VidClass", "OnBeforeIBlockElementUpdateHandler"));

class VidClass{
    // создаем обработчик события "OnAfterIBlockElementAdd"
	// Если в описании картинки - ссылка на ютуб, то картинку ставим с ютуба.
    public static function OnBeforeIBlockElementUpdateHandler(&$arFields){

		// Для множественны полей привязка по ссылке с youtube
		foreach($arFields['PROPERTY_VALUES'][3] as $key=>$val){
			if(is_array($val['VALUE']) && $val['DESCRIPTION']!='' && $val['VALUE']['del']!='Y'){
				$code = videolink1($val['DESCRIPTION']);
				if($code!=''){
					$file = CFile::MakeFileArray('http://i1.ytimg.com/vi/'.$code.'/hqdefault.jpg');
					$arFields['PROPERTY_VALUES'][3][$key]['VALUE'] = $file;						
				}
				//print '<pre>'; print_r($arFields['PROPERTY_VALUES'][3][$key]); die('</pre>');
			}
		}
		//print '<pre>'; print_r($arFields); die('</pre>');
       
    }
}

if(!function_exists('videolink1')){
	function videolink1($inp){
		if(preg_match("#youtu.be/([0-9a-zA-Z\-_]+)#",$inp,$mat))  $inp = $mat[1];
		elseif(preg_match("#youtube.com/embed/([0-9a-zA-Z\-_]+)#",$inp,$mat))  $inp = $mat[1];
		elseif(preg_match("#\?v=#",$inp,$mat)){
			$arr = explode("?v=",$inp);
			if(sizeof($arr)>=2){
				$inp = preg_replace("#&.*#","",$arr[1]);
			}
		}elseif(preg_match("#vimeo.com/([0-9a-zA-Z\-_])+#",$inp,$mat))  $inp = $mat[1];
		
		return $inp;
	}
}




// Обрезалка размеров фото, не работает с облаком
//AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "iIBlockPhotoSave");
//AddEventHandler("iblock", "OnAfterIBlockElementAdd", "iIBlockPhotoSave");
function iIBlockPhotoSave(&$fields){
	$max_width = 2000;
	$max_height = 2000;

	$el = CIBlockElement::GetById($fields['ID'])->GetNext();	
	$imgarr = Array();
	if($el['PREVIEW_PICTURE']!='') $imgarr[] = $el['PREVIEW_PICTURE'];
	if($el['DETAIL_PICTURE']!='') $imgarr[] = $el['DETAIL_PICTURE'];
	$list = CIBlockElement::GetProperty($el['IBLOCK_ID'],$el['ID'],Array(),Array("PROPERTY_TYPE"=>"F"));
	while($prop = $list->GetNext()) if($prop['VALUE']!="") $imgarr[] = $prop['VALUE'];
	foreach($imgarr as $img){
		$width = $max_width;
		$height = $max_height;

		if(is_array($img)) $img = $img['ID'];
		$dbImage = CFile::GetByID($img)->GetNext();
		$path = CFile::GetPath($img);
		if(!preg_match("#http://#",$path)){
			$filepath = $_SERVER['DOCUMENT_ROOT'].$path;
			$size = getimagesize($filepath);
			if($size[1] > $height || $size[0] > $width){
				///CFile::ResizeImageFile($filepath,$filepath,Array('width'=>$width,'height'=>$height));
				
				$rate = $size[0] / $size[1];
				if($size[0]/$width > $size[1]/$height) $height = $width / $rate;
				else $width = $height * $rate;
				
				if($size[2]==1) $src = imagecreatefromgif($filepath);
				elseif($size[2]==2) $src = imagecreatefromjpeg($filepath);
				elseif($size[2]==3) $src = imagecreatefrompng($filepath);
				elseif($size[2]==4) $src = imagecreatefromwbmp($filepath);
				$dst = imagecreatetruecolor($width,$height);
				imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);

				if($size[2]==3) imagepng($dst,$filepath);
				else imagejpeg($dst,$filepath,90);
				$size = getimagesize($filepath);

				global $DB;
				$DB->Query("UPDATE b_file SET WIDTH='".$size[0]."' WHERE ID=".intval($img));
				$DB->Query("UPDATE b_file SET HEIGHT='".$size[1]."' WHERE ID=".intval($img));
				$DB->Query("UPDATE b_file SET FILE_SIZE='".$size1."' WHERE ID=".intval($img));
				CFile::CleanCache($ID);
			}
			
		}
		
	}
}



// регистрируем обработчик
//AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("VidLoadClass", "OnAfterIBlockElementAddHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("VidLoadClass", "OnBeforeIBlockElementUpdateHandler"));

class VidLoadClass{
    // создаем обработчик события "OnAfterIBlockElementAdd"
	// При изменении и добавлении элемента ищем на ютубе картинку и загружаем в превью. Обязательно свойсово lock — это флаг, который не позволяет функции входить в рекурсию.
    public static function OnBeforeIBlockElementUpdateHandler(&$arFields){
        if ($arFields["IBLOCK_ID"] == 1){
			$good = CIBlockElement::GetById($arFields['ID'])->GetNext();
			
			// Для множественны полей привязка по ссылке с youtube
			foreach($arFields['PROPERTY_VALUES'][3] as $key=>$val){
				if(is_array($val['VALUE']) && $val['DESCRIPTION']!='' && $val['VALUE']['del']!='Y'){
					$code = VidClass::vlink($val['DESCRIPTION']);
					if($code!=''){
						$file = CFile::MakeFileArray('http://i1.ytimg.com/vi/'.$code.'/hqdefault.jpg');
						$arFields['PROPERTY_VALUES'][3][$key]['VALUE'] = $file;						
					}
					//print '<pre>'; print_r($arFields['PROPERTY_VALUES'][3][$key]); die('</pre>');
				}
			}
			//print '<pre>'; print_r($arFields); die('</pre>');
        }
    }
    public static function vlink($inp){
		if(preg_match("#youtu.be/([0-9a-zA-Z\-_]+)#",$inp,$mat))  $inp = $mat[1];
		elseif(preg_match("#youtube.com/embed/([0-9a-zA-Z\-_]+)#",$inp,$mat))  $inp = $mat[1];
		elseif(preg_match("#\?v=#",$inp,$mat)){
			$arr = explode("?v=",$inp);
			if(sizeof($arr)>=2){
				$inp = preg_replace("#&.*#","",$arr[1]);
			}
		}elseif(preg_match("#vimeo.com/([0-9a-zA-Z\-_])+#",$inp,$mat))  $inp = $mat[1];
		
		return $inp;
	}
}

?>
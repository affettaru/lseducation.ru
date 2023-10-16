<?
if(NOT_CHECK_PERMISSIONS!=true && !preg_match("#/$#",$_SERVER['REQUEST_URI']) && !preg_match("#\.|\?#",$_SERVER['REQUEST_URI'])) LocalRedirect($_SERVER['REQUEST_URI'].'/');
function iarga_echo($str){
	echo htmlspecialchars($str);
}
function dump($arr){
 ?><pre><?var_dump($arr)?></pre><?
};

function baseprice($id){
    $price = CPrice::GetBasePrice($id);
    return number_format($price['PRICE'],0,'.',' ');
}
function id_price($id,$priceid){
    $price = GetCatalogProductPrice($id,$priceid);
    return number_format($price['PRICE'],0,'.',' ');
}

function getWord($number, $suffix) {
$keys = array(2, 0, 1, 1, 1, 2);
$mod = $number % 100;
$suffix_key = ($mod > 7 && $mod < 20) ? 2: $keys[min($mod % 10, 5)];
return $suffix[$suffix_key];
}


function sendsms($text, $phone=false){
	if($phone) file_get_contents('http://sms.ru/sms/send?api_id=8F2E2EE9-84AF-D0DD-C966-2FDF668321D1&to='.uniphone($phone).'&text='.urlencode($text));
}
function uniphone($phone){
	$phone = preg_replace('#[^0-9]#','',$phone);
	if(strlen($phone) == 11) $phone = preg_replace('#^8#','',$phone);
	if(strlen($phone) == 10 && !preg_match('#^7#',$phone))  $phone = '7'.(string)$phone;
	return $phone;
}

function free_delivery(){
	return 300000;
}


function recapt_init(){
	print '<div class="g-recaptcha" data-sitekey="6Lem-E4UAAAAAHicNLGiDTXgLNneEqgTKGnz-oBN"></div>';
}
function recapt(){
	// тут рекаптча и ее проверка - идеальная штука
	$recapt = false;

	$postfields = Array(
		"secret"=>"6Lem-E4UAAAAABOiMgOv6_X8sOFz0dZ7XkFimrZ3",
		"response"=>$_POST['g-recaptcha-response'],
		"remoteip"=>$_SERVER['REMOTE_ADDR'],
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_POST, 1);
	// Edit: prior variable $postFields should be $postfields;
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
	$result = curl_exec($ch);
	$obj = json_decode($result);
	if($obj->success == true) $recapt = true;
	else $recapt = false;
	// тут закончилась

	return $recapt;
}

function count_delivery($city, $goodprice=0, $isarr=false){
	if($city != $_SESSION['city']){
		$db_vars = CSaleLocation::GetList(array("SORT"=>"ASC"),array("LID" => LANGUAGE_ID,"CODE"=>$city))->GetNext();
		if($db_vars){
			session_start();
			$_SESSION['city'] = $db_vars['CITY_NAME'];
			$city = $db_vars['CITY_NAME'];
		}
	}
		

	$goodprice = 0; // так вырубаем страховку
	$sel = Array("ID","IBLOCK_ID","NAME","PROPERTY_price","PROPERTY_time");
	$el = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>9,"NAME"=>$city), false, false, $sel)->GetNext();
	if($el){
		// деньги
		$price = (int) str_replace(",",".",$el['PROPERTY_PRICE_VALUE']);
		// время
		$time = $el['PROPERTY_TIME_VALUE'];
		$tarr = explode("-",$time);
		if(sizeof($tarr) > 1) $time = $tarr[1];
		$time = (int) preg_replace("#[^0-9]#", "", $time);
		if($goodprice > 0) $price += $goodprice * 0.006;
	}else{
		$arr = spsrCalc($city);
		if($arr){
			$iel = new CIBlockElement;
			$iel->Add(Array(
				"IBLOCK_ID"=>9,
				"NAME"=>$city,
				"PROPERTY_VALUES"=>Array(
					"price"=>ceil($arr['price']),
					"time"=>ceil($arr['time']),
				)
			));
			$time = $arr['time'];
			$price = $arr['price'];
			$tarr = explode("-",$time);
			if(sizeof($tarr) > 1) $time = $tarr[1];
			$time = (int) preg_replace("#[^0-9]#", "", $time);
			if($goodprice > 0) $price += $goodprice * 0.006;
		}else{
			// Если города нет в базе СПСР - считаем по Почте России
			$price = 990;
			$time = 8;
			if($goodprice > 0) $price += $goodprice * 0.03;
		}
	}
	
	$price = ceil($price/10)*10;
	if($goodprice >= free_delivery()) $price = 0;
	if($isarr){
		return Array($price, $time);
	}else{
		return $price;
	}
}

function getSID(){
	$xml = '
	<root xmlns="http://spsr.ru/webapi/usermanagment/login/1.0">
		<p:Params Name="WALogin" Ver="1.0" xmlns:p="http://spsr.ru/webapi/WA/1.0" />
		<Login Login="magnit-sk74" Pass="NO!2320391021" UserAgent="Company name" />
	</root>
	';

	$result = send_xml( $xml );
	$sid = take('SID=&quot;', '&quot;', $result);

	if($sid!="") return $sid;
	else return false;

}
function send_xml( $xml ){
	$addr = 'http://api.spsr.ru/waExec/WAExec';
	$curl = curl_init();

	curl_setopt( $curl, CURLOPT_URL,  $addr);
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt( $curl, CURLOPT_POST, 1);
	curl_setopt( $curl, CURLOPT_POSTFIELDS,   $xml );

	$header = array('Content-Type: application/xml');
 
	curl_setopt( $curl, CURLOPT_HTTPHEADER, $header);
					      

	$result = curl_exec( $curl );
	$result = htmlspecialchars($result); 
	curl_close( $curl );
	return $result;
}

function spsrCalc($city, $weight=1, $debug=0){
	$cityId = cityId($city);
	//$sid = getSID();
	$icn = '2320391021';
	//$link = 'http://spsr.ru/tarifcalc/?fn=TARIFFCOMPUTE_2&ToCity='.$cityId.'|0&FromCity=735|0&Weight='.$weight.'&ToBeCalledFor=0&ICN='.$icn.'&SID='.$sid;
	$link = 'http://spsr.ru/tarifcalc/?fn=TARIFFCOMPUTE_2&ToCity='.$cityId.'|0&FromCity=735|0&Weight='.$weight.'&ToBeCalledFor=0';
	$xml = file_get_contents($link);
	if($debug) print $link." = ";
	$arr = explode("<TariffType>", $xml);
	foreach($arr as $i=>$el){
		$price1 = take('<Total_Dost>', '</Total_Dost>', $el);
		$time1 = take('<DP>', '</DP>', $el);

		$arr1 = explode("</TariffType>", $el);
		$name = $arr1[0];
		if(preg_match("#Пеликан-эконом#",$name)){
			$price = take('<Total_Dost>', '</Total_Dost>', $el);
			$time = take('<DP>', '</DP>', $el);
		}
	}
	if(!$price) $price = $price1;
	if(!$time) $time = $time1;

	$exp = explode("-", $time);
	if(sizeof($exp) > 1) $time = $exp[0]; 

	return Array("time"=>$time, "price"=>$price);
}

function cityId($city){
	$curl = curl_init();
	$link = "http://www.cpcr.ru/cgi-bin/postxml.pl?GetCityName&CityName=".$city;

	curl_setopt($curl, CURLOPT_URL, $link);
	curl_setopt($curl, CURLOPT_TIMEOUT, 2);
	curl_setopt($curl, CURLOPT_HEADER, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Opera 10.00');
	$cityResp = curl_exec($curl);

	$cityRId = take("<City_id>", "</City_id>", $cityResp);
	$cityId = take("<City_id>", "</City_id>", $cityResp);
	$CityName = take("<CityName>", "</CityName>", $cityResp);
	$regionId = take("<Region_ID>", "</Region_ID>", $cityResp);
	$ownerId = take("<city_owner_id>", "</city_owner_id>", $cityResp);
	$countryId = take("<Countries_id>", "</Countries_id>", $cityResp);

	return $cityId;
}
function spsrManCalc($to_name){
	CModule::IncludeModule("iblock");
	$el = CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>15,"NAME"=>$to_name), false, false, Array("ID","NAME","PROPERTY_time","PROPERTY_price"))->GetNext();
	if($el){
		if($el['PROPERTY_PRICE_VALUE'] > 0){
			$price = ceil(str_replace(",",".",$el['PROPERTY_PRICE_VALUE']));
			
			$daysArr = explode("-",$el['PROPERTY_TIME_VALUE']);
			if($days[1]!='') $days = $daysArr[1];
			else $days = $daysArr[0];
			$days = preg_replace("#[^0-9]#","",$days);

			return Array("days"=>$days,"price"=>$price);
		}
	}else{
		return Array("days"=>7,"price"=>1800);
	}
	return false;
}
function spsrNewCalc($to_id, $to_name){
	$adr = "http://www.spsr.ru/ru/system/ajax";
	$key = '
	from_ship_region_id:
	form_build_id:form-me8StFoJRKyWVHSafgPKBxteybLIb1ZW8j92_WzV3qw
	form_id:spsr_calculator_form
	from_ship:Краснодар
	from_ship_id:735
	from_ship_owner_id:
	to_send:'.$to_name.'
	to_send_id:'.$to_id.'
	to_send_owner_id:0
	weight:0.05
	EncloseType:18
	width:
	_length:
	height:
	cost:1
	type:0
	pre_notification:1
	payment_of_receiver:1
	';
	$keyArr = explode("\n",$key);
	$postArr = Array();
	$postStr = "";
	foreach($keyArr as $str){
		$arr = explode(":",$str);
		$key = trim($arr[0]);
		$val = trim($arr[1]);
		if($key!=""){
			$postArr[$key] = $val;
			$postStr .= $key."=".$val."&";
		}
	}
	//print $postStr;
	if( $curl = curl_init()) {
	    curl_setopt($curl, CURLOPT_URL, $adr);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($curl, CURLOPT_POST, true);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $postStr);
	    $out = curl_exec($curl);
	    curl_close($curl);

	    
	    //'<tr><td>Услуги по доставке "Пеликан-стандарт"</td><td><b>1123.16 руб.</b></td><td>2 - 3</td></tr>'
	    $outArr = json_decode($out);
	    //print_r($outArr[1]->data);
	    $first = take('<tr><td>Услуги по доставке "Пеликан-стандарт"','</tr>', $outArr[1]->data);
	    $summ = take('</td><td><b>',' руб.</b></td>', $first);
	    $time = take('- ','</td>', $first);
	    return Array("days"=>$time,"price"=>$summ);
	    
	}
}
function ip_city(){

	$str = getcurl('http://api.sypexgeo.net/json/'.$_SERVER['REMOTE_ADDR'].'/');
	$obj = json_decode($str);
	$name = $obj->city->name_ru;
	if($name!='') return $name;


	$gb = new IPGeoBase();
	$data = $gb->getRecord($_SERVER['REMOTE_ADDR']);
	return iconv("windows-1251","utf-8",$data['city']);
}
function ip_base(){
	$db_vars = CSaleLocation::GetList(array("SORT"=>"ASC"),array("LID" => LANGUAGE_ID,"CITY_NAME"=>ip_city()))->GetNext();
	$value = $db_vars['ID'];
	return $value;
}
function ip_country(){
	$gb = new IPGeoBase();
	$data = $gb->getRecord($_SERVER['REMOTE_ADDR']);
	return $data['cc'];
}
function getcurl( $addr ){
	$curl = curl_init();

	curl_setopt( $curl, CURLOPT_URL,  $addr);
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

	$result = curl_exec( $curl );
	curl_close( $curl );
	return $result;
}
function getparam($name,$val){
	global $APPLICATION;
	$get = $_GET;
	$get[$name] = $val;
	$uri = $APPLICATION->GetCurDir()."?";
	foreach($get as $name=>$val){
		if($val!=''){
			if(is_array($val)){
				foreach($val as $val1) $uri.= $name.'[]='.$val1.'&';
			}else{
				$uri.= ($name . '=' . $val . '&');
			}
		}
	}
	return substr($uri,0,strlen($uri)-1);
	
}
function checkphone($phone){
	$phone = preg_replace("#[^0-9]#", "", $phone);
	if(strlen($phone)<9 || strlen($phone)>14) return false;
	else return true;
}
function getIBlockElement_i($id){
	$el = CIBlockElement::GetById($id)->GetNext();
	$props = CIBlockElement::GetProperty($el['IBLOCK_ID'],$el['ID'],Array("SORT"=>"ASC"),Array());
	while($prop = $props->GetNext()){
		if($prop['MULTIPLE']!='Y') $el['PROPERTIES'][$prop['CODE']] = $prop;
		else foreach($prop as $key=>$val) $el['PROPERTIES'][$prop['CODE']][$key][] = $val;
	}
	return $el;
}
function take($start, $stop, $response){
	$ar = explode($start, $response);
	$content = explode($stop, $ar[1]);
	$content = $content[0];
	return $content;
}
function getprice($id,$curr="RUB", $num=1, $arr=false){
	global $USER;
	$price = CCatalogProduct::GetOptimalPrice($id, $num, $USER->GetUserGroupArray());
	$price['PRICE']['PRICE'] = CCurrencyRates::ConvertCurrency($price['PRICE']['PRICE'],$price['PRICE']["CURRENCY"],$curr);
	if($arr) return $price;
	return $price['PRICE']['PRICE'];
}

function getbase($str){
	preg_match("#([^\?]*)#",$str,$mat);
	return $mat[1];
}
function prep_br($str){
	return str_replace('\n','<br>',$str);
}

function sublet($str, $letters, $mode=1){
	$arr = explode(" ", $str);
	$l = 0;
	$ret = '';
	foreach($arr as $el){$l += strlen($el); if($l < $letters) $ret .= $el.' ';}
	if(strlen($ret) < strlen($str)) $add = '&#8230';
	return trim($ret).$add;
}
function prep($summ){
	$summ = ((int) ($summ * 100)) / 100;
	$str = CurrencyFormat($summ, "RUB");
	return preg_replace("#\.00$#","",$str);
} 
function dateprocess($date=0, $day=1, $month=1, $year=1){
        if($date == 0) $date = date("d.m.Y");
	$date = strtotime($date);
	switch(date("m", $date)){
		case 1: $m = "января"; break;
		case 2: $m = "февраля"; break;
		case 3: $m = "марта"; break;
		case 4: $m = "апреля"; break;
		case 5: $m = "мая"; break;
		case 6: $m = "июня"; break;
		case 7: $m = "июля"; break;
		case 8: $m = "августа"; break;
		case 9: $m = "сентября"; break;
		case 10: $m = "октября"; break;
		case 11: $m = "ноября"; break;
		case 12: $m = "декабря"; break;
	}
        if($day) $day1 = (int) date("d", $date);
        if($month) $month1 = $m;
        if($year) $year1 = date("Y", $date);
	return $day1 . " " . $month1 ." " . $year1;
}
function dayprocess($date=0){
        if($date == 0) $date = date("d.m.Y");
	$date = strtotime($date);
	switch(date("w", $date)){
		case 1: $m = "понедельник"; break;
		case 2: $m = "вторник"; break;
		case 3: $m = "среда"; break;
		case 4: $m = "четверг"; break;
		case 5: $m = "пятница"; break;
		case 6: $m = "суббота"; break;
		case 7: $m = "воскресенье"; break;	
		case 0: $m = "воскресенье"; break;	
	}
	return $m;
}



function unhtmlentities ($string){
	$trans_tbl = get_html_translation_table (HTML_ENTITIES);
	$trans_tbl = array_flip ($trans_tbl);
	$str = strtr ($string, $trans_tbl);
	
	$str = str_replace('&amp;amp;quot;','"',$str);
	$str = str_replace('&amp;quot;','"',$str);

	return $str;
}

function sklon($num, $form1, $form2, $form3){
	if(rest10($num)==0 || rest10($num)>4 || ($num>=11 && $num<=14)) return $form1;
	elseif(rest10($num)==1) return $form2;
	else return $form3;
}

function rest10($num){
	return $num - floor($num/10)*10;
}

function comment($iblock_id,$code1,$code2,$name){
	include($_SERVER['DOCUMENT_ROOT']."/inc/comments.php");
}
function comms($id){
	return CIBlockElement::GetList(Array(), Array("SECTION_CODE"=>$id))->SelectedRowsCount();
}
function comments($item){
	$num = $item['PROPERTIES']['comments']['VALUE'];
	if($num > 0) return ' <span class="comments-number">'.$num.'</span>';
}

function videolink($inp){
	if(preg_match("#youtu.be/([0-9a-zA-Z\-_]+)#",$inp,$mat))  $inp = $mat[1];
	elseif(preg_match("#youtube.com/embed/([0-9a-zA-Z\-_]+)#",$inp,$mat))  $inp = $mat[1];
	elseif(preg_match("#v=#",$inp,$mat)){
		$arr = explode("v=",$inp);
		if(sizeof($arr)>=2){
			$inp = preg_replace("#&.*#","",$arr[1]);
		}
	}elseif(preg_match("#vimeo.com/([0-9a-zA-Z\-_])+#",$inp,$mat))  $inp = $mat[1];
	
	return $inp;
}





function loadlink($file,$name=false,$ext=false){
	$path = CFile::GetPath($file);
	if(!$name){
		$f = CFile::GetById($file)->GetNext();
		$name = $f['original_name'];
		$ext = explode('.',$path);
		$name = $name.'.'.$ext[(sizeof($ext)-1)];
	}elseif(!$ext){
		$ext = explode('.',$path);
		$name = $name.'.'.$ext[(sizeof($ext)-1)];
	}
	
	$link = '/inc/file.php/'.$name.'?file='.$path;
	return $link;
}



// Отправка письма с файлом
// file:Адрес##Имя##
function custom_mail1($to='',$subject='',$message='',$headers=false,$params=false){
	/*
	if(preg_match('#html#',$headers) && !preg_match("#NO_MAIL_TPL#",$message)){
		$cont = file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/inc/posting/tpl.php');
		$cont = str_replace("#WORK_AREA#",$message,$cont);
		$cont = str_replace("#TITLE#",'Сообщение с сайта Ditalic.com',$cont);
		$message = $cont;
	}
	*/

	if(preg_match("#file:#",$message)){
		$exp = explode('file:',$message);
		foreach($exp as $i=>$el){
			if($i==0) continue;
			$sec = explode('##',$el);
			$files[] = Array("file"=>$sec[0],"name"=>$sec[1]);
			$message = str_replace('file:'.$sec[0].'##'.$sec[1].'##','',$message);
		}

		if(sizeof($files) > 0){
			$exp = explode("\n",$headers);
			$from = str_replace('From: ','',$exp[0]);			
		  
			$boundary = "---"; //Разделитель
			/* Заголовки */
			$headers = "From: $from\nReply-To: $from\n";
			$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";
			$body = "--$boundary\n";
			/* Присоединяем текстовое сообщение */
			$body .= "Content-type: text/html; charset='utf-8'\n";
			$body .= "Content-Transfer-Encoding: quoted-printable\n\n";
			//$body .= "Content-Disposition: attachment; filename==".base64_encode($files[0]['name'])." \n\n";
			$body .= $message."\n";
			foreach($files as $file){
				if(preg_match("#".$_SERVER['DOCUMENT_ROOT']."#", $file['file'])) $filename = $file['file'];
				else $filename = $_SERVER['DOCUMENT_ROOT'].$file['file']; //Имя файла для прикрепления


				$exp = explode('.',$filename);
				$ext = $exp[sizeof($exp)-1];
				$name = $file['name'].'.'.$ext;
				$name = str_replace($ext.'.'.$ext,$ext,$name);

				$body .= "--$boundary\n";
				$file = fopen($filename, "r"); //Открываем файл
				$text = fread($file, filesize($filename)); //Считываем весь файл
				fclose($file); //Закрываем файл
				/* Добавляем тип содержимого, кодируем текст файла и добавляем в тело письма */
				$body .= "Content-Type: file/".$ext."; name=".($name)."\n"; 
				$body .= "Content-Transfer-Encoding: base64\n";
				$body .= "Content-Disposition: attachment; filename=".($name)."\n\n";
				$body .= chunk_split(base64_encode($text))."\n";
			}
			$body .= "--".$boundary ."--\n";
			return mail($to, $subject, $body, $headers); //Отправляем письмо


			//return sendMail($to,$from_mail,$from_name,$subject,$message,$_SERVER['DOCUMENT_ROOT'].$files[0]['file'],$files[0]['name']);
		}else return mail($to,$subject,$message,$headers,$params);
	}else{
		return mail($to,$subject,$message,$headers,$params);
	}
};


function iarga_invert ($string){
  $search = array(
  "й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
  "ф","ы","в","а","п","р","о","л","д","ж","э",
  "я","ч","с","м","и","т","ь","б","ю"
  );
  $replace = array(
  "q","w","e","r","t","y","u","i","o","p","[","]",
  "a","s","d","f","g","h","j","k","l",";","'",
  "z","x","c","v","b","n","m",",","."
  );
  return mb_str_replace($search, $replace, $string);
}


function ib_translit($string){
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '',  'ы' => 'y',   'ъ' => '',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya', ' '=>'_'
    );
	$string = trim(mb_ereg_replace('[^а-яА-Яa-zA-Z0-9\-_\s]',"",$string));
	foreach($converter as $f=>$t){
		$string = mb_str_replace($f,$t,$string);
	}
	$string = mb_strtolower($string);
	return $string;
}
if (!function_exists("mb_str_replace")){
    function mb_str_replace($needle, $replacement, $haystack) {
        return implode($replacement, mb_split($needle, $haystack));
    }
}
function phone($phone,$sectid,$element,$name)
{
    setcookie("phone", $phone,time()+3600*24*7);
    setcookie("sectId", $sectid,time()+3600*24*7);
    setcookie("elemId", $element,time()+3600*24*7);
    setcookie("elementname", $name,time()+3600*24*7);

} 
function on_basket($rewNoOnlyId)
{
    setcookie("rewNoOnlyId", $rewNoOnlyId,time()+3600*24*7);
} 
function summprod($summprod)
{
    setcookie("summprod", $summprod,time()+3600*24*7);
} 

//проверяем наличие пользовательского свойства по уроку. Создаем новое, если нет
function addUserProperty($code, $sort, $type, $name){

    $obUserField = new CUserTypeEntity();

    $eUser = $obUserField->GetList( array($by=>$order), array("FIELD_NAME"=>$code) )->GetNext();

    if(!$eUser["ID"]){
        $idprop = $obUserField->Add(array("ENTITY_ID" => "USER", "SORT"=>$sort, "FIELD_NAME" => $code,"USER_TYPE_ID" => $type, "EDIT_FORM_LABEL" => Array("ru"=>$name)));
    }
    return $idprop;

}

//даляем пользовательские свойства
function deleteUserProperty($code){
	$obUserField = new CUserTypeEntity();

    $eUser = $obUserField->GetList( array($by=>$order), array("FIELD_NAME"=>$code) )->GetNext();
     if($eUser["ID"]){
     		$obUserField->Delete($eUser["ID"]);
     }
}

//проверяем наличие группы по уроку. Создаем новую, если нет
function addGroup($groupId){
    $Groups = CGroup::GetList(($by="c_sort"), ($order="desc"), array("STRING_ID"=>"T_".$groupId))->NavNext(true, "f_");
    if(!$Groups["ID"]){
        $group = new CGroup;
        $sort = explode('_',$groupId);
        $NEW_GROUP_ID = $group->Add(array("ACTIVE" => "Y", "C_SORT" => "100".$sort[1], "NAME" => "Допуск экзамен ".$groupId, "STRING_ID"      => "T_".$groupId));
    }
}

//удаление группу пользователей
function deleteGroup($id){
	$id = explode('_',$id);
	$Groups = CGroup::GetList(($by="c_sort"), ($order="desc"), array("STRING_ID"=>"T_".$id[1]))->NavNext(true, "f_");
	if($Groups["ID"]){
		$group = new CGroup;
		$group->Delete($Groups["ID"]);
	}
}
?>
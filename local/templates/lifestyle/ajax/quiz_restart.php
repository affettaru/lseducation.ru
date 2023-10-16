<?include($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
require $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';

$ID = $_REQUEST['user'];
$answers = 'UF_ANSWER_'.$_REQUEST['test'];
$qRight = 'UF_EXT_'.$_REQUEST['test'];
$qWrong = 'UF_NOEXT_'.$_REQUEST['test'];


$user = new CUser;
$fields = Array( 
    $answers => '', 
    $qRight => '', 
    $qWrong => '', 
    "UF_SAVE_ANSWERS" => '',
); 
$user->Update($ID, $fields);
$strError .= $user->LAST_ERROR;

$result['status'] = "success"; 
$result['url']='ELEMENT_ID='.$_REQUEST["NUM"];
$result['restart']='Y';
echo json_encode($result);
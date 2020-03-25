<!--
   ____                 __    ____  ____
  / __/__  _______ ___ / /_  / __ \/ __/
 / _// _ \/ __/ -_|_-</ __/ / /_/ /\ \
/_/  \___/_/  \__/___/\__/  \____/___/
          WET STONE 1.0
-->
<html>
<head>
  <title>Forest OS Installer</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<style>
body {
	background-attachment: fixed;
	margin: 0;
  padding: 0;
  height: 100%;
  font-family: "Helevetica neue", Helvetica, Arial, sans-serif;
  background: #e6e6e6;
	}
#labels{
  border-top:1px solid #dcdbdb;
  color:#6b6969;
  width:100%;
  margin-top:25px;
  padding: 5 0;
  text-transform: uppercase;
}
.buttoncustom:hover{
  filter: brightness(110%);
}
</style>
<?php

$followlink = $_SERVER['SERVER_NAME'];
$homedir = basename(__DIR__);

if(!preg_match("/$homedir/",$followlink)){
  $followlink = $followlink.'/'.$homedir;
}else{
  $homedir = '';
}

/* #Display errors */
ini_set('display_errors','Off');

/*get os info*/
$urlu = 'http://forest.hobbytes.com/media/os/update.php';
$fileu = file_get_contents($urlu);
$arrayu = json_decode($fileu,TRUE);

foreach ($arrayu as $key)
{
  $fileos  =  $key['file'];
  $version  = $key['version'];
  $subversion  = $key['subversion'];
  $codename = $key['codename'];
}

/*variables*/
function crypt_s($string, $salt){
  $crypt_string = addslashes(strip_tags(htmlspecialchars(crypt($string,'$2a$10$'.md5($salt)))));
  $crypt_string = str_replace('$2a$10$','',$crypt_string);
  return $crypt_string;
}


$download = $_GET['download'];
$userlogin = strtolower(addslashes(strip_tags(htmlspecialchars($_POST['loginin']))));
$userlogin = str_replace(' ', '_', $userlogin);
$passwordlogin = crypt_s(addslashes(strip_tags(htmlspecialchars($_POST['passwordin']))),$userlogin);
$userlang = strtolower(addslashes(strip_tags(htmlspecialchars($_POST['reglang']))));
$hostbd = strtolower(addslashes(strip_tags(htmlspecialchars($_POST['bdhostin']))));
$namebd = strtolower(addslashes(strip_tags(htmlspecialchars($_POST['bdnamein']))));
$userbd = strtolower(addslashes(strip_tags(htmlspecialchars($_POST['bduserin']))));
$passwordbd = $_POST['bdpassin'];
$date = date('d.m.y H:i:s');

if(!empty($userlogin) && !empty($passwordlogin)){
  $fuid = strtoupper(md5($userlogin.$passwordlogin.$date.$hostbd.$namebd.$userbd.$passbd));
  define("DB_DSN","mysql:host=$hostbd;dbname=$namebd");
  define("DB_USERNAME","$userbd");
  define("DB_PASSWORD","$passwordbd");
}

/*GUI CLASS*/
class gui{

  function formstart($method,$action)
  {
    echo '<div style="background-color:#f7f7f7; border-radius:10px; margin:15px auto; padding:10px; border:1px solid #ccc;">
          <form method="'.$method.'" action="'.$action.'" enctype="multipart/form-data">';
  }

  function formend()
  {
    echo '</form></div>';
  }

  function inputs($article, $type, $name, $value, $size, $placeholder,$disabled)
  {
    echo '<div style="padding:10px; margin:10px;"><label for="'.$name.'" style="font-size:17px; border:none; color:#4c4c4c; width:'.$size.'%; ">'.$article.':  </label><input id="'.$name.'" placeholder="'.$placeholder.'" style="width:'.$size.'%; padding:10px; font-size:17px; background-color:#f2f2f2; border-width:0px; color:#3a3a3a; float:right; transition: all 0.2s ease;" type="'.$type.'" name="'.$name.'" value="'.$value.'" '.$disabled.'/></div>';
  }

  function button($article, $textcolor, $backgroundcolor, $size,$name)
  {
    echo '<p style="padding:10px; margin:25px; text-align: center;"><input id="'.$name.'" class="buttoncustom" style="-webkit-appearance:none; background:'.$backgroundcolor.'; border:none; width:auto; color:'.$textcolor.'; padding:10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 0; cursor: pointer; border-radius:3px; text-transform:uppercase; transition: all 0.2s ease;" type="submit" name="'.$name.'" value="'.$article.'" /></p>';
  }
}
$gui = new gui;
?>
<div style="min-height:100%; height:auto !important; height:100%; margin: 0 auto -60px;">
  <div style="margin:auto; text-align:center; max-width:500px; height:80%;">
    <div id="login_container" style="margin:auto; text-align:center; max-width:500px; height:80%;">
      <div>
        <p style="text-align:center">
          <div style="background-image: url(http://forest.hobbytes.com/media/os/updates/uplogo.png); background-size:cover; margin:auto; height:90px; width:90px;">
          </div>
        </p>
        <?php
        if(empty($_GET['lang'])){
        ?>
          <div style="padding:10px; margin:10px;">
            <div style="font-size:20px; color:#565656; padding:10px; font-weight:900;">
              <?php
              echo 'Forest OS'.' '.$codename.', version: '.$subversion;
              ?>
            </div>
          <div style="margin:10px; font-size:20px; color: #565656;">
          Choose your language / Выберите язык
          </div>
            <select id="chlang" style="width:70%; padding:10px; font-size:17px; background-color:#f2f2f2; border-width:0px; color:#3a3a3a; transition: all 0.2s ease;">
              <option value="ru">Русский</option>
              <option value="en">English</option>
            </select>
            <?php
            $gui->button('Ok', '#fff', 'linear-gradient(45deg, #af5a7f 30%, #d04848 90%)', '30','lngbtn');
            ?>
            <script>
            $("#lngbtn").click(function(){
              window.location.href = "?hash=<?php echo md5(date('d-m-y-h-i-s')) ?>&lang="+$("#chlang").val();
            });
            </script>
          </div>
          <?php
        }else{
          if($_GET['lang'] == 'ru'){
            $t_label = 'Установка системы';
            $t_user = 'Пользователь';
            $t_login = 'Логин';
            $t_password = 'Пароль';
            $t_lang = 'Язык';
            $t_image = 'не загружать фоновые рисунки (включите если у вас медленный интернет)';
            $t_ht = 'использовать ОС как основную страницу сайта (заменит файл <b>.htaccess</b>)';
            $t_bd = 'База данных';
            $t_bdhost = 'Адрес';
            $t_bdname = 'Имя';
            $t_button = 'Завершить установку';
            $t_privacy = 'При установке системы, некоторые данные (адрес сайта и ваш уникальный индетификатор) отправятся на наш сервер.';
            $t_login_error = 'уже существует, выберите другое свободное имя';
          }else{
            $t_label = 'System setup';
            $t_user = 'User';
            $t_login = 'Login';
            $t_password = 'Password';
            $t_lang = 'Language';
            $t_image = "don't upload back pictures (turn on if you have slow internet)";
            $t_ht = 'use the OS as the main page of the site (replace the <b>.htaccess</b> file)';
            $t_bd = 'Database';
            $t_bdhost = 'Address';
            $t_bdname = 'Name';
            $t_button = 'Continue';
            $t_privacy = 'When you install the system, some data (the website address and your unique indentifier) will be sent to our server.';
            $t_login_error = 'already exists, select a different free name';
          }
        ?>
        <span style="font-size:29px; font-variant: all-small-caps; font-weight:600; color:#444;">
          <?php echo $t_label ?>
        </span><br />
        <span style="font-size:17px; font-weight:900; color:#636363;">
          <b>Forest OS</b> Wet Stone
        </span>
<?php
if(!empty($fuid)){
  $usercheck = file_get_contents('http://forest.hobbytes.com/media/os/ubase/checkuser.php?check='.$userlogin);
  if($usercheck == 'true' && !empty($userlogin)){
    die('<br><span style="color:#ef6464;">'.$t_login.' <b>'.$userlogin.'</b> '.$t_login_error.'</span>');
  }
  if(!empty($userlogin) && !empty($passwordlogin) && !empty($fuid) && !empty($hostbd) && !empty($namebd) && !empty($userbd) && !empty($passwordbd)){
    if(!isset($_POST['htcheck'])){
     $ht_temp = file_get_contents('.htaccess');
    }
    $userhash = md5($fuid.$_SERVER['DOCUMENT_ROOT'].$passwordlogin);
    $ch = curl_init('http://forest.hobbytes.com/media/os/updates/'.$fileos.'.zip');
    $fp = fopen('os.zip','wb');
    curl_setopt($ch, CURLOPT_FILE,$fp);
    curl_setopt($ch, CURLOPT_HEADER,0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    $zip=new ZipArchive;
    if($zip->open('os.zip') === TRUE){
    $zip->extractTo('./');
    $zip->close();
    }

    if(is_file('system/core/library/bd.php')){
      $conn = new PDO (DB_DSN, DB_USERNAME, DB_PASSWORD);
      $sql = "CREATE TABLE IF NOT EXISTS forestusers (login VARCHAR(150), password VARCHAR(200), fuid VARCHAR(150), status VARCHAR(150), TempKey VARCHAR(255));
      INSERT INTO forestusers (login, password, fuid, status, TempKey) VALUES ('$userlogin', '$passwordlogin', '$fuid', 'superuser', NULL)";
      try {
        $conn->exec($sql);
        //подготавливаем нового пользователя;
        mkdir('system/users/');
        mkdir('system/core/design/walls/');
        mkdir('system/core/design/images/');

        $ch = curl_init('http://forest.hobbytes.com/media/os/walls/night-trees-stars.jpg');
        $fp = fopen('system/core/design/walls/forestoswall.jpg','wb');
        curl_setopt($ch, CURLOPT_FILE,$fp);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        mkdir('system/users/'.$userlogin.'/');
        mkdir('system/users/'.$userlogin.'/desktop/');
        mkdir('system/users/'.$userlogin.'/documents/');
        mkdir('system/users/'.$userlogin.'/documents/images/');
        mkdir('system/users/'.$userlogin.'/trash/');
        mkdir('system/users/'.$userlogin.'/settings/');
        file_put_contents('system/users/'.$userlogin.'/settings/language.foc',$userlang);
        if(is_file('system/core/design/walls/forestoswall.jpg')){
          mkdir('system/users/'.$userlogin.'/settings/etc/');
          if(!isset($_POST['backgroundcheck'])){
            copy('system/core/design/walls/forestoswall.jpg', 'system/users/'.$userlogin.'/settings/etc/wall.jpg');
          }else{
            unlink('system/core/design/walls/forestoswall.jpg');
            unlink('system/core/design/walls/webwall.jpg');
          }
          copy('system/core/design/themes/BlackMamba.fth','system/users/'.$userlogin.'/settings/etc/theme.fth');
          $dr = $_SERVER['DOCUMENT_ROOT'];
          $content = "[link]\n\rdestination=system/apps/Explorer/\n\rfile=main\n\rkey=dir\n\rparam=$dr/system/users/$userlogin/trash\n\rname=Explorer\n\rlinkname=Корзина\n\ricon=system/apps/Explorer/assets/trashicon.png";
          file_put_contents('system/users/'.$userlogin.'/desktop/trash.link',$content);
          $extconfiguration = file_get_contents('http://forest.hobbytes.com/media/os/configfiles/extconfiguration.foc');
          file_put_contents('system/core/extconfiguration.foc',$extconfiguration);
          $myfile = fopen('system/core/osinfo.foc',"w");
          $content = '[forestos]'.PHP_EOL.PHP_EOL.'version='.$version.PHP_EOL.PHP_EOL.'subversion='.$subversion.PHP_EOL.PHP_EOL.'revision='.$fileos.PHP_EOL.PHP_EOL.'codename='.$codename;
          fwrite($myfile,PHP_EOL.$content);
          fclose($myfile);
          file_get_contents('http://forest.hobbytes.com/media/os/ubase/adduser.php?fuid='.$fuid.'&login='.$userlogin.'&version='.str_replace(' ','_',$codename.$subversion).'&followlink='.$followlink.'&userhash='.$userhash);
          if(!empty($ht_temp)){
            file_put_contents('.htaccess',$ht_temp);
          }
        }
      }
      catch (PDOException $e){
        echo 'false: '.$e->getMessage().'\n';
        die();
      }
      unset($conn,$sql);

      //add string to bd library
      $bdaddstring =
      '<?php
        define("DB_DSN","mysql:host='.$hostbd.';dbname='.$namebd.'");
        define("DB_USERNAME","'.$userbd.'");
        define("DB_PASSWORD","'.$passwordbd.'");
      ?>';

      $file = 'system/core/library/bd_inc.php';
      $bdaddstring .= file_get_contents($file) . PHP_EOL;
      file_put_contents($file, $bdaddstring  ."\n");
      unlink('os.zip');
      unlink('index.php');
      $_SESSION['loginuser'] = $userlogin;
      $_SESSION['superuser'] = $userlogin;
      header("Location:/os.php");
  }else{
    echo '<br>Неудалось загрузить файл ОС';
  }
  } else {
    echo '<br>Некоторые поля не заполнены или не введены корректные данные!';
  }
}else{

$gui->formstart('POST','');
?>
<div id="labels"> <?php echo $t_user ?> </div>
<?php
$gui->inputs($t_login, 'text', 'loginin', $userlogin,'70', $t_login,'');
$gui->inputs($t_password, 'password', 'passwordin', '','70',$t_password,'');
?>
<div style="padding:10px; margin:10px;">
  <label for="reglang" style="font-size:17px; border:none; color:#4c4c4c; width:'.$size.'%; "><?php echo $t_lang ?>:  </label>
  <select id="reglang" name="reglang" style="width:70%; padding:10px; float:right; font-size:17px; background-color:#f2f2f2; border-width:0px; color:#3a3a3a; transition: all 0.2s ease;">
    <option value="ru">Русский</option>
    <option value="en">English</option>
  </select>
</div>
<div style="display: grid; grid-template-rows: 50% 50%; width: 100%;">
  <div style="padding:10px;">
    <input style="width:auto;" type="checkbox" id="backgroundcheck" name="backgroundcheck">
    <label for="backgroundcheck"><?php echo $t_image ?></label>
  </div>
  <div style="padding:10px; display:none;">
    <input style="width:auto;" type="checkbox" id="htcheck" name="htcheck" checked>
    <label for="htcheck"><?php echo $t_ht ?></label>
  </div>
</div>
<div id="labels"> <?php echo $t_bd ?> </div>
<?php
$gui->inputs($t_bdhost, 'text', 'bdhostin', $hostbd,'70',$t_bdhost,'');
$gui->inputs($t_bdname, 'text', 'bdnamein', $namebd,'70',$t_bdname,'');
$gui->inputs($t_login, 'text', 'bduserin', $userbd,'70',$t_login,'');
$gui->inputs($t_password, 'password', 'bdpassin', '','70',$t_password,'');
$gui->button($t_button, '#fff', 'linear-gradient(45deg, #778890 30%, #607d88 90%)', '30','logins');
?>
<div style="font-size:11px; color:#6c6c6c; padding:10px; font-weight:600;">
  <?php
  echo 'Forest OS'.' '.$codename.' '.$subversion;
  ?>
</div>

<span style="font-size:10px; color:#6c6c6c; padding:10px;">
  <?php echo $t_privacy ?>
</span>
<?php
$gui->formend();
}
?>

<span style="font-size:10px; color:#6c6c6c; padding:10px;">
  Hobbytes 2017 - <?php echo date('Y'); ?>
</span>
</div>
</div>
</div>
</div>
<?php
}
?>
<script>
  $("#reglang").val("<?php echo $_GET['lang'] ?>");
</script>
</body>
</html>

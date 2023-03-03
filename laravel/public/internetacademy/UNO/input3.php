<?php
session_start();

$mai=array();

if(!isset($_GET['reset']) && isset($_SESSION['turn'])){
    $turn=$_SESSION['turn'];
    $turn++;
}else{
    $turn=0;
    for($f=0;$f<2;$f++){ $mai[$f]=7; }
}

$deck=array();
$card=array();
$comp=array();
$mae=array();
$ngflag=0;

if(!isset($_GET['reset'])){
    if(isset($_SESSION['deck'])) $deck = $_SESSION['deck'];
    if(isset($_SESSION['card'])) $card = $_SESSION['card'];
    if(isset($_SESSION['comp'])) $comp = $_SESSION['comp'];
    if(isset($_SESSION['mae']))  $mae  = $_SESSION['mae'];
    if(isset($_SESSION['mai']))  $mai  = $_SESSION['mai'];
}

echo var_dump($mai);

//deckが保持されてない、resetが押された => deckを作る、maeを設定する
//　　　　　　　　　　　　　　　 それ以外 => deckに、保持データを格納
if(isset($_SESSION['deck']) && !isset($_GET['reset'])){
    $deck = $_SESSION['deck'];
}else{
    $deck=init_cards();

    $mae=array_shift($deck);
    for($d=0;$d<7;$d++){
        $card[]=array_shift($deck);
        $comp[]=array_shift($deck);
    }
}
if(isset($_GET['draw'])){ 
    if(($turn-1)%2===0){ $card[]=array_shift($deck); $mai[($turn)%2]++;}
    if(($turn-1)%2===1){ $comp[]=array_shift($deck); $mai[($turn)%2]++;}
}

$dasucard=array();
if(($turn-1)%2===0){
    for($e=0;$e< $mai[($turn)%2] ;$e++){
        if(isset($_GET['i'.$e+1])){
            $dasucard = $card[$e];
            echo var_dump($dasucard);
            if($mae['color']===($dasucard['color']) ||
               sprintf('%02d',$mae['num'])===sprintf('%02d',$dasucard['num']) ||
               $dasucard['num']===27 || $dasucard['num']===43){ //HとWはどんな色でも出せる
                unset($card[$e]);
                $mai[($turn)%2]--;
            }else{
                echo "出せないカードです！プレイヤーの負けです。　ゲームを終了します。";
                $ngflag=1;
            }
        }
    }
}elseif(($turn-1)%2===1){
    for($e=0;$e< $mai[($turn)%2] ;$e++){
        if(isset($_GET['i'.$e+1])){
            $dasucard = $comp[$e];
            if($mae['color']===($dasucard['color']) ||
               sprintf('%02d',$mae['num'])===sprintf('%02d',$dasucard['num']) ||
               $dasucard['num']===27 || $dasucard['num']===43){ //HとWはどんな色でも出せる
                unset($comp[$e]);
                $mai[($turn)%2]--;
            }else{
                echo "出せないカードです！Ｃｏｍの負けです。　ゲームを終了します。";
                $ngflag=1;
            }
        }
    }
}

if ($ngflag===0){
    $_SESSION['mae']  = $mae;
}elseif(isset($dasucard)){
    $_SESSION['mae']  = $dasucard;    
}else{
    $_SESSION['mae']  = $mae;
}

function init_cards(){
    $decks = array();
    $colors = array("赤","青","黄","緑");

    foreach($colors as $color){
        for($i=0;$i<=45;$i++){
            $decks[] = array(
                'num' => $i,
                'color' => $color
            );
        }
    }
    shuffle($decks);
    return($decks);
}

function left($str, $num, $encoding = "UTF-8"){
    return mb_substr($str, 0, $num, $encoding);
}

function right($str, $num, $encoding = "UTF-8"){
    return mb_substr($str, $num * (-1), $num, $encoding);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>HTML内にPHPを記述する方法</title>
</head>
<body>
<p>手札：
<?php

    foreach($card as $play){
        if($play['num']>19):
            echo $play['color']."-".chr(813+$play['num']) . ' ';
        else:
            echo $play['color'].sprintf('%02d',$play['num']) . ' ';
        endif;
    }
?>
</p>
<hr />

<p>Com<br>手札：
<?php
    foreach($comp as $play){
        if($play['num']>19):
            echo $play['color']."-".chr(813+$play['num']) . ' ';
        else:
            echo $play['color'].sprintf('%02d',$play['num']) . ' ';
        endif;
    }
?>
</p>
<hr />
<?php

$_SESSION['deck'] = $deck;
$_SESSION['card'] = $card;
$_SESSION['comp'] = $comp;
$_SESSION['mae']  = $mae;
$_SESSION['turn'] = $turn;

echo "前ｶｰﾄﾞ : ";
if($dasucard && $ngflag===0){ $mae=$dasucard; }
if($mae['num']>19):
    echo $mae['color']."-".chr(813+$mae['num']) . ' ';
else:
    echo $mae['color'].sprintf('%02d',$mae['num']) . ' ';
endif;

if ($mai[($turn)%2]>10){ $ngflag=1; }

echo "mai" . ($turn+1)%2 . ":" . $mai[($turn+1)%2] . "  turn:" . $turn;
?>
<h1><?php echo 'タイトル'; ?></h1>
    <h1>入力画面</h1>
<ul>
    <li><a href="?reset">reset</a></li>

    <?php if($ngflag === 0):?>
    <?php if($mai[($turn+1)%2] > 0 ):?> <li><a href="?i1">1</a></li> <?php endif;?>
    <?php if($mai[($turn+1)%2] > 1 ):?> <li><a href="?i2">2</a></li> <?php endif;?>
    <?php if($mai[($turn+1)%2] > 2 ):?> <li><a href="?i3">3</a></li> <?php endif;?>
    <?php if($mai[($turn+1)%2] > 3 ):?> <li><a href="?i4">4</a></li> <?php endif;?>
    <?php if($mai[($turn+1)%2] > 4 ):?> <li><a href="?i5">5</a></li> <?php endif;?>
    <?php if($mai[($turn+1)%2] > 5 ):?> <li><a href="?i6">6</a></li> <?php endif;?>
    <?php if($mai[($turn+1)%2] > 6 ):?> <li><a href="?i7">7</a></li> <?php endif;?>
    <?php if($mai[($turn+1)%2] > 7 ):?> <li><a href="?i8">8</a></li> <?php endif;?>
    <?php if($mai[($turn+1)%2] > 8 ):?> <li><a href="?i9">9</a></li> <?php endif;?>
    <?php if($mai[($turn+1)%2] > 9 ):?> <li><a href="?i10">10</a></li> <?php endif;?>
    <li><a href="?draw">draw</a></li>
    <?php endif;?>
    <?php if($ngflag === 1):?>
    <?php echo 'ゲーム終了です。再度始めるにはresetを押してください。'; ?>
    <?php endif;?>

</ul>

<form action="input3.php" method="post">
    <input type="submit" name="sub" value="ﾀｰﾝ終了">
</form>
<?php

    $_SESSION['card'] = array_values($card);
    $_SESSION['comp'] = array_values($comp);
    $_SESSION['mai'] = $mai;
    if ($dasucard){
        $_SESSION['mae']  = $dasucard;
    }else{
        $_SESSION['mae']  = $mae;
    }
    ?>
</body>
</html>
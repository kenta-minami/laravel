
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ＵＮＯ</title>

    @vite('resources/css/app.css')
</head>

<body>

@if($gameend!==1  )
  ・・・残:<?php echo count($playCards) . "枚 " . $playscore . "点 "; ?>  [Player1] 手札： 
  <!-- @if($turn%2===0) -->
    @foreach ($playCards as $key => $playCard)
      <a href="{{route('uno.next',['id' => $playCards[$key]['display'],'no'=>$key],false)}}">{{ $playCard['display'] }}</a>
    @endforeach
  <!-- @endif -->

  <br>
  <hr>
  ・・・残:<?php echo count($compCards) . "枚 " . $compscore . "点 "; ?>  [Player2] 手札： 
  <!-- @if($turn%2===1) -->
    @foreach ($compCards as $key => $compCard)
      <a href="{{route('uno.index',['id' => $compCards[$key]['display'],'no'=>$key],false)}}">{{ $compCard['display'] }}</a>
    @endforeach
  <!-- @endif -->
@endif

<br>
<hr>
<br>

・・・前ｶｰﾄﾞ：
    {{ $maeCard}}
<br>
<hr>
<br>

<?php if(!$nimaime && $wildflag!==1):?>
    <?php if($turn%2===0 && !(count($compCards) > 29) && $playscore <= 500):?>
        <a href="{{route('uno.index',['no'=>'draw'])}}">・・・☆draw</a><br>
    <?php elseif($turn%2===1 && !(count($playCards) > 29) && $compscore <= 500):?>
        <a href="{{route('uno.index',['no'=>'draw'])}}">・・・☆draw</a><br>
    <?php else:?>
        <?php if($turn%2===1){ echo "<br>Player1の負けです。<br>";} ?>
        <?php if($turn%2===0){ echo "<br>Player2の負けです。<br>";} ?>
        <?php echo "10枚を超えたので試合を終了します。resetを押してください。<br>";?>
    <?php endif;?>
<?php elseif($nimaime):?>
    <?php echo "<br>"; ?>
    ・・・出しますか？<br>
    <a href="{{route('uno.index',['no'=>'yes'])}}">・・・Yes</a><br>
    <a href="{{route('uno.index',['no'=>'non'])}}">・・・N o</a><br>
<?php elseif($wildflag===1):?>
    ・・・何色にしますか？<br>
    <a href="{{route('uno.index',['no'=>'w赤'])}}">・・・赤</a><br>
    <a href="{{route('uno.index',['no'=>'w青'])}}">・・・青</a><br>
    <a href="{{route('uno.index',['no'=>'w黄'])}}">・・・黄</a><br>
    <a href="{{route('uno.index',['no'=>'w緑'])}}">・・・緑</a><br>
<?php elseif($next===1):?>
    <?php echo "<br>"; ?>
    ・・・次のプレイヤーのターンです。<br>
    <a href="{{route('uno.index',['no'=>'OK'])}}">・・・O K</a><br>
<?php endif;?>

・・・<a href="{{route('uno.index')}}">☆reset</a><br>
<br><br><br>

・・・<a href="{{route('uno.index',['no'=>'next'])}}">☆ルール</a><br><br><br>
<?php if($Exhibit===1){$turn--; }?>
<br>

</body>

</html>


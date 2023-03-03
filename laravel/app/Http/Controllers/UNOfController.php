<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TodoList;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class UNOfController extends Controller
{
    public function index(Request $request)
    {

        //$hoge = "(・∀・)";

        $nummax = 29;  //数字カードの最大値
        $maisu = 17;   //手札初期配布枚数
        $numcolor = 2; //出現する色の種類($numcolor<6)
        $numalpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";//出現する文字カードの種類($numalpha<27)    ABCDEFGHIJKLMNOPQRSTUVWXYZ

        $dasuflag=0;
        $lett="";
        $nimaime= 0;
        $Aflag=0;
        $helpflag = 0;
        $helpflag2=session('helpflag');
        $wildflag = 0;
        $wildflag2=session('wildflag');
        $Bflag=0;
        $Nflag=0;
        $Nflagt=0;
        $Nflag2=session('nflag');
        $Jflag=0;
        $Zflag=0;
        $gettop=0;
        $gettop1=0;
        $cardcolor=0;
        $ccount=0;
        $Exhibit=0;
        $Xchange=0;
        $playalpha=0;
        $compalpha=0;
        $playlensc=0;
        $complensc=0;
        $playwin=0;
        $compwin=0;
        $gameend=0;
        $next=0;
        $onum=0;
        $Znum=0;
        $vc="";
        $vccount=0;
        $archiv = 0;
        $score1=0;
        $turn = session('turn');
//        if($turn===0){ 
            $archive[]="";
//        }else{ 
//            $archive = session('archive'); 
//        }
        $nimaime2 = session('nimaime');
        $nimaime3 = "";
        $gameend2 = session('gameend');
        $playscore = session('playscore');
        $compscore = session('compscore');
        $maeCard1="";
        $Jarr=[];
        $rand1 = mt_rand( 1, 2 );
        $rand2 = mt_rand( 0, 1 ); 
        if($rand2===0){
            $lett="数字"; 
        }else{
            $lett="文字"; 
        }
        $rand3 = mt_rand( 0, 3 ); 
        switch ($rand3){
            case 0: 
                $rand3="赤"; 
                break;
            case 1: 
                $rand3="黄"; 
                break;
            case 2: 
                $rand3="青"; 
                break;
            case 3: 
                $rand3="緑"; 
                break;
        }
//        echo $request->input('no') . " " . $request->input('id');

        if($request->input('status')==='next'){
            echo "interval page";
            $deck=session('deck');
            $playCards=session('playCards');
            $compCards=session('compCards');
            $maeCards=session('maeCards');
            $turn=session('turn');
            $nimaime=session('nimaime');
            $helpflag=session('helpflag');
            $playscore=session('playscore');
            $compscore=session('compscore');
            $playwin=session('playwin');
            $compwin=session('compwin');
            session(['deck' => $deck]);
            session(['playCards' => $playCards]);
            session(['compCards' => $compCards]);
            session(['maeCards' => $maeCards]);
            session(['turn' => $turn]);
            session(['nimaime' => $nimaime]);
            session(['helpflag' => $helpflag]);
            session(['playscore' => $playscore]);
            session(['compscore' => $compscore]);
            session(['playwin' => $playwin]);
            session(['compwin' => $compwin]);
            if(!($turn===0)){
                session(['archive' => $archive]); 
            }

            $next=1;

            return view('uno.index', [
                'playCards'=> $playCards,
                'compCards'=> $compCards,
                'maeCard'=> $maeCards,
                'turn'=> $turn,
                'nimaime'=> $nimaime,
                'helpflag'=> $helpflag,
                'wildflag'=> $wildflag,
                'Exhibit'=> $Exhibit,
                'gameend'=> $gameend,
                'next'=> $next,
                'playscore'=> $playscore,
                'compscore'=> $compscore,
                'playlensc'=> $playlensc,
                'complensc'=> $complensc,
            ]
        );

    
        }elseif(
            $request->input('no')==='rule'){
            echo "ＵＮＯ<br><br>「色」または「数字」を揃えて「場」（ここでは「前カード」と称する）に置いていくゲームです。<br>
                数字は０から" . $nummax . "まで存在します。また、文字カードが存在し、ｱﾙﾌｧﾍﾞｯﾄのAからZまで全て存在しています。<br>
                現在、E,G,L以外の２３種類の文字カードが完璧に制御されており、これら以外の文字カードを出すとバグが発生する<br>
                ことがある状態になっています。<br><br>
                【 ２枚目のカードの出し方 】<br>
                カードは通常１枚しか出せませんが、色が異なる場合でも、19より小さい数字カードの「数字の1の位が等しい別のカード」を所持していた場合、<br>
                それを出すことができます。「Joker, Nuisance, Gettopcard, Wild以外」の同じ文字の文字カードを2枚所持していた場合も、<br>
                同様に出すことができます。出せる場合は「出しますか？」と聞いてくるので、「はい」「いいえ」を選んで決めてください。<br>
                ただし、文字カードを2枚目に出した場合は、2枚目に出した文字カードの効果は、Draw two以外は発揮されません。<br>
                また、Jokerを所持していれば、2枚目のカードが出せる状態であっても、出すことができなくなります。<br><br>
                【 文字カードの効果 】<br>
                ・A：Annihilate =>  ランダムに指定されたプレイヤーの、数字カードまたは文字カードが全て消滅します。<br>
                ・B：Barrior =>  相手から、「Punish, Yield」以外の文字カードで何らかの攻撃を受けた際に、それをバリアします。1度攻撃を防いだらBarriorは1枚消えます。<br>
                ・C：thisCard all =>  出したthisCardallの色のカードが全て手札から消えます。<br>
                ・D：Draw two =>  次のプレイヤーはデッキからカードを2枚引きます。<br>
                ・・E：Exhibit =>  次のプレイヤーのカードを1秒間、見ることができます。<br>
                ・F：Force => 次のプレイヤーがHelpを所持していれば、そのHelpは全て消えます。<br>
                ・・G：Get top card => 場の一番上のカードを自分のものにできます。<br>
                ・H：Help => Nuisanceを持っていない限り、色に関係なく好きなときに出せます。Helpを2枚出すと好きなカードを1枚捨てることができます。<br>
                ・I：Impose => 次のプレイヤーにNuisanceを強制的に所持させ、押しつけることができます。すでに所持していた場合は2枚目を所持することになります。<br>
                ・J：Joker => Jokerを所持している限り、ランダムに選ばれたカードが1枚、ランダムに変更されます。また、カード2枚で出すのが不可能となります。<br>　　　　　　　Jokerを所持した状態で出せないカードを出そうとすると、1枚カードを引いた上で次のプレイヤーに移行します。従って1枚カードを引くだけのターンとなります。<br>　　　　　　　出せるカードか念入りに確認した上でカードを出すようにしてください。Jokerを出せた場合は、出した次のターンより、ランダムに変更されるのはなくなります。<br>　　　　　　　また、Jokerを所持した状態でoneMoretimeを出した場合、カードを１枚引く仕様を食らうのが相手のプレイヤーとなります。<br>
                ・K：Keep => 勝ち上がるときの残り枚数が1増えます。例えば「3」だった場合、手札の残り枚数を3枚にすれば勝利となります。<br>
                ・・L：Limit => 次のターンのプレイヤーは、2枚でカードを出すことしかできなくなります。1枚でしかカードが出せない場合は、カードを1枚引きます。<br>
                ・M：one More time => もう1度あなたのターンです。<br>
                ・N：Nuisance => このカードを所持しているとHelpおよびWildを出すことができません。<br>
                ・O：O[オー] => 通常の文字カード得点以外に、9以下の数字カードの数字の和だけ、得点が加算されます。<br>
                ・P：Punish => あなたのカードの枚数が次のプレイヤーの半分の枚数の手札であれば、手札が3枚増えます。そうでなければ何も起きません。<br>
                ・Q：Quast => 全プレイヤー、手札の中からカードが２枚消えます。<br>
                ・R：Reverse => 2人でのプレイだと、もう一度あなたのターンとなります。<br>
                ・S：Skip => 次のターンのプレイヤーを飛ばします。<br>
                ・T：Two people skip => 次の2人分のプレイヤーのターンを飛ばします。<br>
                ・U：Ultimate => １枚引いたカードと前カードが、数字や文字および色が等しければ、残り手札が１枚になります。等しくなければ、手札が３枚増えます。<br>
                ・V：Vanish => aを任意の色としたとき、次のプレイヤーがa色というthisCardallを所持していれば、あなたのa色のカードと次のプレイヤーのそのthisCardallが全て消えます。<br>
                ・W：Wild => Nuisanceを持っていない限り、色に関係なく好きなときに出せます。次のターンのプレイヤーが出すカードの色を決めることができます。<br>
                ・X：Xchange => 出すと、手札の中身が全て変わります。出したあとの手札の枚数は変わりません。なお、変更されたあとの手札にXchangeがあれば、それを2枚目として出すことができます。<br>
                ・Y：Yield => 次のプレイヤーに、所持しているNuisanceとJokerを、全て譲渡します。<br>
                ・Z：Zero => 通常得点の" . $nummax . "点を獲得したあと、得点の最上位以外の数字が全て0になります。<br><br>
                【得点に関して】<br>
                カードを出すと、出したカードに応じて以下の得点が与えられます。<br>
                ・数字カード => その数字ぶんの点数<br>
                ・文字カード => " . $nummax+1 . "点<br><br>
                なお、所持しているカードの枚数と、得点の各位の数の和が一致した場合、ボーナスとして得点に２００点が加算されます。";
        }else{
            if($request->input('id') || (($request->input('no')==='yes' || $request->input('no')==='non') && 
                !($request->input('id'))) || mb_substr($request->input('no'), 0, 1)==="w"){
                
                $playdis=[];
                $compdis=[];
                $playCards = session('playCards');
                $compCards = session('compCards');
                $playJun = session('playJun');
                $compJun = session('compJun');
                $deck = session('deck');
                $maeCard = session('maeCards');
                $cardarch = array('num' => ord(mb_substr($maeCard,-1,1)), 'color' =>  mb_substr($maeCard, 0, 1), 'display' => $maeCard); 
                
                if($turn%2===0){
                    $archiv = sprintf('%03d', $turn) . " ";
                    foreach($playCards as $index => $value){
                        if(mb_substr($value['display'],-1,1)==="N"){
                            $Nflag=1;
                        }
                        if(mb_substr($value['display'],-1,1)==="J"){
                            $Jflag=1;
                        }
                        if(mb_substr($value['display'],-2,1)==="0"){
                            $onum=$onum+intval(mb_substr($value['display'],-1,1)); 
                        }
                    }
                    if($Jflag===1){ 
                        $dasuflag=1; 
                        $rand4 = mt_rand( 1, count($playCards) ); 
                        $playCards[]=array_shift($deck); 
                        $playCards = array_values($playCards); 
                        $maeCard1===0;
                    }
                    foreach($compCards as $index => $value){
                        if(mb_substr($value['display'],-1,1)==="N"){
                            $NflagT=1;
                        }
                        if(mb_substr($value['display'],-1,1)==="B"){
                            $Bflag=1;
                        }
                    }
                    if($request->input('no')==='yes' || $request->input('no')==='non' || mb_substr($request->input('no'), 0, 1)==="w"){
                        echo "<br>";
                    }else{
                        if(sprintf('%02d',$playCards[$request->input('no')]['num'])===mb_substr($maeCard, -2, 2) ||
                        strcmp($playCards[$request->input('no')]['color'],mb_substr($maeCard, 0, 1))==0 ||
                        strcmp(mb_substr($playCards[$request->input('no')]['display'], -1, 1),mb_substr($maeCard, -1, 1))==0 ||
                        (($playCards[$request->input('no')]['num']===$nummax+8 || $playCards[$request->input('no')]['num']===$nummax+23)
                        && $Nflag===0) || $helpflag2===1 || mb_substr($playCards[$request->input('no')]['display'], -1, 1)==="U"  ){
                            echo "<br>";
                            if($helpflag2!==1){
                                if(mb_substr($playCards[$request->input('no')]['display'], -2, 1)==="-"): 
                                    $maeCard1 = "-".mb_substr($playCards[$request->input('no')]['display'], -1, 1);
                                    else: $maeCard1 = sprintf('%02d', intval(mb_substr($playCards[$request->input('no')]['display'], -2, 2)));
                                endif;
                                $gettop=array('num' => $playCards[$request->input('no')]['num'], 'color' =>  mb_substr($maeCard, 0, 1), 'display' => $maeCard);
                                $gettop1=$maeCard;
                                $getN=array('num' => $nummax + 14, 'color' =>  $rand3, 'display' => $rand3 . "-N");
                                $cardarch=array($gettop, $cardarch);
                                $playalpha=mb_substr($playCards[$request->input('no')]['display'], -1, 1);
                                $cardcolor=$playCards[$request->input('no')]['color'];
                                $maeCard=$playCards[$request->input('no')]['color'].$maeCard1;
                                $archiv = $archiv . " " . $maeCard;
                                echo "・・・";
                                $cardU=array_shift($deck)['display'];
                                if($playalpha!=="O"){ 
                                    $onum=0;
                                }
                                if    ($playalpha==="A"){ 
                                    echo "Annihilate: Player" . $rand1 . "の" . $lett . "カードが全て消えました。"; 
                                    $Aflag=1;
                                }
                                elseif($playalpha==="B"){
                                    echo "Barrior: 前のプレイヤーから文字カードで何か攻撃を受けたときにそれを防御します。Barrior単独で出すと何も起きません。";
                                }
                                elseif($playalpha==="C"){
                                    foreach($playCards as $index => $value){ 
                                        if(($value['color'])===$cardcolor){ 
                                            unset($playCards[$index]); 
                                            $ccount++; 
                                        }
                                    }
                                    if($ccount!==0){ 
                                        echo "thisCard all: " . $cardcolor . "色のthisCard allを出しましたので、あなたの手札にある" . $cardcolor . "色のカード" . $ccount-1 . "枚が全て消えました。";
                                    }else{ 
                                        echo "thisCard all: " . $cardcolor . "色のthisCard allを出しましたが、あなたの手札に" . $cardcolor . "色のカードがありませんでした。";
                                    }
                                }
                                elseif($playalpha==="D"){ 
                                    echo "Draw two : 次のﾌﾟﾚｲﾔｰは2枚、場からｶｰﾄﾞを引きます。"; 
                                    foreach($compCards as $index=>$value){ 
                                        if(mb_substr($value['display'],-1,1)==="B"){
                                            unset($compCards[$index]);
                                            echo "Barriorを所持していたため回避しました。所持していたBarriorは消えました。"; 
                                            break; 
                                        }else{
                                            $compCards[]=array_shift($deck); 
                                            $compCards[]=array_shift($deck); 
                                            break; 
                                        }
                                    }
                                }
                                elseif($playalpha==="E"){ 
                                    foreach($compCards as $index=>$value){ 
                                        if(mb_substr($value['display'],-1,1)==="B"){
                                            unset($compCards[$index]);
                                            echo "Barriorを所持していたため回避しました。所持していたBarriorは消えました。"; 
                                            break; 
                                        }
                                    }
                                    echo "Exhibit: 次のプレイヤーのカードを１秒間だけみることができます。";
                                    sleep(1); 
                                    foreach($compCards as $index=>$value){ 
                                        echo $value['display'];
                                    }
                            }
                                elseif($playalpha==="F"){ 
                                    echo "Force: あなたがHelpを所持していたので、Forceの効果により全て無くなりました。"; 
                                    foreach($compCards as $index=>$value){ 
                                        if(mb_substr($value['display'],-1,1)==="B"){
                                            unset($compCards[$index]);
                                            echo "Barriorを所持していたため回避しました。所持していたBarriorは消えました。"; 
                                            break; 
                                        }
                                        if(mb_substr($value['display'],-1,1)==="H"){
                                            unset($compCards[$index]); 
                                        }
                                    }
                                }
                                elseif($playalpha==="G"){ 
                                    echo "Get top card: このカードを出す前に前カードに置いてあったカードがあなたの手札に加わりました。";
                                    $playCards[count($playCards)]=$gettop;
                                }
                                elseif($playalpha==="H"){ 
                                    echo "Help: もう1枚Helpを出すと1枚自由にカードを捨てられます。"; 
                                }
                                elseif($playalpha==="I"){ 
                                    foreach($compCards as $index=>$value){ 
                                        if(mb_substr($value['display'],-1,1)==="B"){
                                            unset($compCards[$index]);
                                            echo "Barriorを所持していたため回避しました。所持していたBarriorは消えました。"; 
                                            break; 
                                        }
                                    }
                                    echo "Impose: 前のプレイヤーさんからNuisanceが押しつけられました。";
                                    if($Nflagt===0){
                                        $compCards[count($compCards)+1]=$getN;
                                    }
                                }
                                elseif($playalpha==="J"){ 
                                    if($Nflag===1){ 
                                        /*echo "Nuisanceを所持しているのでJokerは出せません。"; */
                                    }else{
                                        echo "Jokerが出されたので、次のターンから通常通りカードが減っていきます。"; 
                                    }
                                }
                                elseif($playalpha==="L"){ 
                                    foreach($compCards as $index=>$value){ 
                                        if(mb_substr($value['display'],-1,1)==="B"){
                                            unset($compCards[$index]);
                                            echo "Barriorを所持していたため回避しました。所持していたBarriorは消えました。"; 
                                            break; 
                                        }
                                    }
                                    echo "Limit : あなたは、「ルール上２枚出せるカード」しか出すことができなくなります。１枚でカードを出そうとしても、出せません。"; 
                                }
                                elseif($playalpha==="K"){ 
                                    echo "Keep : 勝ち上がるときの残り枚数が１増えます。現在" . $playwin . "枚だったのが" . $playwin+1 . "枚になりました。"; 
                                    $playwin++; 
                                }
                                elseif($playalpha==="M"){ 
                                    echo "one More time : もう一度あなたのﾀｰﾝです。"; 
                                    $turn++; 
                                }
                                elseif($playalpha==="N"){ 
                                    echo "Nuisance : このカードを出したので、Joker, HelpとWildを出すことができるようになりました。";
                                }
                                elseif($playalpha==="O"){ 
                                    echo "O[オー] : 通常の" . $nummax+1 . "点に加え、ターン開始時の手札の、９以下の数字カードの数字ぶん(" . $onum . "点)、得点が加算されました。";
                                }
                                elseif($playalpha==="P"){ 
                                    if((count($compCards)*2)<=count($playCards)){ 
                                        echo "Punish : 前のプレイヤーの手札の枚数が、あなたの手札の枚数の半分だったので、手札が３枚増えます。"; 
                                        $playCards[]=array_shift($deck); 
                                        $playCards[]=array_shift($deck); 
                                        $playCards[]=array_shift($deck);
                                    }else{
                                        echo "前のプレイヤーの手札の枚数が、あなたの手札の枚数の半分ではなかったので、何も起きませんでした。";
                                    }
                                }
                                elseif($playalpha==="Q"){ 
                                    echo "Quash : 全プレイヤーの手札の中から、ランダムで2枚、カードが消えました。"; 
                                    unset( $playCards[mt_rand( 0, count($playCards)-1)] ); 
                                    unset( $playCards[mt_rand( 0, count($playCards)-1)] ); 
                                    unset( $compCards[mt_rand( 0, count($compCards)-1)] ); 
                                    unset( $compCards[mt_rand( 0, count($compCards)-1)] ); 
                                    $playCards = array_values($playCards); 
                                    $compCards = array_values($compCards); 
                                }
                                elseif($playalpha==="R"){ 
                                    echo "Reverse : 次のﾌﾟﾚｲﾔｰのﾀｰﾝを飛ばします。"; 
                                    $turn++; 
                                }
                                elseif($playalpha==="S"){ 
                                    echo "Skip : 次のﾌﾟﾚｲﾔｰのﾀｰﾝを飛ばします。"; 
                                    $turn++; 
                                }
                                elseif($playalpha==="T"){ 
                                    echo "Two people skip : 先のﾌﾟﾚｲﾔｰ2人のﾀｰﾝを飛ばします。"; 
                                    $turn++; 
                                    $turn++; 
                                }
                                elseif($playalpha==="U"){ 
                                    echo "Ultimate : １枚、カードをランダムに引いた「" . $cardU . "」が、Ultimateを出す前のカード「" . $gettop['display'] . "」に文字または数字が一致"; 
                                    if(mb_substr($cardU,-1,2)===mb_substr($gettop['display'],-1,2)){
                                        echo "したので、手札が１枚になります。"; 
                                        $playCards=[]; 
                                        $playCards[]=array_shift($deck); 
                                    }else{
                                        echo "しなかったので、手札が３枚増えます。"; 
                                        $playCards[]=array_shift($deck); 
                                        $playCards[]=array_shift($deck); 
                                        $playCards[]=array_shift($deck); 
                                    }
                                }
                                elseif($playalpha==="V"){ 
                                    foreach($compCards as $index=>$value){
                                        if(mb_substr($value['display'],-1,1)==="C"){ 
                                            if($Bflag===1){
                                                unset($compCards[$index]);
                                                echo "Barriorを所持していたため回避しました。所持していたBarriorは消えました。"; 
                                            }else{
                                                $vc=$value['color']; 
                                                unset($compCards[$index]); 
                                                break;
                                            }
                                        }else{
                                            echo "Vanish : 次のプレイヤーはthisCardallを所持していませんでした。"; 
                                            $vc=0; 
                                            break;
                                        }
                                    }
                                    if($vc!==0){
                                        foreach($playCards as $index=>$value){ 
                                            if($value['color']===$vc){ 
                                                unset($playCards[$index]); 
                                                $vccount++; 
                                            }
                                        }
                                        echo "前のﾀｰﾝのﾌﾟﾚｲﾔｰの出したVanishの効果により、あなたがthisCardallを所持していたので、あなたの" . $vc . "色のthisCardallと、前のﾀｰﾝのﾌﾟﾚｲﾔｰの". $vc . "色のカード" . $vccount . "枚が全て消えました。"; 
                                        $compCards = array_values($compCards);
                                    }
                                }
                                elseif($playalpha==="W"){ 
                                    echo "Wild : 次のﾌﾟﾚｲﾔｰの出す色を設定します。"; 
                                    $wildflag=1; 
                                }
                                elseif($playalpha==="X"){ 
                                    echo "Xchange : あなたの手札が全て変わりました。枚数は変わっていません。"; 
                                    $Xchange=1; 
                                }
                                elseif($playalpha==="Y"){ 
                                    echo "Yield : 前のプレイヤーがNuisanceとJokerを所持して"; 
                                    if($Nflag===1 || $Jflag===1){ 
                                        echo "いるため、全て譲渡されました。";
                                        foreach($playCards as $index => $value){
                                            if(mb_substr($value['display'],-1,1)==="N"){
                                                $compCards[]=$playCards[$index];
                                            }
                                            if(mb_substr($value['display'],-1,1)==="J"){
                                                $compCards[]=$playCards[$index];
                                            }
                                        }
                                    }else{
                                        echo "いないため、何も起きませんでした。";
                                    }
                                }
                                elseif($playalpha==="Z"){ 
                                    echo "Zero : 前のプレイヤーの得点の最上位以外が０になりました。"; 
                                    $Zflag=1; 
                                }
                            }
                            if(count($playCards)===$playwin){ //thisCardallで勝利する場合
                                $gameend=1;
                                goto gameend;
                            }
                            unset($playCards[$request->input('no')]);
                            $playCards = array_values($playCards);
                            if($Xchange===1){
                                $playmai=count($playCards);
                                $playCards=[];
                                for($d=0;$d<$playmai;$d++){
                                    $playCards[]=array_shift($deck);
                                }            
                            }
                        }else{
                            if($compalpha!=="O"){ 
                                $onum=0;
                            }
                            if(mb_substr($request->input('id'),-1,1)==="W"){ 
                                $turn++;
                            }
                            if($Nflag===1 && $dasuflag===0){
                                $dasuflag=1; 
                                echo "Nuisanceを所持しているのでJoker・Help・Wildは出せません<br>"; 
                            }else{
                                $dasuflag=1;
                                echo "出せません<br>"; 
                            }
                            $maeCard1=0;
                        }
                    }
                    foreach($playCards as $index => $value){ 
                        $playdis[]=$value['display']; 
                    }
                    if(mb_substr($maeCard, -2, 1)==='-'){ 
                        $nimai="/[青赤黄緑紫]" . '-' . mb_substr($maeCard, -1, 1) . "/"; 
                    }else{  
                        $nimai="/[青赤黄緑紫][01]" . mb_substr($maeCard, -1, 1) . "/"; 
                    }
                    $nimai=preg_grep($nimai, $playdis);
                    if($dasuflag===0){
                        if($request->input('no')==='yes'){
                            if(mb_substr($nimaime2,-2,1)==="-"):
                                $maeCard1 = "-".mb_substr($nimaime2,-1,1);
                                else: $maeCard1 = sprintf('%02d',$playCards[intval(mb_substr($nimaime2,0,2))]['num']);
                            endif;
                            $maeCard = $playCards[intval(mb_substr($nimaime2,0,2))]['color'].$maeCard1;
                            $archiv = $archiv . " " . $maeCard;
                            unset($playCards[intval(mb_substr($nimaime2,0,2))]);
                            $playCards = array_values($playCards);
                        }else{
                            if($request->input('no')==='non' || $nimaime){
                                $nimaime="";
                            }
                            foreach($nimai as $index => $value){ 
                                $nimaime3 = mb_substr($value,-1,1);
                                if($nimaime3!=="G" && $nimaime3!=="N" && $nimaime3!=="W"){
                                    if($nimai && !($request->input('no')==='non')){
                                        echo "２枚目に [" . $index . "] " . $value . " を出せます<br><br>";
                                        if($nimaime3==="M"){$turn--;}
                                        $nimaime=sprintf('%02d',$index).$value;
                                    }else{
                                        $nimaime=""; 
                                        $maeCard1=0;
                                    }
                                }
                                break;
                            }
                        }
                    }
                    if($request->input('no')==='yes' && mb_substr($nimaime2,-1,1)==="H"){ 
                        $turn--; 
                        $helpflag=1; 
                        $maeCard1=0;
                        echo "Helpが2枚出されました。好きなカードを1枚捨てることができます。捨てるカードを選んでください。" ;
                    }
                    if($request->input('no')==='yes' && mb_substr($nimaime2,-1,1)==="D"){
                        $compCards[]=array_shift($deck); 
                        $compCards[]=array_shift($deck);
                        echo "Draw twoが２枚出されたのでデッキに４枚カードが加わりました。" ;
                    }else{
                        if(mb_substr($request->input('no'), 0, 1)==="w"){
                            $maeCard = mb_substr($request->input('no'), -1, 1) . "__";
                            $maeCard1="-W";
                            $archiv = $archiv . " " . mb_substr($request->input('no'), 1, 1);
                        }
                    }
                    if($request->input('no')==='yes' && mb_substr($nimaime2,-1,1)==="W"){}
                    if($request->input('no')==='yes' && mb_substr($nimaime2,-1,1)==="G"){}
                    if(mb_substr($request->input('id'), -1, 1)==="W"){ 
                        $turn--;
                    }
                    if($dasuflag!==1){
                        if(!($dasuflag!==1 && $nimaime!==0 ) || $request->input('no')==='non'){
                            $turn++; 
                            $next=1; //次のターンへ
                        }
                    }
                    if(!($maeCard1) && $Jflag===0){
                    }else{
                        if($Jflag===1){
                            echo "Joker: Jokerを所持している限り、ランダムに選ばれたカードが1枚、ランダムに変更されます。"; 
                            $turn++;
                        }
                        if(mb_substr($request->input('no'), 0, 1)!=="w" && $request->input('no')!=='non'){
                            if($compalpha!=="O"){ 
                                $onum=0;
                            }
                            $score1=0;
                            if(mb_substr($maeCard1,0,1)==="-" || mb_substr($nimaime2,-2,1)==="-"){
                                $score1=$nummax+1; 
                            }else{
                                $score1=intval($maeCard1); 
                            } 
                            $playscore=$playscore+$score1+$onum;
                            if($Zflag===1){ 
                                $playscore=mb_substr($playscore,0,1) * (pow(10,(strlen($playscore)-1))); 
                            }
                            for($e=0;$e<strlen($playscore);$e++){ 
                                $playlensc=$playlensc+mb_substr($playscore,$e,1); 
                            }
                        }
                    }
                    $playCards = array_values($playCards);
                    if(count($playCards)===$playlensc){echo "カードの枚数と、得点の各位の数の和が一致しました！ボーナス得点で２００点が加算されました！";
                        $playscore=$playscore+200; 
                    }
                    $archiv = $archiv . " " . $playscore;
                    $archive= $archiv;
                }else{ //PLAYER2
                    $archiv = sprintf('%03d', $turn);
                    foreach($compCards as $index => $value){
                        if(mb_substr($value['display'],-1,1)==="N"){
                            $Nflag=1;
                        }
                        if(mb_substr($value['display'],-1,1)==="J"){
                            $Jflag=1;
                        }
                        if(mb_substr($value['display'],-2,1)==="0"){
                            $onum=$onum+intval(mb_substr($value['display'],-1,1));
                        }
                    }
                    if($Jflag===1){ 
                        $dasuflag=1; 
                        $rand4 = mt_rand( 1, count($compCards) ); 
                        $compCards[]=array_shift($deck); 
                        $compCards = array_values($compCards); 
                        $maeCard1===0;
                    }
                    if($request->input('no')==='yes' || $request->input('no')==='non' || mb_substr($request->input('no'), 0, 1)==="w"){
                        echo "<br>";
                    }else{
                        if(sprintf('%02d',$compCards[$request->input('no')]['num'])===mb_substr($maeCard, -2, 2) ||
                        strcmp($compCards[$request->input('no')]['color'],mb_substr($maeCard, 0, 1))==0 ||
                        strcmp(mb_substr($compCards[$request->input('no')]['display'], -1, 1),mb_substr($maeCard, -1, 1))==0 ||
                        (($compCards[$request->input('no')]['num']===$nummax+8 || $compCards[$request->input('no')]['num']===$nummax+23)
                        && $Nflag===0) || $helpflag2===1 || mb_substr($compCards[$request->input('no')]['display'], -1, 1)==="O"  ){
                            echo "<br>";
                            if($helpflag2!==1){
                                if(mb_substr($compCards[$request->input('no')]['display'], -2, 1)==="-"): 
                                    $maeCard1 = "-".mb_substr($compCards[$request->input('no')]['display'], -1, 1);
                                    else: $maeCard1 = sprintf('%02d', intval(mb_substr($compCards[$request->input('no')]['display'], -2, 2)));
                                endif;
                                $gettop=array('num' => $compCards[$request->input('no')]['num'], 'color' =>  mb_substr($maeCard, 0, 1), 'display' => $maeCard);
                                $getN=array('num' => $nummax + 14, 'color' =>  $rand3, 'display' => $rand3 . "-N");
                                $compalpha=mb_substr($compCards[$request->input('no')]['display'], -1, 1);
                                $cardcolor=$compCards[$request->input('no')]['color'];
                                $maeCard=$compCards[$request->input('no')]['color'].$maeCard1;
                                $archiv = $archiv . " " . $maeCard;
                                echo "・・・";
                                $cardU=array_shift($deck)['display'];
                                if($compalpha!=="O"){ 
                                    $onum=0;
                                }
                                if    ($compalpha==="A"){
                                    echo "Annihilate: Player" . $rand1 . "の" . $lett . "カードが全て消えました。"; 
                                    $Aflag=1; 
                                }
                                elseif($compalpha==="B"){
                                    echo "Barrior: 前のプレイヤーから文字カードで何か攻撃を受けたときにそれを防御します。Barrior単独で出すと何も起きません。";
                                }
                                elseif($compalpha==="C"){
                                    foreach($compCards as $index => $value){ 
                                        if(($value['color'])===$cardcolor){ 
                                            unset($compCards[$index]); 
                                            $ccount++; 
                                        }
                                    }
                                    if($ccount!==0){ 
                                        echo "thisCard all: " . $cardcolor . "色のthisCard allを出しましたので、あなたの手札にある" . $cardcolor . "色のカード" . $ccount-1 . "枚が全て消えました。";
                                    }
                                    else{ echo "thisCard all: " . $cardcolor . "色のthisCard allを出しましたが、あなたの手札に" . $cardcolor . "色のカードがありませんでした。";
                                    }
                                }
                                elseif($compalpha==="D"){ 
                                    echo "Draw two : 次のﾌﾟﾚｲﾔｰは2枚、場からｶｰﾄﾞを引きます。"; 
                                    foreach($playCards as $index=>$value){ 
                                        echo mb_substr($value['display'],-1,1);
                                        if(mb_substr($value['display'],-1,1)==="B"){
                                            unset($playCards[$index]);
                                            echo "Barriorを所持していたため回避しました。所持していたBarriorは消えました。"; 
                                            break; 
                                        }else{
                                            $playCards[]=array_shift($deck); 
                                            $playCards[]=array_shift($deck); 
                                            break; 
                                        }
                                    }
                                }
                                elseif($compalpha==="E"){ 
                                    echo "Exhibit: 次のプレイヤーのカードを１秒間だけみることができます。";
                                    sleep(1); 
                                    foreach($playCards as $index=>$value){ 
                                        echo $value['display']; 
                                    }
                                }
                                elseif($compalpha==="F"){ 
                                    echo "Force: あなたがHelpを所持していたので、Forceの効果により全て無くなりました。"; 
                                    foreach($playCards as $index=>$value){ 
                                        if(mb_substr($value['display'],-1,1)==="H"){
                                            unset($playCards[$index]); 
                                        }
                                    }
                                }
                                elseif($compalpha==="G"){ 
                                    echo "Get top card: このカードを出す前に前カードに置いてあったカードがあなたの手札に加わりました。";
                                    $compCards[count($compCards)+1]=$gettop; 
                                }
                                elseif($compalpha==="H"){ 
                                    echo "Help: もう1枚Helpを出すと1枚自由にカードを捨てられます。"; 
                                }
                                elseif($compalpha==="I"){ 
                                    echo "Impose: 前のプレイヤーさんからNuisanceが押しつけられました。";
                                    if($Nflagt===0){
                                        $playCards[count($playCards)+1]=$getN; 
                                    }
                                }
                                elseif($compalpha==="J"){ 
                                    if($Nflag===1){ 
                                        /*echo "Nuisanceを所持しているのでJokerは出せません。"; */
                                    }else{
                                        /*echo "Joker: Jokerを所持している限り、ランダムに選ばれたカードが1枚、ランダムに変更されます。"; */
                                    }
                                }
                                elseif($compalpha==="K"){ 
                                    echo "Keep : 勝ち上がるときの残り枚数が１増えます。残り枚数が現在" . $compwin . "枚で勝利できるのが、" . $compwin+1 . "枚で勝利できるようになりました。"; $compwin++; 
                                }
                                elseif($compalpha==="M"){ 
                                    echo "one More time : もう一度あなたのﾀｰﾝです。"; 
                                    $turn++; 
                                }
                                elseif($compalpha==="N"){ 
                                    echo "Nuisance : このカードを出したので、HelpとWildを出すことができるようになりました。";
                                }
                                elseif($compalpha==="O"){ 
                                    echo "O[オー] : 通常の" . $nummax+1 . "点に加え、ターン開始時の手札の９以下の数字カードの数字ぶん(" . $onum . "点)、得点が加算されました。";
                                }
                                elseif($compalpha==="P"){ 
                                    echo "Punish : 前のプレイヤーの手札の枚数が、あなたの手札の枚数の半分";
                                    if((count($playCards)*2)<=count($compCards)){ 
                                        echo "だったので、手札が３枚増えます。"; 
                                        $compCards[]=array_shift($deck); 
                                        $compCards[]=array_shift($deck); 
                                        $compCards[]=array_shift($deck);
                                    }else{
                                        echo "ではなかったので、何も起きませんでした。"; 
                                    }
                                }
                                elseif($compalpha==="Q"){ 
                                    echo "Quash : 全プレイヤーの手札の中から、ランダムで2枚、カードが消えました。"; 
                                    unset($playCards[mt_rand( 0, count($playCards)-1)] ); 
                                    unset($playCards[mt_rand( 0, count($playCards)-1)] ); 
                                    unset($compCards[mt_rand( 0, count($compCards)-1)] ); 
                                    unset($compCards[mt_rand( 0, count($compCards)-1)] ); 
                                    $playCards = array_values($playCards); 
                                    $compCards = array_values($compCards); 
                                }
                                elseif($compalpha==="R"){ 
                                    echo "Reverse : 次のﾌﾟﾚｲﾔｰのﾀｰﾝを飛ばします。"; 
                                    $turn++; 
                                }
                                elseif($compalpha==="S"){ 
                                    echo "Skip : 次のﾌﾟﾚｲﾔｰのﾀｰﾝを飛ばします。"; 
                                    $turn++; 
                                }
                                elseif($compalpha==="T"){ 
                                    echo "Two people skip : 先のﾌﾟﾚｲﾔｰ2人のﾀｰﾝを飛ばします。"; 
                                    $turn++; 
                                    $turn++; 
                                }
                                elseif($compalpha==="U"){ 
                                    echo "Punish : １枚、カードをランダムに引いた「" . $cardU . "」が、Punishを出す前のカード" . $gettop['display'] . "に文字または数字が一致"; 
                                    if(mb_substr($cardU['display'],-1,2)===mb_substr($gettop['display'],-1,2)){
                                        echo "したので、手札が１枚になります。"; 
                                        $compCards=[]; 
                                        $compCards[]=array_shift($deck); 
                                    }else{
                                        echo "しなかったので、手札が３枚増えます。"; 
                                        $compCards[]=array_shift($deck); 
                                        $compCards[]=array_shift($deck); 
                                        $compCards[]=array_shift($deck); 
                                    }
                                }
                                elseif($compalpha==="V"){ 
                                    echo "Vanish : "; 
                                    foreach($playCards as $index=>$value){ 
                                        if(mb_substr($value['display'],-1,1)==="C"){ 
                                            $vc=$value['color']; unset($playCards[$index]); 
                                            break;
                                        }else{
                                            echo "次のプレイヤーはthisCardallを所持していませんでした。"; $vc=0; break;
                                        }
                                    }
                                    if($vc!==0){
                                        foreach($compCards as $index=>$value){ 
                                            if($value['color']===$vc){ 
                                                unset($compCards[$index]); 
                                                $vccount++; 
                                            }
                                        }
                                        echo "前のﾀｰﾝのﾌﾟﾚｲﾔｰの出したVanishの効果により、あなたがthisCardallを所持していたので、あなたの" . $vc . "色のthisCardallと、前のﾀｰﾝのﾌﾟﾚｲﾔｰの". $vc . "色のカード" . $vccount . "枚が全て消えました。"; 
                                        $playCards = array_values($playCards);
                                    }
                                }
                                elseif($compalpha==="W"){ 
                                    echo "Wild : 次のﾌﾟﾚｲﾔｰの出す色を設定します。"; 
                                    $wildflag=1; 
                                }
                                elseif($compalpha==="X"){ 
                                    echo "Xchange : あなたの手札が全て変わりました。"; 
                                    $Xchange=1; 
                                }
                                elseif($compalpha==="Y"){ 
                                    echo "Yield : 前のプレイヤーがNuisanceとJokerを所持して"; 
                                    if($Nflag===1 || $Jflag===1){ echo "いるため、全て次のプレイヤーに譲渡します。";
                                    foreach($compCards as $index => $value){
                                        if(mb_substr($value['display'],-1,1)==="N"){
                                            $playCards[]=$compCards[$index];
                                        }
                                        if(mb_substr($value['display'],-1,1)==="J"){
                                            $playCards[]=$compCards[$index];}
                                        }
                                    }else{
                                        echo "いないため、何も起きませんでした。";
                                    }
                                }
                                elseif($compalpha==="Z"){ echo "Zero : あなたの得点の最上位以外が０になります。"; $Zflag=1; }
                                if($turn%2===1 && mb_substr($maeCard,-1,1)==="L"){ $maeCard=$gettop; echo "Limitによる制限の影響でそのカードは出せません。"; }
                            }
                            if(count($compCards)===$compwin){ //thisCardallで勝利する場合
                                $gameend=1;
                                goto gameend;
                            }
                            $archiv=$archiv . " " . $compCards[$request->input('no')]['display'];
                            unset($compCards[$request->input('no')]);
                            $compCards = array_values($compCards);
                            if($Xchange===1){
                                $compmai=count($compCards);
                                $compCards=[];
                                for($d=0;$d<$compmai;$d++){
                                    $compCards[]=array_shift($deck);
                                }            
                            }
                        }else{
                            if($compalpha!=="O"){ 
                                $onum=0;
                            }
                            if(mb_substr($request->input('id'),-1,1)==="W"){ 
                                $turn++;
                            }
                            if($Nflag===1 && $dasuflag===0){
                                $dasuflag=1; 
                                echo "Nuisanceを所持しているのでHelp・Wildは出せません<br>"; 
                            }elseif($Jflag===1 && $dasuflag===0){
                                $dasuflag=1; 
                                $rand4 = mt_rand( 1, count($compCards) ); 
                                array_splice( $compCards, $rand4+1, 1, array_shift($deck)); }
                            else{
                                $dasuflag=1;
                                echo "出せません<br>"; 
                            }
                            $maeCard1=0;
                        }
                    }
                    foreach($compCards as $index => $value){ 
                        $compdis[]=$value['display']; 
                    }
                    if(mb_substr($maeCard, -2, 1)==='-'){ 
                        $nimai="/[青赤黄緑]" . '-' . mb_substr($maeCard, -1, 1) . "/"; 
                    }
                    else{  
                        $nimai="/[青赤黄緑][01]" . mb_substr($maeCard, -1, 1) . "/"; 
                    }
                    $nimai=preg_grep($nimai, $compdis);
                    if($dasuflag===0){
                        if($request->input('no')==='yes'){
                            if(mb_substr($nimaime2,-2,1)==="-"):
                                $maeCard1 = "-".mb_substr($nimaime2,-1,1);
                                else: $maeCard1 = sprintf('%02d',$compCards[intval(mb_substr($nimaime2,0,2))]['num']);
                            endif;
                            $maeCard = $compCards[intval(mb_substr($nimaime2,0,2))]['color'].$maeCard1;
                            $archiv = $archiv . " " . $maeCard;
                            unset($compCards[intval(mb_substr($nimaime2,0,2))]);
                            $compCards = array_values($compCards);
                        }else{
                            if($request->input('no')==='non' || $nimaime){
                                $nimaime="";
                            }
                            foreach($nimai as $index => $value){ 
                                    $nimaime3 = mb_substr($value,-1,1);
                                    if($nimaime3!=="G" && $nimaime3!=="N" && $nimaime3!=="W"){
                                        if($nimai && !($request->input('no')==='non')){
                                            echo "２枚目に [" . $index . "] " . $value . " を出せます<br><br>";
                                            if($nimaime3==="M"){
                                                $turn--;
                                            }
                                            $nimaime=sprintf('%02d',$index).$value;
                                        }else{
                                            $nimaime=""; 
                                            $maeCard1=0;
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                    if($request->input('no')==='yes' && mb_substr($nimaime2,-1,1)==="H"){ 
                        $turn--; 
                        $helpflag=1;
                        echo "Helpが2枚出されました。好きなカードを1枚捨てることができます。捨てるカードを選んでください。" ;
                        $maeCard1=0;
                    }
                    if($request->input('no')==='yes' && mb_substr($nimaime2,-1,1)==="W"){ 
                        $turn++; 
                        echo " wild2枚目";
                    }
                    if($request->input('no')==='yes' && mb_substr($nimaime2,-1,1)==="D"){
                        $playCards[]=array_shift($deck); 
                        $playCards[]=array_shift($deck);
                        echo "Draw twoが２枚出されたのでデッキに４枚、カードが加わりました。" ;
                    }else{
                        if(mb_substr($request->input('no'), 0, 1)==="w"){
                            $maeCard = mb_substr($request->input('no'), -1, 1) . "__";
                            $maeCard1="-W";
                        }
                    }
                    if(mb_substr($request->input('id'), -1, 1)==="W"){ 
                        $turn--;
                    }
                    if($dasuflag!==1){
                        if(!($dasuflag!==1 && $nimaime!==0 ) || $request->input('no')==='non'){
                            $turn++;  //次のターンへ
                            $next=1;
                        }else{
                            echo " ";
                        } 
                    }
                    if(!($maeCard1) && $Jflag===0){
                    }else{
                        if($Jflag===1){
                            echo "Joker: Jokerを所持している限り、ランダムに選ばれたカードが1枚、ランダムに変更されます。"; 
                            $turn++;
                        }
                        if(mb_substr($request->input('no'), 0, 1)!=="w" && $request->input('no')!=='non'){
                            if($compalpha!=="O"){ 
                                $onum=0;
                            }
                            $score1=0;
                            if(mb_substr($maeCard1,0,1)==="-"){
                                $score1=$nummax+1; 
                            }else{
                                $score1=intval($maeCard1);
                            } 
                            $compscore=$compscore+$score1+$onum;
                            if($Zflag===1){ 
                                $compscore=mb_substr($compscore,0,1) * (pow(10,(strlen($compscore)-1))); 
                            }
                            for($e=0;$e<strlen($compscore);$e++){ 
                                $complensc=$complensc+mb_substr($compscore,$e,1); 
                            }
                        }
                    }
                }
                if(count($compCards)===$complensc){
                    echo "カードの枚数と、得点の各位の数の和が一致しました！ボーナス得点で２００点が加算されました！";
                    $compscore=$compscore+200; 
                }
                $archiv = $archiv . " " . $compscore;
                if($Aflag===1){
                    foreach($playCards as $index => $value){ 
                        $playdis[]=$value['display']; 
                    }
                    foreach($compCards as $index => $value){ 
                        $compdis[]=$value['display']; 
                    }
                    if    ($rand1===1){
                        if($lett==="数字"){ 
                            foreach($playdis as $index => $value){ 
                                if(mb_substr($value,1,1)!=="-"){ 
                                    unset($playCards[$index]); 
                                }
                            }
                        }
                        elseif($lett==="文字"){ 
                            foreach($playdis as $index => $value){ 
                                if(mb_substr($value,1,1)==="-"){ 
                                    unset($playCards[$index]);
                                }
                            }
                        }
                        $playCards = array_values($playCards);
                    }
                    elseif($rand1===2){
                        if($lett==="数字"){ 
                            foreach($compdis as $index => $value){ 
                                if(mb_substr($value,1,1)!=="-"){ 
                                    unset($compCards[$index]);
                                }
                            }
                        }
                        elseif($lett==="文字"){ 
                            foreach($compdis as $index => $value){ 
                                if(mb_substr($value,1,1)==="-"){ 
                                    unset($compCards[$index]); 
                                }
                            }
                        }
                        $compCards = array_values($compCards);
                    }
                }
                if(!$nimaime || ($nimaime && $request->input('no')==='non')){
                    if(session('turn')%2===1){ 
                        echo "turn: " . $turn . "  【Player " . ($turn%2)+1 . "】<br><br>"; }
                    if(session('turn')%2===0){ 
                        echo "turn: " . $turn . "  【Player " . ($turn%2)+1 . "】<br><br>"; }
                }
                //$archive[]=$archiv;
            }else{
                if(session('turn')%2===0 && $request->input('no')==='draw'){
                    $turn = session('turn');
                    $deck=$this->init_cards($nummax,$numcolor,$numalpha);
                    $playCards = session('playCards',session()->all());
                    $playCards[]=array_shift($deck);
                    $compCards = session('compCards',session()->all());
                    $maeCard = session('maeCards');
                    $turn++;
                    echo "<br>";
                    echo "・・・";
                    echo "turn: " . $turn . "  【Player " . (($turn)%2)+1 . "】<br><br>";
                }elseif(session('turn')%2===1 && $request->input('no')==='draw'){
                    $turn = session('turn');
                    $deck=$this->init_cards($nummax,$numcolor, $numcolor);
                    $playCards = session('playCards',session()->all());
                    $compCards = session('compCards',session()->all());
                    $compCards[]=array_shift($deck);
                    $maeCard = session('maeCards');
                    $turn++;
                    echo "<br>";
                    echo "・・・";
                    echo "turn: " . $turn . "  【Player " . (($turn)%2)+1 . "】<br><br>";
                }else{
                    $turn = 0;
                    $w = 0;
                    session()->flush();
                    $deck=$this->init_cards($nummax,$numcolor, $numalpha);
                    for($d=0;$d<$maisu;$d++){
                        $playCards[]=array_shift($deck);
                        $compCards[]=array_shift($deck);
                    }

                    $mae=array_shift($deck);
                    $maeCard=$mae['display'];
                    $archive[]="初:" . $maeCard;
                    $playscore=0;
                    $compscore=0;
                    echo var_dump($archive);
                    echo "<br>";
                    echo "・・・";
                    echo "turn: 0 【Player 1】<br><br>";
                }
            }

            if(count($playCards) <= $playwin){
                $gameend=1; 
            }
            if(count($compCards) <= $compwin){
                $gameend=1; 
            }
gameend:
            if($gameend===1){
                echo "Player" . ($turn%2) . "の勝利です。おめでとうございます。" ;
            }
            var_dump($archive); echo "<br>";

            session(['deck' => $deck]);
            session(['playCards' => $playCards]);
            session(['compCards' => $compCards]);
            session(['maeCards' => $maeCard]);
            session(['turn' => $turn]);
            session(['nimaime' => $nimaime]);
            session(['helpflag' => $helpflag]);
            session(['playscore' => $playscore]);
            session(['compscore' => $compscore]);
            session(['playwin' => $playwin]);
            session(['compwin' => $compwin]);
//            session(['archive' => $archive]);
            echo $turn;
            if(!($turn===0)){session(['archive' => $archive]); }

        return view('uno.index', [
            'playCards'=> $playCards,
            'compCards'=> $compCards,
            'maeCard'=> $maeCard,
            'turn'=> $turn,
            'nimaime'=> $nimaime,
            'helpflag'=> $helpflag,
            'wildflag'=> $wildflag,
            'Exhibit'=> $Exhibit,
            'gameend'=> $gameend,
            'next'=> $next,
            'playscore'=> $playscore,
            'compscore'=> $compscore,
            'playlensc'=> $playlensc,
            'complensc'=> $complensc,
        ]
        );
        return redirect()->route('uno.next', ['id' => $playCards[$key]['display'],'no'=>$key]);
        }
    }
    public function next(){
    }

    private function init_cards($nummax,$numcolor,$numalpha){
        $decks = [];
        $colors = ["赤","青"];
        if($numcolor>2){
            $colors[]="緑";
        }
        if($numcolor>3){
            $colors[]="黄";
        }
        if($numcolor>4){
            $colors[]="紫";
        }
        foreach($colors as $color){
            for($i=0;$i<=$nummax+mb_strlen($numalpha);$i++){
                if($i>$nummax){
                    $display = $color . '-' . mb_substr($numalpha,$i-$nummax-1,1);
                }else{
                    $display = $color . sprintf('%02d',$i);
                }
                $decks[] = array(
                    'num' => $i,
                    'color' => $color,
                    'display' => $display,
                );
            }
        }
        shuffle($decks);
        return($decks);
    }
}
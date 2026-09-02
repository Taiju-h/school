<?php
App::uses('AppController', 'Controller');
/*
App::import('Vendor', 'tcpdf/tcpdf');
App::import('Vendor', 'fpdi/fpdi');
*/
/**
 * Eschedules Controller
 *
 * @property Eschedule $Eschedule
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class EschedulesController extends AppController
{

/**
 * Components
 *
 * @var array
 */
    public $components = array('Paginator', 'Flash', 'Session', 'Pdf');

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->Paginator->settings = array(
                'sort' => 'Eschedule.deadline',
                'direction' => 'DESC'    );

        $this->Eschedule->recursive = 0;
        $this->set('eschedules', $this->Paginator->paginate());
    }
    public function sankalist()
    {
        if ($this->request->is('post')) {
            $this->sankalist_pdf($this->request->data['Mlecturer']['id']);
        }

        $this->loadModel('Mlecturer');
        $mlecturers = $this->Mlecturer->find('list', array('conditions' => array('Mlecturer.dispflg' => 1)));
        $this->set(compact('mlecturers'));
    }



    public function seikyusho()
    {
        if ($this->request->is('post')) {
            $this->seikyusho_pdf($this->request->data['Mlecturer']['id'], $this->request->data['Mlecturer']['nen']['year'], $this->request->data['Mlecturer']['tuki']['month']);
            exit;
        }

        $this->loadModel('Mlecturer');
        $mlecturers = $this->Mlecturer->find('list', array('conditions' => array('Mlecturer.dispflg' => 1)));
        $this->set(compact('mlecturers'));
    }
    public function todaylist()
    {
        if ($this->request->is('post')) {
            $this->todaylist_pdf($this->request->data['Vtodaylist']['kaisaidate']);
            exit;
        }

        $this->loadModel('Vtodaylist');
        $vtodaylists = $this->Vtodaylist->find('list', array('fields' => array('kaisaidate', 'kaisaidate'), 'order' => 'Vtodaylist.kaisaidate'));
        $this->set(compact('vtodaylists'));
    }
    public function seikyusho_pdf($id = null, $year = null, $mon = null)
    {
        //var_dump($id);exit;
        $week = array("日", "月", "火", "水", "木", "金", "土");


        $yymm = $year . '-' . $mon . '-01';
        $today = new DateTime($yymm);

        $date = $today->modify('last day of this months')->format('Y年m月d日');

        $pdf = $this->Pdf->pdfstart();

        $total = $total_cnt = 0;

        $dateS = $today->modify('last day of this months')->format('Y-m-01');

        $dateE = $today->modify('last day of this months')->format('Y-m-d');


        $eschedules = $this->Eschedule->find('all', array(
                 'fields' => array('Eschedule.id', 'Eschedule.date1', 'Eschedule.date2', 'Eschedule.date3','Mryoukin.daytimes', 'Mryoukin.priname', 'Mryoukin.kng1', 'Mryoukin.kng2'),
                'conditions' => array(array('Eschedule.enddate >=' => $dateS, 'Eschedule.date1 <=' => $dateE), 'Mryoukin.mlecturer_id' => $id),
                'order' => 'Eschedule.date1'));
 // var_dump($eschedules);
  //exit;


        $line_count = $line_max = 31;
        foreach ($eschedules as $eschedule) {

            if ($line_count >= $line_max - 3) {
                //続きなのか？
                if ($line_max != $line_count) {
                    $x = 17;
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(82, $yadd, "次ページへ", 0, 0, 'R');

                    $x = 185;
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(11, $yadd, number_format($total), 0, 0, 'R');
                }

                //改ページ処理
                // ページ追加
                $pdf->AddPage();
                $pdf->setSourceFile('./files/SchoolSeikyu.pdf');
                $page = $pdf->importPage(1);
                $pdf->useTemplate($page);
                $pdf->SetFont('kozminproregular', '', 11);//日本語のフォントも使えるよーこれは明朝体
                $font_path = './ipag00303/ipag.ttf';

                if (file_exists($font_path)) {
                    $font_name = $pdf->addTTFfont($font_path, 'TrueTypeUnicode');
                    $pdf->SetFont($font_name, '', 10);
                }

                //		$today = new DateTime($yymm);
                $pdf->Text(170, 3.5, $date);
                $pdf->SetFontSize(28);
                $pdf->Text(95, 12, "請求書");
                $pdf->SetFont('kozminproregular', '', 11);//日本語のフォントも使えるよーこれは明朝体

                $this->loadModel('Mdivision');
                $mdivision = $this->Mdivision->find('first', array('conditions' => array('Mdivision.id' => 1)));

                $this->loadModel('Mprivacy');
                $mprivacy = $this->Mprivacy->find('first', array('conditions' => array('Mprivacy.mlecturer_id' => $id)));


                $pdf->SetFontSize(16);
                $y = 22;
                $pdf->Text(18, $y, $mdivision['Mdivision']['kaishaname']);
                $pdf->SetFontSize(14);
                $y += 7;

                $pdf->Text(18, $y, $mdivision['Mdivision']['daihyouname'] . '殿');
                $pdf->SetFontSize(10);
                $y += 6;
                $pdf->Text(18, $y, "〒" .  $mdivision['Mdivision']['postcode'] . ' ' . $mdivision['Mdivision']['address']);
                $y += 5;
                $pdf->Text(48, $y, $mdivision['Mdivision']['address2']);
                $y += 5;
                $pdf->Text(18, $y, "電話番号：" . $mdivision['Mdivision']['telno']);

                $y = 27;
                $pdf->Text(120, $y, "〒" .  $mprivacy['Mprivacy']['sei_postcode'] . ' ' .  $mprivacy['Mprivacy']['sei_add1']);
                $y += 5;
                $pdf->Text(150, $y, $mprivacy['Mprivacy']['sei_add2']);
                $y += 5;
                $pdf->Text(140, $y, $mprivacy['Mprivacy']['sei_name']);
                $y += 5;
                $pdf->Text(120, $y, "電話番号：" . $mprivacy['Mprivacy']['sei_tel']);


                $pdf->SetFontSize(12);
                $x = 40;
                $y1 = 52.5;
                $y2 = $y1 + 2.5;
                $y3 = $y2 + 2.5;
                $pdf->Text($x, $y1, "  内         　容");
                $pdf->Text($x, $y3, "  (参    加    者)");
                $x += 66;
                $pdf->Text($x, $y2, "日　付");
                $x += 20;
                $pdf->Text($x, $y1, "回");
                $pdf->Text($x, $y3, "目");
                $x += 7.5;
                $pdf->Text($x, $y2, "区分");
                $x += 11.5;
                $pdf->Text($x, $y1, "人");
                $pdf->Text($x, $y3, "数");
                $x += 9.5;
                $pdf->Text($x, $y2, "単　価");
                $x += 24;
                $pdf->Text($x, $y2, "金　額");

                $y = 64.5;
                //$Y = 6; //高さ
                    $X = 18; //幅
                    $yadd = 5.95;

                if ($line_max != $line_count) {
                    $x = 17;
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(82, $yadd, "前ページより", 0, 0, 'R');
                    $x = 185;
                    $pdf->SetXY($x, $y);
                    $pdf->Cell(11, $yadd, number_format($total), 0, 0, 'R');

                    $y += $yadd;
                    $line_count = 1;
                } else {
                    $line_count = 0;
                }
            }




            $K_name = $eschedule['Mryoukin']['priname'];
            $x = 17;

            $msubscriptions = $this->Eschedule->Msubscription->find('all', array(
                 'fields' => array('Msubscription.mstudentst1_id', 'Msubscription.mstudentst2_id', 'Msubscription.mstudentst3_id', 'Msubscription.mpaymentmethod_id', 'Muser.name'),
                'conditions' => array('Msubscription.eschedule_id =' => $eschedule['Eschedule']['id'], 'Msubscription.mworkst_id =' => array(46,50), 'Msubscription.mpaymentmethod_id >' =>  0),
                'order' => 'Msubscription.muser_id'));
            $oldx = $x;
            $kaisai_flg = 1;
            for ($ix = 1; $eschedule['Mryoukin']['daytimes'] >= $ix; $ix++) {
                //オンライン講座は１回しかカウントしない
                if(($eschedule['Mryoukin']['daytimes'] > 3) && ($ix > 1))
                    break;
                $sankacnt[3] = $sankacnt[2] = $sankacnt[1] = 0;
                $wkname[3] = $wkname[2] = $wkname[1] = null;
                if ($eschedule['Eschedule']['date' .$ix] < $dateS)
                    continue;

                if ($eschedule['Eschedule']['date' .$ix] > $dateE)
                    break;

                $pdf->SetFontSize(11);
                $x = 17;
                $pdf->Text($x, $y, $K_name);
                $K_name = null;


                foreach ($msubscriptions as $msubscription) {

                    if ($msubscription['Msubscription']['mstudentst'.$ix . '_id'] != 20) {
                        continue;
                    }
                    //受講済みの場合のみカウントする
                    switch ($msubscription['Msubscription']['mpaymentmethod_id']) {
                        case 6:
                            $wkix = 2;
                            break;
                        case 7:
                            $wkix = 3;
                            break;
                        default:
                            $wkix = 1;
                            break;
                    }
                    $sankacnt[$wkix]++;
                    //var_dump( $sankacnt[$wkix],'<BR>');
                    if ($sankacnt[$wkix] == 5) {
                        $wk_sprit = '/';
                    } else {
                        $wk_sprit = ',';
                    }
                    $wkname[$wkix] .= $wk_sprit . $msubscription['Muser']['name'] . '様';
                }

                $dispdate = 1;
                for ($wkix = 0; $wkix <= 3; $wkix++) {
                    if ($sankacnt[$wkix] < 1) {
                        continue;
                    }
                    $x = $oldx;

                    $y += $yadd;
                    $line_count++;
                    $pdf->SetFontSize(9);
                    $pdf->SetXY($x, $y);

                    $wk_name = explode("/", $wkname[$wkix]);

                    $pdf->Cell(82, $yadd, substr($wk_name[0], 1), 0, 0, 'R');

                    if (isset($wk_name[1])) {
                        $y += $yadd;
                        $line_count++;
                        $pdf->SetXY($x, $y);
                        $pdf->Cell(82, $yadd, $wk_name[1], 0, 0, 'R');
                    }
                    $x += 84.5;
                    $pdf->SetFontSize(11);
                    if ($dispdate) {
                        $pdf->Text($x, $y, $eschedule['Eschedule']['date' .$ix]);
                        $dispdate = 0;
                        $kaisai_flg = 0;

                        $pdf->Text($x + 27, $y, $ix);
                    }
                    //回数
                    $x += 27;
                    $x += 6.5;
                    $wk_kbn = NULL;
                    switch ($wkix) {
                        case '3':
                            $wk_kbn = '新仕';
                            break;
                        case '2':
                            $wk_kbn = '仕事';
                            break;
                        default:
                            $wk_kbn = '一般';
                            break;
                    }
                    $pdf->Text($x, $y, $wk_kbn);
                    $x += 11.5;
                    $pdf->Text($x, $y, $sankacnt[$wkix]);
                    $x += 5.5;
                    $pdf->SetXY($x, $y);

                    //割引料金が2にはいってる
                    if($wkix == 2) $wkwkix = 'kng2' ;
                    else $wkwkix = 'kng1';
                    $pdf->Cell($X, $yadd, number_format($eschedule['Mryoukin'][$wkwkix]), 0, 0, 'R');

                    $x += 26;

                    $pdf->SetXY($x, $y);
                    $pdf->Cell($X, $yadd, number_format($eschedule['Mryoukin'][$wkwkix] * $sankacnt[$wkix]), 0, 0, 'R');
                    $total += $sankacnt[$wkix] * $eschedule['Mryoukin'][$wkwkix];
                    $total_cnt += $sankacnt[$wkix];
                }

                if ($kaisai_flg) {
                    $x = $oldx;
                    $x += 84.5;

                    $pdf->Text($x, $y, $eschedule['Eschedule']['date1']);
                }
            }
            $y += $yadd;
            $line_count++;
        }
        //$var_dump($dateS);


        //相殺
        /*
                $pdf->SetFontSize(11);
                $x = 17;
                $pdf->Text($x, $y,  "通信費");

                $x += 84.5;
                $pdf->Text($x, $y, "2017-10-20" );
                $x += 27;
                $x += 6.5;
                $x += 11.5;
                $pdf->Text($x, $y, 1);
                $x += 5.5;
                $wk_kng = 380;
                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');

                $x += 26;

                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
                $total += $wk_kng;

                $y += $yadd;
                $x = 17;
                $pdf->Text($x, $y,  "9/16西洋占星術中級(独立)の過請求分");

                $x += 84.5;
                $pdf->Text($x, $y, "2017-10-31" );
                $pdf->SetFontSize(11);
                $x += 27;
                $x += 6.5;
                $x += 11.5;
                $pdf->Text($x, $y, 3);
                $x += 5.5;
                $wk_kng = -7380;
                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');

                $x += 26;

                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng * 3), 0, 0, 'R');
                $total += $wk_kng * 3;

        //相殺

                $pdf->SetFontSize(11);
                $x = 17;
                $pdf->Text($x, $y,  "鑑定料金貴社分");

                $x += 84.5;
                $pdf->Text($x, $y, "2017-11-29" );
                $x += 27;
                $x += 6.5;
                $x += 11.5;
                $pdf->Text($x, $y, 1);
                $x += 5.5;
                $wk_kng = -4000;
                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
                $total += $wk_kng;
                $x += 26;

                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');

        //相殺

                $pdf->SetFontSize(11);
                $x = 17;
                $pdf->Text($x, $y,  "鑑定料金貴社分");

                $x += 84.5;
                $pdf->Text($x, $y, "2018-06-30" );
                $x += 27;
                $x += 6.5;
                $x += 11.5;
                $pdf->Text($x, $y, 1);
                $x += 5.5;
                $wk_kng = -4000;
                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
                $total += $wk_kng;
                $x += 26;

                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');

                    $y += $yadd;
                    $line_count++;
        */
        /*
        //相殺

                $pdf->SetFontSize(11);
                $x = 17;
                $pdf->Text($x, $y,  "鑑定料金貴社分");

                $x += 84.5;
                $pdf->Text($x, $y, "2018-07-03" );
                $x += 27;
                $x += 6.5;
                $x += 11.5;
                $pdf->Text($x, $y, 1);
                $x += 5.5;
                $wk_kng = -4000;
                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
                $total += $wk_kng;
                $x += 26;

                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');

                    $y += $yadd;
                    $line_count++;
        */

        //相殺
        /*
                $pdf->SetFontSize(11);
                $x = 17;
                $pdf->Text($x, $y,  "鑑定料金貴社分");

                $x += 84.5;
                $pdf->Text($x, $y, "2019-08-25" );
                $x += 27;
                $x += 6.5;
                $x += 11.5;
                $pdf->Text($x, $y, 1);
                $x += 5.5;
                $wk_kng = -2000;
                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
                $total += $wk_kng;
                $x += 26;

                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');

                    $y += $yadd;
                    $line_count++;

        */
        //相殺
        /*
                $pdf->SetFontSize(11);
                $x = 17;
                $pdf->Text($x, $y,  "鑑定料金貴社分");

                $x += 84.5;
                $pdf->Text($x, $y, "2019-09-07" );
                $x += 27;
                $x += 6.5;
                $x += 11.5;
                $pdf->Text($x, $y, 1);
                $x += 5.5;
                $wk_kng = -4000;
                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
                $total += $wk_kng;
                $x += 26;

                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');

                    $y += $yadd;
                    $line_count++;
                */
        //相殺

        /*
                $pdf->SetFontSize(11);
                $x = 17;
                $pdf->Text($x, $y,  "鑑定料金貴社分");

                $x += 84.5;
                $pdf->Text($x, $y, "2019-10-20" );
                $x += 27;
                $x += 6.5;
                $x += 11.5;
                $pdf->Text($x, $y, 1);
                $x += 5.5;
                $wk_kng = -4000;
                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
                $total += $wk_kng;
                $x += 26;
                $pdf->SetXY($x, $y);
                $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');

                    $y += $yadd;
                    $line_count++;

        */

        //相殺
        /*
	$pdf->SetFontSize(11);
        $x = 17;
        $pdf->Text($x, $y, "鑑定料金貴社分");

        $x += 84.5;
        $pdf->Text($x, $y, "2020-09-16");
        $x += 27;
        $x += 6.5;
        $x += 11.5;
        $pdf->Text($x, $y, 1);
        $x += 5.5;
        $wk_kng = -4000;
        $pdf->SetXY($x, $y);
        $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
        $total += $wk_kng;
        $x += 26;

        $pdf->SetXY($x, $y);
        $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
        $y += $yadd;
        $line_count++;

        $pdf->SetFontSize(11);
        $x = 17;
        $pdf->Text($x, $y, "鑑定料金貴社分");

        $x += 84.5;
        $pdf->Text($x, $y, "2020-09-26");
        $x += 27;
        $x += 6.5;
        $x += 11.5;
        $pdf->Text($x, $y, 1);
        $x += 5.5;
        $wk_kng = -2000;
        $pdf->SetXY($x, $y);
        $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
        $total += $wk_kng;
        $x += 26;

        $pdf->SetXY($x, $y);
        $pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
        $y += $yadd;
        $line_count++;
*/
/*
		$pdf->SetFontSize(11);
		$x = 17;
		$pdf->Text($x, $y, "鑑定料金貴社分");

		$x += 84.5;
		$pdf->Text($x, $y, "2020-12-27");
		$x += 27;
		$x += 6.5;
		$x += 11.5;
		$pdf->Text($x, $y, 1);
		$x += 5.5;
		$wk_kng = -3600;
		$pdf->SetXY($x, $y);
		$pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
		$total += $wk_kng;
		$x += 26;

		$pdf->SetXY($x, $y);
		$pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
		$y += $yadd;
		$line_count++;

*/
		/*ルビー先生過払い金精算
		$pdf->SetFontSize(11);
		$x = 17;
		$pdf->Text($x, $y, "2021/10月2022/6月分殺西洋Online");

		$x += 84.5;
		$pdf->Text($x, $y, "2022-10-31");
		$x += 27;
		$x += 6.5;
		$x += 11.5;
		$pdf->Text($x, $y, 1);
		$x += 5.5;
		//$wk_kng = -60321;
		$wk_kng = -63142;
		$pdf->SetXY($x, $y);
		$pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
		$total += $wk_kng;
		$x += 26;

		$pdf->SetXY($x, $y);
		$pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
		$y += $yadd;
		$line_count++;
		*/
		//桜先生過払い金精算
		/*
		$pdf->SetFontSize(11);
		$x = 17;
		$pdf->Text($x, $y, "2022/2月分相殺数秘Online");

		$x += 84.5;
		$pdf->Text($x, $y, "2022-10-31");
		$x += 27;
		$x += 6.5;
		$x += 11.5;
		$pdf->Text($x, $y, 1);
		$x += 5.5;
		//$wk_kng = -2000;
		$wk_kng = -1796;
		$pdf->SetXY($x, $y);
		$pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
		$total += $wk_kng;
		$x += 26;

		$pdf->SetXY($x, $y);
		$pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
		$y += $yadd;
		$line_count++;
		*/
//源泉徴収払い戻しマリア
/*
		$pdf->SetFontSize(11);
		$x = 17;
		$pdf->Text($x, $y, "2023年06月分源泉徴収払い戻し");

		$x += 84.5;
		$pdf->Text($x, $y, "2023-06-30");
		$x += 27;
		$x += 6.5;
		$x += 11.5;
		$pdf->Text($x, $y, 1);
		$x += 5.5;
		//$wk_kng = -2000;
		$wk_kng = 17767;
		$pdf->SetXY($x, $y);
		$pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
		$total += $wk_kng;
		$x += 26;

		$pdf->SetXY($x, $y);
		$pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
		$y += $yadd;
		$line_count++;
*/
 if($mprivacy['Mprivacy']['target_flg'] == false) {
	//var_dump($mprivacy['Mprivacy'], "</br>");
    $pdf->SetFontSize(11);
    $x = 17;
    $pdf->Text($x, $y, "源泉徴収分");
    $x += 84.5;
    $pdf->Text($x, $y, "");
    $x += 27;
    $x += 6.5;
    $x += 11.5;
    $pdf->Text($x, $y, 1);
    $x += 5.5;
    $c_zai = $total * 0.1021 *(-1);
    $pdf->SetXY($x, $y);
    $pdf->Cell($X, $yadd, number_format($c_zai), 0, 0, 'R');
    $x += 26;

    $pdf->SetXY($x, $y);
    $pdf->Cell($X, $yadd, number_format($c_zai), 0, 0, 'R');
    $total += $c_zai;
}



//7月分sakura 
/*
        $y += $yadd;
        $line_count++;
		$pdf->SetFontSize(11);
		$x = 17;
		$pdf->Text($x, $y, "2023年07月誤返金(17767)");

		$x += 84.5;
		$pdf->Text($x, $y, "2023-08-31");
		$x += 27;
		$x += 6.5;
		$x += 11.5;
		$pdf->Text($x, $y, 1);
		$x += 5.5;
		$wk_kng = -17767;
		$x += 26;

		$pdf->SetXY($x, $y);
		$pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
		$total += $wk_kng;
		$y += $yadd;
		$line_count++;
	
		$pdf->SetFontSize(11);
		$x = 17;
		$pdf->Text($x, $y, '2023年07月過剰源泉徴収(5061-3247=1814)');
		
		$x += 84.5;
		$pdf->Text($x, $y, "2023-08-31");

		$x += 27;
		$x += 6.5;
		$x += 11.5;
		$pdf->Text($x, $y, 1);
		$x += 5.5;
		$wk_kng = 1814;
		$x += 26;
		$pdf->SetXY($x, $y);
		$pdf->Cell($X, $yadd, number_format($wk_kng), 0, 0, 'R');
		$total += $wk_kng;
*/

        //余白
        $y += $yadd;
        $line_count++;
        $x = 17;
        $pdf->SetXY($x, $y);
        $pdf->Cell(82, $yadd, "以　下　余　白", 0, 0, 'R');
        $pdf->Text($x, $y,  "以　下　余　白");

        $y = 256;
        	$pdf->Text(170,3.5, $date);

        $pdf->SetFontSize(12);
        $x = 50;
        $pdf->Text($x, $y, "税 込 金 額");
        $x += 31;
        $pdf->SetXY($x, $y);
        $pdf->Cell($X, $yadd, number_format($total), 0, 0, 'R');
        $x += 23;
        $pdf->Text($x, $y, "合計人数");
        $x += 26;
        $pdf->SetXY($x, $y);
        $pdf->Cell($X, $yadd, number_format($total_cnt), 0, 0, 'R');
        $x += 22;
        $pdf->Text($x, $y, "総　   額");
        $x += 27;

        $pdf->SetXY($x, $y);
        $pdf->Cell($X, $yadd, number_format($total), 0, 0, 'R');

        $y = 267;
        $pdf->SetFontSize(11);

        $pdf->Text(20, $y, "以下の口座にお振込ください。");
        $y = $y + 6.25;
        $pdf->Text(28, $y, "銀行名　  " . $mprivacy['Mbank']['name']);
        $pdf->Text(88, $y, "支店名  　" . $mprivacy['Mprivacy']['branchname']);
        $y = $y + 6.25;
        $pdf->Text(28, $y, "口座種別  " . $mprivacy['Mbaccounttype']['name']);
        $pdf->Text(88, $y, "口座番号  " . $mprivacy['Mprivacy']['account']);
        $y = $y + 6.25;
        $pdf->Text(28, $y, "口座名義  " . $mprivacy['Mprivacy']['accountname']);



        $pdf->Output($date . "請求書.pdf", 'FD');



        exit;
    }

    public function sankalist_pdf($id = null)
    {
        //var_dump($id);exit;
        $week = array("日", "月", "火", "水", "木", "金", "土");

        $modified = new DateTime();
        $date = $modified->format('Y年m月d日');


        $pdf = $this->Pdf->pdfstart();
        // ページ追加
        $pdf->AddPage();
        $pdf->setSourceFile('./files/SchoolList.pdf');
        $page = $pdf->importPage(1);
        $pdf->useTemplate($page);
        $pdf->SetFont('kozminproregular', '', 11);//日本語のフォントも使えるよーこれは明朝体
        $font_path = './ipag00303/ipag.ttf';

        if (file_exists($font_path)) {
            $font_name = $pdf->addTTFfont($font_path, 'TrueTypeUnicode');
            $pdf->SetFont($font_name, '', 10);
        }

        $pdf->Text(170, 3.5, $date);

        $pdf->SetFontSize(16);
        $pdf->Text(55, 8, "スケジュールの確定している講座の申し込み状況");


        $pdf->SetFontSize(12);
        $x = 14;
        $pdf->Text($x, 18, "講 座");
        $x += 23;
        $pdf->Text($x, 18, "開催日");
        $x += 18;
        $pdf->Text($x, 15.5, "曜");
        $pdf->Text($x, 20.5, "日");
        $x += 8;
        $pdf->Text($x, 15.5, "時");
        $pdf->Text($x, 20.5, "刻");
        $x += 9;
        $pdf->Text($x, 15.5, "回");
        $pdf->Text($x, 20.5, "数");
        $x += 6.5;
        $pdf->Text($x, 15.5, "人");
        $pdf->Text($x, 20.5, "数");
        $x += 7;
        $pdf->Text($x, 15.5, "定");
        $pdf->Text($x, 20.5, "員");
        $x += 47;
        $pdf->Text($x, 18, "参    加     者");

        $pdf->SetFontSize(10);

        //$pdf->SetFontSize(11);

        $y = 26;
        $add =5.04;
        $eschedules = $this->Eschedule->find('all', array(
                'conditions' => array('Eschedule.enddate >' => $modified->format('Y-m-d'), 'Mryoukin.mlecturer_id' => $id),
                'order' => 'Eschedule.date1'));

        foreach ($eschedules as $eschedule) {
            //$wk = $eschedule['Eschedule']['id'];
            $msubscriptions = $this->Eschedule->Msubscription->find('all', array(
                'conditions' => array('Msubscription.eschedule_id =' => $eschedule['Eschedule']['id'], 'Msubscription.mworkst_id >=' => 20,  'Msubscription.mworkst_id <' => 100 ),
                'order' => 'Msubscription.muser_id'));
            //var_dump($msubscriptions );
           // echo "</br>".  $eschedule['Eschedule']['id']. "</br>";

            for ($ix = 1; $eschedule['Mryoukin']['daytimes'] >= $ix; $ix++) {
                $sankacnt= 0;

                if ($ix ==  1) {
                    $pdf->Text(7, $y, $eschedule['Mryoukin']['rname']);
                }
                $x = 33;
                $pdf->Text($x, $y, $eschedule['Eschedule']['date'. $ix]);
                $x += 22;
                $w = date('w', strtotime($eschedule['Eschedule']['date'. $ix]));

                $pdf->Text($x, $y, $week[$w]);
                //時刻
                if (empty($eschedule['Eschedule']['jikan'])) {
                    $pdf->SetTextColor(0, 0, 0);

                    $optime = $eschedule['Mryoukin']['optime'];
                } else {
                    $optime = $eschedule['Eschedule']['jikan'];
                    $pdf->SetTextColor(255, 0, 0);
                }
                $optimes = explode("～", $optime);
                $x += 5.8;
                $pdf->Text($x, $y, $optimes[0]);
                //回数
                $pdf->SetTextColor(0, 0, 0);
                $x += 12.7;
                $pdf->Text($x, $y, $ix);
                //定員
                if ($eschedule['Eschedule']['capacity'] > 0) {
                    $capacity = $eschedule['Eschedule']['capacity'];
                } else {
                    $capacity = $eschedule['Mryoukin']['capacity'];
                }
                $x += 13;
                $pdf->Text($x, $y, $capacity);

                $wkname = null;
                foreach ($msubscriptions as $msubscription) {
                    if ($msubscription['Msubscription']['mstudentst'.$ix . '_id'] == 10) {
                        $sankacnt++;
                        if ($sankacnt == 5) {
                            $wk_sprit = '/';
                        } else {
                            $wk_sprit = ' ,';
                        }
                        $wkname .= $wk_sprit . $msubscription['Muser']['name'];
                        //$wkname .= ', ' . $msubscription['Muser']['name'] . '様';
                    }
                }


                $wk_name = explode("/", $wkname);
//var_dump($wk_name );
//echo "</br>";
                //参加人数
                $x -= 6;
                $pdf->Text($x, $y, $sankacnt);

                $x += 12;
                $pdf->Text($x, $y, substr($wk_name[0], 2));

                if (isset($wk_name[1])) {
                    $y += $add;
                    $line_count++;
                    $pdf->Text($x, $y, $wk_name[1]);
                }




                $y += $add;
            }
        }



        $pdf->Output($date . "講座の申し込み状況.pdf", 'FD');



        exit;
    }
    public function todaylist_pdf($kaisaidate = null)
    {
        $week = array("日", "月", "火", "水", "木", "金", "土");

        $modified = new DateTime();
        $date = $modified->format('Y年m月d日');


        $pdf = $this->Pdf->pdfstart('B5');
        // ページ追加
        $pdf->AddPage();
        $pdf->setSourceFile('./files/SchoolToday.pdf');
        $page = $pdf->importPage(1);
        $pdf->useTemplate($page);
        $pdf->SetFont('kozminproregular', 'B', 11);//日本語のフォントも使えるよーこれは明朝体
        $font_path = './ipag00303/ipag.ttf';

        if (file_exists($font_path)) {
            $font_name = $pdf->addTTFfont($font_path, 'TrueTypeUnicode');
            $pdf->SetFont($font_name, '', 10);
        }

        $pdf->Text(140, 3.5, $date);

        $pdf->SetFontSize(16);
        $pdf->Text(55, 7.5, "参加一覧（締め切り後送付）");


        $pdf->SetFontSize(12);
        $x = 24;
        $pdf->Text($x, 18, "開 催 日");
        $x += 22.5;
        $pdf->Text($x, 15.5, "曜");
        $pdf->Text($x, 20.5, "日");
        $x += 12.5;
        $pdf->Text($x, 18, "講    座");
        $x += 30;
        $pdf->Text($x, 18, "参加者情報（名前、誕生日、時刻、出生地）");

        $pdf->SetFont('kozminproregular', '', 11);//日本語のフォントも使えるよーこれは明朝体



        $sql = "SELECT Eeschedule.id, Eeschedule.date1, Eeschedule.date2, Eeschedule.date3,  ";
        $sql .= "Mryoukin.rname, Mryoukin.bdflg, Muser.name, Muser.birthday, Muser.birthtime, Muser.birthplace";
        $sql .= " FROM  eschedules as  Eeschedule,  mryoukins as Mryoukin, msubscriptions, musers as Muser";
        $sql .= " WHERE ((Eeschedule.date1 = '" . $kaisaidate . "' AND  (Mryoukin.daytimes = 1 OR msubscriptions.mstudentst1_id = 10))";
        $sql .= "  OR (Eeschedule.date2  = '" . $kaisaidate . "' AND  (Mryoukin.daytimes = 1 OR msubscriptions.mstudentst2_id = 10))";
        $sql .= "  OR (Eeschedule.date3 = '". $kaisaidate . "' AND  (Mryoukin.daytimes = 1 OR msubscriptions.mstudentst3_id = 10)))";
        $sql .= " AND Eeschedule.id = msubscriptions.eschedule_id";
        $sql .= " AND msubscriptions.mworkst_id >= 40 AND msubscriptions.mworkst_id <= 50";
        $sql .= " AND Muser.id = msubscriptions.muser_id";
        $sql .= " AND Mryoukin.id = msubscriptions.mryoukin_id";
        $sql .= " ORDER BY Eeschedule.id";
        //var_dump($sql);
        $data1 = $this->Eschedule->query($sql);

        $y = 19.6;
        $add = 8.9;

        $old = '';
        foreach ($data1 as $data) {
            if ($data['Eeschedule']['id'] != $old) {
                $y += $add;
                //var_dump( $data['Mryoukin']['rname']);
            $pdf->SetFont('kozminproregular', '', 11);//日本語のフォントも使えるよーこれは明朝体
                $x = 22.5;
                $pdf->Text($x, $y, $kaisaidate);
                $x += 24.5;
                $w = date('w', strtotime($kaisaidate));
                $pdf->Text($x, $y, $week[$w]);
                $x += 7.5;
                $pdf->Text($x, $y, $data['Mryoukin']['rname']);
                $old = $data['Eeschedule']['id'];
            } else {
            }
            $x = 61;

            $wkname = $data['Muser']['name'] . '様';
            //			if($data['Mryoukin']['bdflg']) {
            $wkname .= ", " . $data['Muser']['birthday'];
            if ($data['Muser']['birthplace'] != '問い合わせ') {
                $wkname .= ", " . $data['Muser']['birthtime'] .  ", " . $data['Muser']['birthplace'];
            }
            //			}

            $x += 24.5;
            $pdf->SetFont('kozminproregular', '', 10);//日本語のフォントも使えるよーこれは明朝体
            $pdf->Text($x, $y, $wkname);
            $y += $add;
        }

        $pdf->Output($date . "参加者情報.pdf", 'FD');

        exit;
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {
        if (!$this->Eschedule->exists($id)) {
            throw new NotFoundException(__('Invalid eschedule'));
        }
        $options = array('conditions' => array('Eschedule.' . $this->Eschedule->primaryKey => $id));
        $this->set('eschedule', $this->Eschedule->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add($id = null){
        if ($this->request->is('post') || $this->request->is('put')) {
            if(is_null($id))
                $this->Eschedule->create();
            else  $this->request->data['Eschedule']['id'] = $id;

            /* 最終日を設定する */
            for($ix = 9; $ix > 0; $ix--) {
                 $wk = 'date' . $ix;
                if (! empty($this->request->data['Eschedule']['date'.$ix]['year'])) {
                    $enddate = $this->request->data['Eschedule']['date'.$ix];
                    break;
                }
            }

            $this->request->data['Eschedule']['enddate'] = $enddate['year'] . '-' . $enddate['month'] . '-' . $enddate['day'];
            /* 締切を設定する */
            if(empty($this->request->data['Eschedule']['deadline']['year'])) {
                $date = date_create($this->request->data['Eschedule']['date1']['year'] . '-' . $this->request->data['Eschedule']['date1']['month'] . '-' . $this->request->data['Eschedule']['date1']['day']);
                date_sub($date, date_interval_create_from_date_string('3 days'));
                $this->request->data['Eschedule']['deadline'] = date_format($date, 'Y-m-d');
            }
//var_dump($this->request->data['Eschedule']);
//echo $error;
            if ($this->Eschedule->save($this->request->data,false)) {
                $this->Flash->success(__('成功'));
                return $this->redirect(array('action' => 'add'));
            } else {
                $this->Flash->error(__('失敗した・。・'));
                }
        }

        if(!is_null($id))
            $this->request->data = $this->Eschedule->read(null, $id);
        $mryoukins = $this->Eschedule->Mryoukin->find('list', array('conditions' => array('Mryoukin.pending_flg' => 0, 'Mryoukin.anytime_flg' => 0, 'Mryoukin.delflg' => 0)));
        $this->set(compact('mryoukins'));
        $this->set('id', $id);

        $this->index();
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        $this->Eschedule->id = $id;
        if (!$this->Eschedule->exists()) {
            throw new NotFoundException(__('Invalid eschedule'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Eschedule->delete()) {
            $this->Flash->success(__('The eschedule has been deleted.'));
        } else {
            $this->Flash->error(__('The eschedule could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'add'));
    }
}

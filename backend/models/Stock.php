<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;


class Stock extends \yii\db\ActiveRecord
{

    public static function GetMarketStatus(){

        $url = 'http://money18.on.cc/js/daily/market_status/MAIN_s.js';
        $content = file_get_contents($url);

        $string = trim(str_replace("'", "",str_replace("M18.s_MAIN", '',str_replace(';', '', str_replace('=', '', $content)))));

        if($string != 'DC'){ //If DC(DAY CLOSE), market closed
            $status = true;
        } else { //ELSE, market open
            $status = false;
        }

        return $status;
    }

    public static function GetMarketHSI(){

        //HSI
        $hsi_js_url = 'http://money18.on.cc/js/real/index/HSI_r.js';
        $hsi_content = file_get_contents($hsi_js_url);

        $M18 = 'M18.r_HSI';
        $hsi_content = explode( $M18 , $hsi_content );

        $string = str_replace("'", '"',str_replace(';', '', str_replace('=', '', $hsi_content[1])));

        $hsi_content = explode( ',' , $string )[2];

        $hsi = trim(str_replace('value:','',str_replace('"', '', $hsi_content)));

        $hsi_change = trim(str_replace('difference:','',str_replace('"', '', explode( ',' , $string )[3])));
        //print_r($hsi_change);die();

        return ['hsi' => $hsi, 'hsi_change' => $hsi_change];
    }

    public static function GetMarketIndex(){

        //World Index
        $world_index_js = 'http://money18.on.cc/js/daily/worldidx/worldidx_b.js';
        $world_content = iconv("big5","UTF-8//IGNORE",file_get_contents($world_index_js));

        $world_content = explode('M18.b_worldidx',$world_content)[1];
        $world_sting = str_replace(';','',str_replace('=', '', $world_content));

        $world_sting = json_decode($world_sting);

        $world_index = $world_sting->member;

        //道瓊斯工業指數
        $INDU = $world_index[0]->Point;
        $INDU_change = $world_index[0]->Difference;
        $world_index['INDU'] = $INDU;
        $world_index['INDU_change'] = $INDU_change;

        //標準普爾指數
        $SPX = $world_index[1]->Point;
        $SPX_change = $world_index[1]->Difference;
        $world_index['SPX'] = $SPX;
        $world_index['SPX_change'] = $SPX_change;

        //納斯達克指數NASDAQ
        $NASDAQ = $world_index[2]->Point;
        $NASDAQ_change = $world_index[2]->Difference;
        $world_index['NASDAQ'] = $NASDAQ;
        $world_index['NASDAQ_change'] = $NASDAQ_change;

        //英國富時指數
        $UKX = $world_index[3]->Point;
        $UKX_change = $world_index[3]->Difference;
        $world_index['UKX'] = $UKX;
        $world_index['UKX_change'] = $UKX_change;

        //上海綜合指數
        $SHCOMP = $world_index[4]->Point;
        $SHCOMP_change = $world_index[4]->Difference;
        $world_index['SHCOMP'] = $SHCOMP;
        $world_index['SHCOMP_change'] = $SHCOMP_change;

        //深圳成分指數
        $SIOM = $world_index[5]->Point;
        $SIOM_change = $world_index[5]->Difference;
        $world_index['SIOM'] = $SIOM;
        $world_index['SIOM_change'] = $SIOM_change;

        //日經平均指數
        $NKY = $world_index[6]->Point;
        $NKY_change = $world_index[6]->Difference;
        $world_index['NKY'] = $NKY;
        $world_index['NKY_change'] = $NKY_change;

        //滬深300指數
        $SHSZ = $world_index[7]->Point;
        $SHSZ_change = $world_index[7]->Difference;
        $world_index['SHSZ'] = $SHSZ;
        $world_index['SHSZ_change'] = $SHSZ_change;

        //海峽時報指數
        $fsSTI = $world_index[8]->Point;
        $fsSTI_change = $world_index[8]->Difference;
        $world_index['fsSTI'] = $fsSTI;
        $world_index['fsSTI_change'] = $fsSTI_change;

        //吉隆坡綜合指數
        $KLCI = $world_index[9]->Point;
        $KLCI_change = $world_index[9]->Difference;
        $world_index['KLCI'] = $KLCI;
        $world_index['KLCI_change'] = $KLCI_change;

        //曼谷SET指數
        $SET = $world_index[10]->Point;
        $SET_change = $world_index[10]->Difference;
        $world_index['SET'] = $SET;
        $world_index['SET_change'] = $SET_change;

        //台灣加權指數
        $TWSE = $world_index[11]->Point;
        $TWSE_change = $world_index[11]->Difference;
        $world_index['TWSE'] = $TWSE;
        $world_index['TWSE_change'] = $TWSE_change;

        //悉尼普通股指數
        $AS30 = $world_index[12]->Point;
        $AS30_change = $world_index[12]->Difference;
        $world_index['AS30'] = $AS30;
        $world_index['AS30_change'] = $AS30_change;

        //首爾綜合指數
        $KOSPI = $world_index[13]->Point;
        $KOSPI_change = $world_index[13]->Difference;
        $world_index['KOSPI'] = $KOSPI;
        $world_index['KOSPI_change'] = $KOSPI_change;

        //孟買證交所指數
        $SENSEX = $world_index[14]->Point;
        $SENSEX_change = $world_index[14]->Difference;
        $world_index['SENSEX'] = $SENSEX;
        $world_index['SENSEX_change'] = $SENSEX_change;

        return $world_index;
    }


    public static function GetStockPrice($stock){

        $url = 'http://money18.on.cc/js/real/hk/quote/'.$stock.'_r.js';
        $content = file_get_contents($url);

        if (strpos($content, 'http-equiv') !== false) {
            //no stock found
            $price = 0;
        } else {

            $M18 = 'M18.r_' . $stock;
            $first_step = explode($M18, $content);

            $string = str_replace("'", '"', str_replace(';', '', str_replace('=', '', $first_step[1])));

            $json = json_decode($string);

            $price = $json->np;

        }

        return $price;
    }

    public static function GetStockInfo($stock){

        $url = 'http://money18.on.cc/js/daily/hk/quote/'.$stock.'_d.js';
        $content = iconv("big5","UTF-8//IGNORE",file_get_contents($url));

        if (strpos($content, 'http-equiv') !== false) {
            //no stock found
            $info = null;
        } else {

            $M18 = 'M18.d_' . $stock;
            $first_step = explode($M18, $content);

            $string = str_replace(';', '', str_replace('=', '', $first_step[1]));

            $json = json_decode($string);

            $info['name'] = $json->nameChi;
            $info['lotsize'] = $json->lotSize;
            $info['uaCode'] = $json->uaCode;
        }

        return $info;
    }

    public static function GetStockTypeList(){

        $stock_type_list =
            ['0' => '正股', '1' => '衍生工具'];

        return $stock_type_list;
    }

}

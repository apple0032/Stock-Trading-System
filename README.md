
Stock-Trading-System
-------------------
<p align="center">
  <img src="https://image.ibb.co/jxp29c/11.png">
</p>

The Stock System is a Yii2 based simulation of Hong Kong stock trading system. 
It takes the real price of stocks in Hong Kong market, simulate the trading
action, manage the storage, and also analyze the information that the system collected.
My purpose of doing this project is to increase the profit of my investment behaviour. 

Update
-------
Version 1.1 (2018-07-22)
- 各項錯誤/代碼修正
- 現在股價等於或低於0.01時,股價將被設定為0(為處理衍生工具被殺的BUG)
- 現在可於"存倉列表"中,所持股份的右方直接點擊沽出股份,然後直接被帶到沽出頁面進行操作
- 現在於沽出頁面輸入股份號碼之後,系統會從數據庫中提取用戶當前持股量,並預設沽出數目為全部所持股份
- 衍生工具被系統發現為"被殺"(其參考市值及市場價為0)時, 則不論存倉列表或分析圖表的市值均為0 (存倉列表不會移除市值為0的股份)
- 主頁各參數修正


UI && Function
-------
* Simulation of trading HK stock
<img src="https://image.ibb.co/i6wSaH/trade.png">

* Check the details & history of your tradings
<img src="https://image.ibb.co/iQOZNx/history.png">

* Analyze your investment portfolio through chart & table
<img src="https://image.ibb.co/mMPDFH/storage.png">

* Full Support of searching <b>REAL TIME PRICE</b> of HK stock and international market
index.
<img src="https://image.ibb.co/kzkQUc/market.png">
<img src="https://image.ibb.co/gcG4Nx/search.png">

<br>

DEMO
-----
<a href="http://stonesgaming.com">http://stonesgaming.com</a>
* username : demo / password : demo

<br>
<b>Note: All of the investment actions in this web pages are only under simulation.</b>

<br><br>

Resources
-----------
### Backend UI
* <a href="https://adminlte.io/">AdminLTE Control Panel</a>

### RSS resources

* http://www.hkej.com/rss/onlinenews.xml
* https://hket.com/rss/finance (include image)

### RSS Feed widget

* https://feed.mikle.com/

### RSS Source code

```
<--script type="text/javascript" src="https://feed.mikle.com/js/fw-loader.js" data-fw-param="70012/"><--/script>
```
```
<--iframe src="https://feed.mikle.com/widget/v2/70012/" height="947px" width="100%" class="fw-iframe" scrolling="no" frameborder="0"><--/iframe>
```

### Logo Design

* Logo made with <a href="https://www.designevo.com/" title="Free Online Logo Maker">DesignEvo</a>
* source and copyright file under @project/frontend/web/images/

### HK Stock Policy

* <a href="http://www.hkex.com.hk/Services/Trading-hours-and-Severe-Weather-Arrangements/Trading-Hours/Securities-Market?sc_lang=zh-HK">HKEX</a>

### HK Market Status
* <a href="http://www.hkex.com.hk/services/trading-hours-and-severe-weather-arrangements/trading-hours/securities-market?sc_lang=zh-hk">System Trading Status</a>


### Stock trading explanation
* <a href="http://www.htisec.com/sites/all/themes/hitong/files/Remarks_of_Average_Buying_Unit_Price____Average_Unit_Price_-_TC.pdf">http://www.htisec.com/sites/all/themes/hitong/files/Remarks_of_Average_Buying_Unit_Price____Average_Unit_Price_-_TC.pdf</a>

### Charts/Graphs API
* <a href="https://canvasjs.com/php-charts/">CanvasJS</a>


### Stock Information API

* Sorry! Based on copyright issues, I cannot provide this file in github.




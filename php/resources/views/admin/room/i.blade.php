<!--pages/indexInfo/index.wxml-->
<import src="../../components/shopCars/index.wxml" />
<import src="../../wxParse/wxParse.wxml"/>
<view class='wrap'>
    <view class='brief'>
        <view class='lunbo'>
            <swiper indicator-dots="{{indicatorDots}}" indicator-color="#ffb66d" indicator-active-color="#ff8b90" autoplay="{{autoplay}}" interval="{{interval}}" duration="{{duration}}" bindchange='quIndex' style="height:800rpx;">
                <block wx:for="{{imgUrls}}" wx:key="key" wx:index="Lindex">
                    <swiper-item>
                        <image src="{{item}}" class="banner" bindtap='bannerto' data-shuju='{{item}}' mode='aspectFit' />
                    </swiper-item>
                </block>
            </swiper>
            <view class='lunboNum' wx:if='{{imgsZong>1}}'>{{quIndexs}}/{{imgsZong}}</view>
        </view>
        <view class='describe-tit'>{{dataBox.goods_name}}</view>
        <view class='describe-cont'>{{dataBox.goods_introduction}}</view>
    </view>
    <view class='describe'>

        <view class='describe-btn {{tag}}'>
            <view class='describe-btn-price'>¥{{dataBox.present_price}}<view class='describe-btn-price-old'>¥{{dataBox.original_price}}</view></view>
            <view class='describe-btn-right'>
                <view wx:if="{{en == false ? true : false}}" class='describeBtn unique contact'>
                    <contact-button size="22" class='pos'></contact-button>
                    <image src="../../images/zixunindex.png" bindtap='weixinPhone'></image>
                </view>
                <view wx:if="{{en == false ? true : false}}" class='describeBtn unique' bindtap='phone'><image src="../../images/kefuindex.png"></image></view>
                <view wx:if="{{en == false ? false : true}}" class='describeBtn unique contact'>
                    <contact-button size="22" class='pos'></contact-button>
                    <image src="../../images/zixunindexen.png" bindtap='weixinPhone'></image>
                </view>
                <view wx:if="{{en == false ? false : true}}" class='describeBtn unique' bindtap='phone'><image src="../../images/kefuindexen.png"></image></view>
            <!-- <view wx:if="{{!gouStyle}}" class='addShopcar' bindtap='addShop' id='{{dataBox.goods_id}}'>+{{lang.cart}}</view> -->
                <view class='unique' wx:if="{{en == false ? true : false}}">
                    <image wx:if="{{!gouStyle && inventory_status===1}}" class='addShopcar' bindtap='addShop' id='{{dataBox.goods_id}}' src='../../images/addShopicon.png'></image>
                    <image wx:if="{{gouStyle && inventory_status===1}}" bindtap='infoDele' id='{{dataBox.goods_id}}' class='addShopcars' src='../../images/deleShioicon.png'></image>
                    <image wx:if="{{inventory_status===2}}" bindtap='infoDele' id='{{dataBox.goods_id}}' class='addShopcars' src='../../images/yishouqing.png'></image>
                </view>
                <view class='unique' wx:if="{{en == false ? false : true}}">
                    <image wx:if="{{!gouStyle && inventory_status===1}}" class='addShopcar' bindtap='addShop' id='{{dataBox.goods_id}}' src='../../images/addShopiconen.png'></image>
                    <image wx:if="{{gouStyle && inventory_status===1}}" bindtap='infoDele' id='{{dataBox.goods_id}}' class='addShopcars' src='../../images/deleShioiconen.png'></image>
                    <image wx:if="{{inventory_status===2}}" bindtap='infoDele' id='{{dataBox.goods_id}}' class='addShopcars' src='../../images/yishouqingen.png'></image>
                </view>
            </view>
        </view>
    </view>
    <view class='photoInfo'>
        <view class='photoInfo-titline'>
            <image src="../../images/yuan.png" mode="widthFix"></image>
        </view>

        <view class='photoInfo-tit'>
            {{goodTit}}
        </view>
        <view class='detail_trans'>
            <template is="wxParse" data="{{wxParseData:article.nodes}}"/>
        </view>
    <!-- <view class='photoInfoimg'>{{dataBox.goods_description}} </view> -->
    </view>

    <!--返回顶部开始  -->
    <image class="backtop {{show ? 'backtopNone':''}}" src='../../images/fanhuidingbu.png' bindtap='goTop' wx:if="{{show}}"></image>
    <!--返回顶部end  -->

</view>
<!--底部购物车组件开始  -->
<!-- <template is="shopInfoCars" data="{{gouNum:gouNum,shopShow:shopShow,shopCars:shopCars,lang: lang,shopPrice:shopPrice,shopYoumone:shopYoumone}}" /> -->
<!--底部购物车组件end  -->

<view class='footerBuy'>
    <view class='footerBuy-left' bindtap='goucar'>
        <view class='footerBuy-left-buyicon unique'>
            <image src='../../images/gouwuche.png'></image>
            <view wx:if="{{gouNum!=0}}" class='footerBuy-left-buyicon-num'>{{gouNum}}</view>
        </view>
    <!-- <view wx:if="{{gouNum!=0}}" class='footerBuy-left-price'>
      ¥{{shopPrice}}
            <view class='footerBuy-left-pricedown'>{{lang.discount}}{{shopYoumone}}{{lang.unite}}</view>
    </view> -->
        <view wx:if="{{gouNum==0}}" class='footerBuy-null'>
            {{lang.add}}
        </view>
    </view>
    <view bindtap='goPay'  wx:if="{{gouNum!=0}}" class='footerBuy-right'>
        <image src='../../images/jiesuandi.png' mode='widthFix'></image>
        {{lang.go}}
    </view>
</view>
<!--底部购物栏end  -->








<!--购物车弹出框开始  -->
<view bindtap='close' wx:if="{{shopShow && gouNum!=0}}" class='mark'></view>
<view wx:if="{{gouNum!=0}}" class='shopCar {{shopShow?"shopCarOn":""}}'>
    <!--商品头部  -->
    <view class='shopCar-tit'>
        <view class='shopCar-tit-left'>{{lang.select}}</view>
        <view class='shopCar-tit-right'>
            <view class='shopCar-tit-right-kong' bindtap='clear'>
                <image class='shopCar-tit-right-kongicon' src='../../images/kong.png'></image>
                <view class='shopCar-tit-right-text'>{{lang.clear}}</view>
            </view>
            <image bindtap='close' class='closeBtn' src='../../images/close.png'></image>
        </view>
    </view>
    <!--商品头部end  -->

    <!--购物车列表  -->
    <view class='shoplistBox'>
        <view class='shopCar-list' wx:for="{{shopCars}}" wx:for-item="shopCarsCont" wx:key="index">
            <view class='shopCar-list-name'>{{shopCarsCont.goods_name}}</view>
            <view class='shopCar-list-jiage'>
                <view class='zhekou'>¥{{shopCarsCont.original_price}}</view>
                <view class='price'>¥{{shopCarsCont.present_price}}</view>
                <image bindtap='deleShopcar' data-gid='{{shopCarsCont.goods_id}}' class='shanchu' data-shiJin='{{shopCarsCont.present_price}}' data-youJin='{{shopCarsCont.original_price}}' src='../../images/shanchu.png'></image>
            </view>
        </view>
    </view>

    <!--购物车列表end  -->
</view>


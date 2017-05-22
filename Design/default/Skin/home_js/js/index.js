/**
 * Created by Administrator on 2017/5/5.
 */
/**
 * Created by Administrator on 2017/4/26.
 */
var picNum=$(".slide-ul").children("li").length;/*获取图片数量*/
var liWidth=6.4+'rem';/*这里单个图片宽*/
var liHeight=2.78+'rem'; /*这里单个图片高*/
var firstPos=0;/*初始定位*/
var slideInterval;/*定时器名字*/
$(document).ready(function(){
    $(".slide").css("width",liWidth); /*这里是最外层div宽*/
    $(".slide-list-ul").css("width",picNum*0.4+"rem"); /*这里是快速跳转条的宽*/
    $(".slide-ul").css("width",(parseInt(liWidth)*picNum+0.4*picNum)+"rem"); /*这里ul条总宽*/
    $(".slide-ul").children("li").css("width",liWidth); /*这里是单个li宽*/
    $(".slide").css("height",liHeight); /*这里是最外层div高*/
    $(".slide-list-ul").css("height","0.1rem"); /*这里是快速跳转条的高*/
    $(".slide-ul").css("height",liHeight); /*这里ul条高*/
    $(".slide-ul").children("li").css("height",liHeight);  /*这里是单个li高*/
    for(var i=0;i<picNum;i++){   /*动态生成下边的快速跳转list*/
        $(".slide-list-ul").append(
            "<li class='slide-list-li"+i+"'>"+  //生成li标签
            "</li>"
        )
    }
    for(var i=0;i<picNum;i++){   /*动态生成下边的快速跳转list*/
        $(".slide-list-li"+i).removeClass("bg-white");
    }
    $(".slide-list-li"+parseInt(firstPos/(parseInt(liWidth)+0.4))).addClass("bg-white");

});
function slideTimer() {  //定时器执行函数
    slideInterval=              //定时器id
        setInterval(function(){
            if(firstPos<(picNum-1)*parseInt(liWidth)){ /*当定位值小于总的ul宽度时*/
                $(".slide-ul").css("left",-(firstPos+=parseInt(liWidth)+0.4)+"rem"); /*改变ul的定位值（每次向右移动一个图片宽度）*/
            }else {
                firstPos=-parseInt(liWidth); /*当定位值超出ul宽度时重置定位值为负的一个图片宽度*/
                $(".slide-ul").css("left",-(firstPos+=parseInt(liWidth))+"rem");/*改变ul的定位值向右移动一个图片宽度*/
            }
            for(var i=0;i<picNum;i++){   /*动态生成下边的快速跳转list*/
                $(".slide-list-li"+i).removeClass("bg-white");
            }
            $(".slide-list-li"+parseInt(firstPos/(parseInt(liWidth)+0.4))).addClass("bg-white");
        },2000);/*换图时间*/
}
slideTimer();


for(var i=0;i<picNum;i++){
    (function (i) {
        $(document).on("click",".slide-list-li"+i, function() {  //动态绑定事件
            $(".slide-ul").css("left",-((parseInt(liWidth)+0.4)*i)+"rem"); //移动到当前点击位置的图片
            for(var j=0;j<picNum;j++){   /*动态生成下边的快速跳转list*/
                $(".slide-list-li"+j).removeClass("bg-white");
            }
            $(".slide-list-li"+i).addClass("bg-white");
            firstPos=((parseInt(liWidth)+0.4)*i); //修改全局变量
            window.clearInterval(slideInterval); //每次点击后清除该定时器，避免刚手动切换图片就又自动切换。
            slideTimer(); //接着重启定时器。
        });
    })(i)
}




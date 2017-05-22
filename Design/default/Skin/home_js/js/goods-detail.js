/**
 * Created by Administrator on 2017/5/5.
 */
var  num=$(".modal-num").text();
$(".convert").click(function () {
    $(".modal-main-bg").css("display","block");
    $(".modal-main-bg").css("top","0")
});
$(".main-cancel2").click(function () {
    $(".modal-success-bg,.modal-main-bg,.modal-false-bg").css("display","none");
});
$(".reduce").click(function () {
    if(num>1){
        $(".modal-num").text(--num);
    }
});
$(".add").click(function () {
    if(true){ //这里改条件
        $(".modal-num").text(++num);
    }
});


//点击兑换
$(".main-done").click(function () {
    var thisElement = $(this);
    var goods_inte = $(".inte").text();
    var my_inte = $('.my_inte').text();
    var goods_num = $(".number").text();//兑换商品数量
    var url = thisElement.attr('url');
    var goods_id = $("#goods_id").attr('goods_id');//商品编号
    var goods_title = $('.goods_title').text();
    if(goods_inte && my_inte && goods_num && url && goods_id && goods_title)
    {
        $.ajax({
            url:url,
            type:'post',
            data:{goods_num:goods_num,goods_id:goods_id,goods_inte:goods_inte,goods_title:goods_title},
            success:function(response)
            {
                if(response == 0 || response == 3)
                {
                    alert('兑换失败！');
                    $(".modal-main-bg").hide();
                }else if(response == 1)
                {
                    $(".modal-main-bg").css("display","none");
                    $(".modal-false-bg").css("display","block");
                }else if(response == 2)
                {
                    $(".modal-main-bg").css("display","none");
                    $(".modal-success-bg").css("display","block");
                }
            }
        });
    }
});

$(".false-cancel,.false-done").click(function () {
    $(".modal-false-bg").css("display","none")
});

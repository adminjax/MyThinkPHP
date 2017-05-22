/**
 * Created by Administrator on 2017/1/4.
 */
$(document).ready(function()
{
    //初始化模态框
    $(".rule_goods").bind('click',function()
    {
        $("#modal").iziModal({
            closeOnEscape:true,
            overlayClose:false,
            width:300
        });
        $(".rule_num").css('display','block');
    });

    //打开模态框
    $(document).on('click', '.trigger', function (event) {
        event.preventDefault();
        $('#modal').iziModal('open');
    });

    //关闭模态框
    $(document).on('click', '.cancel1,.rule_cancel', function (event) {
        event.preventDefault();
        $('#modal').iziModal('close');
        window.location.reload();
    });

    //请求兑换
    $(document).on('click', '.rule1', function (event) {
        event.preventDefault();
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
                        return false;
                    }else if(response == 1)
                    {
                        $(".rule_num").children().first().html('');
                        $('.btn_number').html('积分不足，无法兑换');
                        $('.rule').html('确定');
                        $('.rule').removeClass('rule1');
                        $('.rule').addClass('rule_cancel');
                    }else if(response == 2)
                    {
                        $(".rule_num").children().first().html('');
                        $('.btn_number').html('兑换成功');
                        $('.rule').html('确认');
                        $('.cancel').html('<a href="#">积分详情</a>');
                        $('.rule').removeClass('rule1');
                        $('.rule').addClass('cancel1');
                        $('.cancel').removeClass('cancel1');
                    }
                }
            });
        }
    });

    //减少商品数量
    $(document).on('click', '.reduction', function (event) {
        event.preventDefault();
        var goods_num = $(this).next('span').text();
        goods_num = parseInt(goods_num) - 1;
        if(goods_num <= 1) goods_num = 1;
        $(this).next('span').text(goods_num)
    });

    //增加商品数量
    $(document).on('click', '.add', function (event) {
        event.preventDefault();
        var goods_num = $(this).prev('span').text();
        goods_num = parseInt(goods_num) + 1;
        $(this).prev('span').text(goods_num)
    });
});
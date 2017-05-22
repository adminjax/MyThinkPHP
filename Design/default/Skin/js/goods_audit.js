/**
 * Created by Administrator on 2016/12/27.
 */
$(document).ready(function()
{
    //获取排版信息
    $(".box-1 .data-table .goods_sort").unbind('click').bind('click',function()
    {
        var thisElement = $(this).find('.goods_s');
        var sort_id = thisElement.attr('sort_id');
        var url = $(this).parent().parent().attr('url');
        if(sort_id){
            $.ajax({
                url:url,
                type:'post',
                data:{sort_id:sort_id},
                success:function(response){
                    $(".box-2>.slider>ul").html(response);
                }
            });
        }
    });


    //商品审核通过
    $(".adopt").unbind('click').bind('click', pass);
    //商品审核拒绝
    $(".refuse").unbind('click').bind('click', pass);
    //排版审核通过
    $(".pass").unbind('click').bind('click', typeSetting);
    //排版审核拒绝
    $(".refuse_p").unbind('click').bind('click', typeSetting);

    //商品排版审核
    function typeSetting()
    {
        var input = $("tr.click input[type=checkbox]:checked");
        if(input[0]) {
            var dd = '';
            input.each(function () {
                dd += $(this).attr('sort_id') + ',';
            });
            var type = $(this).attr('class');
            var url = $(this).parent().attr('url');
            $.ajax({
                url: url,
                type: 'post',
                data: {sort_id: dd, type: type},
                success: function (response) {console.log(response)
                    if (response == 1) {
                        alert('审核成功！');
                        window.location.reload();
                        return false;
                    } else {
                        alert('审核失败！');
                        return false;
                    }
                }
            });
        }else{
            alert('请选择项目！');
        }
    }

    //商品审核
    function pass()
    {
        var input = $("tr.click input[type=checkbox]:checked");
        if(input[0])
        {
            var dd = '';
            input.each(function()
            {
                var thisElement = $(this);
                dd+=thisElement.attr('g_id')+',';
                /*dd+=thisElement.attr('g_id')+':'+thisElement.attr('active')+',';*/
            });
            var audit_ = $(this).attr('class');
            var par_url = input.parent().parent().parent().parent().attr('url');

            $.ajax({
                url:par_url,
                type:'post',
                data:{g_id:dd,type:audit_},
                success:function(response)
                {console.log(response);
                    if(response == 1 && audit_)
                    {
                        alert('审核成功！');
                        window.location.reload();
                        return false;
                    }else
                    {
                        alert('审核失败！');
                        return false;
                    }
                }
            });
        }else{
            alert('请选择项目！');
        }
    }
});
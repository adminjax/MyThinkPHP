
$(document).ready(function()
{

    //视频预览
    $(".video_click").unbind('click').bind('click',video_preview);
    function video_preview()
    {
        var thisElement = $(this).find('.title');
        var video_url = thisElement.children().attr('video_url');
        var video_pic = thisElement.children().attr('video_pic');
        var pic = '';
        if(!video_pic){
            pic = '../../../../Design/default/Skin/images/logo.png';
        }else{
            pic = '1.jpg';
        }
        var html = '<video src="'+video_url+'" poster="'+pic+'" controls preload style="background-size:cover;background-repeat: no-repeat;background-position: 50% 50%;">' +
            '<div>你的浏览器不支持该视频</div>' +
            '</video>'+'<input type="hidden" value="'+video_pic+'"/>';
        $(".video_preview").html(html);
        var url = $(".video_preview input").val();
        $(".video_preview video").css("background-image","url("+url+")");
        $(".video_preview video").css("border","1px solid gainsboro");
    }
    //删除视频
    $(".delete").unbind('click').bind('click',video_delete);
    function video_delete()
    {
        var thisElement = $(this);
        var vid = thisElement.next().attr('vid');//视频ID
        var url = thisElement.next().attr('url');
        if(vid && confirm('你确定删除吗？'))
        {
            $.ajax({
                url : url,
                type : 'post',
                data : {vid:vid,type:3},
                success : function(response){//console.log(response);
                    if(response==1)
                    {
                        alert('删除成功！');
                        thisElement.parent().parent().remove();
                        return false;
                    }else{
                        alert('删除失败！');
                    }
                }
        });
        }
    }

    //视频审核
    $(".audit").unbind('click').bind('click',video_audit);
    function video_audit()
    {
        var thisElement = $(this);
        var vid = thisElement.next().attr('vid');//视频ID
        var url = thisElement.next().attr('url');
        if(vid && url)
        {
            $.ajax({
                url : url,
                type : 'post',
                data : {vid:vid,type:4},
                success : function(response)
                {console.log(response);
                    if(response==1)
                    {
                        alert('审核成功！');
                        thisElement.parent().parent().remove();
                        window.location.reload();
                        return false;
                    }else{
                        alert('审核失败！');
                    }
                }
            });
        }
    }
    //搜索视频
    $(".search").unbind('click').bind('click',video_search);
    function video_search()
    {
        var thisElement = $(this);
        var text = thisElement.prev().val();
        if(text == ''){
            window.location.href = $(this).attr('url');
            return false;
        }
        else{
            //提交表单
            jQuery(".search").click(function(){
                jQuery(".search_text").submit();
            });
        }
    }
});

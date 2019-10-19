$(function(){
    $('.item_jminfo .tabs-tit li').click(function(){
        var index = $(this).index()
        if(index != 0){
            sessionStorage.setItem('ProjectDetailsSecondaryNavigationIndex91',index)
        }
    })
})
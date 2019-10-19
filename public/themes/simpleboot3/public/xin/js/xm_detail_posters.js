$(function(){
    $('.nav-xm-info .tabs li').click(function(){
        var index = $(this).index()
        if(index != 0){
            sessionStorage.setItem('ProjectDetailsSecondaryNavigationIndex91',index)
        }
    })
})
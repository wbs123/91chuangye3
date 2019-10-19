$(function(){
    if (sessionStorage.getItem('ProjectDetailsSecondaryNavigationIndex91')) {
        var ProjectDetailsSecondaryNavigationIndex91;
        ProjectDetailsSecondaryNavigationIndex91 = sessionStorage.getItem('ProjectDetailsSecondaryNavigationIndex91')
        console.log(ProjectDetailsSecondaryNavigationIndex91)
        $('.nav-xm-info .tabs li').eq(ProjectDetailsSecondaryNavigationIndex91).find('a').click()
        sessionStorage.removeItem('ProjectDetailsSecondaryNavigationIndex91')
    }
})
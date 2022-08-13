const drawer = document.querySelector('.drawer');
const drawerCont = document.querySelector('.drawer-cont');
const drawerButton = document.getElementById('drawer-button');
const body = document.querySelector('body');

if(drawerButton)
{
    drawerButton.addEventListener("click",function(){
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0; 
        drawer.classList.toggle("open");
        drawerCont.classList.toggle('open');
        body.classList.toggle('open');
    });

    drawerCont.addEventListener("click",function(){
        drawer.classList.toggle("open");
        drawerCont.classList.toggle('open');
        body.classList.toggle('open');
    });
}
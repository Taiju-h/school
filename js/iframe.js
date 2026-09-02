$(function(){
var userAgent = window.navigator.userAgent.toLowerCase();

if (userAgent.indexOf('iphone') != -1) {
        document.write('');
}else if(userAgent.indexOf('Android') != -1){
        document.write('<script type="text/javascript" src="/jquery.browser.js"></script><script type="text/javascript" src="/jquery-iframe-auto-height.js"></script>');
}else{
        document.write('<script type="text/javascript" src="/jquery.browser.js"></script><script type="text/javascript" src="/jquery-iframe-auto-height.js"></script>');
};
});
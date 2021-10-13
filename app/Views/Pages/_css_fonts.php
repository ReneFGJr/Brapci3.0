<?php
$URL = $_SERVER['app.baseURL'];
echo "
<style>
@font-face {
    font-family: 'Handel Gothic';
    src: url('".$URL."/css/fonts/HandelGothic/handel_gothic.eot'); /* IE9*/
    src: url('".$URL."/css/fonts/HandelGothic/handel_gothic.eot#iefix') format('embedded-opentype'), /* IE6-IE8 */
    url('".$URL."/css/fonts/HandelGothic/handel_gothic.woff2') format('woff2'), /* chrome firefox */
    url('".$URL."/css/fonts/HandelGothic/handel_gothic.woff') format('woff'), /* chrome firefox */
    url('".$URL."/css/fonts/HandelGothic/handel_gothic.ttf') format('truetype'), /* chrome firefox opera Safari, Android, iOS 4.2+*/
    url('".$URL."/css/fonts/HandelGothic/handel_gothic.svg#Handel Gothic') format('svg'); /* iOS 4.1- */
    url('".$URL."/css/fonts/Roboto/Roboto-Thin.ttf') format('truetype'), /* chrome firefox opera Safari, Android, iOS 4.2+*/
}
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap');
</style>
";
?>
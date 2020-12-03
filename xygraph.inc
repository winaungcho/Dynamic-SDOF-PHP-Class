<?php

function plotGraph($imgw, $imgh, $DT,$u){
$n = count($u);
$peak = 0.0;
        for ($t = 0;$t < $n;$t++)
        {
            if (abs($u[$t]) > $peak) if (is_finite($u[$t])) $peak = abs($u[$t]);
        }
        $mgleft = $mgright = $mgtop = $mgbot = 20;
        $x0 = 10;
        $y0 = ($imgh - $mgtop - $mgbot) / 2;
        $scx = ($imgw-40)/($n*$DT);
        $scy = 160.0 / $peak / 2.0;
        $ngx = 10;
        $ngy = 4;
        
$sgx = ($imgw-$mgleft-$mgright-$x0)/$ngx;
$sgy = ($imgh-$mgtop-$mgbot)/$ngy;


$im = @imagecreatetruecolor($imgw, $imgh)
      or die("Cannot Initialize new GD image stream");
$text_color = imagecolorallocate($im, 200, 250, 250);
$line_color = imagecolorallocate($im, 233, 14, 91);
$grid_color = imagecolorallocate($im, 0, 233, 0);
for ($i=0; $i<=$ngx; $i++) {
    $x1 = $mgleft+$x0+$i*$sgx;
    imageline($im, $x1, $mgtop, $x1, $imgh-$mgbot, $grid_color);
    $tbuff = sprintf("%d", $i*$sgx/$scx);
    imagestring($im, 2, $x1, $imgh-$mgbot,  $tbuff, $text_color);
};
    $y1 = $y0+$mgtop;
    imageline($im, $x0+$mgleft, $y1, $imgw-$mgright, $y1, $grid_color);
    $tbuff = sprintf("%d", 0);
    imagestring($im, 2, $x0, $y1,  $tbuff, $text_color);
for ($i=1; $i <= $ngy/2; $i++) {
    $y1 = $y0+$mgtop+$i*$sgy;
    imageline($im, $x0+$mgleft, $y1, $imgw-$mgright, $y1, $grid_color);
    $tbuff = sprintf("-%d", $i*$sgy/$scy);
    imagestring($im, 2, $x0, $y1,  $tbuff, $text_color);
    $y1 = $y0+$mgtop-$i*$sgy;
    imageline($im, $x0+$mgleft, $y1, $imgw-$mgright, $y1, $grid_color);
    $tbuff = sprintf("%d", $i*$sgy/$scy);
    imagestring($im, 2, $x0, $y1,  $tbuff, $text_color);
}

$x1 = $x0+$mgleft; $y1 = $y0+$mgtop;
for ($i=0; $i<$n; $i++) {
    $x2 = $i*$DT*$scx+$x0+$mgleft; $y2 = -$u[$i]*$scy+$y0+$mgtop;
    if (($x2 > $x0) && ($x2 < ($imgw-$mgright)) && ($y1 > $mgtop) && ($y2 < ($imgh-$mgbot)))
        imageline($im, $x1, $y1, $x2, $y2, $line_color);
    $x1 = $x2; $y1 = $y2;
};

ob_start();
        imagepng($im);
        printf('<img src="data:image/png;base64,%s"/ width="$imgw">', base64_encode(ob_get_clean()));

}
function plotXYGraph($imgw, $imgh, $u){
$n = count($u);
$peak = 0.0;
        for ($t = 0;$t < $n;$t++)
        {
            if (abs($u[$t][1]) > $peak) if (is_finite($u[$t][1])) $peak = abs($u[$t][1]);
        }
        $mgleft = $mgright = $mgtop = $mgbot = 20;
        $x0 = 10;
        $y0 = ($imgh - $mgtop - $mgbot) / 2;
        $scx = ($imgw-40)/($u[$n-1][0]-$u[0][0]);
        $scy = 160.0 / $peak / 2.0;
        $ngx = 10;
        $ngy = 4;
        
$sgx = ($imgw-$mgleft-$mgright-$x0)/$ngx;
$sgy = ($imgh-$mgtop-$mgbot)/$ngy;


$im = @imagecreatetruecolor($imgw, $imgh)
      or die("Cannot Initialize new GD image stream");
$text_color = imagecolorallocate($im, 200, 250, 250);
$line_color = imagecolorallocate($im, 233, 14, 91);
$grid_color = imagecolorallocate($im, 0, 233, 0);
for ($i=0; $i<=$ngx; $i++) {
    $x1 = $mgleft+$x0+$i*$sgx;
    imageline($im, $x1, $mgtop, $x1, $imgh-$mgbot, $grid_color);
    $tbuff = sprintf("%d", $i*$sgx/$scx);
    imagestring($im, 2, $x1, $imgh-$mgbot,  $tbuff, $text_color);
};
    $y1 = $y0+$mgtop;
    imageline($im, $x0+$mgleft, $y1, $imgw-$mgright, $y1, $grid_color);
    $tbuff = sprintf("%d", 0);
    imagestring($im, 2, $x0, $y1,  $tbuff, $text_color);
for ($i=1; $i <= $ngy/2; $i++) {
    $y1 = $y0+$mgtop+$i*$sgy;
    imageline($im, $x0+$mgleft, $y1, $imgw-$mgright, $y1, $grid_color);
    $tbuff = sprintf("-%d", $i*$sgy/$scy);
    imagestring($im, 2, $x0, $y1,  $tbuff, $text_color);
    $y1 = $y0+$mgtop-$i*$sgy;
    imageline($im, $x0+$mgleft, $y1, $imgw-$mgright, $y1, $grid_color);
    $tbuff = sprintf("%d", $i*$sgy/$scy);
    imagestring($im, 2, $x0, $y1,  $tbuff, $text_color);
}

$x1 = $x0+$mgleft; $y1 = $y0+$mgtop;
for ($i=1; $i<$n; $i++) {
    $x2 = $u[$i][0]*$scx+$x0+$mgleft; $y2 = -$u[$i][1]*$scy+$y0+$mgtop;
    if (($x2 > $x0) && ($x2 < ($imgw-$mgright)) && ($y1 > $mgtop) && ($y2 < ($imgh-$mgbot)))
        imageline($im, $x1, $y1, $x2, $y2, $line_color);
    $x1 = $x2; $y1 = $y2;
};

ob_start();
        imagepng($im);
        printf('<img src="data:image/png;base64,%s"/ width="$imgw">', base64_encode(ob_get_clean()));

}
?>

<?php
$peak = 0;
$imgfile = "xydata2.txt";
$sdof = new SDOF();
$sdof->loadGAcc("kobens.txt");
//$peak = $sdof->calcRHA();
$peak = $sdof->calcSpectrum();
$sdof->saveSpectrum($peak);
$sdof->drawSpectrum($imgfile, $peak);

?>
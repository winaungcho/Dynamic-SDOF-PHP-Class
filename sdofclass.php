<?php
include("xygraph.inc");

class SDOF
{
    public $PI;
    public $Ag, $DT, $u, $Umax, $accgraphname;
    public $M, $K, $wd, $Td, $Cc, $C, $zi;
    function __construct()
    {
        $this->PI = 3.1415;
        $this->K = 10;
        $this->M = 10;
        $this->maxTd = 10;
        $this->Td = 0.4;
        $this->DT = 0.02;
        $this->g = 981; // assume mm
        $this->zi = 0.05;
        $this->nt = 0;
        $this->Umax = array();
        $this->u = array();
        $this->gaccloaded = FALSE;
        $this->setKM(5, 5);
    }
    
    function setKM($k, $m)
    {
        $this->K = $k;
        $this->M = $m;
        $this->calcKM();
    }
    function calcKM()
    {
        $this->wd = sqrt(4 * $this->K * $this->M - ($this->zi * $this->zi * 4 * $this->K * $this->M)) / (2 * $this->M);
        $this->Td = 2 * $this->PI / $this->wd;
        $this->Cc = 2.0 * sqrt($this->K * $this->M);
        $this->C = $this->zi * $this->Cc;
    }
    function calcTd()
    {
        $this->wd = 2.0 * $this->PI / $this->Td;
        $this->M = $this->K / ($this->wd * $this->wd) * (1.0 - $this->zi * $this->zi);
        $this->Cc = 2.0 * sqrt($this->K * $this->M);
        $this->C = $this->zi * $this->Cc;
    }
    function loadGAcc($name)
    {
        $this->accgraphname = $name;
        if (file_exists($name))
        {
            $lines = file($name);
            foreach ($lines as $line)
            {
                $line = trim($line);
                if (!empty($line))
                {
                    $pt = explode(",", $line);
                    $this->Ag[$i] = $pt;
                    $i++;
                }
            }
        }
        $this->nt = count($this->Ag);
        $this->gaccloaded = true;
        $this->DT = $this->Ag[2][0]-$this->Ag[1][0];
    }
    function calcRHA()
    {
        if (!$this->gaccloaded) return 0;
        //calcKM();
        
        $p = array();
        reset($this->Ag);
        foreach ($this->Ag as $val)
        {
            $p[] = $val[1] * $this->M;
        }
        $this->u[0] = 0;
        $this->u[1] = $p[0] - $b * $this->u[0];
        $n = count($this->Ag);
        $umax = 0.0;
        for ($i = 1;$i < $n;$i++)
        {
            $this->DT = $this->Ag[$i][0]-$this->Ag[$i-1][0];
            $k = $this->M / ($this->DT * $this->DT) + $this->C / (2.0 * $this->DT);
        	$a = $this->M / ($this->DT * $this->DT) - $this->C / (2.0 * $this->DT);
        	$b = $this->K - 2.0 * $this->M / ($this->DT * $this->DT);
        
            $this->u[$i + 1] = ($p[$i] - $a * $this->u[$i - 1] - $b * $this->u[$i]) / $k;
            if ($umax < abs($this->u[$i + 1])) $umax = abs($this->u[$i + 1]);
        };
        return $umax * $this->wd * $this->wd;
    }
    function calcSpectrum()
    {
        if (!$this->gaccloaded) return 0;
        for ($t = 0;$t < $this->nt;$t++)
        {
            $this->Td = $t*0.02+0.001;
            $this->calcTd();
            $this->Umax[$t][0] = $this->Td;
            $this->Umax[$t][1] = $this->calcRHA();
            if ($this->Td > $this->maxTd)
            	break;

        }
        $peak = 0.0;
        $n=count($this->Umax);
        for ($t = 0;$t < $n;$t++)
        {
            if ($this->Umax[$t][1] > $peak) if (is_finite($this->Umax[$t][1])) $peak = $this->Umax[$t][1];
        }
        return $peak;
    }
    function drawSpectrum($imgfile, $peak)
    {
    	
    	echo "<h1>Ground Acceleration</h1>";
        plotXYGraph(680, 200, $this->Ag);
        echo "<table>";
        echo "<tr><th>Data file</th><td>".$this->accgraphname."</td><td></td></tr>";
        echo "<tr><th>Unit</th><td>mm/s²</td><td></td></tr>";
        echo "</table>";
        echo "<h1>Response Spectrum of SDOF</h1>";
        plotXYGraph(680, 200, $this->Umax);
        echo "<table>";
        echo "<tr><th>Data file</th><td>".$this->accgraphname."</td><td></td></tr>";
        echo "<tr><th>Fictitious K</th><td>".$this->K."</td><td></td></tr>";
        echo "<tr><th>Fictitious M</th><td>".$this->M."</td><td></td></tr>";
        echo "<tr><th>ΔT</th><td>".$this->DT."</td><td></td></tr>";
        echo "<tr><th>Dumping ratio</th><td>".$this->zi."</td><td></td></tr>";
        echo "<tr><th>Peak Acc</th><td>".$peak."</td><td></td></tr>";
        echo "</table>";
        
        echo "<h1>Response History Analysis of SDOF</h1>";
        $this->setKM(50, 4);
    	$p=0; 
    	
    	$p = $this->calcRHA();
    	plotGraph(680, 200, $this->DT, $this->u);
        $n = count($this->u);
        echo "<table>";
        echo "<tr><th>Time steps</th><td>".$n."</td><td></td></tr>";
        echo "<tr><th>K</th><td>".$this->K."</td><td></td></tr>";
        echo "<tr><th>M</th><td>".$this->M."</td><td></td></tr>";
        echo "<tr><th>ΔT</th><td>".$this->DT."</td><td></td></tr>";
        echo "<tr><th>Dumping ratio</th><td>".$this->zi."</td><td></td></tr>";
        echo "<tr><th>Peak Acc</th><td>".$p."</td><td></td></tr>";
        echo "</table>";
    }
    function saveSpectrum($peak)
    {
        $imgfile = "xydata2.txt";
        $imgw = 680;
        $imgh = 200;
        $mgleft = $mgright = $mgtop = $mgbot = 20;
        $x0 = 10;
        $y0 = ($imgh - $mgtop - $mgbot) / 2;
        $scx = 50;
        $scy = 160.0 / $peak / 2.0;
        $ngx = 10;
        $ngy = 4;

        $fp = fopen($imgfile, "w");
        if ($fp)
        {
        
            fputs($fp, "$imgw|$imgh|$mgleft|$mgright|$mgtop|$mgbot|$x0|$y0|$scx|$scy|$ngx|$ngy\r\n");
            fputs($fp, "$this->DT|$this->nt\r\n");
            
            fputs($fp, $this->Umax[0][1]);
            
            for ($i = 1;$i < $this->maxTd;$i++)
            {
                fputs($fp, ",".$this->Umax[$i][1]);
            }
            
            fclose($fp);
        }
    }
}

?>

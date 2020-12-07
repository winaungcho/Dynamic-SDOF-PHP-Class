# Dynamic-SDOF-PHP-Class
PHP class for the dynamic analysis on single degree of freedom structure due to earthquake ground shaking.

1. SDOF Class solve for the dynamic motion equation in which including inertia force, dumping force, elastic force of sdof structure to resist the forces due to ground shaking.
2. Differential motion equation is discrete by using finite difference method. By this method, central difference method to solve response of SDOF structure.
3. Ground acceleration data due to Kobe earthquake is used in test example.
4. Analysis is carried on step by step time interval according to seismic record stored in kobens.txt.
5. 2 analysis are implemented in class, RHA response history analysis and other is to generate response spectrum curve.
6. Response spectrum curve help engineers to design earthquake resisting buildings.
7. If one has accelerogram recorded by strong motion accelerometer, response spectrum graph can be easily driven by using SDOF Class.
8. To do a response history analysis, first input stiffness and mass properties of system using #setKM($k, $m) function. Then load accelerogram file using #loadGAcc($name) where $name is the url or kobens.txt. Then run #calcRHA(). Out put response is in array $this->u and corresponding time is as that of accelerogram.
9. To perform spectrum analysis, first load accelerogram file using #loadGAcc($name). Then run #calcSpectrum(). Result is in array $this->Umax which is an array of pairs [T, RA] where T is damped natural period of system and RA represent the response acceleration at the center of mass.
10. Sample file format of accelerogram is 
<pre>
  0,-0.3
0.02,-0.3
0.04,0
0.06,0.3
0.08,0.3
0.1,0.3
0.12,0.3
0.14,0.3
0.16,0.3
0.18,0.3
0.2,0.3
0.22,0.3
0.24,0.3
0.26,0.3
0.28,0.3
0.3,0
0.32,0
  …
  …
  </pre>

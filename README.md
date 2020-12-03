# Dynamic-SDOF-PHP-Class
PHP class for the dynamic analysis on single degree of freedom structure due to earthquake ground shaking.

1 SDOF Class solve for the dynamic motion equation in which including inertia force, dumping force, elastic force of sdof structure to resist the forces due to ground shaking.
2 Differential motion equation is discrete by using finite difference method. By this method, central difference method to solve response of SDOF structure.
3 Ground acceleration data due to Kobe earthquake is used in test example.
4 Analysis is carried on step by step time interval according to seismic record stored in kobens.txt.
5 2 analysis are implemented in class, RHA response history analysis and other is to generate response spectrum curve.
6 Response spectrum curve help engineers to design earthquake resisting buildings.
7 If one has accelerogram recorded by strong motion accelerometer, response spectrum graph can be easily driven by using SDOF Class.

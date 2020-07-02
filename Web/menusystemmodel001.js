function menusystemmodel001Show(x, y, mnuname) {
	z_index++;
	var MenuSystemItems = null;
	var miindex= 0;
	var index= z_index;

	MenuSystemItems = new Array();
	miindex=0;
	MenuSystemItems[miindex++] = new LMMenuItemStruct(mnuname + "_MenuItem2",0,0,122,32,0,0,null,0,null,new LMBranchEx("0","http://www.prothom-alo.com",null,0.0,null,null,1,1,1,1,1,1,0,640,480,"shuhag"),null,null,projectroot+"/menusystemmodel001/menusystemitem0.gif",projectroot+"/menusystemmodel001/menusystemitem0_over.gif",null);
	var MenuSystemModel001_MNU1 = new LMMenu("MenuSystemModel001_MNU1",x+0,y+30,122,32,0,0,null,0,null,MenuSystemItems,0);
	LMObjects[objindex++]= MenuSystemModel001_MNU1;

	MenuSystemItems = new Array();
	miindex=0;
	MenuSystemItems[miindex++] = new LMMenuItemStruct(mnuname + "_MenuItem1",0,0,90,30,0,0,null,0,null,null,null,null,projectroot+"/menusystemmodel001/menusystemitem1.gif",projectroot+"/menusystemmodel001/menusystemitem1_over.gif",MenuSystemModel001_MNU1);
	var MenuSystemModel001_MNU2 = new LMMenu(mnuname,x+0,y+0,90,30,1,0,null,0,null,MenuSystemItems,1);
	LMObjects[objindex++]= MenuSystemModel001_MNU2;

	RegisterMainMenu(MenuSystemModel001_MNU2);

	ReIndexMenu(MenuSystemModel001_MNU2, index);

}

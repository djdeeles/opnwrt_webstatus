function loadInfo() {
	$.getJSON("data.php?refresh", 
		function(result) {
		//mem
		var totalMem = result[0];
		$("#totalMem").html(totalMem);
		var usedMem = result[1];
		$("#usedMem").html(usedMem);
		var availMem = result[2];
		$("#availMem").html(availMem);
		var memPercent = result[3];
		$("#memPercent").html("(" + memPercent + "%)");
		var memSpan = result[4];
		$('#memSpan1, #memSpan2, #memSpan3').removeClass().addClass("label label-" + memSpan);
		$("#memPercentBar").css("width", memPercent + "%");
		$('#memPercentBar').removeClass().addClass("progress-bar progress-bar-" + memSpan + " progress-bar-striped active");
		//swap
		var totalSwap = result[5];
		$("#totalSwap").html(totalSwap);
		var usedSwap = result[6];
		$("#usedSwap").html(usedSwap);
		var availSwap = result[7];
		$("#availSwap").html(availSwap);
		var swapPercent = result[8];
		$("#swapPercent").html("(" + swapPercent + "%)");
		var swapSpan = result[9];
		$('#swapSpan1, #swapSpan2, #swapSpan3').removeClass().addClass("label label-" + swapSpan);
		$("#swapPercentBar").css("width", swapPercent + "%");
		$('#swapPercentBar').removeClass().addClass("progress-bar progress-bar-" + swapSpan + " progress-bar-striped active");	
		//disk1
		var totalDisk1 = result[10];
		$("#totalDisk1").html(totalDisk1);
		var usedDisk1 = result[11];
		$("#usedDisk1").html(usedDisk1);
		var availDisk1 = result[12];
		$("#availDisk1").html(availDisk1);
		var diskPercent1 = result[13];
		$("#diskPercent1").html("(" + diskPercent1 + "%)");
		var disk1Span = result[14];
		$('#disk1Span1, #disk1Span2, #disk1Span3').removeClass().addClass("label label-" + disk1Span);
		$("#diskPercent1Bar").css("width", diskPercent1 + "%");		
		$('#diskPercent1Bar').removeClass().addClass("progress-bar progress-bar-" + disk1Span + " progress-bar-striped active");	
		//disk2
		var totalDisk2 = result[15];
		$("#totalDisk2").html(totalDisk2);
		var usedDisk2 = result[16];
		$("#usedDisk2").html(usedDisk2);
		var availDisk2 = result[17];
		$("#availDisk2").html(availDisk2);
		var diskPercent2 = result[18];
		$("#diskPercent2").html("(" + diskPercent2 + "%)");			
		var disk2Span = result[19];
		$('#disk2Span1, #disk2Span2, #disk2Span3').removeClass().addClass("label label-" + disk2Span);
		$("#diskPercent2Bar").css("width", diskPercent2 + "%");
		$('#diskPercent2Bar').removeClass().addClass("progress-bar progress-bar-" + disk2Span + " progress-bar-striped active");
		//load
		var load1M = result[20];
		$("#load1M").html(load1M);
		var load5M = result[21];
		$("#load5M").html(load5M);
		var load15M = result[22];
		$("#load15M").html(load15M);		
		var loadPercent = result[23];		
		$("#loadPercent").html("(" + loadPercent + "%)");			
		var loadSpan = result[24];
		$('#loadSpan1, #loadSpan2, #loadSpan3').removeClass().addClass("label label-" + loadSpan);
		$("#loadPercentBar").css("width", loadPercent + "%");
		$('#loadPercentBar').removeClass().addClass("progress-bar progress-bar-" + loadSpan + " progress-bar-striped active");
	    //uptime and localtime                
	    var uptime = result[25];
	    $("#uptime").html(uptime); 
	    //connections
	    var connections = result[26];
	    $("#connections").html(connections);                
	    var connPercent = result[27];                                
	    $("#connPercent").html("(Connections " + connPercent + "%)");
	    var connSpan = result[28];
	    $('#connSpan1, #connSpan2, #connSpan3, #connSpan4').removeClass().addClass("label label-" + connSpan);
	    $("#connPercentBar").css("width", connPercent + "%");
	    $('#connPercentBar').removeClass().addClass("progress-bar progress-bar-" + connSpan + " progress-bar-striped active");
	    //threads                
	    var runningthreads = result[29];
	    $("#runningthreads").html(runningthreads);
	    //WAN transfer
	    var WANrx = result[30];
	    $("#WANrx").html(WANrx + " Kb/s");
	    var WANrxPercent = result[31];
	    $("#WANrxPercentBar").css("width", WANrxPercent + "%");
	    var WANRecieveSpan = result[32];
	    $('#WANRecieveSpan').removeClass().addClass("label label-" + WANRecieveSpan);
	    $('#WANrxPercentBar').removeClass().addClass("progress-bar progress-bar-" + WANRecieveSpan + " progress-bar-striped active");
	    var WANtx = result[33];
	    $("#WANtx").html(WANtx + " Kb/s");
	    var WANtxPercent = result[34];
	    $("#WANtxPercentBar").css("width", WANtxPercent + "%");
	    var WANSendSpan = result[35];
	    $('#WANSendSpan').removeClass().addClass("label label-" + WANSendSpan);	    
	    $('#WANtxPercentBar').removeClass().addClass("progress-bar progress-bar-" + WANSendSpan + " progress-bar-striped active");    
	    //$("#WANPercent").html("(" + Math.round((WANtxPercent + WANrxPercent)/2) + "%)");
	    //LAN transfer
	    var LANrx = result[36];
	    $("#LANrx").html(LANrx + " Kb/s");
	    var LANrxPercent = result[37];
	    $("#LANrxPercentBar").css("width", LANrxPercent + "%");
	    var LANRecieveSpan = result[38];
	    $('#LANRecieveSpan').removeClass().addClass("label label-" + LANRecieveSpan);
	    $('#LANrxPercentBar').removeClass().addClass("progress-bar progress-bar-" + LANRecieveSpan + " progress-bar-striped active");
	    var LANtx = result[39];
	    $("#LANtx").html(LANtx + " Kb/s");
	    var LANtxPercent = result[40];     
	    $("#LANtxPercentBar").css("width", LANtxPercent + "%");
	    var LANSendSpan = result[41];
	    $('#LANSendSpan').removeClass().addClass("label label-" + LANSendSpan);	 
	    $('#LANtxPercentBar').removeClass().addClass("progress-bar progress-bar-" + LANSendSpan + " progress-bar-striped active");  
	    //$("#LANPercent").html("(" + Math.round((LANtxPercent + LANrxPercent)/2) + "%)");
	    //ping
	    //LAN transfer
	    var WLANrx = result[42];
	    $("#WLANrx").html(WLANrx + " Kb/s");
	    var WLANrxPercent = result[43];
	    $("#WLANrxPercentBar").css("width", WLANrxPercent + "%");
	    var WLANRecieveSpan = result[44];
	    $('#WLANRecieveSpan').removeClass().addClass("label label-" + WLANRecieveSpan);
	    $('#WLANrxPercentBar').removeClass().addClass("progress-bar progress-bar-" + WLANRecieveSpan + " progress-bar-striped active");
	    var WLANtx = result[45];
	    $("#WLANtx").html(WLANtx + " Kb/s");
	    var WLANtxPercent = result[46];     
	    $("#WLANtxPercentBar").css("width", WLANtxPercent + "%");
	    var WLANSendSpan = result[47];
	    $('#WLANSendSpan').removeClass().addClass("label label-" + WLANSendSpan);	 
	    $('#WLANtxPercentBar').removeClass().addClass("progress-bar progress-bar-" + WLANSendSpan + " progress-bar-striped active");  
	    //$("#LANPercent").html("(" + Math.round((LANtxPercent + LANrxPercent)/2) + "%)");
	    //ping
	    var usping = result[48];
	    $("#usping").html(usping);
	    var euping = result[49];
	    $("#euping").html(euping);
	    var gtping = result[50];
	    $("#gtping").html(gtping);
	    var apping = result[51];
	    $("#apping").html(apping);
	    // Refresh time
	    var refreshtime = result[52];
	    $("#refreshtime").html(refreshtime);
	});
}
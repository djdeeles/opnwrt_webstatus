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
		$("#memPercentBar").css("width", memPercent + "%");
		var memPercentBarDiv = result[4];
		$('#memPercentBarDiv').removeClass().addClass("progress progress-" + memPercentBarDiv + " progress-striped active");		
		var memSpan = result[5];
		$('#memSpan1, #memSpan2, #memSpan3').removeClass().addClass("label label-" + memSpan);
		//swap
		var totalSwap = result[6];
		$("#totalSwap").html(totalSwap);
		var usedSwap = result[7];
		$("#usedSwap").html(usedSwap);
		var availSwap = result[8];
		$("#availSwap").html(availSwap);
		var swapPercent = result[9];
		$("#swapPercent").html("(" + swapPercent + "%)");
		$("#swapPercentBar").css("width", swapPercent + "%");
		var swapPercentBarDiv = result[10];
		$('#swapPercentBarDiv').removeClass().addClass("progress progress-" + swapPercentBarDiv + " progress-striped active");	
		var swapSpan = result[11];
		$('#swapSpan1, #swapSpan2, #swapSpan3').removeClass().addClass("label label-" + swapSpan);
		//disk1
		var totalDisk1 = result[12];
		$("#totalDisk1").html(totalDisk1);
		var usedDisk1 = result[13];
		$("#usedDisk1").html(usedDisk1);
		var availDisk1 = result[14];
		$("#availDisk1").html(availDisk1);
		var diskPercent1 = result[15];
		$("#diskPercent1").html("(" + diskPercent1 + "%)");
		$("#diskPercent1Bar").css("width", diskPercent1 + "%");
		var diskPercent1BarDiv = result[16];
		$('#diskPercent1BarDiv').removeClass().addClass("progress progress-" + diskPercent1BarDiv + " progress-striped active");			
		var disk1Span = result[17];
		$('#disk1Span1, #disk1Span2, #disk1Span3').removeClass().addClass("label label-" + disk1Span);
		//disk2
		var totalDisk2 = result[18];
		$("#totalDisk2").html(totalDisk2);
		var usedDisk2 = result[19];
		$("#usedDisk2").html(usedDisk2);
		var availDisk2 = result[20];
		$("#availDisk2").html(availDisk2);
		var diskPercent2 = result[21];
		$("#diskPercent2").html("(" + diskPercent2 + "%)");
		$("#diskPercent2Bar").css("width", diskPercent2 + "%");
		var diskPercent2BarDiv = result[22];
		$('#diskPercent2BarDiv').removeClass().addClass("progress progress-" + diskPercent2BarDiv + " progress-striped active");			
		var disk2Span = result[23];
		$('#disk2Span1, #disk2Span2, #disk2Span3').removeClass().addClass("label label-" + disk2Span);
		//load
		var load1M = result[24];
		$("#load1M").html(load1M);
		var load5M = result[25];
		$("#load5M").html(load5M);
		var load15M = result[26];
		$("#load15M").html(load15M);		
		var loadPercent = result[27];		
		$("#loadPercent").html("(" + loadPercent + "%)");
		$("#loadPercentBar").css("width", loadPercent + "%");
		var loadPercentBarDiv = result[28];
		$('#loadPercentBarDiv').removeClass().addClass("progress progress-" + loadPercentBarDiv + " progress-striped active");			
		var loadSpan = result[29];
		$('#loadSpan1, #loadSpan2, #loadSpan3').removeClass().addClass("label label-" + loadSpan);
	    //uptime and localtime                
	    var uptime = result[30];
	    $("#uptime").html(uptime); 
	    //connections
	    var connections = result[31];
	    $("#connections").html(connections);                
	    var connPercent = result[32];                                
	    $("#connPercent").html("(Connections " + connPercent + "%)");
	    $("#connPercentBar").css("width", connPercent + "%");
	    var connPercentBarDiv = result[33];
	    $('#connPercentBarDiv').removeClass().addClass("progress progress-" + connPercentBarDiv + " progress-striped active");
	    var connSpan = result[34];
	    $('#connSpan1, #connSpan2, #connSpan3, #connSpan4').removeClass().addClass("label label-" + connSpan);
	    //threads                
	    var runningthreads = result[35];
	    $("#runningthreads").html(runningthreads);
	    //WAN transfer
	    var WANrx = result[36];
	    $("#WANrx").html(WANrx + " Kb/s");
	    var WANrxPercent = result[37];
	    $("#WANrxPercentBar").css("width", WANrxPercent + "%");
	    var WANrxPercentBarDiv = result[38];
	    $('#WANrxPercentBarDiv').removeClass().addClass("progress progress-" + WANrxPercentBarDiv + " progress-striped active");
	    var WANRecieveSpan = result[39];
	    $('#WANRecieveSpan').removeClass().addClass("label label-" + WANRecieveSpan);
	    var WANtx = result[40];
	    $("#WANtx").html(WANtx + " Kb/s");
	    var WANtxPercent = result[41];
	    $("#WANtxPercentBar").css("width", WANtxPercent + "%");
	    var WANtxPercentBarDiv = result[42];
	    $('#WANtxPercentBarDiv').removeClass().addClass("progress progress-" + WANtxPercentBarDiv + " progress-striped active");
	    var WANSendSpan = result[43];
	    $('#WANSendSpan').removeClass().addClass("label label-" + WANSendSpan);	        
	    $("#WANPercent").html("(" + Math.round((WANtxPercent + WANrxPercent)/2) + "%)");
	    //LAN transfer
	    var LANrx = result[44];
	    $("#LANrx").html(LANrx + " Kb/s");
	    var LANrxPercent = result[45];
	    $("#LANrxPercentBar").css("width", LANrxPercent + "%");
	    var LANrxPercentBarDiv = result[46];
	    $('#LANrxPercentBarDiv').removeClass().addClass("progress progress-" + LANrxPercentBarDiv + " progress-striped active");
	    var LANRecieveSpan = result[47];
	    $('#LANRecieveSpan').removeClass().addClass("label label-" + LANRecieveSpan);
	    var LANtx = result[48];
	    $("#LANtx").html(LANtx + " Kb/s");
	    var LANtxPercent = result[49];
	    $("#LANtxPercentBar").css("width", LANtxPercent + "%");
	    var LANtxPercentBarDiv = result[50];
	    $('#LANtxPercentBarDiv').removeClass().addClass("progress progress-" + LANtxPercentBarDiv + " progress-striped active");
	    var LANSendSpan = result[51];
	    $('#LANSendSpan').removeClass().addClass("label label-" + LANSendSpan);	        
	    $("#LANPercent").html("(" + Math.round((LANtxPercent + LANrxPercent)/2) + "%)");
	    //ping
	    var usping = result[52];
	    $("#usping").html(usping);
	    var euping = result[53];
	    $("#euping").html(euping);
	    var gtping = result[54];
	    $("#gtping").html(gtping);
	    var apping = result[55];
	    $("#apping").html(apping);
	    var refreshtime = result[56];
	    $("#refreshtime").html(refreshtime);
	});
}
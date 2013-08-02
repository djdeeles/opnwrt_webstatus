		function BeginPoll()
		{
			$.get('/status/lighttpd/status', null, LoadData);
		}
		function LoadData(data, textStatus)
		{
			if(textStatus == "success")
			{
				var d = new Date();
				var elem = document.createElement('div');
				var start = data.indexOf('<h1>');
				var stop = data.lastIndexOf('</body>');
				elem.innerHTML = data.slice(start, stop);
				data = null;
				var $e = $(elem);
				var $sc = $('table.status:first td.string', $e);
				$('#server-name').text(_getServerName($sc[0].textContent));
				$('#uptime').html('up ' + $sc[1].textContent);
				$('#requests-total').html($sc[3].textContent.replace(/\s?([Mk])?req/, '$1').toUpperCase());
				$('#traffic-total').html(_getTraffic($sc[4].textContent));
				$('#requests-avg').html(_getRequests($sc[5].textContent));
				$('#traffic-avg').html(_getTraffic($sc[6].textContent));
				$('#requests-avg-5s').html(_getRequests($sc[7].textContent));
				$('#traffic-avg-5s').html(_getTraffic($sc[8].textContent));
				$('#last-update').text('Last updated ' + d.toLocaleTimeString());
				var $crows = $('table.status:last tr', $e);
				var $ctable = $('#connectionsTable');
				var $ctbody = $('tbody', $ctable);
				$ctbody.empty();
				$crows.each(function() {
					var columns = $('td', this);
					if(columns.length == 0) return;
					var $tr = $('<tr>');
					$tr.append($('<td>').text(columns[0].textContent));
					$tr.append($('<td>').text(columns[4].textContent));
					$tr.append($('<td>').text(columns[5].textContent));
					$tr.append($('<td>').text(_getUri(columns[6].textContent)));
					$ctbody.append($tr);
				});
			}
			else
			{
				alert('failed loading data');
			}
			setTimeout(BeginPoll, 5 * 1000);
		}
		function _getServerName(serverString)
		{
			return serverString.replace(/ \(\)$/, '');
		}
		function _getRequests(requestString)
		{
			return requestString.replace(/\s*req/, '');
		}
		function _getTraffic(trafficString)
		{
			var m = trafficString.match(/(\d+)(\.\d+)? ([kMGTPEZY]?) ?byte(\/s)?/);
			if(m == null) return trafficString;
			var integer = m[1];
			var fraction = m[2];
			var suffix = m[3];
			var traffic = integer;
			if(suffix.match(/G|T|P|E|Z|Y/) && integer < 100)
				traffic += fraction;
			traffic += suffix + "b";
			if(m[4]) traffic += "/s";
			return traffic;
		}
		function _getUri(uriString)
		{
			var m = uriString.match(/^(\/[^ ]+)(?: \((.*)\))?$/);
			if(m != null && m[2] && m[1] == m[2])
				return m[1];
			return uriString;
		}
		$(BeginPoll);
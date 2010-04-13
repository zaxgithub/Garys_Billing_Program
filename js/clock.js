var timerID = null;
var timerRunning = false;
function stopclock (){
        if(timerRunning)
                clearTimeout(timerID);
        timerRunning = false;
}

function startclock () {
        stopclock();
        showtime();
}

function showtime () {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds()
        var timeValue = "" + ((hours >12) ? hours -12 :hours)
        
		var days = new Array(
			'Sunday','Monday','Tuesday',
			'Wednesday','Thursday','Friday','Saturday');
		var months = new Array(
			'January','February','March','April','May',
			'June','July','August','September','October',
			'November','December');
		var date = ((now.getDate()<10) ? "0" : "")+ now.getDate();
		var today =  days[now.getDay()] + ", " +
		months[now.getMonth()] + " " +
		date + ", " +
		(fourdigits(now.getYear()));
       
        timeValue += ((minutes < 10) ? ":0" : ":") + minutes
        timeValue += ((seconds < 10) ? ":0" : ":") + seconds
        timeValue += (hours >= 12) ? " P.M." : " A.M."
        document.getElementById('jsclock').innerHTML = today + ' &nbsp; &nbsp; ' + timeValue;
        timerID = setTimeout("showtime()",1000);
        timerRunning = true;
}

function fourdigits(number)	{
	return (number < 1000) ? number + 1900 : number;
}


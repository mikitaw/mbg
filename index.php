<?
	require_once('functions.php');

//  die($_REQUEST['ajax']);

  /*if ($_REQUEST['ajax'] != 'true') {
//require('functions.inc');
//require('header1.html');
   if (!isset($myPageTitle)) {
       $myPageTitle = '';
   }*/
?>
<html>
<head>
<title>
My backyard games
</title>
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
<link href="styles/style.css" type="text/css" rel="stylesheet" />
<link href="styles/purple.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.ba-resize.js"></script>
<script type="text/javascript" src="js/knockout-3.4.1.js"></script>
<script type="text/javascript" src="js/knockout.simpleGrid.3.0.js"></script>
</head>

<body>
<div id="main_container">
	<div class="twenty"></div>
	<div id="header">
		<div class="forty"></div>
		<div class="logo">My backyard games</div>
		<div class="header_menu">
			<?if (isLoggedIn()) {?>
				<a onclick="step.go(1);">My games</a>
			<?}?>
			<a href="forum">Forum</a>
			<a href="invite-a-friend.php">Invite a Friend</a>
			<?if (isLoggedIn()) {?>
				<a onclick="logout();">Logout</a>
			<?} else {?>
				<a href="register.php">Register</a>
				<a onclick="step.go(0);">Login</a>
			<?}?>
		</div>
	</div>
	<!--***** end of header *****-->
	<div id="message"></div>
	<div id="load" class="cssload-container" style="opacity: 0"><div class="cssload-loading"><i></i><i></i><i></i><i></i></div></div>
	<div id="main_content">
<?
//} // if not ajax

	
/*if (isLoggedIn()) {
    require_once('game.php');
} else {
    require_once('loginTemplate.php');
}*/
?>
<div id="loginForm">
	<form class="main_form" name="lform" onload="" data-bind="visible: !VM.logged(), submit: submitHandler, with: $root.user">
		<table align="center" width="100%">
			<tr>
				<td colspan="2" align="center"><input type="text" name="username" placeholder="Username" data-bind="textInput: login"></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="password" name="password" placeholder="Password" data-bind="textInput: password"></td>
			</tr>
			<tr>
				<td align="right"><input type="checkbox" data-bind="checked: autoLogin" name="autoLogin" id="autoLogin"> <label for="autoLogin" class="checkbox">Auto login</label></td>
				<td class="buttons_center"><input id="login" class="button" type="submit" name="mode" value="Login"></td>
			</tr>
			<!--<tr>
				<td valign="bottom" height="100" colspan="2" align="center">Forgot your password? <a href="forgot.php">Click here to retrieve</a></td>
			</tr>-->
			<tr>
				<td colspan="2" align="center" height="40" valihn="bottom"><br><a href="register.php">Not yet registered?</a> It's fast and free!</font></td>
			</tr>
		</table>
	</form>
	<div class="onlymes" data-bind="visible: logged">You're logged.<br>
		<a onclick="logout();">Do you want to leave? :(</a>
	</div>
</div>
<!--<div id="gameList2" style="display: none;">
<table width="100%" cellpadding="2" cellspacing="0">
<tr>
	<th align="center">gameName</th>
	<th align="center">created_on</th>
  <th align="center">updated_on</th>
</tr>
<tr>
<td colspan="3">
	<table width="100%" cellpadding="2" cellspacing="0" data-bind="foreach: games">
	<tr>
		<td align="center"><a class="redlink" href="#" data-bind='text: name'></a></td>
		<td align="center" data-bind="text: created_on"></td>
		<td align="center" data-bind="text: updated_on"></td>
	</tr>
	</table>
</td>
</tr>
</table>
</div>-->

<div id="gameList">
	<div class="createnew" onclick="VM.formNewGame.show()" data-bind="visible: !(VM.formNewGame.vis())">Create a new game
	</div>
	<form class="createnewform" data-bind="visible: VM.formNewGame.vis, submit: VM.formNewGame.save"><!--
		--><button class="button" name="cancel" onClick="VM.formNewGame.hide()" type="reset">Cancel</button><!--
		--><input type="text" name="newGameName" placeholder="Name of new game..." data-bind="value: VM.formNewGame.name, valueUpdate: 'afterkeydown'"><!--
		--><button class="button" name="save" type="submit">Save</button>
	</form>
	<!-- ko foreach: { data: games, afterAdd: showElement, beforeRemove: hideElement } -->
        <label class="gamerow">
			<input type="radio" name="game"  onclick="selectgame(this);" data-bind="checkedValue: $index, checked: $root.curgame.index" /> 
			<span class="gamename" data-bind="text: gameName"></span>
			<span class="crupd" data-bind="html: 'Created on '+created_on+ifupdated(created_on, updated_on)"></span>
			<div class="delete" title="Delete this game" data-bind="click: deleteGame"></div>
		</label>
	<!-- /ko -->
</div>

<script>

</script>

<div id="playerList">
	<div class="titleoflist" data-bind="visible: VM.players().length">
	&nbsp;Players in this game
	</div>
	<div class="createnew" onclick="VM.formNewPlayer.show()" data-bind="visible: !(VM.formNewPlayer.vis())">Create a new player
	</div>
	<form class="createnewform" data-bind="visible: VM.formNewPlayer.vis, submit: VM.formNewPlayer.save"><!--
		--><button class="button" name="cancel" onClick="VM.formNewPlayer.hide()" type="reset">Cancel</button><!--
		--><input type="text" name="newPlayerName" placeholder="Name of new player..." data-bind="value: VM.formNewPlayer.name, valueUpdate: 'afterkeydown'"><!--
		--><button class="button" name="save" type="submit">Save</button>
	</form>
	<!-- ko foreach: { data: players, afterAdd: showElement, beforeRemove: hideElement } -->
		<label class="gamerow">
			<input type="checkbox" name="player" onclick="selectplayer(this);" data-bind="checkedValue: playerID, checked: $root.chosenPlayers" /> 
			<span class="gamename" data-bind="text: playerName"></span>
			<span class="crupd" data-bind="html: 'Created on '+created_on+ifupdated(created_on, updated_on)"></span>
			<div class="delete" title="Delete this player" data-bind="click: deletePlayer"></div>
		</label>
	<!-- /ko -->
	<div class="titleoflist otpl" onclick="VM.otherPlayersBlock(VM.otherPlayersBlock() ? false : true)" data-bind="visible: VM.otherPlayers().length">
	&nbsp;Other players...
	</div>
	<div data-bind="visible: otherPlayersBlock">
		<!-- ko foreach: { data: otherPlayers, afterAdd: showElement, beforeRemove: hideElement } -->
			<label class="gamerow">
				<input type="checkbox" name="player" onclick="selectplayer(this);" data-bind="checkedValue: playerID, checked: $root.chosenPlayers" /> 
				<span class="gamename" data-bind="text: playerName"></span>
				<span class="crupd" data-bind="html: 'Created on '+created_on+ifupdated(created_on, updated_on)"></span>
				<div class="delete" title="Delete this player" data-bind="click: deletePlayer"></div>
			</label>
		<!-- /ko -->
	</div>
</div>

<div id="scorePage">
    <h1><span data-bind="text: VM.curgame.name"></span> - Round <span data-bind="text: VM.curgame.round"></span></h1>
	<h2>Player: <span data-bind="text: VM.curplayer.name"></span></h2>
	<button class="button" name="nextpl" onclick="VM.curplayer.next();">Next player</button>
	<button class="button" name="nextr" onclick="VM.curgame.nextRound();">New round</button>
	<br>
	<!--<form onSubmit="return false;" method="post" class="main_form" name="gamePlayer">-->
	<div class="scoreForm">
		<input id="score" name="score" type="text" value="00:00:00" disabled>
		<label for="score" class="pause"></label>
		<button class="button start" name="startButton" onClick="stopwatch.start();" >START</button>
		<button class="button stop" name="stopButton" onClick="stopwatch.stop();" disabled>STOP</button>
	</div>
	
	<br>
	<table class="scores">
    <thead>
		<tr><th colspan="2">Latest scores</th></tr>
        <tr><th>Player</th><th>Score</th></tr>
    </thead>
	<tfoot>
		<tr >
			<td colspan="2"><button class="button" name="more" onClick="score.more();">More</button></td>
		</tr> 
	</tfoot>
    <tbody data-bind="foreach: { data: scores, afterAdd: updateTable }">
        <tr>
            <td data-bind="text: playerName"></td>
            <td data-bind="text: score"></td>
        </tr>
    </tbody>
	</table>
	
	<table class="scores">
    <thead>
		<tr><th colspan="3">Best scores</th></tr>
        <tr><th>Position</th><th>Player</th><th>Score</th></tr>
    </thead>
	<tfoot>
		<tr >
			<td colspan="3"><button class="button" name="moreb" onClick="score.moreB();">More</button></td>
		</tr> 
	</tfoot>
    <tbody data-bind="foreach: { data: bestScores, afterAdd: updateTable }">
        <tr>
			<td data-bind="text: $index()+1"></td>
            <td data-bind="text: playerName"></td>
            <td data-bind="text: score"></td>
        </tr>
    </tbody>
	</table>
	
	<table class="scores">
    <thead>
		<tr><th colspan="5">Game scores</th></tr>
        <tr><th>Position</th><th>Player</th><th>Average score</th><th>Total score</th><th>Rounds played</th></tr>
    </thead>
	<tfoot>
		<tr >
			<td colspan="5"><button class="button" name="moreg" onClick="score.moreG();">More</button></td>
		</tr> 
	</tfoot>
    <tbody data-bind="foreach: { data: gameScores, afterAdd: updateTable }">
        <tr>
			<td data-bind="text: $index()+1"></td>
            <td data-bind="text: playerName"></td>
            <td data-bind="text: averScore"></td>
            <td data-bind="text: score"></td>
            <td data-bind="text: roundsPlayed"></td>
        </tr>
    </tbody>
	</table>	
</div>

<button class="button back" onclick="step.prev();">Back</button>
<button class="button next" onclick="step.next();">Next</button>

<script>
var VM = false;
function isset(r) {
    return typeof r !== 'undefined';
};
</script>
<script>
function ifupdated(created_on, updated_on) {
	if (created_on == updated_on) {
		return '';
	} else {
		return '<br>Updated on '+updated_on
	}
};

function checkName (name, GorP) { //GorP: true - this is game, false - this is player
	//name = name.trim();
	/*if (/^[A-Za-z0-9_ ]+$/.test(name) === false) {
		message.set('You can use only letters, numbers and underscores',1);
		return false;
	}*/
	if (name.length < 1 || name.length > 40) {
		message.set('Name must have 1 to 40 symbols',1);
		return false;
	}
	if (GorP) {
		for (i = 0; i < VM.games().length; i++) {
			if (name == VM.games()[i].gameName) {
				message.set('The game with the same name already exists',1);
				return false;
			}
		}
	} else {
		for (i = 0; i < allPlayers.length; i++) {
			if (name == allPlayers[i].playerName) {
				message.set('The player with the same name already exists',1);
				return false;
			}
		}
	}
	return true;
};

function getUNIXtime () {
	return parseInt(Math.round(Date.now()/1000.0));
};

function addZero(tx) {
	//tx = tx.toString();
	if (tx < 10) {
		tx="0"+tx;
	};
	return(tx);
};

function parseUNIXtoDT (unixtime, UTC) { //DT is Data and Time, e.g. "2017-02-03 12:45"
	if (UTC) { //for GMT+0 UTC == true
		var diff = new Date().getTimezoneOffset(); //difference in minutes
		diff = diff*60; //in seconds
		unixtime = unixtime + diff;
	}
	var time = new Date(unixtime*1000);
	var month = addZero(time.getMonth()+1);
	var day = addZero(time.getDate());
	var hour = addZero(time.getHours());
	var min = addZero(time.getMinutes());
	return time.getFullYear()+'-'+month+'-'+day+' '+hour+':'+min;
};

function getCurDT (UTC) {
	return parseUNIXtoDT(getUNIXtime(), UTC);
};

function parseDTtoUNIX (dt) {
	return Math.round(Date.parse(dt)/1000.0);
};

function parseDTtoCurGMT (dt) {
	var UNIXtime = parseDTtoUNIX(dt);
	var diff = new Date().getTimezoneOffset(); //difference in minutes
	diff = diff*60; //in seconds
	UNIXtime = UNIXtime - diff;
	return parseUNIXtoDT(UNIXtime);
};

function getTime () {
	var time = new Date();
	var hour = addZero(time.getHours());
	var min = addZero(time.getMinutes());
	var sec = addZero(time.getSeconds());
	return hour+':'+min+':'+sec;
};

function shadowFooter (){
	if (($(document).height()) - document.body.offsetHeight == ($(window).scrollTop())) {
		$('.footer').removeClass('shadow');
	} else {
		$('.footer').addClass('shadow');
	}
};

var mob = false, keyHeight;
if ($("#header .forty").height()) {
	keyHeight = $("#header .forty").height()
} else {
	mob = true;
	keyHeight = 1;
};
var totalWidth = $(window).width();

function fixHeader (){
	if ($(window).scrollTop() >= keyHeight) {
		$('#header').addClass('shadow').css({"height":"auto","position":"fixed"});
		$('.header_menu').css({"display":"inline-block"});
		if (totalWidth > 675) {
			$('.header_menu').css({"padding":"10px 0"});
		} else {
			$('.header_menu').css({"padding-bottom":"10px"});
		};
		$('.forty').hide();
	} else {
		$('#header').removeClass('shadow').css({"position":"absolute"});;
		if (!mob) {
			$('#header').css({"height":"20%"});
		};
		$('.header_menu').css({"display":"block", "padding":"0"});
		$('.forty').show();
	}
};

$(window).resize(shadowFooter);
$(window).scroll(shadowFooter);
$(window).scroll(fixHeader);

function startPlayerRound() {
//    document.gamePlayer.mode.value = 'DELETE';
//    document.gamePlayer.submit();
	startButton = document.getElementById('startButton');
	startButton.style='visibility:hidden';
//	setTimeOut
	stopButton = document.getElementById('stopButton');
	stopButton.focus();
	//stopButton.style='visibility:';

	setInterval(countTime, 1000);
};

var timefield = $('#scorePage input[name=score]');

var stopwatch = {
	secs: 0,
	interval: null,
	start: function () {
		if (this.interval) {
			this.pause();
			return false;
		}
		$('#scorePage button.start').text('PAUSE');
		$('#scorePage button.stop').text('STOP');
		$('#scorePage button.stop').removeAttr('disabled');
		$('#scorePage label.pause').hide();
		function timegone(){
			stopwatch.secs++;
			var sec = Math.abs(stopwatch.secs%60);
			var min = Math.abs(Math.floor(stopwatch.secs/60)%60);
			var hour = Math.abs(Math.floor(stopwatch.secs/60/60)%24);
			if (sec.toString().length == 1) sec = '0' + sec;
			if (min.toString().length == 1) min = '0' + min;
			if (hour.toString().length == 1) hour = '0' + hour;
			$(timefield).val(hour + ':' + min + ':' + sec);
		};
		this.interval = setInterval(timegone,1000);
	},
	pause: function () {
		$('#scorePage button.start').text('RESUME');
		$('#scorePage button.stop').text('RECORD');
		$('#scorePage label.pause').show();
		clearInterval(this.interval);
		this.interval = null;
	},
	stop: function () {
		this.pause();
		$('#scorePage label.pause').hide();
		score.record(this.secs);
	},
	clear: function () {
		this.pause();
		$('#scorePage button.start').text('START');
		$('#scorePage button.stop').text('STOP');
		$('#scorePage button.stop').attr('disabled', 'disabled');
		$('#scorePage label.pause').hide();
		$(timefield).val('00:00:00');
		this.secs = 0; 
	}
}

function countTime()
{
	gamePlayer.score.value = parseInt(gamePlayer.score.value) + 1;
}

function stopPlayerRound() {
    //document.gamePlayer.mode.value = 'RECORD_PLAYER_RESULT';
   // document.gamePlayer.submit();
//	startButton = document.getElementById('startButton');
//	startButton.style='visibility:hidden';
	clearInterval(start_time_interval);
}

var step = {
	num: 0, // 0 - page login, 1 - page gameList, 2 - page playerList, 3 - page score
	allow: false,
	go: function (num) {
		if (isset(num)) {
			this.num = num;
		};
		$('button.back').removeAttr('disabled');
		$('button.next').removeAttr('disabled');
		$('body,html').animate({scrollTop:0},300);
		switch (this.num) {
			case 0:
				create.loginForm();
				$('button.back').attr('disabled', 'disabled');
				break;
			case 1:
				create.gameList();
				break;
			case 2:
				create.playerList();
				break;
			case 3:
				create.scorePage();
				score.load();
				$('button.next').attr('disabled', 'disabled');
				break;
			default:
				message.set('Error: the next page does not exist',1);
				this.num = 1;
				create.gameList();
		};
	},
	next: function () {
		switch (this.num) {
			case 0:
				this.allow = true;
				break;
			case 1:
				if (VM.curgame.index() !== '') {
					this.allow = true;
				} else {
					message.set("You haven't chosen any game!",1);
					this.allow = false;
				}
				break;
			case 2:
				if (VM.chosenPlayers().length > 1) {
					this.allow = true;
				} else {
					message.set('To continue you must select at least two players',1);
					this.allow = false;
				}
				break;
			case 3:
				this.allow = false;
				break;
			default:
				message.set('Error: the next page does not exist',1);
				this.allow = true;
				this.num = 0;
		};
		if (this.allow) {
			this.num++;
			this.go();
		} else {
			//message.set("You can't continue");
			return false;
		}	
	},
	prev: function () {
		//var curgame = VM.curgame.index(); //to return page without cleaning the selection
		//var curplayers = VM.chosenPlayers();
		VM.formNewGame.hide();
		VM.formNewPlayer.hide();
		this.num--;
		this.go();
	
		/*setTimeout(function() {
			VM.curgame.index(curgame);
			var obj = $('#gameList input[value = '+curgame+']').parent();
				selectgame(obj);
			}, 500);
		
		VM.chosenPlayers(curplayers);*/
	}
};

function setFocus(obj){
	$(obj).focus();
};

function selectgame(obj) {
	var obj = $(obj).parent();
	$('#gameList label').attr('class','gamerow');
	$(obj).attr('class','gamerowsel');
	//VM.curgame.index(0);
	
	setTimeout(function() { //the timer, to skip knockoutjs forward
				step.next();
				$('button.next, button.back').show();
			}, 10);
};

function selectplayer(obj) {
	//$('#gameList label').attr('class','gamerow');
	var obj = $(obj).parent();
	var myclass = $(obj).attr('class');
	switch (myclass) {
		case 'gamerow':
			$(obj).attr('class','gamerowsel');
			break;
		case 'gamerowsel':
			$(obj).attr('class','gamerow');
			break;
	}
};

function hideall () {
	$("#main_content > div").hide();
};

function showOneBlock (blockID) {
	hideall();
	$(blockID).show();
	/*$(blockID).children().show();*/
};

function changeTitle (newtitle) {
	if (isset(newtitle)) {
		$('title').text(newtitle+' - My backyard games');
	} else {
		$('title').text('My backyard games');
	}
};

var timer,
	mesnum = 0;

var message = {
	set: function (text, type) {
		var pre = 'Message: ';
		if (isset(type)) {
			if (type == 0) pre = 'Server: ';
			if (type == 1) pre = 'Browser: ';
		}
		console.log('['+getTime()+'] '+pre+text);
		if (timer) {
			clearTimeout(timer);
		}
		timer = setTimeout(function() {
				$("#message").fadeTo(1000, 0);
			}, 7000);
		$("#message").html(text).fadeTo(100, 1)/*.delay(5000).fadeTo(1000, 0)*/;
	},
	multiset: function (text, type) {
		var pre = 'Message: ';
		if (isset(type)) {
			if (type == 0) pre = 'Server: ';
			if (type == 1) pre = 'Browser: ';
		}
		console.log('['+getTime()+'] '+pre+text);
		mesnum++;
		$('#message p:empty').remove();
		$('<p class="'+mesnum+'">'+text+'<p>').appendTo($('#message'));
		var newmes = $('#message p.'+mesnum);
		$(newmes).fadeTo(0, 0).fadeTo(200, 1);
		setTimeout(function() {
				$(newmes).fadeTo(1000, 0);
			}, 5000);
		setTimeout(function() {
				$(newmes).remove();
			}, 6000);
		//$("#message").html(text).fadeTo(100, 1)/*.delay(5000).fadeTo(1000, 0)*/;
	},/*,
	infinite: function (text) {
		$("#message").html(text).fadeTo(100, 1);
	}*/
};

/*function message (data) {
		$("#message").html(data).fadeTo(100, 1).delay(5000).fadeTo(1000, 0);
};*/

var loading = {
	go: function () { 
		$("#load").fadeTo(100, 1);
		$('body').css('cursor', 'wait');
		},
	stop: function (text) {
		if (isset(text)) {
			message.set(text,0);
			//var clear = function () {  };
			//setTimeout(clear, 5000);
		};
		$("#load").fadeTo(300, 0);
		$('body').css('cursor', 'default');
	}
};

function fBeforeSend () {
	loading.go();
};

function fError (jqXHR, exception) {
	if /*(jqXHR.status === 0) {
		typeError = 'Not connect.\n Verify Network.'; 
	} else if*/ (jqXHR.status == 404) {
		typeError = 'Requested page not found. [404]'; 
	} else if (jqXHR.status == 500) {
		typeError = 'Internal Server Error [500].'; 
	} else if (exception === 'parsererror') {
		typeError = jqXHR.responseText;
	} else if (exception === 'timeout') {
		typeError = 'Time out error.';
	} else if (exception === 'abort') {
		typeError = 'Ajax request aborted.'; 
	} else {
		typeError = 'Uncaught Error.\n' + jqXHR.responseText; 
	}
	message.set("<b>Ajax error:</b> " + typeError,0);
};
	
function goAjax (dataSentJS, fSuccess){
       // var dataSentJS = ko.toJS(dataSent);
        $.ajax({
            url: 'ajax.php',
			data: dataSentJS,
			dataType: 'html',
            type: 'POST',
            contentType: 'application/x-www-form-urlencoded',
			timeout: 5000,
			//processData: false,
			beforeSend: loading.go,
			error: fError,
			success: fSuccess
        })/*.success(successHandler).error(errorHandler)*/;
		return fSuccess;
};

var gamesLoaded = false, playersLoaded = false, lastSelectedGame = false, scoresLoaded = false;

var allPlayers = [];

var create = {
	loginForm: function(){
		changeTitle();
		showOneBlock("#loginForm");
		setFocus('#loginForm input[name = username]');
	},
	gameList: function(){
		function success(data) {
			changeTitle('Select game');
			showOneBlock("#gameList");
			if (gamesLoaded) return;
			data = JSON.parse(data);
			message.set(data[0],0);
			VM.games.removeAll();
			VM.curgame.clear();
			if (data.length < 2) {
				VM.formNewGame.show();
			} else {
				for (i = 1; i < data.length; i++){
					VM.gameRows(data[i]);
				};
			};
			gamesLoaded = true;
			loading.stop();
		};
		if (!gamesLoaded) {
			var dataSentJS = {mode: 'gameList'};
			goAjax(dataSentJS, success);
		} else {
			success();
		}
	},
	playerList: function(){
		if (!playersLoaded) {
			function success(data) {
				data = JSON.parse(data);
				if (!data[1]) {
					message.set(data[0],0);
				} else {
					console.log('['+getTime()+'] Server: '+data[0]);
					data.splice(0,2);
					if (data.length == 0) {
						VM.formNewPlayer.show();
						message.set("You haven't created any player but you can rectify it right now",1);
					} else {
						allPlayers = data;
					};
					playersLoaded = true;
				}
			};
			goAjax({mode: 'playerList'}, success);
		};
		function waitIfNotLoaded(){
			if(!playersLoaded) {
				setTimeout(waitIfNotLoaded,10); //expectation of ajax
			} else {
				if (isset(VM.curgame.index())) {
					changeTitle('Select player');
					showOneBlock("#playerList");
					VM.curgame.set();
					gID = VM.curgame.getID();
					if (lastSelectedGame != gID) {
						loading.go();
						VM.players.removeAll();
						VM.otherPlayers.removeAll();
						VM.chosenPlayers.removeAll();
						for (var i = 1; i < allPlayers.length; i++){
							if (allPlayers[i].gameID == gID) {
								VM.playerRows(allPlayers[i]);
							} else {
								VM.otherPlayersRows(allPlayers[i]);
							}
						};
						if (!VM.players().length) {
							message.set("In this game you haven't had players, but you can add players from other games or create new",1);
						}
						if (VM.players().length < 2) {
							VM.otherPlayersBlock(true);
						}
						lastSelectedGame = gID;
						loading.stop();
					}
				};
			};
		};
		waitIfNotLoaded();
	},
	scorePage: function(){
		if (VM.chosenPlayers().length > 1) {
			VM.curplayer.clear();
			changeTitle('Start game');
			showOneBlock("#scorePage");
		} else {
			message.set('To continue you must select at least two players',1);
			return false;
		}
	}
};

function makeGameScores(arr) {
	//arr[0].roundsPlayed = 0;
	//arr[0].score = parseInt(arr[i].score);
	var result = [];
//  var arr = JSON.parse(JSON.stringify(arr));

 nextInput:
    for (var i = 0; i < arr.length; i++) {
		var str = arr[i].playerName; 
		for (var j = 0; j < result.length; j++) {
			if (result[j].playerName == str) {
				result[j].score = result[j].score+parseInt(arr[i].score);
				result[j].roundsPlayed++;
				continue nextInput;
			}
		}
		arr[i].roundsPlayed = 1;
		arr[i].score = parseInt(arr[i].score);
		result.push(arr[i]);
	}
	
	for (var i = 0; i < result.length; i++) {
		result[i].averScore = Math.round(result[i].score/result[i].roundsPlayed);
	}
	return result;
};

var score = {
	data: null,
	dataB: null, //B - best, for table Best scores
	dataG: null, //G - game, for table Game scores
	save: function (sec) {
		function success (data) {
			data = JSON.parse(data);
			message.set(data[0],0);
			loading.stop();
			if (!data[1]) {
				message.set('Error saving score to the server. Try again.',0);
				return false;
			} else {
				stopwatch.clear();
				return true;
			}
		};
		var dataSent = {
			mode: 'recordScore',
			playerID: VM.curplayer.ID,
			gameID: VM.curgame.ID,
			roundNumber: VM.curgame.round(),
			score: sec
		};
		var dataSentJS = ko.toJS(dataSent);
		return goAjax(dataSentJS, success);
	},
	record: function (sec) {
		if (isNaN(sec)) {
			message.set('Error saving on the client side',1);
			console.log('The recorded result is not a number');
			return false;
		};
		if (!scoresLoaded) {
			message.set('Error saving',1);
			console.log('Data were not obtained. Updating the tables of scores is impossible');
			return false;
		};
		if (score.save(sec)) {
			insert = {playerName: VM.curplayer.getName(), 
							score: sec };
			//update table Latest scores
			score.data.unshift(insert);
			VM.scores.unshift(insert);
			//update table Best scores
			for (i = 0; i < score.dataB.length; i++) {
				if (sec <= parseInt(score.dataB[i].score)) {
					score.dataB.splice(i,0,insert);
					if (i < 6) {
						VM.bestScores.splice(i,0,insert);
					}
					break;
				}
			};
			//update table Game scores
			l = score.dataG.length;
			for (i = 0; i < l; i++) {//find name
				if (insert.playerName == score.dataG[i].playerName) {
					score.dataG[i].score = score.dataG[i].score+insert.score;
					score.dataG[i].roundsPlayed++;
					score.dataG[i].averScore = Math.round(score.dataG[i].score/score.dataG[i].roundsPlayed);
						if (i < 8) {
						VM.gameScores.splice(i,1);
						VM.gameScores.splice(i,0,score.dataG[i]);
					}
					break;
				} else if (i == l-1) { //if such a name is missing
					for (j = 0; j < l; j++) { //find a place to add a new line
						if (insert.score <= parseInt(score.dataG[j].score)) {
							insert = {playerName: insert.playerName, 
											score: insert.score,
											averScore: insert.score,
											roundsPlayed: 1};
							score.dataG.splice(j,0,insert);
							if (j < 8) {
								VM.gameScores.splice(j,0,insert);
							};
							break;
						};
					};
				};
			};
		} else {
			return false;
		}
	},
	load: function () {
		function success (data) {
			var data = JSON.parse(data);
			message.set(data[0],0);
			data.splice(0,1);
			score.data = $.makeArray(data);
			score.dataB = JSON.parse(JSON.stringify(data));
			score.dataG = makeGameScores(JSON.parse(JSON.stringify(data)));
			score.dataB.sort(function(a, b) {
				return a.score - b.score;
			});
			score.dataG.sort(function(a, b) {
				return a.averScore - b.averScore;
			});
			VM.scores.removeAll();
			VM.bestScores.removeAll();
			VM.gameScores.removeAll();
			score.more();
			score.moreB();
			score.moreG();
			if (data.length < 8) {
				$('button[name=more], button[name=moreb]').hide();
			};
			if (score.dataG.length < 8) {
				$('button[name=moreg]').hide();
			};
			scoresLoaded = true;
			loading.stop();
		};
		dataSentJS = {mode: 'scoreList',
						gameID: VM.curgame.getID()};
		goAjax(dataSentJS, success);
	},
	more: function () {
		i = VM.scores().length;
		ii = this.data.length;
		diff = ii-i;
		if (diff > 8) {
			for (var n = i; n < i+5; n++){
				VM.scoreRows(score.data[n]);
			};
			//this.data.splice(0,5);
		} else {
			for (i; i < ii; i++){
				VM.scoreRows(score.data[i]);
			};
			//this.data.splice(0,l);
			$('button[name=more]').attr('onclick', 'score.less();').text('Hide');
		}
	},
	less: function () {
		if (VM.scores().length > 5) {
			VM.scores.splice(5);
		}
		$('button[name=more]').attr('onclick', 'score.more();').text('More');
	},
	moreB: function () {
		i = VM.bestScores().length;
		ii = this.dataB.length;
		diff = ii-i;
		if (diff > 8) {
			for (var n = i; n < i+5; n++){
				VM.scoreBRows(score.dataB[n]);
			};
		} else {
			for (i; i < ii; i++){
				VM.scoreBRows(score.dataB[i]);
			};
			$('button[name=moreb]').attr('onclick', 'score.lessB();').text('Hide');
		}
	},
	lessB: function () {
		if (VM.bestScores().length > 5) {
			VM.bestScores.splice(5);
		}
		$('button[name=moreb]').attr('onclick', 'score.moreB();').text('More');
	},
	moreG: function () {
		i = VM.gameScores().length;
		ii = this.dataG.length;
		diff = ii-i;
		if (diff > 8) {
			for (var n = i; n < i+5; n++){
				VM.scoreGRows(score.dataG[n]);
			};
		} else {
			for (i; i < ii; i++){
				VM.scoreGRows(score.dataG[i]);
			};
			$('button[name=moreg]').attr('onclick', 'score.lessG();').text('Hide');
		}
	},
	lessG: function () {
		if (VM.gameScores().length > 5) {
			VM.gameScores.splice(5);
		}
		$('button[name=moreg]').attr('onclick', 'score.moreG();').text('More');
	}
};

function logout () {
	function success(data) {
		VM.logged(false);
		data = JSON.parse(data);
		message.set(data[0]+' (Reloading page in 5 seconds)');
		$('div.header_menu a:eq(2)').text('Register').attr({'onclick':'','href':'register.php'});
		$('div.header_menu a:eq(3)').text('Login').attr('onclick', 'step.go(0);');
		$('button.next, button.back').hide();
		step.go(0);
		loading.stop();
		setTimeout(function() {window.location.reload();}, 5000);
	};
	var dataSentJS = {mode: 'logout'};
	goAjax(dataSentJS, success);
};

function deleteGame (obj) {
	if (!confirm('Are you sure you want to delete game "'+obj.gameName+'"?')) {
		return false;
	};
	function success (data) {
		data = JSON.parse(data);
		if (data[1]) {
			VM.games.remove(obj);
			if (VM.curgame.getID() == obj.gameID) {
				VM.curgame.clear();
			};
		};
		message.set(data[0],0);
		loading.stop();
	};	
	var dataSent = {
			mode: 'deleteGame',
			gameID: obj.gameID,
			gameName: obj.gameName
	};
	goAjax(dataSent, success);
};

function deletePlayer (obj) {
	if (!confirm('Are you sure you want to delete player "'+obj.playerName+'"?')) {
		return false;
	};
	function success (data) {
		data = JSON.parse(data);
		if (data[1]) {
			VM.players.remove(obj);
			VM.chosenPlayers.remove(obj.playerID);
		};
		message.set(data[0],0);
		loading.stop();
	};	
	var dataSent = {
			mode: 'deletePlayer',
			playerID: obj.playerID,
			playerName: obj.playerName
	};
	goAjax(dataSent, success);
};

var VM = {
	logged: ko.observable(false),
	
	showElement: function(elem) {
		if (elem.nodeType === 1) {
			$(elem).hide().slideDown();
		}
	},
	
	hideElement: function(elem)	{
		if (elem.nodeType === 1) {
			$(elem).slideUp(function() {
				$(elem).remove(); }
		)};
	},
	
	updateTable: function(element) {
		if (scoresLoaded) {
			if (element.nodeType === 1) {
				$(element).addClass('ss');
				setTimeout(function() {$(element).removeClass('ss');}, 5000);
			}
		}
	},
		
    user: {
		mode: 'loginForm',
        login: ko.observable(),
        password: ko.observable(),
		autoLogin: ko.observable(false)
	},
	
	submitHandler: function () {
		function fSuccess (data) {
			data = JSON.parse(data);
			message.set(data[0],0); //data[0].gameName
			if (data[1]) {
				//$("form[name='lform']").detach();
				step.next();
				$('div.header_menu a:eq(2)').text('My games').attr('onclick','step.go(1);').removeAttr('href');
				$('div.header_menu a:eq(3)').text('Logout').attr('onclick', 'logout();');
				VM.user.password('');
				VM.logged(true);			
			} else {
				loading.stop();
			};
			//setTimeout("$('#message').fadeOut(1000)", 5000);
		};
		var dataSentJS = ko.toJS(this.user);
		goAjax(dataSentJS, fSuccess);
	},

	games: ko.observableArray(),
	players: ko.observableArray(),
	otherPlayers: ko.observableArray(),
	chosenPlayers: ko.observableArray(),
	scores: ko.observableArray(),
	bestScores: ko.observableArray(),
	gameScores: ko.observableArray(),
	
	gameRows: function (data){
		data.created_on = parseDTtoCurGMT(data.created_on);
		data.updated_on = parseDTtoCurGMT(data.updated_on);
		this.games.push(data);
	},
	
	playerRows: function (data){
		data.created_on = parseDTtoCurGMT(data.created_on);
		data.updated_on = parseDTtoCurGMT(data.updated_on);
		this.players.push(data);
	},	
	
	otherPlayersRows: function (data){
		data.created_on = parseDTtoCurGMT(data.created_on);
		data.updated_on = parseDTtoCurGMT(data.updated_on);
		this.otherPlayers.push(data);
	},
	
	scoreRows: function (data){
		this.scores.push(data);
	},
	
	scoreBRows: function (data){
		this.bestScores.push(data);
	},
	
	scoreGRows: function (data){
		this.gameScores.push(data);
	},
	
	otherPlayersBlock: ko.observable(false),
	
	curgame: {
		index: ko.observable(),
		name: ko.observable('Name is not defined'),
		round: ko.observable('Round is not defined'),
		clear: function () {
			this.index('');
			this.name('Name is not defined');
			this.round('Round is not defined');
			this.ID = null;
		},
		set: function () {
			var index = this.index();
			if (isset(index)) {
				var array = VM.games();
				if (array[index].gameID === VM.curgame.ID) return true;
				this.ID = array[index].gameID;
				//this.name = array[index].gameName;
				this.name(array[index].gameName);
				this.round(array[index].roundNumber);
				console.log('['+getTime()+'] Browser: Game "'+this.name()+'" selected!');
				return true;
			} else {
				message.set("You haven't chosen any game!",1);
				return false;
			}
		},
		getID: function (){
			if (isset(this.ID)) {
				return parseInt(this.ID);
				//message.set('Game is selected!');
			} else {
				message.set("You haven't chosen any game!",1);
				return false;
			}
		},
		getName: function (){
			if (isset(this.index())) {
				return this.name();
			} else {
				message.set("You haven't chosen any game!",1);
				return false;
			}
		},
		nextRound: function () {
			function success(data) {
				data = JSON.parse(data);
				message.set(data[0],0);
				if (data[1]) {
					index = VM.curgame.index();
					VM.games()[index].roundNumber++;
					VM.games()[index].updated_on = getCurDT(); //????
					VM.curgame.round(VM.curgame.round()+1);
					VM.curplayer.clear();
					stopwatch.clear();
					$('#scorePage button[name=nextpl]').removeAttr('disabled');
				} else {
					console.log('['+getTime()+'] Browser: The next round cannot be started. Synchronization error.');
				}
				loading.stop();
			};
			var dataSentJS = {mode: 'updateGame',
								gameID: VM.curgame.getID(),
								roundNumber: VM.curgame.round()+1,
								updated_on: getCurDT(true) };
			goAjax(dataSentJS, success);
		}
	},

	findIndex: function (ID, array){
	    for (var i = 0; i < array.length; i++) {
			if (array[i].playerID == ID) {
				return i;
			};
		};
	},
	
	curplayer: {
		name: ko.observable('Name is not defined'),
		clear: function (){
			this.set(0);
		},
		set: function (cpIndex){
			var array = VM.players();
			this.cpIndex = cpIndex; // cpIndex - index of element in the array VM.chosenPlayers
			this.ID = VM.chosenPlayers()[cpIndex];
			this.index = VM.findIndex(this.ID, array); // index - index of element in the array VM.players
			this.name(array[this.index].playerName);
		},
		getName: function (){
			if (isset(this.index)) {
			//var index = VM.findIndex(this.ID, array);
			//this.index = index;
				return this.name();
			} else {
				message.set("You haven't chosen players!",1);
				return false;
			}
        },
		next: function (){
			remain = VM.chosenPlayers().length - this.cpIndex - 1; //number of remaining players
			if (remain == 1) {
				$('#scorePage button[name=nextpl]').attr('disabled', 'disabled');
			};
			if (remain > 0) {
				//var array = VM.players();
				this.set(this.cpIndex+1);
				stopwatch.clear();
			} else {
				message.set('The current player is the last one',1);
				return false;
			}
		},
		otherNext: function (ID){ 
			cpIndex = VM.chosenPlayers.indexOf(ID);
			this.set(cpIndex);
		}
	},
	
	formNewGame: {
		name: ko.observable(),
		vis: ko.observable(false),
		show: function() {
			this.vis(true);
			setFocus('#gameList input[name=newGameName]');
		},
		hide: function() {
			this.vis(false);
			this.name('');
		},
		save: function() {
			function success (data) {
				data = JSON.parse(data);
				message.set(data[0],0);
				loading.stop();
				if (data[1]) {
					VM.formNewGame.hide();
					data[2].created_on = parseDTtoCurGMT(data[2].created_on);
					data[2].updated_on = data[2].created_on;
					VM.games.unshift(data[2]);
					VM.curgame.index(0);
					selectgame('#gameList > label:first-of-type input');
				}
			};
			var name = VM.formNewGame.name();
			if (!checkName(name, true)) {
				VM.formNewGame.name('');
				return false;
			}
			var dataSent = {
				mode: 'saveNewGame',
				gameName: name,
				created_on: getCurDT(true)
			};
			var dataSentJS = ko.toJS(dataSent);
			goAjax(dataSentJS, success);
		}
	},
	
	formNewPlayer: {
		name: ko.observable(),
		vis: ko.observable(false),
		show: function() {
			this.vis(true);
			setFocus('#playerList input[name=newPlayerName]');
		},
		hide: function() {
			this.vis(false);
			this.name('');
		},
		save: function() {
			function success (data) {
				data = JSON.parse(data);
				message.set(data[0],0);
				loading.stop();
				if (data[1]) {
					VM.formNewPlayer.hide();
					data[2].created_on = parseDTtoCurGMT(data[2].created_on);
					data[2].updated_on = data[2].created_on;
					VM.players.unshift(data[2]);
					VM.chosenPlayers.push(data[2].playerID);
					selectplayer('#playerList > label:first-of-type input');
				}
			};
			var name = VM.formNewPlayer.name();
			if (!checkName(name, false)) {
				VM.formNewPlayer.name('');
				return false;
			}
			var dataSent = {
				mode: 'saveNewPlayer',
				playerName: name,
				gameID: VM.curgame.getID(),
				created_on: getCurDT(true)
			};
			var dataSentJS = ko.toJS(dataSent);
			goAjax(dataSentJS, success);
		}
	},
	
	/*scores: ko.observableArray([{ playerName: "Well-Travelled Kitten", score: 352},
    { playerName: "Speedy Coyote", score: 89}]),
	
	/*gridViewModel: new ko.simpleGrid.viewModel({
        data: this.scores,
        columns: [
            { headerText: "Player", rowText: "playerName" },
            { headerText: "Score", rowText: "score" }
        ],
        pageSize: 10
    })*/
	

	
	/*initGrid: function () {
		var items = ko.observableArray(initialData);
		gridViewModel = new ko.simpleGrid.viewModel({
        data: items,
        columns: [
            { headerText: "Item Name", rowText: "name" },
            { headerText: "Sales Count", rowText: "sales" },
            { headerText: "Price", rowText: function (item) { return "$" + item.price.toFixed(2) } }
        ],
        pageSize: 4
    });
	$('#main_content').append("<div data-bind='simpleGrid: gridViewModel'> </div>");
	},
	
	gridViewModel: (function () { new ko.simpleGrid.viewModel({
        data: ko.observableArray(initialData),
        columns: [
            { headerText: "Item Name", rowText: "name" },
            { headerText: "Sales Count", rowText: "sales" },
            { headerText: "Price", rowText: function (item) { return "$" + item.price.toFixed(2) } }
        ],
        pageSize: 4
    })}),
	
	PagedGridModel: function() {
		var items = ko.observableArray(initialData);
	    VM.gridViewModel(new ko.simpleGrid.viewModel({
        data: items,
        columns: [
            { headerText: "Item Name", rowText: "name" },
            { headerText: "Sales Count", rowText: "sales" },
            { headerText: "Price", rowText: function (item) { return "$" + item.price.toFixed(2) } }
        ],
        pageSize: 4
    }));
	$('#main_content').append("<div data-bind='simpleGrid: gridViewModel'> </div>");
	}
	
	/*gridViewModel: new ko.simpleGrid.viewModel({
        data: this.items,
        columns: [
            { headerText: "Item Name", rowText: "name" },
            { headerText: "Sales Count", rowText: "sales" },
            { headerText: "Price", rowText: function (item) { return "$" + item.price.toFixed(2) } }
        ],
        pageSize: 4
    })*/
};

/*var PagedGridModel = function(items) {
	this.items = ko.observableArray(items);
	    this.gridViewModel = new ko.simpleGrid.viewModel({
        data: this.items,
        columns: [
            { headerText: "Item Name", rowText: "name" },
            { headerText: "Sales Count", rowText: "sales" },
            { headerText: "Price", rowText: function (item) { return "$" + item.price.toFixed(2) } }
        ],
        pageSize: 4
    });
};*/
</script>
<script>
$(document).ready(function() {
	if (!VM) {
		$("#main_content > div").hide();
		$('div.header_menu, button.next, button.back').hide();
		$("#main_content").append('<div class="onlymes">An error occurred in initialization Knockout JS.</div>');
	} else {
	<?if (isLoggedIn()) {?>
		VM.logged(true);
		step.next();
	<? } else { ?>
		setFocus('#loginForm input[name = username]');
	<? } ?>
	ko.applyBindings(VM);
		/*ko.applyBindings(new PagedGridModel(initialData));*/
	}
});
//var subscription = 
</script>


<?
  /*if ($_REQUEST['ajax'] != 'true') {*/
?>
</div><!--/main_content-->
<!--***** begin of footer *****-->
<div class="footer">
	<a href="index.php">Home</a>
	<a href="privacy.php">Privacy Policy</a>
	<a href="contact.php">Contact Us</a>
</div>
<!--***** end of footer *****-->
</div> <!--main_container-->
</body>
</html>
<?
  //}

?>
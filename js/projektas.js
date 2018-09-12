$(function() {
	var bonus = Cookies.get('bonus');
	if(bonus==2) Cookies.set('mod',0.97);
	else Cookies.set('mod',0.95);
	Cookies.set('botgame', 730);
	loadbot(1);
	loadInv(730);
});
	function loadInv(gamenr){
		var id = Cookies.get('who');
		Cookies.set('usergame', gamenr);
		$.ajax({
			url: '/Newproject/inventory.php',
           		dataType: 'json',
           		cache: false,
			type: 'POST',
			data: { who: id, game: gamenr ,ident :"user"},
			success: function (data) {
			if(typeof data.error != "undefined") {
						$('#user').html("<div class='innerError'>" + data.error + " </div>");
			}else additems(data,1);
			}
		});
	}
	function loadbot(botnr){
		switch(botnr){
			case 1:
			Cookies.set('botid', '76561198165970813');
			break;
			case 2:
			Cookies.set('botid', '76561198263760347');
			break;
			case 3:
			Cookies.set('botid', '76561198354714728');
		}
		loadbotinv(730);
	}
	function loadbotinv(gamenr){
		Cookies.set('botgame', gamenr);
		var id = Cookies.get('botid');
		$.ajax({
			url: '/Newproject/inventory.php',
           		dataType: 'json',
           		cache: false,
			type: 'POST',
			data: { who: id, game: gamenr,ident :"bot" },
			success: function (data) {
						if(typeof data.error != "undefined") {
						$('#bot').html("<div class='innerError'>Unable to load bot items , if this problem continues , please contact one of the admins </div>");
			}else additems(data,0);
			}
		});
	}
	function additems(my_array,who){
		var numb = 0.00;
		var my_array = $.map(my_array, function(value, index) {return [value];});
			var usergame = Cookies.get('usergame');
			var botgame = Cookies.get('botgame');
		if(who ==1){
			var bonus = Cookies.get('bonus');
			$('#user').empty();
			$('#Uselected').empty();
			$('#userprice').text(numb.toFixed(2));
			if(usergame != botgame) changeprice("user");
			else changeprice("usersame");
		}else{
			$('#bot').empty();
			$('#Bselected').empty();
			$('#botprice').text(numb.toFixed(2));
			if(usergame != botgame) changeprice("bot");
			else changeprice("botsame");
		}
        		for (var i=0; i<my_array.length; i++) {
           			var background = "url(https://steamcommunity-a.akamaihd.net/economy/image/" +  my_array[i]["icon_url"] + ")";
				var newdiv = document.createElement("div");
				newdiv.style.backgroundImage = background;
				if(who==1){
					newdiv.className = "item";
					var mod = Cookies.get('mod');
				}else{
					newdiv.className = "items";
					var mod = 1;
				}
				var cost = (my_array[i]["price"]*mod).toFixed(2);
				if (cost== 0){
					cost= "Unstable price";
				}
				if (ArrayHas("overstock",my_array[i])){
					cost= "Overstock";
				}
				if (ArrayHas("banned",my_array[i])){
					cost= "Unavailable";
				}
				var price = document.createAttribute("price");
				price.value= (my_array[i]["price"]*mod).toFixed(2);
				newdiv.setAttributeNode(price);

				var name=document.createAttribute("name");
				name.value= my_array[i]["market_hash_name"];
				newdiv.setAttributeNode(name);

				var assetid=document.createAttribute("assetid");
				assetid.value= my_array[i]["assetid"];
				newdiv.setAttributeNode(assetid);
				if(who==1){
					var changed=document.createAttribute("changed");
					if(mod==0.95 || mod==0.97) changed.value=false;
					else changed.value=true;
					newdiv.setAttributeNode(changed);
				}
				var title=document.createAttribute("title");
				if(ArrayHas("fraudwarnings",my_array[i])) title.value=my_array[i]["market_hash_name"] + "<br>" + my_array[i]["fraudwarnings"];
				else title.value=my_array[i]["market_hash_name"];
				newdiv.setAttributeNode(title);
				if(ArrayHas("stickers",my_array[i])){
					var content=document.createAttribute("data-content");
					content.value=my_array[i]["stickers"];
					newdiv.setAttributeNode(content);
				}
				if(who ==1) document.getElementById("user").appendChild(newdiv);
				else document.getElementById("bot").appendChild(newdiv);
				var prices = document.createElement("div");
				prices.className = "price";
				prices.innerHTML = cost;
				newdiv.appendChild(prices);
				if(ArrayHas("wear",my_array[i])){
					var wear = document.createElement("div");
					wear.className = "wear";
					wear.innerHTML = my_array[i]["wear"];
					newdiv.appendChild(wear);
				}
				if(ArrayHas("st",my_array[i])){
					var st = document.createElement("div");
					st.className = "st";
					st.innerHTML = "<img style='margin-top:3px;' src='images/ST.png'>";
					newdiv.appendChild(st);
				}
				var divclass = document.createElement("div");
				divclass.className = "inspect";
				if(ArrayHas("inspectlink",my_array[i])){
					var toggableE = document.createElement("a");
					toggableE.className = "InspectLink";
					toggableE.innerHTML = "Inspect Link<Br>";
					var link = document.createAttribute("href");
					link.value= my_array[i]["inspectlink"];
					toggableE.setAttributeNode(link);
					var target = document.createAttribute("target");
					target.value= "_blank";
					toggableE.setAttributeNode(target);
					divclass.appendChild(toggableE);
				}
				var toggableE2 = document.createElement("a");
				toggableE2.className = "MarketLink";
				toggableE2.innerHTML = "Market";
				var link = document.createAttribute("href");
				if(who ==1) var game = Cookies.get('usergame');
				else game = Cookies.get('botgame');
				link.value= "http://steamcommunity.com/market/listings/"+game+"/"+my_array[i]["market_hash_name"];
				toggableE2.setAttributeNode(link);
				var target = document.createAttribute("target");
				target.value= "_blank";
				toggableE2.setAttributeNode(target);
				divclass.appendChild(toggableE2);
				newdiv.appendChild(divclass);
				
        		}
		if(who==1){
			sort(1,1);
		}else{
			sort(1,0);
		}
	}
	function trade(){
		var divv = document.getElementById("trade").children;
		var useritems = [];
		var usersassets = [];
		var botsitems = [];
		var botsassets = [];
		var Uprice = $('#userprice').text(); 
		var Bprice = $('#botprice').text(); 
		var user = document.getElementById("Uselected").children;
		var bot = document.getElementById("Bselected").children;
		for(var i = 0; i < user.length; i++) {
			useritems.push(user[i].attributes[3]["nodeValue"]);
			usersassets.push(user[i].attributes[4]["nodeValue"]);
		}
		for(var i = 0; i < bot.length; i++) {
			botsitems.push(bot[i].attributes[3]["nodeValue"]);
			botsassets.push(bot[i].attributes[4]["nodeValue"]);
		}
		var id = Cookies.get('who');
		var botid = Cookies.get('botid');
		var usergame = Cookies.get('usergame');
		var botgame = Cookies.get('botgame');
		var bonus = Cookies.get('bonus');
	if(bonus==2) var Bonus = 1;
	else var Bonus = 0;
		$.ajax({
			url: '/Newproject/trade.php',
           		dataType: 'json',
           		cache: false,
			type: 'POST',
			data: { who: id,bot: botid, usergame: usergame, botgame: botgame, Uitems: useritems, Bitems: botsitems, Bassets: botsassets, Uassets: usersassets , Bprice: Bprice , Uprice: Uprice, bonus: Bonus},
			success: function (data) {
			if(typeof data.error != "undefined") {
						divv["0"].style.display = "none";
						$('#modalError').html("<span class='close'>&times;</span>" + data.error);
						divv["2"].style.display = "block";
						
			}else {
				divv["0"].style.display = "none";
				$('#modalSucc').html("<span class='close'>&times;</span> Your trade offer was proccesed and confirmed .You can go to your trade by clicking <a href=https://steamcommunity.com/tradeoffer/"+ data+ ">Here</a> Thank you for using Sazone.LT trading service ! Have a nice day !");
				divv["1"].style.display = "block";
			}
			}
		});
	}
	function sort(numb ,who){
		if(who==1) var divs = $("#user .item");
		else var divs = $("#bot .items");
    		var Order = divs.sort(function (a, b) {
			if(numb==0){return +a.getAttribute('price') - +b.getAttribute('price');}
			return +b.getAttribute('price') - +a.getAttribute('price');
    		});
		if(who ==1) $("#user").html(Order);
		else $("#bot").html(Order);
	}
	function changeprice(who){
		var bonus = Cookies.get('bonus');
		if(who == "botsame"){
			var divs = document.getElementsByClassName('item');
				for(var i = 0; i < divs.length; i++) {
					var newdiv = divs[i];
						if(newdiv.attributes[5]["nodeValue"] =="true"){
							var tempPrice = newdiv.attributes[2]["nodeValue"]
							newvalue = document.createAttribute('price');
							if(bonus==2) newvalue.value = (0.97*parseFloat(tempPrice)/0.92).toFixed(2);
							else newvalue.value = (0.95*parseFloat(tempPrice)/0.9).toFixed(2);
							newdiv.setAttributeNode(newvalue);
							newvalue = document.createAttribute('changed');
							newvalue.value = false;
							newdiv.setAttributeNode(newvalue);
               			 			var words = $(newdiv.children[0]).text(); 
							if(tempPrice!=0.00 && words != "Overstock" && words != "Unavailable" ){
								if(bonus==2) $(newdiv.children[0]).text((0.97*parseFloat(tempPrice)/0.92).toFixed(2));
								else $(newdiv.children[0]).text((0.95*parseFloat(tempPrice)/0.9).toFixed(2));
							}
						}
				pricechange();
				}
		if(bonus==2) Cookies.set('mod',0.97);
		else Cookies.set('mod',0.95);
		}else if (who=="bot"){
			var divs = document.getElementsByClassName('item');
				for(var i = 0; i < divs.length; i++) {
					var newdiv = divs[i];
						if(newdiv.attributes[5]["nodeValue"] =="false"){
							var tempPrice = newdiv.attributes[2]["nodeValue"]
							newvalue = document.createAttribute('price');
							if(bonus==2) newvalue.value = (0.92*parseFloat(tempPrice)/0.97).toFixed(2);
							else newvalue.value = (0.9*parseFloat(tempPrice)/0.95).toFixed(2);
							newdiv.setAttributeNode(newvalue);
							newvalue = document.createAttribute('changed');
							newvalue.value = true;
							newdiv.setAttributeNode(newvalue);
               			 			var words = $(newdiv.children[0]).text(); 
							if(tempPrice!=0.00 && words != "Overstock" && words != "Unavailable"){
								if(bonus==2) $(newdiv.children[0]).text((0.92*parseFloat(tempPrice)/0.97).toFixed(2));
								else $(newdiv.children[0]).text((0.9*parseFloat(tempPrice)/0.95).toFixed(2));
							}
						}
				pricechange();
				}
			if(bonus==2) Cookies.set('mod',0.92);
			else Cookies.set('mod',0.9);
		}else if (who=="user"){
			if(bonus==2) Cookies.set('mod',0.92);
			else Cookies.set('mod',0.9);
		}else{
			if(bonus==2) Cookies.set('mod',0.97);
			else Cookies.set('mod',0.95);
		}
	}

	function pricechange(){
		var user = document.getElementById("Uselected").children;
                var variable = $('#userprice').text(); 
 		var change = 0.00;
		$('#userprice').text(change.toFixed(2)); 
		for(var i = 0; i < user.length; i++) {
			var tempPrice =user[i].attributes[2]["nodeValue"];
			var variable = $('#userprice').text(); 
			var change = parseFloat(variable)+parseFloat(tempPrice);
			$('#userprice').text(change.toFixed(2)); 
		}
}
	$(document).contextmenu(function() {
  // nukomentuoti later kad return false  return false;
	});
	$(document).ready(function(){ 
		$('#containuser ').popover({ trigger: "hover focus",selector: ".item",container: "#containbot",html: true, placement: 'auto bottom'}); 
		$('#containbot').popover({ trigger: "hover focus",selector: ".items",container: "#containbot",html: true, placement: 'auto bottom'}); 
	});
	function ArrayHas(key, array) {
		if (key in array) return true;
		return false;
	}
	$(document).on('click mousedown', function(event) {		
	console.log($(event.target));
	if(event.button==0){
		if ($(event.target).is('.modalOpen')){
			$(event.target).parent()["0"].lastElementChild.style.display = "block";
		}else if ($(event.target).is('.close')){
			$(event.target).parent().parent().parent()[0].style.display = "none";
		}else if ($(event.target).is('.modal-content')){
			$(event.target).parent()[0].style.display = "none";
		}else if ($(event.target).is('.dropdownOpen')){
			if($(event.target).is('img')){
				$('.dropdown-content').not($($(event.target).parent()["0"].nextElementSibling)).hide();
				$($(event.target).parent()["0"].nextElementSibling).fadeToggle();
			}else{
				$('.dropdown-content').not($($(event.target).parent()["0"].lastElementChild)).hide();
				$($(event.target).parent()["0"].lastElementChild).fadeToggle();
			}
		}else if ($(event.target).is('.submit')){
			var id = Cookies.get('who');
			var link = $('input[id="Tradelink"]');
			$.ajax({
				url: '/Newproject/tradelink.php',
           			dataType: 'json',
           			cache: false,
				type: 'POST',
				data: { who: id,link: link["0"].value},
				success: function (data) {
					if(typeof data.error != "undefined") {
						$(".TlinkError").show();
						$(".TlinkSucc").hide();
					}else {
						$(".TlinkSucc").show();
						$(".TlinkError").hide();
					}
				}
			});
		}else if ($(event.target).is('.item')){
			if(!$(event.target).is('.inspect * , .inspect')){
				var isClicked = $(event.target).data('clicked');
    				if(!isClicked) {
					var price = $(event.target).attr('price');
               			 	var variable = $('#userprice').text(); 
 					var change = parseFloat(variable)+parseFloat(price);
					$('#userprice').text(change.toFixed(2)); 
					$(event.target).detach().appendTo('#Uselected')
       	 				$(event.target).data('clicked', true);
    				}else{
					var price = $(event.target).attr('price');
              			  	var variable = $('#userprice').text(); 
 					var change = parseFloat(variable)-parseFloat(price);
					$('#userprice').text(change.toFixed(2)); 
        				$(event.target).detach().appendTo('#user')
					$(event.target).data('clicked', false);
    				}
			}
		}else if ($(event.target).is('.items')){
			if(!$(event.target).is('.inspect * , .inspect')){
				var isClicked = $(event.target).data('clicked');
    				if (!isClicked) {
					var price = $(event.target).attr('price');
             		   		var variable = $('#botprice').text(); 
 					var change = parseFloat(variable)+parseFloat(price);
					$('#botprice').text(change.toFixed(2)); 
					$(event.target).detach().appendTo('#Bselected')
        				$(event.target).data('clicked', true);
    				}else{
					var price = $(event.target).attr('price');
                			var variable = $('#botprice').text(); 
 					var change = parseFloat(variable)-parseFloat(price);
					$('#botprice').text(change.toFixed(2)); 
        				$(event.target).detach().appendTo('#bot')
					$(event.target).data('clicked', false);
    				}
			}
		}else if ($(event.target).is('.refreshU')){
			var usergame = Cookies.get('usergame');
			loadInv(usergame);
		}else if ($(event.target).is('.refreshB')){
			var botgame = Cookies.get('botgame');
			loadbotinv(botgame);
		}else if ($(event.target).is('#trade')){
			$(event.target)["0"].children["0"].style.display = "block";
		}else{
			if (!$($(event.target)["0"].parentElement).is('.dropdown-content')&&!$($(event.target)["0"].parentElement).is('.modal-content')&&!$($(event.target)["0"].parentElement).is('form')&&!$($(event.target)["0"].parentElement).is('.modal-inner')&&!$($(event.target)["0"].parentElement).is('tr')&&!$($(event.target)["0"].parentElement.parentElement).is('#userdrop')) $(".dropdown-content").hide();		
			if (!$(event.target).is('.inspect * , .inspect')) $(".inspect").hide();
		}
	}else if (event.button==2){
		if ($(event.target).is('.item')){
			$('.inspect').not($(event.target).children(".inspect")).hide();
			$(event.target).children(".inspect").fadeToggle();
		}else if ($(event.target).is('.items')){
			$('.inspect').not($(event.target).children(".inspect")).hide();
			$(event.target).children(".inspect").fadeToggle();
		}
	}
	$(this).off('click');
	});

	function search(who) {
		var input, filter, id, divu, td, i;
		if(who == "bot"){
			input = document.getElementById("ItemsInput");
			filter = input.value.toLowerCase();
			id = document.getElementById("bot");
			div = id.getElementsByClassName('items');
		}else{
			input = document.getElementById("ItemInput");
			filter = input.value.toLowerCase();
			id = document.getElementById("user");
			div = id.getElementsByClassName('item');
		}
		for (i = 0; i < div.length; i++) {
			var price = div[i].attributes[3]["nodeValue"];
			price = price.replace(' |', '');
  			if (price) {
				if (price.toLowerCase().includes(filter)) {
					div[i].style.display = "";
				} else {
					div[i].style.display = "none";
				}
			} 
		}
	}
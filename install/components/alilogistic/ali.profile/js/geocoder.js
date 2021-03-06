var Place = function(){
	
	this.components = {
		country : [],
		province : [],
		locality : [],
		area : [],
		airport : [],
		district: [],
		street : [],
		house : []
	};
	
	this.kind = null;
	this.text = null;
	this.name = null;
	this.description = null;
	this.addressFormatted = null;
	this.country = null;

	this.visible = ['country','province','locality','area','airport','district','street','house'];

	this.init = function(components){
		var p = new Place();
		components.forEach(function(item){
			if(p.components.hasOwnProperty(item.kind)){
				p.components[item.kind].push(item.name);
			}
		});

		return p;
	}

	this.placeToLi = function(comps){
		var html = "<li class='place_item'";
		var c = comps && comps.length ? comps : this.visible;
		var components = this.components;
		

		

		var values = [];
		c.forEach(function(item){
			var value = components[item].length ? components[item].join(" ") : null;
			if(value)
				values.push(value);
		});
		
		if(!values.length) return null;
		
		html +=" data-value='"+this.name+"'>";
		html += values.join(", ");
		html +="</li>";

		return html;
	}

};


var PlacesCollection = function(){
	this.places = [];

	this.importYnadex = function(response){
		var places = [];
		if(response.hasOwnProperty("GeoObjectCollection") && 
		   response.GeoObjectCollection.hasOwnProperty("featureMember")){
			if(response.GeoObjectCollection.featureMember.length){
				var geo_items = response.GeoObjectCollection.featureMember;

				geo_items.forEach(function(item,i){
					if(item.hasOwnProperty("GeoObject") && 
						item.GeoObject.hasOwnProperty("metaDataProperty") &&
						item.GeoObject.metaDataProperty.hasOwnProperty("GeocoderMetaData") &&
						item.GeoObject.metaDataProperty.GeocoderMetaData.hasOwnProperty("Address") &&
						item.GeoObject.metaDataProperty.GeocoderMetaData.Address.hasOwnProperty("Components")){

						var components = item.GeoObject.metaDataProperty.GeocoderMetaData.Address.Components;
						var place_data = {};
						var place = (new Place()).init(components);

						place.name = item.GeoObject.name;
						place.description = item.GeoObject.description;
						place.kind = item.GeoObject.metaDataProperty.GeocoderMetaData.kind;
						place.text = item.GeoObject.metaDataProperty.GeocoderMetaData.text;
						
						place.country = item.GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.CountryName;
						
						place.addressFormatted = item.GeoObject.metaDataProperty.GeocoderMetaData.Address.formatted;


						places.push(place);
					}
				});
			}
		}
		this.places = places;
	};
};

var aliGeocoder = function(){
	this.init = function(textinputs){

	}
}

var placesToHtml = function(places,components){
	this.html = "<ul class='yandex_places_autocomplete'>";
	
	var list = "";
	if(places.length){
		places.forEach(function(item,i){
			var li = item.placeToLi(components);
			if(li)
				list += li;
		});
	}
	
	if(!list.length) return null;
	this.html +=list;
	this.html += "</ul>";
	return this.html;
}


var placesPropertyToHtml = function(places,property,value,kind){
	this.html = "<ul class='yandex_places_autocomplete'>";
	
	var list = "";
	if(places.length){
		places.forEach(function(item,i){
			var components = item.components;
			if(kind && kind.length){
				var v = "";
				var p = "";
				kind.forEach(function(k){
					if(components.hasOwnProperty(k) && components[k].length){
						if(item.hasOwnProperty(property) && item.hasOwnProperty(value)  && item[property] && item[value]){
							v = item[value];
							p = item[property];
						}
					}
				});
				if(v && p)
					list += "<li class='place_item' data-value='"+v+"'>"+p+"</li>";

			}else{
				if(item.hasOwnProperty(property) && item.hasOwnProperty(value) && item[property]){
					list += "<li class='place_item' data-value='"+item[value]+"'>"+item[property]+"</li>";
				}
			}
		});
	}
	if(!list.length) return null;
	this.html += list;
	this.html += "</ul>";

	return this.html;
}


$(function(){

	var host = "https://geocode-maps.yandex.ru/1.x/";

	$("body").on("keyup","div.geocoder-region input[type=text]",function(event){
		event.preventDefault();
		var val = $(this).val();
		var parent = $(this).parents("div.geocoder.geocoder-region");
		var list = parent.find("div.places-list");

		if(!list.length){
			list = $("<div/>").addClass("places-list").html($("<ul/>"));
			parent.append(list);
			list.hide();
		}

		if(val.length > 3){
			$.ajax({
					url:host,
					data:{
						geocode:val,
						format:'json',
						results:10
					},
					dataType:"json",
					success:function(resp){
						var places = [];
						if(resp.hasOwnProperty("response")){
							var collect = new PlacesCollection();
							collect.importYnadex(resp.response);

							places = collect.places;
						}
						console.log(places);
						var html = placesPropertyToHtml(places,"description","description",['province','country','locality']);

						list.html(html);
						list.show();
					},
					error:function(msg){
						console.log(msg);
					}
			})
		}
	});


	$("body").on("keyup","div.geocoder-town input[type=text]",function(event){
		event.preventDefault();
		var val = $(this).val();
		var parent = $(this).parents("div.geocoder.geocoder-town");
		var list = parent.find("div.places-list");
		var region = $(this).parents(".form-route").find("div.geocoder-region").find("input[type=text]").val();

		if(!list.length){
			list = $("<div/>").addClass("places-list").html($("<ul/>"));
			parent.append(list);
			list.hide();
		}

		if(val.length > 3){
			
			val = region+", "+val;

			$.ajax({
					url:host,
					data:{
						geocode:val,
						format:'json',
						results:10
					},
					dataType:"json",
					success:function(resp){
						var places = [];
						if(resp.hasOwnProperty("response")){
							var collect = new PlacesCollection();
							collect.importYnadex(resp.response);

							places = collect.places;
						}
						
						var html = placesPropertyToHtml(places,"name","name");

						list.html(html);
						list.show();
					},
					error:function(msg){
						console.log(msg);
					}
			})
		}
	});

	$("body").on("keyup","div.geocoder-address input[type=text]",function(event){
		event.preventDefault();
		var val = $(this).val();
		var parent = $(this).parents("div.geocoder.geocoder-address");
		var list = parent.find("div.places-list");
		var town = $(this).parents(".form-route").find("div.geocoder-town").find("input[type=text]").val();
		
		if(!list.length){
			list = $("<div/>").addClass("places-list").html($("<ul/>"));
			parent.append(list);
			list.hide();
		}

		if(val.length > 3){

			val = town+", "+val;

			$.ajax({
					url:host,
					data:{
						geocode:val,
						format:'json'
					},
					dataType:"json",
					success:function(resp){
						var places = [];
						if(resp.hasOwnProperty("response")){
							var collect = new PlacesCollection();
							collect.importYnadex(resp.response);

							places = collect.places;
						}

						var html = placesPropertyToHtml(places,"name","name",['street','house']);
						
						list.html(html);
						list.show();
					},
					error:function(msg){
						console.log(msg);
					}
			})
		}
	});

	$("body").on("click","li.place_item",function(event){
		event.preventDefault();
			
		var data = $(this).data();
		$(this).parents("div.geocoder").find("input[type=text]").val(data.value);
		$(this).parents("div.places-list").hide();
	});
})
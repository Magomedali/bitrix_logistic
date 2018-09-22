var Place = function(data){

	this.country = data.country;
	this.province = data.province;
	this.locality = data.locality;
	this.street = data.street;
	this.house = data.house;
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
						components.forEach(function(c,j){
							place_data[c.kind] = c.name;
						});

						places.push(new Place(place_data));
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
	
	if(places.length){
		places.forEach(function(item,i){
			var parts = [];
			if(components && components.length){
				this.html = "<ul class='yandex_places_autocomplete' data-components='"+components.join("|")+"'>";
				components.indexOf("country") >=0 && typeof item.country != 'undefined' ? parts.push(item.country) : null;
				components.indexOf("province") >=0 && typeof item.province != 'undefined' ? parts.push(item.province) : null;
				components.indexOf("locality") >=0 && typeof item.locality != 'undefined' ? parts.push(item.locality) : null;
				components.indexOf("street") >=0 && typeof item.street != 'undefined' ? parts.push(item.street) : null;
				components.indexOf("house") >=0 && typeof item.house != 'undefined' ? parts.push(item.house) : null;
			}else{
				typeof item.country != 'undefined' ? parts.push(item.country) : null;
				typeof item.province != 'undefined' ? parts.push(item.province) : null;
				typeof item.locality != 'undefined' ? parts.push(item.locality) : null;
				typeof item.street != 'undefined' ? parts.push(item.street) : null;
				typeof item.house != 'undefined' ? parts.push(item.house) : null;
			}
			if(parts.length){
				this.html+= "<li class='place_item' data-country='"+item.country+"'"+
							" data-province='"+item.province+"'"+
							" data-locality='"+item.locality+"'"+
							" data-street='"+item.street+"'"+
							" data-house='"+item.house+"'"+
							">";
				this.html += parts.join(", ");
				this.html += "</li>";
			}
			
		});
	}
	this.html += "</ul>";

	return this.html;
}


$(function(){

	var host = "https://geocode-maps.yandex.ru/1.x/";
	$("body").on("keyup","div.geocoder-town input[type=text]",function(event){
		event.preventDefault();
		var val = $(this).val();
		var parent = $(this).parents("div.geocoder.geocoder-town");
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
						var html = placesToHtml(places,['province','locality']);

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
		console.log(town);
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
						var html = placesToHtml(places,['street','house']);

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
			var ul_components = $(this).parent("ul").data("components");
			var components = [];
			if(typeof ul_components != 'undefined' && ul_components != ""){
				var components = ul_components.split("|");
			}
			var parts = [];
			if(components.length){
				components.indexOf("country") >=0 && data.country != 'undefined' && typeof data.country != 'undefined' ? parts.push(data.country) : null;
				components.indexOf("province") >=0 && data.province != 'undefined' && typeof data.province != 'undefined' ? parts.push(data.province) : null;
				components.indexOf("province") >=0 && data.province !== data.locality && data.locality != 'undefined' && typeof data.locality != 'undefined' ? parts.push(data.locality) : null;
				components.indexOf("street") >=0 && data.street != 'undefined' && typeof data.street != 'undefined' ? parts.push(data.street) : null;
				components.indexOf("house") >=0 && data.house != 'undefined' && typeof data.house != 'undefined' ? parts.push(data.house) : null;
			}else{
				data.country != 'undefined' && typeof data.country != 'undefined' ? parts.push(data.country) : null;
				data.province != 'undefined' && typeof data.province != 'undefined' ? parts.push(data.province) : null;
				data.province !== data.locality && data.locality != 'undefined' && typeof data.locality != 'undefined' ? parts.push(data.locality) : null;
				data.street != 'undefined' && typeof data.street != 'undefined' ? parts.push(data.street) : null;
				data.house != 'undefined' && typeof data.house != 'undefined' ? parts.push(data.house) : null;
			}
			
			
			$(this).parents("div.geocoder").find("input[type=text]").val(parts.join(", "));
			$(this).parents("div.places-list").hide();
		});
})
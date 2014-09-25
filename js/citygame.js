function CityGame(map) {
	this.map = map;
	this.timer = 0.0;
	this.timerset = false;
	this.score = 0;
	this.city = {};
	this.citysaved = [];
	
	this.timerLapse = 0.1;
	
	this.mapClickListener = null;
			
	// Init
	this.initRound();
}

CityGame.prototype = {
	
	initRound: function() {
		var that = this;
		
		this.prepareCity(function() {
			alert('Indiquer la position de ' + that.city.name + ' sur la carte de France');
			that.initTimer();
			that.initMapEvent();
		});
	},
	
	cancelRound: function() {
		var that = this;
		
		google.maps.event.removeListener(this.mapClickListener);
		this.stopTimer();
		
		setTimeout(function() {
			that.initRound();
		}, 2000);
	},
	
	prepareCity: function(callback) {
		var that = this;
		
		$.ajax({
			url: 'ajax.php?action=getnextcity',
			method: 'post',
			data: { 
				exclude: that.citysaved
			},
			success: function(data) {
				if (typeof(data.city) !== 'undefined') {
					that.city = data.city;
					that.citysaved.push(data.city.id);
					if (isCallback(callback)) {
						callback();
					}
				}
			}
		});
	},
	
	initMapEvent: function() {
		var that = this;
		
		this.mapClickListener = google.maps.event.addListener(this.map, 'click', function(e) {
			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(that.city.lat, that.city.lng),
				map: that.map
			});
			var distance = computeDistance(e.latLng.lat(), e.latLng.lng(), that.city.lat, that.city.lng);
			var points = that.computePoints(distance);
			console.log(distance.toFixed(3) + ' km, ' + points + ' points en ' + that.timer.toFixed(1) + ' secondes');
			that.cancelRound();
		});
	},
	
	computePoints: function (distance) {
		var points = Math.max(0, -0.5 * distance + 80);
		points+= points * Math.max(0, -2 * this.timer + 100) / 100;
		return Math.round(points);
	},
	
	initTimer: function() {
		this.timer = 0;
		this.timerset = true;
		this.incrementTimer();
	},
	
	incrementTimer: function() {
		this.timer += this.timerLapse;
		
		if (this.timerset) {
			var that = this;
			setTimeout(function() {
				that.incrementTimer();
			}, this.timerLapse * 1000);
		}
	},
	
	stopTimer: function() {
		this.timerset = false;
	}
};

function isCallback(callback) {
	return typeof(callback) === 'function';
}

/**
 * Calcul la distance entre deux points terrestres
 * 
 * @param float lat1
 * @param float lng1
 * @param float lat2
 * @param float lng2
 * @returns float
 */
function computeDistance(lat1, lng1, lat2, lng2) {
	var radius = 6378137; // Earth, 6 378 km de rayon
	var deg2rad = function(angle) { return (angle / 180) * Math.PI; };
	var rlng1 = deg2rad(lng1);
	var rlat1 = deg2rad(lat1);
	var rlng2 = deg2rad(lng2);
	var rlat2 = deg2rad(lat2);
	var dlng = (rlng2 - rlng1) / 2;
	var dlat = (rlat2 - rlat1) / 2;
	var a = (Math.sin(dlat) * Math.sin(dlat)) + Math.cos(rlat1) * Math.cos(rlat2) * (Math.sin(dlng) * Math.sin(dlng));
	var d = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
	
	return (radius * d) / 1000;
	
}
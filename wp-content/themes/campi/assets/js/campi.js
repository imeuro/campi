// Globals
const Baseurl = ['localhost','meuro.dev'].includes(window.location.hostname) ? '/campi' : 'https://www.ifratellicampi.com';
const WPREST_Base = Baseurl+'/wp-json/wp/v2';
const current_lang = document.body.dataset.lang;


// Library
async function getPostsFromWp( urlRequest ) {
	try {
		const response = await fetch( urlRequest )
		const data = await response.json()
		return data
	} catch ( e ) {
		console.error( e )
	}
}

// ext scripts/css loader
function fetchLoader(objAttr, chainId = null, elementType = "script", target = document.body) {
	return new Promise(function (resolve, reject) {
	    let element = document.createElement(elementType);

	    for (const property in objAttr) {
	        element.setAttribute(property, objAttr[property]);
	    }
	    let path = objAttr.src || objAttr.href;
	    element.onload = () => {
	        resolve(element);
	        console.debug(`[fetchLoader] ${path}\n`,  chainId + " - OK - Time: " + performance.now());
	    };
	    element.onerror = e => {
	        reject(
	            new Error(`[fetchLoader] LOADING ERROR for: ${path}`)
	        );
	        console.debug(e);
	    };

	    target.appendChild(element);
	});
}

function wait(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}






/* HOME */
const HPslider = document.querySelector('.home .imageslider');
if (HPslider) {
	Array.from(HPslider.children).forEach((el) => {
		if (el.dataset.index == 1) {
			el.classList.add('active');
		} else {  }

		el.style.zIndex = el.dataset.index;
	})

	let sfoglioRun = 1;
	let sfoglioSlider = () => {
		if (sfoglioRun < HPslider.children.length) { 
			for (let i = 0; i < HPslider.children.length; i++) {
				if (!HPslider.children[i].classList.contains('active')) {
					HPslider.children[i].classList.add('active');
					sfoglioRun++;
					console.debug(HPslider.children.length, sfoglioRun);
					break;
				}
			}
		} else {
			console.debug(sfoglioRun,'RESET!');
			for (let i = 1; i < HPslider.children.length; i++) {
				HPslider.children[i].classList.remove('active');
			}
			sfoglioRun = 1;
		}
	}
	setInterval(sfoglioSlider, 4000);
}

/* MAPPA */

const mapDiv = document.getElementById('campi-map');
let BaseCoords = window.innerWidth<600 ? [10.023,45.142] : [10.018, 45.137];
let BaseZoom = window.innerWidth<600 ? 8 : 10.5;
var locationsList = getPostsFromWp(WPREST_Base+'/luoghi?_fields=acf,id,slug,name,content&per_page=99');

// THE MAP BOX
const generateMapbox = () => {
	
	mapboxgl.accessToken = mapDiv.dataset.map;
	mapboxgl.accessToken = 'pk.eyJ1IjoibWV1cm8iLCJhIjoiY2xmcjA2ZDczMDEwYTQzcWZwZXk4dmpvdSJ9.YHkGCdl-D6YkWDJbNGOBEQ';

	
	map = new mapboxgl.Map({
		container: 'campi-map', // container ID
		style: 'mapbox://styles/meuro/cm1s1ux52010p01r2h86726z2?optimize=true', // style URL
		center: BaseCoords, // starting position [lng, lat]
		zoom: BaseZoom, // starting zoom
		//glyphs: 'mapbox://fonts/meuro/OPS%20Placard%20Regular/0-255.pbf',
		// cooperativeGestures: true,
	});
	if (window.innerWidth>600) {
		map.addControl(new mapboxgl.NavigationControl(),'bottom-right');
	}
	// Add geolocate control to the map.
	map.addControl(
		new mapboxgl.GeolocateControl({
			positionOptions: {
				enableHighAccuracy: true
			},
			// When active the map will receive updates to the device's location as it changes.
			trackUserLocation: true,
			// Draw an arrow next to the location dot to indicate which direction the device is heading.
			showUserHeading: true
		}),'bottom-right'
	);
}

if (mapDiv) {
	console.debug('wewe');
	fetchLoader({
		href: 'https://api.mapbox.com/mapbox-gl-js/v3.7.0/mapbox-gl.css',
		rel: 'stylesheet',
		type: 'text/css',
		media: 'all'
	}, "MAPBOX_init", 'link', document.head)
	.then( element => fetchLoader({
			src: 'https://api.mapbox.com/mapbox-gl-js/v3.7.0/mapbox-gl.js',
			async: false
		}, "MAPBOX_init"))
	.then( element => {
		generateMapbox();
	})
}

document.addEventListener('DOMContentLoaded', () => {

	locationsList.then( 
			ARTdata => {
				var features = [];
				var geoJSON = {};
				let i = 1;
				geoJSON.type = 'FeatureCollection';
				Object.values(ARTdata).forEach(el => {
					console.debug( {el} );
					var art = {};
					art.type = "Feature";
					art.properties = {}
					art.properties.type = el.type;
					art.properties.title = el.name;
					art.properties.description = el.acf.descrizione_dettagliata;
					art.properties.post_id = el.id;
					// art.properties.testo_eng = el.acf.testo_eng;
					// art.properties.location_id = el.acf.location_id;
					// art.properties.location_name = el.acf.map_location.name;
					art.properties.location_address = el.acf.map_location.street_name+', '+el.acf.map_location.street_number+'<br>'+el.acf.map_location.city+' ('+el.acf.map_location.state_short+') '+'<br>'+el.acf.map_location.country;
					art.geometry = {};
					art.geometry.type = "Point"
					art.geometry.coordinates = [el.acf.map_location.lng,el.acf.map_location.lat];
					i++;
					features.push(art);
				});

				geoJSON.features = features;
				CAWgeoJSON_locations = geoJSON;
				console.debug(CAWgeoJSON_locations)
			}
	);
});


/* FOGLIA */

//carousel in pagina
const carousel = document.querySelector('.CSScarousel');
if (carousel) {
	const loadJS = new Promise((resolve, reject) => {
		const script = document.createElement('script');
		document.body.appendChild(script);
		script.onload = resolve;
		script.onerror = reject;
		script.async = true;
		script.src = '/campi/wp-content/themes/campi/assets/js/CSScarousel.js';
	});
	loadJS.then(() => { 
		console.debug('CSScarousel JS loaded.');
		CSScarousel.setControls();
	});
}

// ancore in aside sx
const anchors = document.querySelectorAll('.aside-anchor');
Array.from(anchors).forEach((el) => {
	el.addEventListener('click', (ev) => {
		ev.preventDefault();
		console.debug(el);
		 document.getElementById(el.dataset.section).scrollIntoView({ behavior: "smooth" });
	})
})

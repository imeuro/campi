// Globals
const Baseurl = ['localhost','meuro.dev'].includes(window.location.hostname) ? '/campi' : 'https://www.ifratellicampi.com';
const WPREST_Base = Baseurl+'/wp-json/wp/v2';
const current_lang = document.body.dataset.lang;


// Fn Library
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
let BaseZoom = window.innerWidth<600 ? 6 : 7.5;
var locationsList = getPostsFromWp(WPREST_Base+'/luoghi?_fields=parent,acf,id,slug,name,content&per_page=99');
const ShiftMap = window.innerWidth<600 ? -0.0005 : -0.002;

// THE MAP BOX

if (mapDiv) {
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
		fetchLocations();
	})
}

// document.addEventListener('DOMContentLoaded', () => {
let fetchLocations = () => {
	locationsList.then( 
		LOCdata => {
			var features = [];
			var geoJSON = {};
			let i = 1;
			geoJSON.type = 'FeatureCollection';
			Object.values(LOCdata).forEach(el => {
				if (el.parent == 0) {
					console.debug( {el} );
					var loc = {};

					loc.type = "Feature";
					loc.properties = {}
					loc.properties.type = el.type;
					loc.properties.title = el.name;
					loc.properties.description = el.acf.descrizione_dettagliata;
					loc.properties.post_id = el.id;
					loc.properties.location_address = el.acf.map_location.street_name+', '+el.acf.map_location.street_number+'<br>'+el.acf.map_location.city+' ('+el.acf.map_location.state_short+') '+'<br>'+el.acf.map_location.country;
					loc.geometry = {};
					loc.geometry.type = "Point"
					loc.geometry.coordinates = [el.acf.map_location.lng,el.acf.map_location.lat];
					i++;
				
					features.push(loc);
				}
			});

			geoJSON.features = features;
			campi_JSON_locations = geoJSON;
			console.debug(campi_JSON_locations)
		}
	)
	.then( element => {
		generateMapbox();
	});
};


const generateMapbox = () => {
	
	mapboxgl.accessToken = mapDiv.dataset.map;
	
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


	map.on('load', () => {

		const markers =[
		  {
		  	url: Baseurl+'/wp-content/themes/campi/assets/img/map-pointer.png', 
		  	id: 'image_location'
		  },
		]

		Promise.all(
            markers.map(img => new Promise((resolve, reject) => {
                map.loadImage(img.url, function (error, res) {
                	if (error) throw error;
                    map.addImage(img.id, res)
                    resolve();
                })
            }))
        ).then(() => {

        	//console.debug('promise done');
        	
			map.addSource('locations', {
				'type': 'geojson',
				'data': campi_JSON_locations
			}) 

			map.addLayer({
				'id': 'locations',
				'type': 'symbol',
				'source': 'locations',
				'minzoom': 3,
				'maxzoom': 20,
				'layout': {
					'icon-image': 'image_location',
					'icon-size': .5,
					'icon-allow-overlap': true,
					'icon-ignore-placement': true,
					'text-allow-overlap': false,
					'text-ignore-placement': false,
					'text-optional': true,
					'text-field': ['get', 'title'],
					'text-variable-anchor': ['bottom-left'],
					'text-radial-offset': 1,
					'text-justify': 'left',
					'text-size': 14,
					// 'text-font': ['OPS Placard Regular'],
				},

				'paint': {
					'text-color': '#000000',
				}
			})

		});


		// check for eventual hash in url and move directly to a pointer
        const hashID = document.location.hash.slice(1);
		if (allAcc && hashID != '') {
			setTimeout( () => { openAccordion(hashID) },1000 );
		}

	});

	let readmorelink;		
	let coords = [];
	let locID = '';
	let features = {};

	map.on('mouseenter', 'locations', (event) => {
		map.getCanvas().style.cursor = 'pointer';
		// If the user clicked on one of your markers, get its information.
		features = map.queryRenderedFeatures(event.point, {
			layers: ['locations'] // replace with your layer name
		});
		if (!features.length) {
			return;
		}
		let feature = features[0];
		console.debug({feature});
		coords = feature.geometry.coordinates;
		locID = feature.properties.post_id;

	});

	map.on('click', 'locations', () => {
	 	openAccordion(locID);
	});

	map.on('mouseleave', 'locations', () => {
		map.getCanvas().style.cursor = '';
	});

}


/* LUOGHI */

// gestione accordion: 
// click su tab: aperto solo uno alla volta
const fatherAcc = document.getElementById('luoghi-container');
let allAcc = document.querySelectorAll('#location-list .location-item');
if (allAcc) {
	Array.from(allAcc).forEach(function (d, index) {
		d.addEventListener('click', () => {
			openAccordion(d.dataset.location);
			// d.classList.add('open');
			d.scrollIntoView({ 
				behavior: 'smooth' 
			});
		});
	});
}
// chiusura accordion con X
const closeAcc = document.getElementById('close-container');
if (closeAcc) {
	closeAcc.addEventListener('click', () => {
		openAccordion('reset');
	});
}


//
const openAccordion = (locID) => {

	// reset accordions
	Array.from(allAcc).forEach(function (d, index) {
		d.querySelector('input[name="panel"]').checked = false;
		d.classList.remove('open');
	});

	if (locID!='reset') { // open clicked accordion
		fatherAcc.classList.add('opened');
		let targetAcc = document.querySelector(`#location-list .location-item[data-location="${locID}"]`);
		if (targetAcc.classList.contains('open')) {

			// close accordion
			targetAcc.querySelector('input[name="panel"]').checked = false;
			targetAcc.classList.remove('open');

		} else {

			// open accordion
			targetAcc.querySelector('input[name="panel"]').checked = true;
			targetAcc.classList.add('open');

			// scroll to accordion div 
			setTimeout(()=>{
				targetAcc.scrollIntoView({ 
					behavior: 'smooth' 
				});
			},1500)

			// muove mappa
			map.flyTo({
				center: [(targetAcc.dataset.lng - ShiftMap),targetAcc.dataset.lat],
				essential: true,
				zoom:16,
				duration: 5000
			});	

		}

		// targetAcc.classList.toggle('open');
		console.debug({targetAcc});

	} else {
		fatherAcc.classList.remove('opened');
		// reset mappa
		// map.flyTo({
		// 	center: [BaseCoords[0],BaseCoords[1]],
		// 	essential: true,
		// 	zoom:BaseZoom,
		// 	duration: 2000
		// });
	}
	
}



/* FOGLIA */

//carousel in pagina
const carousel = document.querySelector('.CSScarousel');
if (carousel) {
	fetchLoader({
		src: Baseurl+'/wp-content/themes/campi/assets/js/CSScarousel.js',
		async: true
	}, "carousel_init")
	.then(() => { 
		console.debug('CSScarousel JS loaded.');
		CSScarousel.setControls();
	});

	// const loadJS = new Promise((resolve, reject) => {
	// 	const script = document.createElement('script');
	// 	document.body.appendChild(script);
	// 	script.onload = resolve;
	// 	script.onerror = reject;
	// 	script.async = true;
	// 	script.src = '/campi/wp-content/themes/campi/assets/js/CSScarousel.js';
	// });
	// loadJS.then(() => { 
	// 	console.debug('CSScarousel JS loaded.');
	// 	CSScarousel.setControls();
	// });
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

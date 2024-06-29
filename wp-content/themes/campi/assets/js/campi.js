



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

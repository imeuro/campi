/* HOME */
const HPslider = document.querySelector('.home .imageslider');
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

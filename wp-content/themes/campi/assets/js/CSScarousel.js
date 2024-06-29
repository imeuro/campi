"use strict";
let CSScarousel;
(CSScarousel = function() {
  let me = CSScarousel;
  me.CSScarouselConfig = {};
  me.observerSlides = [];
  console.log("[CSScarousel] init");

  me.cbObserverSlides = (entries, observer) => {
    //entries.forEach(entry => {
    for (const entry of entries) {
      requestAnimationFrame(() => {
        let parentCarousel = entry.target.parentElement;

        let targetId = parentCarousel.getAttribute("id");

        if (me.CSScarouselConfig[targetId]) {
          if (
            entry.intersectionRatio == 1 &&
            !entry.target.classList.contains("CSScarouselItemActive")
          ) {
            if (me.CSScarouselConfig[targetId].classi) {
              entry.target.classList.add("CSScarouselItemActive");
            }

            if (me.CSScarouselConfig[targetId].eventi) {
              let eventSlideAttivata = new CustomEvent(
                "CSScarouselSlideAttivata",
                {
                  detail: {
                    carousel: parentCarousel,
                    carouselId: targetId,
                    indexSlide: Array.from(parentCarousel.children).indexOf(
                      entry.target
                    )
                  }
                }
              );
              document.dispatchEvent(eventSlideAttivata);
            }

            //gestione per rimozione classe slide attiva
            if (me.CSScarouselConfig[targetId].classi) {
              let allSlides = parentCarousel.querySelectorAll(
                  ":scope > .CSScarouselItem"
                ),
                allSlidesL = allSlides.length,
                i;
              for (i = 0; i < allSlidesL; i++) {
                let rect = allSlides[i].getBoundingClientRect();
                if (rect.left < 0 || rect.right > document.body.clientWidth) {
                  allSlides[i].classList.remove("CSScarouselItemActive");
                }
              }
            }
          }
        }
        
        let curCarousel = document
            .querySelector(".CSScarouselPrev[data-target='#" + targetId + "'], .CSScarouselNexr[data-target='#" + targetId + "']");
        if (curCarousel) {

          //gestione prev
          let isFirstSlide = entry.target.previousElementSibling;
          if (entry.intersectionRatio == 1 && isFirstSlide == null) {
            document
              .querySelector(".CSScarouselPrev[data-target='#" + targetId + "']")
              .classList.add("CSScarouselDisabled");
          } else if (entry.intersectionRatio < 1 && isFirstSlide == null) {
            document
              .querySelector(".CSScarouselPrev[data-target='#" + targetId + "']")
              .classList.remove("CSScarouselDisabled");
          }

          //gestione next
          let isLastSlide = entry.target.nextElementSibling;
          if (entry.intersectionRatio == 1 && isLastSlide == null) {
            document
              .querySelector(".CSScarouselNext[data-target='#" + targetId + "']")
              .classList.add("CSScarouselDisabled");
          } else if (entry.intersectionRatio < 1 && isLastSlide == null) {
            document
              .querySelector(".CSScarouselNext[data-target='#" + targetId + "']")
              .classList.remove("CSScarouselDisabled");
          }

        }
      });
    }
  };

  me.observeSlides = function(slideNodes, id) {
    let slideNodesl = slideNodes.length,
      i;
    for (i = 0; i < slideNodesl; i++) {
      me.observerSlides[id].observe(slideNodes[i]);
      slideNodes[i].classList.add("observerProcessed");
    }
  };

  me.setObserversSlides = function(carousel) {
    let carouselId = carousel.getAttribute("id");
    console.log("[CSScarousel] setObserver per id:", carouselId);

    let config = carousel.getAttribute("data-config") || null;

    if (config != null) {
      me.CSScarouselConfig[carouselId] = JSON.parse(config);
    }

    let options = {
      root: carousel,
      rootMargin: "10px 10px 10px 10px",
      threshold: 1
    };

    me.observerSlides[carouselId] = new IntersectionObserver(
      me.cbObserverSlides,
      options
    );

    let slides;
    if (config != null) {
      slides = carousel.querySelectorAll(":scope > .CSScarouselItem");
    } else {
      slides = carousel.querySelectorAll(
        ":scope > .CSScarouselItem:first-child, :scope > .CSScarouselItem:last-child"
      );
    }

    me.observeSlides(slides, carouselId);
  };

  me.outerWidth = function(el) {
    var width = el.offsetWidth;
    var style = getComputedStyle(el);

    width += parseInt(style.marginLeft) + parseInt(style.marginRight);
    return width;
  };

  me.cbObserverCarousels = (entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("CSScarouselInViewport");
        me.setObserversSlides(entry.target);
      } else {
        entry.target.classList.remove("CSScarouselInViewport");
        let carouselId = entry.target.getAttribute("id");
        if (me.observerSlides[carouselId]) {
          me.observerSlides[carouselId].disconnect();
          console.log(
            "[CSScarousel] disconnect observer per id:",
            carouselId
          );
        }
      }
    });
  };

  me.observeCarousels = function(allCarousels) {
    let allCarouselsl = allCarousels.length,
      i;
    for (i = 0; i < allCarouselsl; i++) {
      me.observerCarousels.observe(allCarousels[i]);
    }
  };

  me.setObserversCarousels = function() {
    let options = {
      rootMargin: "150px 10px 150px 10px",
      threshold: 0.01
    };

    me.observerCarousels = new IntersectionObserver(
      me.cbObserverCarousels,
      options
    );

    let allCarousels = document.querySelectorAll(".CSScarousel");

    me.observeCarousels(allCarousels);
  };

  //gestisce i controlli avanti e indietro
  me.setControls = function() {
    let CSScarouselAllControls = document.querySelectorAll(
        ".CSScarouselControl:not(.CSScarouselProcessed)"
      ),
      i,
      CSScarouselAllControlsL = CSScarouselAllControls.length;
    for (i = 0; i < CSScarouselAllControlsL; i++) {
      //me.setObserver(CSScarouselAllControls[i]);

      CSScarouselAllControls[i].addEventListener("click", function(e) {
        e.preventDefault();
        let direction;
        e.currentTarget.classList.contains("CSScarouselPrev")
          ? (direction = -1)
          : (direction = 1);
        //identifichiamo il target
        let target = e.currentTarget.getAttribute("data-target");
        let CSScarousel = document.querySelector(target);

        //leggiamo il passo impostato
        let CSScarouselPasso = CSScarousel.getAttribute("data-passo");
        //scroll di partenza
        let scrollLeft = CSScarousel.scrollLeft;
        //diamo per scontato che abbiano tutti la stessa larghezza
        let itemWidth = me.outerWidth(
          CSScarousel.querySelector(".CSScarouselItem")
        );

        let destination = Math.floor(
          scrollLeft + itemWidth * CSScarouselPasso * direction
        );
        if (destination < 0) destination = 0;

        let carouselScrollWidth = CSScarousel.scrollWidth;
        let carouselWidth = CSScarousel.offsetWidth;
        let itemInview = carouselWidth / itemWidth;
        let itemActive = Math.round(scrollLeft / itemWidth);

        if (
          scrollLeft + carouselWidth == carouselScrollWidth &&
          direction == -1
        ) {
          destination = Math.round(itemActive - CSScarouselPasso) * itemWidth;
        }

        if (getComputedStyle(CSScarousel).scrollBehavior === "smooth") {
          requestAnimationFrame(function() {
            CSScarousel.scrollTo({
              top: 0,
              left: destination
            });
          });
        } else {
          //fallback per no smooth
          console.log("[CSScarousel] fallback per no smooth");
          //leggiamo il tempo di animazione per fallback
          let CSScarouselTime =
            parseInt(CSScarousel.getAttribute("data-time")) || 300;
          let start = new Date().getTime();
          let timer = setInterval(function() {
            requestAnimationFrame(function() {
              let step = Math.min(
                1,
                (new Date().getTime() - start) / CSScarouselTime
              );
              let position = scrollLeft + step * (destination - scrollLeft) + 0;
              if (step === 1) clearInterval(timer);
              CSScarousel.scrollLeft = position;
            });
          }, 25);
        }
      });
      CSScarouselAllControls[i].classList.add("CSScarouselProcessed");
    }

    //init degli observer
    //me.setObserversSlides();
    me.setObserversCarousels();
  };

  //init di tutti i controlli
  me.setControls();
})();
document.addEventListener('DOMContentLoaded', function () {
    // Registrar el plugin ScrollTrigger
    gsap.registerPlugin(ScrollTrigger);

    // Animar elementos con la clase .animate al entrar en el viewport
    gsap.utils.toArray('.wp-block-cover__inner-container > * > *, .is-layout-flow > * > *:not(.wp-block-details), .wp-block-group:not(#masthead, #colophon) > * > *:not(.wp-block-details)').forEach(function (element) {
        gsap.from(element, {
            scrollTrigger: {
                trigger: element, // Elemento que activa la animación
                //markers: true, // Desactivar marcadores de depuración
                start: 'top 100%', // Inicia cuando el top del elemento está al 80% del viewport
                end: 'bottom 20%', // Termina cuando el bottom del elemento está al 20% del viewport
                toggleActions: 'play none none none', // Reproducir solo una vez
            },
            opacity: 0,
            y: 50,
            duration: 0.3,
        });
        
    });
});

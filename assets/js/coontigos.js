// Añade clase a body cuando se hace scroll
window.addEventListener("scroll", function() {
    if (window.scrollY > 180) {
        document.body.classList.add("scrolled");
    } else {
        document.body.classList.remove("scrolled");
    }
});
// Añade botones de scroll a la izquierda y derecha
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".is-style-group-horizontal-scroll-btns").forEach((content) => {
        if (content.children.length > 1) {
            const rightBtn = document.createElement("button");
            rightBtn.classList.add("scrolling-button", "scrolling-button--right");
            rightBtn.innerHTML = "→";
            rightBtn.disabled = false;

            const leftBtn = document.createElement("button");
            leftBtn.classList.add("scrolling-button", "scrolling-button--left");
            leftBtn.innerHTML = "←";
            leftBtn.disabled = true;

            const buttonContainer = document.createElement("div");
            buttonContainer.classList.add("scrolling-button-container");
            buttonContainer.appendChild(leftBtn);
            buttonContainer.appendChild(rightBtn);
            //content.parentNode.insertBefore(buttonContainer, content.nextSibling);
            // Agregar el contenedor de botones antes del contenido
            content.parentNode.insertBefore(buttonContainer, content);

            // Desplazamiento fijo para móvil y desktop
            function getScrollStep() {
                return window.innerWidth < 768 ? 400 : 288;
            }

            rightBtn.addEventListener("click", () => {
                const scrollContent = content;
                const scrollStep = getScrollStep();
                scrollContent.scrollLeft += scrollStep;
                leftBtn.disabled = false;

                if (scrollContent.scrollWidth - scrollContent.scrollLeft - scrollContent.clientWidth <= 0) {
                    rightBtn.disabled = true;
                }
            });

            leftBtn.addEventListener("click", () => {
                const scrollContent = content;
                const scrollStep = getScrollStep();
                scrollContent.scrollLeft -= scrollStep;
                rightBtn.disabled = false;

                if (scrollContent.scrollLeft <= 0) {
                    leftBtn.disabled = true;
                }
            });
        }
    });
});

// Añade drag para los elementos con scroll horizontal
document.addEventListener('DOMContentLoaded', (event) => {
    const sliders = document.querySelectorAll('.is-style-group-horizontal-scroll');
    let isDown = false;
    let startX;
    let scrollLeft;
  
    // Añade el evento a cada slider
    sliders.forEach(slider => {
        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        slider.addEventListener('mousemove', (e) => {
            if(!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 3; //scroll-fast
            slider.scrollLeft = scrollLeft - walk;
            console.log(walk);
        });
    });
  
  });

//Rank Math FAQ Dropdown
document.addEventListener('DOMContentLoaded', (event) => {
    const faqs = document.querySelectorAll('.rank-math-list-item');
    faqs.forEach(faq => {
        const question = faq.querySelector('.rank-math-question');
        question.addEventListener('click', () => {
            faq.classList.toggle('active');
        });
    });
});


//Toogle Video Sound
document.addEventListener('DOMContentLoaded', (event) => {
    const btnVideos = document.querySelectorAll('.toggle-sound-video');
    const video = document.querySelector('#hero-video video'); 

    if (video && btnVideos.length) {
        btnVideos.forEach(btn => {
            btn.addEventListener('click', () => {
                video.muted = !video.muted;

                // Busca el <p> y el <img> dentro del botón
                const p = btn.querySelector('p');
                const img = btn.querySelector('img');

                if (p && img) {
                    if (video.muted) {
                        img.src = '/wp-content/themes/coontigos/assets/icons/mic-off.svg';
                        p.childNodes[1].nodeValue = 'Activar sonido ';
                    } else {
                        img.src = '/wp-content/themes/coontigos/assets/icons/mic-on.svg';
                        p.childNodes[1].nodeValue = 'Silenciar ';
                    }
                }
            });
        });
    }
});

// Tabs Team// Tabs Team
document.addEventListener('DOMContentLoaded', function() {
    const btnPsicologos = document.getElementById('psicologos-btn');
    const btnColaboradores = document.getElementById('colaboradores-btn');
    const tabPsicologos = document.getElementById('psicologos');
    const tabColaboradores = document.getElementById('colaboradores');

    if (!btnPsicologos || !btnColaboradores || !tabPsicologos || !tabColaboradores) return;

    function showTab(tabToShow, tabToHide, btnToDisable, btnToEnable) {
        tabToShow.classList.add('tab-active');
        tabToShow.classList.remove('tab-inactive');
        tabToHide.classList.remove('tab-active');
        tabToHide.classList.add('tab-inactive');
        btnToDisable.disabled = true;
        btnToEnable.disabled = false;
    }

    // Estado inicial
    tabPsicologos.classList.add('tab-active');
    tabColaboradores.classList.add('tab-inactive');
    btnPsicologos.disabled = true;
    btnColaboradores.disabled = false;

    btnPsicologos.addEventListener('click', function(e) {
        e.preventDefault();
        if (!btnPsicologos.disabled) {
            showTab(tabPsicologos, tabColaboradores, btnPsicologos, btnColaboradores);
        }
    });

    btnColaboradores.addEventListener('click', function(e) {
        e.preventDefault();
        if (!btnColaboradores.disabled) {
            showTab(tabColaboradores, tabPsicologos, btnColaboradores, btnPsicologos);
        }
    });
});

// Filter by Category
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.querySelector('.toggle-filter-by');
    const filterBy = document.querySelector('.filter-by .wp-block-categories-list');

    if (!toggle || !filterBy) return;

    toggle.addEventListener('click', function(e) {
        e.preventDefault();
        filterBy.classList.toggle('filter-by--is-open');
        toggle.classList.toggle('toggle-filter-by--active'); // Añade/quita la clase en el botón
    });
});
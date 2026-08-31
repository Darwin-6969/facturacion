document.addEventListener("DOMContentLoaded", function () {

    /*
     * Animación de entrada para las tarjetas
     */

    const cards = document.querySelectorAll(".dashboard-stat");

    cards.forEach(function (card, index) {

        card.style.opacity = "0";
        card.style.transform = "translateY(15px)";

        setTimeout(function () {

            card.style.transition =
                "opacity 0.5s ease, transform 0.5s ease";

            card.style.opacity = "1";
            card.style.transform = "translateY(0)";

        }, 100 + (index * 100));

    });


    /*
     * Efecto visual para acciones rápidas
     */

    const quickActions =
        document.querySelectorAll(".quick-action");

    quickActions.forEach(function (action) {

        action.addEventListener("mouseenter", function () {

            action.style.transform =
                "translateX(5px)";

        });

        action.addEventListener("mouseleave", function () {

            action.style.transform =
                "translateX(0)";

        });

    });

});

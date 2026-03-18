document.addEventListener("DOMContentLoaded", () => {
    const yearElements = document.querySelectorAll("[data-current-year]");
    const currentYear = new Date().getFullYear();

    yearElements.forEach((element) => {
        element.textContent = currentYear;
    });

    const autoHideAlerts = document.querySelectorAll(".js-auto-hide");
    autoHideAlerts.forEach((alert) => {
        setTimeout(() => {
            alert.classList.add("d-none");
        }, 4000);
    });

    const quantityInputs = document.querySelectorAll(".js-quantity-input");
    quantityInputs.forEach((input) => {
        input.addEventListener("input", () => {
            if (Number(input.value) < 1) {
                input.value = 1;
            }
        });
    });

    const imageSwapTargets = document.querySelectorAll("[data-hover-image]");
    imageSwapTargets.forEach((image) => {
        const originalSrc = image.getAttribute("src");
        const hoverSrc = image.dataset.hoverImage;

        if (!hoverSrc) {
            return;
        }

        image.addEventListener("mouseenter", () => {
            image.setAttribute("src", hoverSrc);
        });

        image.addEventListener("mouseleave", () => {
            image.setAttribute("src", originalSrc);
        });
    });
});

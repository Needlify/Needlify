import "../../components/html/searchField.scss";

document.addEventListener("keydown", (e: KeyboardEvent) => {
    // Slash key "/"
    if (e.key === "/") {
        const searchInput = document.querySelector<HTMLInputElement>("#search-input");
        if (searchInput) {
            if (searchInput !== document.activeElement) {
                e.preventDefault();
            }
            searchInput.focus();
            searchInput.select();
        }
    } else if (e.key === "Escape") {
        const searchInput = document.querySelector<HTMLInputElement>("#search-input");
        if (searchInput && searchInput === document.activeElement) {
            searchInput.blur();
        }
    }
});

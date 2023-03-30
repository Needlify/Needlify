import Sortable from "sortablejs";
import * as feather from "feather-icons";
import "./courseForm.scss";

feather.replace();

const inputSort = document.querySelector("input[name='sort']") as HTMLInputElement;

const updateInputSortValue = () => {
    const sortOrderElement = document.querySelectorAll(".draggable-item");
    const sortValue: string[] = [];

    Array.from(sortOrderElement).forEach(el => {
        sortValue.push(el.getAttribute("data-id") as string);
    });

    inputSort.value = sortValue.join(",");
};

document.addEventListener("DOMContentLoaded", () => {
    updateInputSortValue();
});

const el = document.getElementById("sort-lessons-ul");

if (el) {
    Sortable.create(el, {
        handle: ".handler",
        draggable: ".draggable-item",
        animation: 150,
        swapThreshold: 0.75,
        direction: "vertical",
        onUpdate() {
            updateInputSortValue();
        },
    });
}

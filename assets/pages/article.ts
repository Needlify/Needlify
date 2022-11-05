/* eslint-disable no-param-reassign */
/* eslint-disable @typescript-eslint/no-unused-vars */
/* eslint-disable no-unused-vars */
import hljs from "highlight.js";
import "highlight.js/scss/atom-one-dark.scss";
import GLightbox from "glightbox";
import "glightbox/dist/css/glightbox.min.css";
import slugify from "slugify";

import "../styles/pages/article.scss";

/* Code highlighting */

// hljs.highlightAll();
hljs.configure({ languages: [] }); // To disable auto-detection
hljs.highlightAll();

/* Wrap images into an anchor tag */

function wrap(el, wrapper) {
    el.parentNode.insertBefore(wrapper, el);
    wrapper.appendChild(el);
}

const images = document.querySelectorAll<HTMLImageElement>("#content-container img");

Array.from(images).forEach(img => {
    const a = document.createElement("a");
    a.href = img.src;
    a.classList.add("glightbox");
    wrap(img, a);
});

/* Enable Glightbox */

const lightbox = GLightbox();

/* Wrap tables into divs */

const tables = document.querySelectorAll<HTMLTableElement>("table");

Array.from(tables).forEach(img => {
    const div = document.createElement("div");
    div.classList.add("table-container");
    wrap(img, div);
});

/* Add code blocks language */

const code = document.querySelectorAll<HTMLElement>("pre code.hljs");

Array.from(code).forEach(element => {
    const languages = element.classList.toString().match(/language-\w*/gm);

    if (languages) {
        const actualLang = languages[0].replace("language-", "");
        if (actualLang !== "undefined") {
            element.parentElement?.setAttribute("data-language", actualLang);
        }
    }

    const copy = document.createElement("a");
    copy.innerText = "Copy";
    copy.classList.add("clipboard");
    copy.addEventListener("click", () => {
        navigator.clipboard.writeText(element.innerText);
    });
    element.parentElement?.appendChild(copy);
});

/* Table of content */

const headings = document.querySelectorAll<HTMLHeadingElement>("#content-container :is(h1, h2, h3, h4, h5, h6)");

const ul = document.createElement("ul");

Array.from(headings).forEach((h, index) => {
    // Add link to each headers
    const hId = slugify(h.innerText, { lower: true });
    h.id = hId;
    const hlink = document.createElement("a");
    hlink.href = `#${hId}`;
    wrap(h, hlink);

    // create the toc
    const li = document.createElement("li");

    li.innerHTML = /* html */ `
        <span class="toc-indicator">${index + 1}</span>
        <a href="#${hId}">${h.innerHTML}</a>
    `;
    ul.appendChild(li);
});

document.querySelector("#toc")?.appendChild(ul);

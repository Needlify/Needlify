/* eslint-disable no-param-reassign */
/* eslint-disable @typescript-eslint/no-unused-vars */
/* eslint-disable no-unused-vars */
import slugify from "slugify";

import "../styles/pages/article.scss";

/* Wrap images into an anchor tag */

function wrap(el, wrapper) {
    el.parentNode.insertBefore(wrapper, el);
    wrapper.appendChild(el);
}

/* Table of content */

const headings = document.querySelectorAll<HTMLHeadingElement>("#content-container :is(h1, h2, h3, h4, h5, h6)");

console.log(headings);

if (headings.length > 0) {
    const ul = document.createElement("ul");

    Array.from(headings).forEach((h, index) => {
        // Add link to each headers
        const hId = slugify(h.innerText, { lower: true });
        h.id = hId;
        const hlink = document.createElement("a");
        hlink.href = `#${hId}`;
        hlink.classList.add("cancel");
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
    document.querySelector("#toc-title #spinner")?.remove();
} else {
    document.querySelector("#toc-title")?.remove();
}

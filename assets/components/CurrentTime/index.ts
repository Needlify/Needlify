import { defineCustomElement } from "vue";
import MyCounter from "./index.ce.vue";

customElements.define("my-counter", defineCustomElement(MyCounter));

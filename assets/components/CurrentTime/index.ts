import { defineCustomElement } from "vue";
import CurrentTime from "./CurrentTime.ce.vue";

customElements.define("current-time", defineCustomElement(CurrentTime));
